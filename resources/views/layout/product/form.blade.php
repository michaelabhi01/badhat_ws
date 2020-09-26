@extends('layout.master')
@section('title')
Add/Modify
@endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }} </li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-validation">

    <form action="{{ $user==NULL? route('user.create'): route('users.edit',['id'=>$user->id]) }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-firstname">First Name <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="val-firstname" name="first_name"
                    placeholder="Enter First Name" value="{{ $user->first_name ?? old('first_name') }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-lastname">Last Name <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="val-lastname" name="last_name" placeholder="Enter Last Name"
                    value="{{ $user->last_name ?? old('last_name') }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-email">Email <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="val-email" name="email" placeholder="Your valid email.."
                    value="{{ $user->email ?? old('email') }}">
            </div>
        </div>
        {{-- @if ($user!=NULL)
        <div> ** Enter passwords only when you want to change it. Old password will be replaced with new one. </div>
        @endif --}}

        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-password">Password <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <input type="password" class="form-control" id="val-password" name="password"
                    placeholder="Atleast 6 digits long" autocomplete="false">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-confirm-password">Confirm Password <span
                    class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <input type="password" class="form-control" id="val-confirm-password" name="confirm_password"
                    placeholder="Same as Password" autocomplete="false">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-skill">Department <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <select class="form-control" id="val-skill" name="department_id">
                    <option value="-1">Select Department</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ ( $user!=null && $department->id == $user->department_id) ? 'selected':'' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="val-skill">Role <span class="text-danger">*</span>
            </label>
            <div class="col-lg-6">
                <select class="form-control" id="val-skill" name="role_id">
                    <option value="-1">Select Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ ($user!=null && $role->id == $user->role_id) ? 'selected':'' }}>{{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-8 ml-auto">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

@endsection
