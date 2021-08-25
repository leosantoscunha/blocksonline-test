@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <h2>Login</h2>
            <form data-action="{{ route('auth') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="username">Enter Email</label>
                    <input type="text" id="username" name="username" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="password">Enter Password</label>
                    <input id="password" type="password" name="password" class="form-control" required/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary"/>
                    <a href="{{route('user.create')}}">register</a>
                </div>
            </form>
        </div>
    </div>
@endsection
<script src="{{ asset('js/login.js') }}" defer></script>
