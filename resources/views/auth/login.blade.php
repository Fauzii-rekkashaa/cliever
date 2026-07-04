@extends('layouts.app')

@section('title', 'Login - Cliever')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-bg">
    <div class="auth-card auth-card--login">
        <h1 class="auth-title">Login</h1>

        @if (session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="input-group input-group--full">
                <input type="text" name="username" id="username"
                    class="auth-input @error('username') is-error @enderror"
                    placeholder="Username" value="{{ old('username') }}"
                    required autocomplete="username" autofocus>
            </div>

            <div class="input-group input-group--full">
                <input type="password" name="password" id="password"
                    class="auth-input @error('password') is-error @enderror"
                    placeholder="Password" required autocomplete="current-password">
                <button type="button" class="toggle-pw" onclick="togglePassword('password','eyeIcon')" tabindex="-1">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>

            <div class="auth-submit">
                <button type="submit" class="btn-auth-submit">Login</button>
                <p class="auth-switch">
                    Belum Punya Akun? <a href="{{ route('register') }}">Daftar</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.style.opacity = input.type === 'text' ? '1' : '0.45';
}
</script>
@endpush
