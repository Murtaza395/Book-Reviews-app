<?php

namespace App\Http\Controllers;

use App\Models\review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    //This method shows register page
    public function register(){
        return view("account.register");
    }
    public function ProcessRegister(Request $request){
        $validator=Validator::make($request->all(),[
            "name" =>'required|min:3',
            "email"=>'required|email|unique:users',
            'password'=>'required|confirmed|min:8',
            'password_confirmation'=>'required'
        ]);
        if ($validator->fails()){
            return redirect()->route('account.register')->withErrors($validator)->withInput();
        }
        //Now Register User
        $user =new User();
        $user->name =$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();
        return redirect()->route('account.login')->with('success','You have registered successfully');
    }
    //This function is to handle login system
    public function login(){
        return view('account.login');
    }
    public function authenticate(Request $request){
        $validator=Validator::make($request->all(),[
                'email'=>'required|email',
                'password'=> 'required|min:8'
        ]);
        if($validator->fails()){
            return redirect()->route('account.login')->withErrors($validator)->withInput();
    }
        if(Auth::attempt(['email'=> $request->email,'password'=> $request->password])){
            return redirect()->route('account.profile')->with('success','You are logged in Successfully');
        }
        else{
            return redirect()->route('account.login')->with('error','Either email or password is incorrect.');
        }
}
//This method will show user profile page
public function profile(){
    $user =User::find(Auth::user()->id);
    return view('account.profile',[
        'user'=> $user,
    ]);
}
public function updateprofile(Request $request){
   $rules= [
        'name'=>'required|min:3',
        'email'=>'required|email|unique:users,email,'.Auth::user()->id.',id',

   ];
   if($request->image!=""){
    $rules['image'] = 'image';
   }
    $validator=Validator::make($request->all(),$rules);
    if($validator->fails()){
        return redirect()->route('account.profile')->withErrors( $validator)->withInput();
    }
    $user = User::find(Auth::user()->id);
    $user->name=$request->name;
    $user->email=$request->email;
    $user->save();

    //Delete old image here
    File::delete(public_path('uploads/profile/'.$user->image));
    File::delete(public_path('uploads/profile/thumb/'.$user->image));

    if($request->image!=""){
    $image=$request->image;
    $ext= $image->getClientOriginalExtension();
    $imageName=time().'.'.$ext;
    $image->move(public_path('uploads/profile'), $imageName);
    $user->image = $imageName;
    $user->save();

    $manager = new ImageManager(Driver::class);
    $img = $manager->read(public_path('uploads/profile/'.$imageName));
    $img->cover(150, 150);
    $img->save(public_path('uploads/profile/thumb/'.$imageName));
    }

    return redirect()->route('account.profile')->with('success','Profile Updated successfully');
}
public function logout(){
    Auth::logout();
    return redirect()->route('account.profile');
}
public function myReviews(Request $request){
    $reviews=review::with('book')->where('user_id',Auth::user()->id);
    $reviews=$reviews->orderBy('created_at','desc');
    if(!empty($request->keyword)){
        $reviews->where('review','like','%'.$request->keyword.'%');
    }
    $reviews=$reviews->paginate(10);
    return view('account.my-reviews.my-reviews',[
        'reviews'=> $reviews
    ]);
}
    public function editReview($id){
        $review = review::where([
            'id'=>$id,
            'user_id'=>Auth::user()->id,
        ])->with('book')->first();
          return view('account.my-reviews.edit-review',[
        'review'=> $review,
    ]);

    }
    public function updateReview(Request $request, $id){
        $review =review::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'review'=>'required',
            'rating'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('account.myReviews.editReview',$id)->withInput()->withErrors( $validator );
        }
        $review->review=$request->review;
        $review->rating=$request->rating;
        $review->save();
        return redirect()->route('account.myReviews')->with('success','Review updated successfully');


    }
    public function deleteReview($id){
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
        return redirect()->route('account.myReviews');
    }
}
