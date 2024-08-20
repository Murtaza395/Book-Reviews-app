<?php

namespace App\Http\Controllers;

use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class ReviewController extends Controller
{
    public function index(Request $request){
        $reviews =review::with('book','user')->orderBy('created_at','desc');
        if(!empty($request->keyword)){
           $reviews= $reviews->where('review','like','%'.$request->keyword.'%');
        }
       $reviews=$reviews->paginate(10);
        return view('account.reviews.list',[
            'reviews'=>$reviews,
        ]);
    }
    public function edit($id){
        $review =review::findOrFail($id);
        return view('account.reviews.edit',[
            'review'=>$review
        ]);
    }
    public function update(Request $request, $id){
        $review =review::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'review'=>'required',
            'status'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('account.reviews.edit',$id)->withInput()->withErrors( $validator );
        }
        $review->review=$request->review;
        $review->status=$request->status;
        $review->save();
        return redirect()->route('account.reviews')->with('success','Review updated successfully');

    }
    public function delete($id){

        try {
            $review = Review::findOrFail($id); // Fetch the review or fail
            $review->delete(); // Delete the review
            session()->flash('success', 'Review deleted successfully');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the review');
            // Optionally log the exception
            Log::error('Delete review error: ' . $e->getMessage());
        }
        
        // Redirect to the appropriate route
        return redirect()->route('account.reviews');
        }


    }
