@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')

<div class="page-header">
    <h2>👤 Profile Settings</h2>
    <p>Manage your account and security settings</p>
</div>

<div class="profile-grid">

    <!-- PROFILE INFO -->
    <div class="panel">
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- PASSWORD -->
    <div class="panel">
        @include('profile.partials.update-password-form')
    </div>

    <!-- DELETE ACCOUNT -->
    <div class="panel danger">
        @include('profile.partials.delete-user-form')
    </div>

</div>

@endsection
