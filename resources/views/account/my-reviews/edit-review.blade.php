@extends('layouts.app')
@section('main')

<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')             
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white bg-danger">
                   Edit Reviews
                </div>
                <div class="card-body pb-0 bg-dark">   
                    <form action="{{route('account.myReviews.updateReview',$review->id)}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <div>
                               <strong class="text-white bg-danger"> {{$review->book->title}}->(Name of the book) </strong>
                            </div>
                        </div>
                    <div class="mb-3">
                        <label for="review" class="form-label text-white">Review</label>
                       <textarea name="review" id="review"class="@error('review') is-invalid @enderror form-control" rows="5" placeholder="Review">{{old('review',$review->review)}}</textarea>
                        @error('review')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select name="rating" id="rating" class="@error('rating') is-invalid @enderror form-control">
                            <option value="1"{{($review->rating==1)?'selected':''}}>1</option>
                            <option value="2"{{($review->rating==2)?'selected':''}}>2</option>
                            <option value="3"{{($review->rating==3)?'selected':''}}>3</option>
                            <option value="4"{{($review->rating==4)?'selected':''}}>4</option>
                            <option value="5"{{($review->rating==5)?'selected':''}}>5</option>
                        </select>
                        @error('rating')
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