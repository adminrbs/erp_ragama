<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\book;

class BookController extends Controller
{
    //add book
 public function save_book(Request $request){
    try{
        $book = new book();
        $book->book_name = $request->input('txtBookName');
        $book->book_number = $request->input('txtBookNumber');
        $book->book_type_id = $request->input('bookType_id');
        if($book->save()){
            return response()->json(['message' => 'Book saved successfully', 'status' => true]);
        }
       
       

    }catch(\Exception $ex){
        return $ex;
 }
}

//get book list
public function get_book_list(){
    try{
        $book = DB::select("SELECT
        books.book_id,
        books.book_number,
        books.book_name,
        CASE
            WHEN books.book_type_id = 1 THEN 'Sales Return Books'
            WHEN books.book_type_id = 2 THEN 'Cash Books'
            WHEN books.book_type_id = 3 THEN 'Cheque Books'
            ELSE 'Unknown Book Type'
        END AS book_type_name
    FROM
        books;
    ");
        if($book){
            return response()->json(['status' => true, 'data' => $book]);
        }else{
            return response()->json(['status' => false, 'data' => []]);
        }
    
    }catch(\Exception $ex){
        return $ex;
 } 
}

//get book data
public function getBook_data($id){
    try{
        $book = book::find($id);
        if($book){
            return response()->json(['status' => true, 'data' => $book]);
        }else{
            return response()->json(['status' => false, 'data' => []]);
        }
    
    }catch(\Exception $ex){
        return $ex;
 } 
}

//update book
public function updateBook(Request $request,$id){
    try{
        $book = book::find($id);
        $book->book_name = $request->input('txtBookName');
        $book->book_number = $request->input('txtBookNumber');
        $book->is_active = $request->input('status');
        $book->book_type_id = $request->input('bookType_id');
        if($book->update()){
            return response()->json(['message' => 'Book updated successfully', 'status' => true]);
        }else{
            return response()->json(['message' => 'Book updated failed', 'status' => false]);
        }

    }catch(Exception $ex){
        return $ex;
    }
}

//delete book
public function deleteBook($id){
    try{
        $book = book::find($id);
        if($book->delete()){
            return response()->json(['message' => 'Book deleted successfully', 'status' => true]); 
        }else{
            return response()->json(['message' => 'Book deleted failed', 'status' => false]);
        }
        
    }catch(Exception $ex){
        return $ex;
    }
}
}
