@extends('layouts.base')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>

    @if(session('error'))
        <article style="color: red;">
            {{ session('error') }}
        </article>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>
            Username
            <input 
                type="text" 
                name="username" 
                value="{{ old('username') }}" 
                required
            >
        </label>

        @error('username')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <label>
            Password
            <input 
                type="password" 
                name="password" 
                required
            >
        </label>

        @error('password')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <button type="submit">Login</button>
    </form>
@endsection
