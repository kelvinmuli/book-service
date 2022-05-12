<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Author;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authors = Author::all();
        return Helpers::jsonResponse(false,
            200,
            $authors,
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
        //
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
            'name' => 'required|string',
            'email' => 'required|email|unique:authors',
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

        $author = new Author([
            'name'=> $request->name,
            'email'=> $request->email
        ]);

        //save request
        if($author->save())
        {
            return Helpers::jsonResponse(false,
                201,
                $author,
                "Author created successfully"
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $author = Author::find($id);
        if(!$author)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Author details",
                ['No Author found']
            );
        }
        return Helpers::jsonResponse(false,
            200,
            $author,
            '',
            []
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if(!$author)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Author details",
                ['No Author found']
            );
        }

        $dataRequest = $request->json()->all();
        $validator = Validator::make($dataRequest, [
            'name' => 'required|string',
            'email' => 'required|email|unique:authors,email,'.$id,
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

        $author->name = $request->name;
        $author->email = $request->email;

        //update request
        if($author->update())
        {
            return Helpers::jsonResponse(false,
                202,
                $author,
                "Author updated successfully"
            );//202 accepted
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if(!$author)
        {
            return Helpers::jsonResponse(true,
                404,
                null,
                "Invalid Author details",
                ['No Author found']
            );
        }
        if($author->delete())
        {
            return Helpers::jsonResponse(false,
                200,
                '',
                'Author deleted successfully',
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
