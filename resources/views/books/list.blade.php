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
                        Books
                    </div>
                    <div class="card-body pb-0 bg-dark">   
                        <div class="d-flex justify-content-between">
                            <a href="{{route('books.create')}}" class="btn btn-outline-primary">Add Book</a>
                                <form action="" method="get">
                                    <div class="d-flex">
                                <input type="text" name="search" id="search" class="form-control" value="{{Request::get('search')}}" placeholder="Keyword">
                                <button type="submit" class="btn btn-outline-primary ms-2">Search</button>
                                <a href="{{route('books.index')}}" class="btn btn-outline-primary ms-2">Clear</a>
                            </div>
                            </form>
                        </div>                
                        <table class="table  table-striped mt-3">
                            <thead class="table-danger">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                                <tbody class="table-dark border border-primary">
                                    @if ($books->isNotEmpty())
                                    @foreach ($books as $book )
                                    @php
                                    if($book->reviews_count > 0){
                                      $avgRating=$book->reviews_sum_rating/$book->reviews_count;
                                       }
                                      else{
                                            $avgRating=0;
                                          }
                                              $avgRatingPer=($avgRating*100)/5;
                                               @endphp
                                    <tr>
                                        <td>{{$book->title}}</td>
                                        <td> {{$book->author}}</td>
                                        <td>{{number_format($avgRating,1)}} ({{($book->reviews_count > 1)? $book->reviews_count.'Reviews':$book->reviews_count.'Review'}})</td>
                                        <td>
                                            @if ($book->status==1)
                                            <strong class="text-success">Active</strong>
                                                @else
                                                <strong class="text-danger">Block</strong>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('books.edit',$book->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" onClick="deleteBook({{$book->id}})"  class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            <form id="deleteBookFrom{{$book->id}}" action="{{route('books.destroy',$book->id)}}" method="post">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                Books not found
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </thead>
                        </table>   
                        @if ($books->isNotEmpty())
                        {{$books->links()}}
                        @endif
                       
                       {{-- <nav aria-label="Page navigation " >
                            <ul class="pagination">
                              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                              <li class="page-item"><a class="page-link" href="#">1</a></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#">3</a></li>
                              <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                          </nav>  --}}                
                    </div>
                    
                </div>                
            </div>
        </div>             
            </div>
        </div>       
    </div>

@endsection
@section('script')
<script>
    function deleteBook(id){
        if(confirm("Are you sure you want to delete")){
            document.getElementById("deleteBookFrom"+id).submit();
        }
    }
    </script>
@endsection