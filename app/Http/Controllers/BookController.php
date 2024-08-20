<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    //this method will show books listing page
    public function index(Request $request){
        $books=Book::orderBy("created_at","desc");
        if(!empty($request->search)){
            $books = $books->where("title","like","%".$request->search."%");
        }
     $books= $books->withCount('reviews')->withSum('reviews','rating')->paginate(10);
        return view('books.list',[
            'books'=> $books,
        ]);
    }
    //this method will create books listing page
    public function create(){
        return view('books.create');
    }
    //this method will store books
    public function store(Request $request){
        $rules=
            [
                'title'=>'required|min:5',
                'author'=>'required|min:4',
                'status'=>'required',
            ];
            if($request->image!=""){
                $rules['image'] = 'image';
            }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return redirect()->route('books.create')->withInput()->withErrors($validator);
            
        }
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        if($request->image!=""){
            $image = $request->image;
            $ext =$image->getClientOriginalExtension();
            $imageName=time().'.'.$ext;
            $image->move(public_path('uploads/books'),$imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }
        return redirect()->route('books.index')->with('success','Book added successfully.');

        }
    //this method will edit books name
    public function edit($id, Request $request){
        $book = Book::findOrFail($id);
        
        return view('books.edit',[
            'book'=>$book,
        ]);
    }
    //this method will update all the things
    public function update($id,Request $request){
        $book=Book::findOrFail($id);
        $rules=
            [
                'title'=>'required|min:5',
                'author'=>'required|min:4',
                'status'=>'required',
            ];
            if($request->image!=""){
                $rules['image'] = 'image';
            }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return redirect()->route('books.edit',$book->id)->withInput()->withErrors($validator);
            
        }
       
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        if($request->image!=""){
            File::delete(public_path("uploads/books/".$book->image));
            File::delete(public_path("uploads/books/thumb/".$book->image));
            $image = $request->image;
            $ext =$image->getClientOriginalExtension();
            $imageName=time().'.'.$ext;
            $image->move(public_path('uploads/books'),$imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }
        return redirect()->route('books.index')->with('success','Book updated successfully.');

    }
    //this method will delete the book page
    public function destroy($id){
        $book = Book::findorFail($id);
        if($book==null){
            return redirect()->route('books.index')->with('error','Book not found');
        }
        else{
            File::delete(public_path('uploads/books/'.$book->image));
            File::delete(public_path('uploads/books/thumb/'.$book->image));
            $book->delete();
            return redirect()->route('books.index')->with('success','Book deleted successfully');
        }
    }

}
