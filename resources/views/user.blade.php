@extends('layouts.app')
<style>
    /*input:invalid {*/
    /*    border: red solid 3px;*/
    /*}*/
</style>
@section('content')

    <div class="row justify-content-center">
        <div class="col-6">
            <h2>{{$title}}</h2>
            <form data-action="{{ isset($user) ? route('user.update', $user) : route('user.store') }}">
                {{ csrf_field() }}
                <input id="address_id"
                       type="hidden"
                       name="address_id"
                       value="{{isset($user) ? $user->address->id : null}}"/>
                <input id="user_id"
                       type="hidden"
                       name="user_id"
                       value="{{isset($user) ? $user->id : null}}"/>

                <div class="form-group">
                    <label for="userName">Name</label>
                    <input type="text" class="form-control" id="userName" name="userName" value="{{isset($user) && isset($user->user_name) ? $user->user_name : null}}" required>
                    @error('userName')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="userEmail">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" value="{{isset($user) && isset($user->user_email) ? $user->user_email : null}}" required>
                    @error('userEmail')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="userPassword" {{!isset($user->id) ? ' required' : null}}>
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    @error('userPassword')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="age">Age</label>
                    <input type="text" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])/(0[1-9]|1[012])/[0-9]{4}" placeholder="DD/MM/YYYY" class="form-control" id="age" name="age" value="{{isset($user) && isset($user->age) ? $user->age : null}}" required>
                    @error('age')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{isset($user) && isset($user->address->address) ? $user->address->address : null}}" required>
                    @error('address')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{isset($user) && isset($user->address->city) ? $user->address->city : null}}" required>
                    @error('city')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <label for="post_code">Postcode</label>
                    <input type="text" class="form-control" id="post_code" name="post_code" size="5" title="Pattern is not valid use XX-XXX" value="{{isset($user) && isset($user->address->post_code) ? $user->address->post_code : null}}" required>
                    @error('post_code')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
<script src="{{ asset('js/user.js') }}" defer></script>
