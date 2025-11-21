@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-yellow-400 to-yellow-600 bg-clip-text text-transparent mb-4">Profile Settings</h1>
                <p class="text-gray-300 text-lg">Manage your account information and preferences</p>
            </div>

            <div class="grid gap-8 md:grid-cols-1 lg:grid-cols-1">
                <!-- Profile Information -->
                <div class="bg-gray-800 backdrop-filter backdrop-blur-lg rounded-2xl shadow-2xl border border-gray-700 p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white mb-2">Profile Information</h2>
                        <p class="text-gray-400">Update your account's profile information and email address.</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Password Update -->
                <div class="bg-gray-800 backdrop-filter backdrop-blur-lg rounded-2xl shadow-2xl border border-gray-700 p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white mb-2">Update Password</h2>
                        <p class="text-gray-400">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div class="bg-red-900/20 backdrop-filter backdrop-blur-lg rounded-2xl shadow-2xl border border-red-700/50 p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-red-300 mb-2">Delete Account</h2>
                        <p class="text-red-400">Permanently delete your account and all associated data.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
