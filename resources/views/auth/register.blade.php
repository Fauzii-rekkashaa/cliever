@extends('layouts.app')

@section('title', 'Daftar - Cliever')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-bg">
    <div class="auth-card">
        <h1 class="auth-title">Daftar</h1>

        <div class="auth-tabs">
            <button class="tab-btn {{ $role === 'pelajar' ? 'active' : '' }}" id="tabPelajar" onclick="switchTab('pelajar')">
                Daftar Sebagai Pelajar
            </button>
            <button class="tab-btn {{ $role === 'pengajar' ? 'active' : '' }}" id="tabPengajar" onclick="switchTab('pengajar')">
                Daftar Sebagai Pengajar
            </button>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="{{ old('role', $role) }}">

            <div class="input-group input-group--full">
                <input type="text" name="nama"
                    class="auth-input @error('nama') is-error @enderror"
                    placeholder="Nama Lengkap" value="{{ old('nama') }}"
                    autocomplete="name">
            </div>

            <div class="input-row">
                <div class="input-group">
                    <input type="text" name="username"
                        class="auth-input @error('username') is-error @enderror"
                        placeholder="Username" value="{{ old('username') }}"
                        required autocomplete="username">
                </div>
                <div class="input-group">
                    <input type="email" name="email"
                        class="auth-input @error('email') is-error @enderror"
                        placeholder="Email" value="{{ old('email') }}"
                        required autocomplete="email">
                </div>
            </div>

            <div class="input-group input-group--full">
                <input type="password" name="password" id="password"
                    class="auth-input @error('password') is-error @enderror"
                    placeholder="Password" required autocomplete="new-password">
                <button type="button" class="toggle-pw" onclick="togglePassword('password','eyePassword')" tabindex="-1">
                    <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>

            <div class="input-group input-group--full">
                <input type="password" name="password_confirmation" id="passwordConfirm"
                    class="auth-input" placeholder="Konfirmasi Password"
                    required autocomplete="new-password">
                <button type="button" class="toggle-pw" onclick="togglePassword('passwordConfirm','eyeConfirm')" tabindex="-1">
                    <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>

            <div class="input-group input-group--full upload-group" id="uploadSection"
                 style="{{ $role === 'pengajar' ? '' : 'display:none' }}">
                <label class="upload-label">
                    <input type="file" name="sertifikat" id="sertifikat"
                        class="upload-input" accept=".pdf,.jpg,.jpeg,.png"
                        onchange="updateFileName(this)">
                    <span class="upload-btn">Choose File</span>
                    <span class="upload-hint" id="uploadHint">*Upload Sertifikat Profesi/Dokumen Pendukung</span>
                </label>
            </div>

            <div class="auth-submit">
                <button type="submit" class="btn-auth-submit">Daftar</button>
                <p class="auth-switch">
                    Sudah Punya Akun? <a href="{{ route('login') }}">Masuk</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('tabPelajar').classList.toggle('active', role === 'pelajar');
    document.getElementById('tabPengajar').classList.toggle('active', role === 'pengajar');
    const uploadSection = document.getElementById('uploadSection');
    const sertifikat    = document.getElementById('sertifikat');
    if (role === 'pengajar') {
        uploadSection.style.display = 'block';
        sertifikat.required = true;
    } else {
        uploadSection.style.display = 'none';
        sertifikat.required = false;
    }
}
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.style.opacity = input.type === 'text' ? '1' : '0.45';
}
function updateFileName(input) {
    const hint = document.getElementById('uploadHint');
    hint.textContent = input.files.length > 0 ? input.files[0].name : '*Upload Sertifikat Profesi/Dokumen Pendukung';
    hint.style.color = input.files.length > 0 ? 'rgba(255,255,255,0.9)' : '';
}
</script>
@endpush
