@extends('layouts.base')

@section('title', 'Settings')

@section('content')
    <h2>Settings</h2>

    <article>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="" method="POST">
            @csrf

            <label>Username</label>
            <input type="text"
                name="username"
                value="{{ old('username', Auth::user()->username) }}"/>

            <label>Password</label>
            <input type="password"
                name="password"
                placeholder="Password"/>

            <input type="password"
                name="password_confirmation"
                placeholder="Password Confirmation"/>

            <input type="submit" value="Save"/>
        </form>

    </article>

        

@endsection
