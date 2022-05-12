<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $books = Book::all();
        //return response()->json(['books' => $books], 200);
        return Helpers::jsonResponse(false,
            200,
            $books,
            '',
            []
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $dataRequest = $request->json()->all();
        $validator = Validator::make($dataRequest, [
            'author_id' => 'required|numeric',
            'name' => 'required|string',
            'isbn' => 'required|string|unique:books',
        ]);

        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return Helpers::jsonResponse(true,
                422,
                null,
                "Validation error occurred",
                $validator->errors()->toArray()
            );
        }

        $book = new Book([
            'author_id'=> $request->author_id,
            'name'=> $request->name,
            'isbn'=> $request->isbn,
            'published_date'=> $request->published_date ?? ''
        ]);

        //save request
        if($book->save())
        {
            return Helpers::jsonResponse(false,
                201,
                $book,
                "Book created successfully"
            );//201 created
        }
        else{
            return Helpers::jsonResponse(false,
                500,
                null,
                "Failed, problem occurred",
                ['Server error problem']
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Book::with('author')->find($id);
        if(!$book)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Book details",
                ['No Book found']
            );
        }
        return Helpers::jsonResponse(false,
            200,
            $book,
            '',
            []
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if(!$book)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Author details",
                ['No Book found']
            );
        }
        $dataRequest = $request->json()->all();
        $validator = Validator::make($dataRequest, [
            'author_id' => 'required|numeric',
            'name' => 'required|string',
            'isbn' => 'required|string|unique:books,isbn,'.$id,
        ]);

        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return Helpers::jsonResponse(true,
                422,
                null,
                "Validation error occurred",
                $validator->errors()->toArray()
            );
        }

        //check if author exist
        $author = Author::find($request->author_id);
        if(!$author)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Book NOT updated, author not found",
                ['No Author found']
            );
        }

        $book->author_id = $request->author_id;
        $book->name = $request->name;
        $book->isbn = $request->isbn;
        $book->published_date = $request->published_date ?? '';

        //save request
        if($book->save())
        {
            return Helpers::jsonResponse(false,
                202,
                $book,
                "Book updated successfully"
            );//201 created
        }
        else{
            return Helpers::jsonResponse(false,
                500,
                null,
                "Failed, problem occurred",
                ['Server error problem']
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Author::find($id);
        if(!$book)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Book details",
                ['No Book found']
            );
        }
        if($book->delete())
        {
            return Helpers::jsonResponse(false,
                200,
                '',
                'Book deleted successfully',
                []
            );
        }
        else{
            return Helpers::jsonResponse(false,
                500,
                null,
                "Failed, problem occurred",
                ['Server error problem']
            );
        }
    }
}
