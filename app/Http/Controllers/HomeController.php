<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request){
        $books=Book::withCount('reviews')->withSum('reviews','rating')->orderBy("created_at","desc");
        if(!empty($request->keyword)){
        $books->where('title','like','%'.$request->keyword.'%');
        }
        $books =$books->where('status',1)->paginate(8);
            return view("home",[
                'books'=> $books,
            ]);
    }
    public function detail($id){
        $book=Book::with(['reviews.user','reviews'=>function($query){
                $query->where('status',1);
        }])->withCount('reviews')->withSum('reviews','rating')->findOrFail($id);
        if($book->status==0){
            abort(404);
        }
        $relatedBooks=Book::where('status',1)->take(3)->withCount('reviews')->withSum('reviews','rating')->where('id','!=',$id)->inRandomOrder()->get();
        return view('book-detail',[
            'book' => $book,
            'relatedBooks'=> $relatedBooks
        ]);
    }
    public function saveReview(Request $request){
        $validator = Validator::make($request->all(),[
            'review'=>'required|min:10',
            'rating'=>'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        $countReview=Review::where('user_id',FacadesAuth::user()->id)->where('book_id',$request->book_id)->count();
        if($countReview> 0){
            session()->flash('error','You already submitted review');
            return response()->json([
                'status' =>true,
            ]);
        }
        $review = new review();
        $review->review=$request->review;
        $review->rating=$request->rating;
        $review->user_id=FacadesAuth::user()->id;
        $review->book_id=$request->book_id;
        $review->save();
        
        session()->flash('success','Review submitted successfully.');

        return response()->json([
            'status' =>true,
        ]);
    }
}
