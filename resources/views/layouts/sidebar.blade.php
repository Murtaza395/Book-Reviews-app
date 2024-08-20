<div class="card border-0 shadow-lg">
    <div class="card-header bg-danger text-white">
        Welcome, {{Auth::user()->name}}                        
    </div>
    <div class="card-body bg-dark">
        <div class="text-center mb-3 text-white">
            @if (Auth::user()->image!="")
            <img src="{{asset('uploads/profile/thumb/'.Auth::user()->image)}}" class="img-fluid rounded-circle bg-dark" alt=""> 
            @endif
                                       
        </div>
        <div class="h5 text-center text-white">
            <strong>{{Auth::user()->name}}</strong>
            <p class="h6 mt-2 text-white">{{(Auth::user()->reviews->count()>0)?Auth::user()->reviews->count().' Reviews':Auth::user()->reviews->count().' Review'}}</p>
        </div>
    </div>
</div>
<div class="card border-0 shadow-lg mt-3">
    <div class="card-header bg-danger  text-white">
        Navigation
    </div>
    <div class="card-body sidebar bg-dark">
        <ul class="nav flex-column">
            @if (Auth::user()->role=='admin')
            <li class="nav-item">
                <a href="{{route('books.index')}}"class="btn btn-outline-success text-white">Books</a>                               
            </li>
            <li class="nav-item">
                <a href="{{route('account.reviews')}}"class="btn btn-outline-success text-white">Reviews</a>                               
            </li>
            @endif
        
            <li class="nav-item">
                <a href="{{route('account.profile')}}"class="btn btn-outline-success text-white">Profile</a>                               
            </li>
            <li class="nav-item">
                <a href="{{route('account.myReviews')}}"class="btn btn-outline-success text-white">My Reviews</a>
            </li>                          
        </ul>
    </div>
</div>