@extends('layouts.base')

@section('title', 'Login')

@section('content')
<form method="POST" class="form" action="{{ route('login') }}">

    <div class="login-form">
        <h2>Login</h2>

        @if(session('error'))
            <div class="uk-alert uk-alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @csrf

        <label>
            Username
        </label>
        <input 
            type="text" 
            name="username" 
            value="{{ old('username') }}" 
            required
        >

        @error('username')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <label>
            Password
        </label>
        <input 
            type="password" 
            name="password" 
            required
        >

        @error('password')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <button type="submit">Login</button>
    </div>
</form>
@endsection
