@extends('layouts.base')

@section('title', 'Settings')

@section('content')
    <h2>Settings</h2>

    <article>
        

        @if ($errors->any())
            <div class="uk-alert uk-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="" method="POST" class="form">
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
