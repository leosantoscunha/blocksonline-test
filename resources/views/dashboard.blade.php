@extends('layouts.app')
<style>
    /*input:invalid {*/
    /*    border: red solid 3px;*/
    /*}*/
</style>
@section('content')

    <div class="row justify-content-center">
        <div class="col-6">
            <h2>User - {{$user->user_name}}</h2>
            <div class="form-group">
                <label for="userName">Name</label>
                <input type="text" class="form-control" id="userName" name="userName" disabled
                       value="{{$user->user_name}}">
                <label for="userEmail">Email</label>
                <input type="email" class="form-control" id="userEmail" name="userEmail" disabled
                       value="{{$user->user_email}}">
                <label for="age">Age</label>
                <input type="text" class="form-control" id="age" name="age" disabled value="{{$user->age}}">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" disabled
                       value="{{$user->address->address}}">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" disabled value="{{$user->address->city}}">
                <label for="post_code">Postcode</label>
                <input type="text" class="form-control" id="post_code" name="post_code"
                       value="{{$user->address->post_code}}"
                       disabled>
                <label for="registration_date">Registration Date</label>
                <input type="text" class="form-control" id="registration_date" name="registration_date" disabled value="{{$user->registration_date}}">
            </div>

            <a type="button" href="{{route('user.edit', $user)}}" class="btn btn-primary">Edit</a>
        </div>
    </div>
@endsection
<script src="{{ asset('js/user.js') }}" defer></script>
