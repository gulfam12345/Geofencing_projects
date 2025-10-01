@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">My Profile</h2>
        <p class="text-muted">Manage your account information</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Enter your name">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ $user->address }}" placeholder="Enter your address">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Profile Image</label><br>
                    <img id="previewImage" 
                         src="{{ $user->profile_image ? asset('uploads/'.$user->profile_image) : '' }}" 
                         width="120" 
                         class="rounded-circle mb-2 border border-secondary"><br>
                    <input type="file" name="profile_image" id="profileImageInput" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>

            <form action="{{ route('profile.delete') }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">Delete Account</button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.getElementById('profileImageInput').addEventListener('change', function(e){
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                document.getElementById('previewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
