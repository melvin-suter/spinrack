@extends('layouts.base')

@section('title', 'User Management')

@section('content')
    <h1>User Management</h1>


        
    <article>
        <h3>Add User</h3>
        
        @if ($errors->any())
            <div class="uk-alert uk-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/settings/users/add" method="POST" class="form">
            @csrf
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username', '') }}"/>

            <label>Password</label>
            <input type="password" name="password"/>

            <label>Is Admin</label>
            <select name="is_admin">
                <option value="1">Admin</option>
                <option value="0">User</option>
            </select>

            <input type="submit" value="Create" />

        </form>

    </article>

    <article>

        <table class="uk-table table">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Username</td>
                    <td>Password</td>
                    <td>Is Admin</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @if($user->id != Auth::user()->id)
                        <form action="/settings/users/{{$user->id}}" method="POST">
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    @csrf
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}"/>
                                </td>
                                <td>
                                    <input type="password" name="password" value=""/>
                                </td>
                                <td>
                                    <select name="is_admin">
                                        <option value="1" {{$user->is_admin ? 'selected': ''}}>Admin</option>
                                        <option value="0" {{!$user->is_admin ? 'selected': ''}}>User</option>
                                    </select>    
                                </td>
                                <td>
                                    <input type="submit" value="Save" class="no-full-width"/>
                                    <a href="/settings/users/{{$user->id}}/delete" class="button danger no-full-width">Delete</a>
                                </td>
                            </tr>
                        </form>
                    @else
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="wrap">{{ $user->username }}</td>
                            <td></td>
                            <td class="wrap">{{ $user->is_admin ? "Admin": "User" }}</td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </article>


@endsection
