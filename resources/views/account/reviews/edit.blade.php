@extends('layouts.app')
@section('main')

<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')             
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow">
                <div class="card-header bg-danger  text-white">
                   Edit Reviews
                </div>
                <div class="card-body pb-0 bg-dark">   
                    <form action="{{route('account.reviews.update',$review->id)}}" method="post">
                        @csrf
                    <div class="mb-3">
                        <label for="review" class="form-label text-white">Review</label>
                       <textarea name="review" id="review"class="@error('review') is-invalid @enderror form-control" rows="5" placeholder="Review">{{old('review',$review->review)}}</textarea>
                        @error('review')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label text-white">Status</label>
                        <select name="status" id="status" class="@error('status') is-invalid @enderror form-control">
                            <option value="1"{{($review->status==1)?'selected':''}}>Active</option>
                            <option value="0"{{($review->status==0)?'selected':''}}>Block</option>
                        </select>
                        @error('status')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                    </div>  
                    <button class="btn btn-outline-primary mt-2 mb-2 form-control">Update</button>    
                </form>                  
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection