@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>User Details</span>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">ID</label>
                        <div class="col-md-8">
                            {{ $user->id }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">Name</label>
                        <div class="col-md-8">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">Email</label>
                        <div class="col-md-8">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">Email Verified At</label>
                        <div class="col-md-8">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i:s') : 'Not verified' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">Created At</label>
                        <div class="col-md-8">
                            {{ $user->created_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 text-muted">Updated At</label>
                        <div class="col-md-8">
                            {{ $user->updated_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
