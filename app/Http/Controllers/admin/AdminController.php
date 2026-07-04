<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────
    public function dashboard()
    {
        $totalPelajar       = User::where('role', 'pelajar')->count();
        $totalCourse        = Course::count();
        $courseDikonfirmasi = Course::where('status', 'disetujui')->count();
        $menungguKonfirmasi = User::where('role', 'pengajar')->where('status', 'menunggu')->count();

        return view('admin.dashboard', compact(
            'totalPelajar', 'totalCourse',
            'courseDikonfirmasi', 'menungguKonfirmasi'
        ));
    }

    // ─── Data Pelajar ─────────────────────────────────────────
    public function pelajar(Request $request)
    {
        $search = $request->input('search');

        $pelajar = User::where('role', 'pelajar')
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            })
            ->latest()->paginate(10);

        return view('admin.pelajar', compact('pelajar'));
    }

    public function createPelajar()
    {
        return view('admin.pelajar_form', ['user' => null]);
    }

    public function storePelajar(Request $request)
    {
        $request->validate([
            'nama'     => 'nullable|string|max:100',
            'username' => 'required|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'pelajar',
            'status'   => 'aktif',
        ]);

        return redirect()->route('admin.pelajar')->with('success', 'Pelajar berhasil ditambahkan.');
    }

    public function editPelajar(User $user)
    {
        return view('admin.pelajar_form', compact('user'));
    }

    public function updatePelajar(Request $request, User $user)
    {
        $request->validate([
            'nama'     => 'nullable|string|max:100',
            'username' => 'required|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.pelajar')->with('success', 'Data pelajar diperbarui.');
    }

    public function destroyPelajar(User $user)
    {
        $user->delete();
        return redirect()->route('admin.pelajar')->with('success', 'Pelajar berhasil dihapus.');
    }

    // ─── Data Pengajar ────────────────────────────────────────
    public function pengajar(Request $request)
    {
        $search = $request->input('search');

        $pengajar = User::where('role', 'pengajar')
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('keahlian', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            })
            ->latest()->paginate(12);

        $colors = ['#ef4444','#f97316','#eab308','#22c55e','#14b8a6','#3b82f6','#8b5cf6','#ec4899'];
        foreach ($pengajar as $user) {
            $user->avatar_color = $colors[abs(crc32($user->username)) % count($colors)];
        }

        return view('admin.pengajar', compact('pengajar'));
    }

    public function createPengajar()
    {
        return view('admin.pengajar_form', ['user' => null]);
    }

    public function storePengajar(Request $request)
    {
        $request->validate([
            'nama'               => 'nullable|string|max:100',
            'username'           => 'required|unique:users,username',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|min:8',
            'keahlian'           => 'nullable|string|max:255',
            'deskripsi_pengajar' => 'nullable|string|max:1000',
        ]);

        User::create([
            'nama'               => $request->nama,
            'username'           => $request->username,
            'email'              => $request->email,
            'password'           => bcrypt($request->password),
            'role'               => 'pengajar',
            'status'             => 'aktif',
            'keahlian'           => $request->keahlian,
            'deskripsi_pengajar' => $request->deskripsi_pengajar,
        ]);

        return redirect()->route('admin.pengajar')->with('success', 'Pengajar berhasil ditambahkan.');
    }

    public function editPengajar(User $user)
    {
        return view('admin.pengajar_form', compact('user'));
    }

    public function updatePengajar(Request $request, User $user)
    {
        $request->validate([
            'nama'               => 'nullable|string|max:100',
            'username'           => 'required|unique:users,username,' . $user->id,
            'email'              => 'required|email|unique:users,email,' . $user->id,
            'keahlian'           => 'nullable|string|max:255',
            'deskripsi_pengajar' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'nama'               => $request->nama,
            'username'           => $request->username,
            'email'              => $request->email,
            'keahlian'           => $request->keahlian,
            'deskripsi_pengajar' => $request->deskripsi_pengajar,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.pengajar')->with('success', 'Data pengajar diperbarui.');
    }

    public function destroyPengajar(User $user)
    {
        $user->delete();
        return redirect()->route('admin.pengajar')->with('success', 'Pengajar berhasil dihapus.');
    }

    public function konfirmasiPengajar(User $user)
    {
        $user->update(['status' => 'aktif']);
        return back()->with('success', "Pengajar {$user->username} berhasil dikonfirmasi.");
    }

    public function tolakPengajar(User $user)
    {
        $user->update(['status' => 'ditolak']);
        return back()->with('success', "Pengajar {$user->username} ditolak.");
    }

    // ─── Mengelola Course ──────────────────────────────────────
    public function konfirmasi(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'semua');

        $courses = Course::with('pengajar')
            ->when($search, function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('pengajar', fn($q) => $q->where('username', 'like', "%{$search}%"));
            })
            ->when($status !== 'semua', fn($q) => $q->where('status', $status))
            ->latest()->paginate(10);

        $menunggu  = Course::where('status', 'menunggu')->count();
        $disetujui = Course::where('status', 'disetujui')->count();
        $ditolak   = Course::where('status', 'ditolak')->count();

        return view('admin.konfirmasi', compact('courses', 'menunggu', 'disetujui', 'ditolak'));
    }

    public function showKonfirmasi(Course $course)
    {
        $course->load('pengajar', 'materi');
        return view('admin.konfirmasi_show', compact('course'));
    }

    public function setujuiCourse(Course $course)
    {
        $course->update(['status' => 'disetujui']);
        return back()->with('success', 'Course berhasil disetujui.');
    }

    public function tolakCourse(Course $course)
    {
        $course->update(['status' => 'ditolak']);
        return back()->with('success', 'Course ditolak.');
    }

    // ─── Admin hapus materi (dari halaman detail course) ───────
    public function destroyMateriAdmin(Materi $materi)
    {
        $courseId = $materi->course_id;
        $judul    = $materi->judul;
        $materi->delete();

        return redirect()->route('admin.konfirmasi.show', $courseId)
            ->with('success', "Materi \"{$judul}\" berhasil dihapus.");
    }
}
