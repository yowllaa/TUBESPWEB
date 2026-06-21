@extends('layouts.dashboard')

@section('page-title', 'Ubah Profil Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Info Form -->
    <div class="p-6 sm:p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Password Update Form -->
    <div class="p-6 sm:p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account Form -->
    <div class="p-6 sm:p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
