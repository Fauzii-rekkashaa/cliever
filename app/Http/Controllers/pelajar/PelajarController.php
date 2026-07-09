<?php

namespace App\Http\Controllers\Pelajar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Materi;
use App\Models\MateriProgress;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PelajarController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────
    public function dashboard()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('course.pengajar')
            ->latest()
            ->get();

        return view('pelajar.dashboard', compact('enrollments'));
    }

    // ─── My Course ────────────────────────────────────────────
    public function myCourse()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('course.pengajar', 'review')
            ->latest()
            ->get();

        return view('pelajar.mycourse', compact('enrollments'));
    }

    // ─── Course Preview (belum enroll) ─────────────────────────
    public function coursePreview(Course $course)
    {
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('pelajar.course.detail', $course->id);
        }

        // Ambil reviews untuk ditampilkan di halaman preview
        $reviews = Review::where('course_id', $course->id)
            ->with('user')
            ->latest()
            ->get();

        $avgRating    = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('pelajar.course_preview', compact(
            'course', 'reviews', 'avgRating', 'totalReviews'
        ));
    }

    // ─── Detail Course ─────────────────────────────────────────
    public function courseDetail(Course $course)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        abort_if(!$enrollment, 403, 'Kamu belum terdaftar di course ini.');

        $materi = $course->materi()->orderBy('urutan')->get();

        $progressMap = MateriProgress::where('enrollment_id', $enrollment->id)
            ->pluck('selesai', 'materi_id');

        $totalMateri  = $materi->count();
        $selesaiCount = $progressMap->filter(fn($v) => $v)->count();

        $lockedMap    = [];
        $previousDone = true;
        foreach ($materi as $item) {
            $lockedMap[$item->id] = !$previousDone;
            $previousDone = $progressMap[$item->id] ?? false;
        }

        // Cek apakah pelajar sudah pernah review
        $existingReview = Review::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        return view('pelajar.course_detail', compact(
            'course', 'materi', 'enrollment',
            'progressMap', 'lockedMap', 'totalMateri', 'selesaiCount',
            'existingReview'
        ));
    }

    // ─── Submit Review ─────────────────────────────────────────
    public function submitReview(Request $request, Course $course)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Hanya pelajar yang sudah enroll yang bisa review
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        // Update kalau sudah pernah review, insert kalau belum
        Review::updateOrCreate(
            [
                'user_id'   => Auth::id(),
                'course_id' => $course->id,
            ],
            [
                'enrollment_id' => $enrollment->id,
                'rating'        => $request->rating,
                'komentar'      => $request->komentar,
            ]
        );

        return back()->with('success', 'Review berhasil dikirim. Terima kasih!');
    }

    // ─── Buka satu materi ──────────────────────────────────────
    public function materiShow(Materi $materi)
    {
        $course = $materi->course;

        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        abort_if(!$enrollment, 403, 'Kamu belum terdaftar di course ini.');

        $semuaMateri = $course->materi()->orderBy('urutan')->get();
        $progressMap = MateriProgress::where('enrollment_id', $enrollment->id)
            ->pluck('selesai', 'materi_id');

        $previousDone = true;
        $locked       = false;
        foreach ($semuaMateri as $item) {
            if ($item->id === $materi->id) {
                $locked = !$previousDone;
                break;
            }
            $previousDone = $progressMap[$item->id] ?? false;
        }

        abort_if($locked, 403, 'Selesaikan materi sebelumnya terlebih dahulu.');

        $currentIndex = $semuaMateri->search(fn($m) => $m->id === $materi->id);
        $nextMateri   = $semuaMateri->get($currentIndex + 1);
        $prevMateri   = $currentIndex > 0 ? $semuaMateri->get($currentIndex - 1) : null;
        $isSelesai    = $progressMap[$materi->id] ?? false;

        return view('pelajar.materi_show', compact(
            'course', 'materi', 'enrollment', 'nextMateri', 'prevMateri', 'isSelesai'
        ));
    }

    // ─── Tandai materi selesai ─────────────────────────────────
    public function selesaikanMateri(Materi $materi)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $materi->course_id)
            ->firstOrFail();

        $progress = MateriProgress::firstOrNew([
            'enrollment_id' => $enrollment->id,
            'materi_id'     => $materi->id,
        ]);
        $progress->selesai = true;
        $progress->save();

        $enrollment->recalculateProgress();

        $semuaMateri  = $materi->course->materi()->orderBy('urutan')->get();
        $currentIndex = $semuaMateri->search(fn($m) => $m->id === $materi->id);
        $nextMateri   = $semuaMateri->get($currentIndex + 1);

        if ($nextMateri) {
            return redirect()->route('pelajar.materi.show', $nextMateri->id)
                ->with('success', 'Materi selesai! Lanjut ke materi berikutnya.');
        }

        return redirect()->route('pelajar.course.detail', $materi->course_id)
            ->with('success', 'Selamat! Kamu sudah menyelesaikan semua materi. Yuk berikan rating untuk course ini!');
    }

    // ─── Browse Course ─────────────────────────────────────────
    public function browse(Request $request)
    {
        $search = $request->input('search');

        $enrolledIds = Enrollment::where('user_id', Auth::id())->pluck('course_id');

        $courses = Course::where('status', 'disetujui')
            ->whereNotIn('id', $enrolledIds)
            ->with('pengajar', 'reviews')
            ->when($search, fn($q) => $q->where('judul', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('pelajar.browse', compact('courses'));
    }

    // ─── Enroll ────────────────────────────────────────────────
    public function enroll(Course $course)
    {
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('pelajar.course.detail', $course->id)
                ->with('success', 'Kamu sudah terdaftar di course ini.');
        }

        Enrollment::create([
            'user_id'             => Auth::id(),
            'course_id'           => $course->id,
            'tanggal_daftar'      => now()->toDateString(),
            'status_penyelesaian' => 'belum_selesai',
            'progress'            => 0,
        ]);

        return redirect()->route('pelajar.course.detail', $course->id)
            ->with('success', 'Berhasil mendaftar course: ' . $course->judul);
    }

    // ─── Certificates ──────────────────────────────────────────
    public function sertifikat()
    {
        $sertifikat = Enrollment::where('user_id', Auth::id())
            ->where('status_penyelesaian', 'selesai')
            ->with('course.pengajar', 'sertifikat', 'user')
            ->get();

        return view('pelajar.sertifikat', compact('sertifikat'));
    }

    // ─── Download Sertifikat PDF ───────────────────────────────
    public function downloadSertifikat(Enrollment $enrollment)
    {
        abort_if($enrollment->user_id !== Auth::id(), 403);
        abort_if($enrollment->status_penyelesaian !== 'selesai', 403, 'Course belum diselesaikan.');

        $enrollment->load('course.pengajar', 'user', 'sertifikat');

        $tanggalTerbit = $enrollment->sertifikat->tanggal_terbit ?? $enrollment->updated_at;
        $certificateId = 'CERT-' . \Carbon\Carbon::parse($tanggalTerbit)->format('Y')
                       . '-' . str_pad($enrollment->id, 6, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('pelajar.sertifikat_pdf', compact(
            'enrollment', 'tanggalTerbit', 'certificateId'
        ))->setPaper('a4', 'landscape');

        $filename = 'Sertifikat-' . str_replace(' ', '-', $enrollment->course->judul) . '.pdf';

        return $pdf->download($filename);
    }
}
