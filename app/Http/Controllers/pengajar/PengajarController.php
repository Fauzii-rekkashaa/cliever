<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Materi;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajarController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        $courses = Course::where('user_id', Auth::id())
            ->withCount('materi')
            ->with('reviews')
            ->when($search, fn($q) => $q->where('judul', 'like', "%{$search}%"))
            ->latest()
            ->get();

        $totalCourse  = $courses->count();
        $totalMateri  = $courses->sum('materi_count');
        $totalPelajar = 0;

        return view('pengajar.dashboard', compact('courses', 'totalCourse', 'totalMateri', 'totalPelajar'));
    }

    // ─── Lihat Review Course ───────────────────────────────────
    public function reviewCourse(Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);

        $reviews = Review::where('course_id', $course->id)
            ->with('user')
            ->latest()
            ->get();

        $avgRating    = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Hitung distribusi rating (berapa banyak bintang 1,2,3,4,5)
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $reviews->where('rating', $i)->count();
        }

        return view('pengajar.course_reviews', compact(
            'course', 'reviews', 'avgRating', 'totalReviews', 'distribution'
        ));
    }

    // ─── Create Course ────────────────────────────────────────
    public function createCourse()
    {
        return view('pengajar.course_form', ['course' => null]);
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create([
            'user_id'        => Auth::id(),
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'tanggal_dibuat' => now()->toDateString(),
            'thumbnail'      => $thumbnailPath,
            'status'         => 'menunggu',
        ]);

        return redirect()->route('pengajar.dashboard')
            ->with('success', 'Course berhasil ditambahkan, menunggu konfirmasi admin.');
    }

    public function editCourse(Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);
        return view('pengajar.course_form', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $course->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $course->thumbnail,
        ]);

        return redirect()->route('pengajar.dashboard')->with('success', 'Course berhasil diperbarui.');
    }

    public function destroyCourse(Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);
        $course->delete();
        return redirect()->route('pengajar.dashboard')->with('success', 'Course berhasil dihapus.');
    }

    // ─── Materi ───────────────────────────────────────────────
    public function materi(Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);
        $materi = $course->materi()->orderBy('urutan')->get();
        return view('pengajar.materi', compact('course', 'materi'));
    }

    public function storeMateri(Request $request, Course $course)
    {
        abort_if($course->user_id !== Auth::id(), 403);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'konten'      => 'nullable|string',
            'file_materi' => 'nullable|file|max:10240',
        ]);

        $filePath  = null;
        $tipeFinal = 'teks';

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $ext  = strtolower($file->getClientOriginalExtension());

            if ($ext === 'mp4') {
                $tipeFinal = 'video';
            } elseif (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
                $tipeFinal = 'file';
            }

            $filePath = $file->store('materi', 'public');
        }

        if ($tipeFinal === 'teks' && empty($request->konten)) {
            return back()
                ->withErrors(['konten' => 'Isi materi wajib diisi kalau tidak upload file.'])
                ->withInput();
        }

        $urutan = $course->materi()->max('urutan') + 1;

        Materi::create([
            'course_id'        => $course->id,
            'judul'            => $request->judul,
            'tipe'             => $tipeFinal,
            'konten'           => $request->konten,
            'file_materi'      => $filePath,
            'tanggal_diunggah' => now()->toDateString(),
            'urutan'           => $urutan,
        ]);

        $course->load('enrollments.course.materi');
        foreach ($course->enrollments as $enrollment) {
            $enrollment->recalculateProgress();
        }

        return redirect()->route('pengajar.materi', $course->id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function destroyMateri(Materi $materi)
    {
        $course   = Course::with('enrollments.course.materi')->find($materi->course_id);
        $courseId = $materi->course_id;

        $materi->delete();

        if ($course) {
            foreach ($course->enrollments as $enrollment) {
                $enrollment->recalculateProgress();
            }
        }

        return redirect()->route('pengajar.materi', $courseId)
            ->with('success', 'Materi berhasil dihapus.');
    }
}
