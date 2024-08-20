@extends('layouts.app')
@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                @include('layouts.sidebar')
            </div>
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow bg-dark">
                    <div class="card-header  text-white bg-danger">
                        Profile
                    </div>
                    <div class="card-body">
                        <form action="{{route('account.updateprofile')}}" method="post" enctype="multipart/form-data">
                            @csrf
                        <div class="mb-3">
                            <label for="name" class="@error('name') is-invalid @enderror form-label text-white">Name</label>
                            <input type="text" value="{{old('name',$user->name)}}" class="form-control" placeholder="Name" name="name" id="" />
                            @error('name')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="@error('email') is-invalid @enderror form-label text-white">Email</label>
                            <input type="text" value="{{old('email', $user->email)}}" class="form-control" placeholder="Email"  name="email" id="email"/>
                            @error('email')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label text-white">Image</label>
                            <input type="file" name="image" id="image" class="@error('image') is-invalid @enderror btn btn-outline-primary form-control">
                            @error('image')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                        @if (Auth::user()->image!="")
                        <img width="150" src="{{asset('uploads/profile/thumb/'.Auth::user()->image)}}" class="img-fluid mt-4" alt=""> 
                        @endif
                        </div>   
                        <button class="btn btn-outline-primary mt-2 form-control">Update</button>    
                    </form>                 
                    </div>
                </div>                
            </div>
        </div>       
    </div>

@endsection