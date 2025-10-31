@extends('layouts.auth')
<div class="auth-wrapper">
    <form action="{{ route('loginPost') }}" method="POST">

        @csrf
        <div class="mb-3">
            <label for="Email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="Email" name="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="Password" class="form-label">Password</label>
            <input type="password" class="form-control" id="Password" name="password">
        </div>

        <div id="info" class="form-text">
            <a href="{{ route('registerView') }}">New User?</a>
            <a href="{{ route('forget-password') }}">forget password</a>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
