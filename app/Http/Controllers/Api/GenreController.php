<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Models\Genre; // harus ada ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class GenreController extends Controller
{
    public function index(){
        $genres = Genre::all(); 
        return new GenreResource(true,"Get All Resource", $genres);
       
    //    if($genres->isEmpty()) {
    //     return response()->json([ //ketika tdk berhasil
    //         "status" => true,
    //         "message" => "Resource data not found!",
    //     ], 200);
    //    }
       
       
    //     return response()->json([ //ketika berhasil
    //         "status" => true,
    //         "message" => "Get All Resource",
    //         "data" => $genres
    //     ], 200);
    }

    public function store(Request $request){
        //membuat validasi
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "description" => "required|string"
        ]);

        // melakukan cek data yang bermasalah
        if($validator->fails()) {
            return response()->json([ 
                "succcess" => false,
                "message" => $validator->errors()
            ], 404);
        }
        
        //membuat data genre
        $genre = Genre::create([
            "name" => $request -> name,
            "description" => $request -> description
        ]); // dari request validator

        //memberi pesan berhasil
        return response()->json([ //ketika berhasil
            "success" => true,
            "message" => "Resource added succesfully",
            "data" => $genre
        ], 201);
    }

    public function show(string $id){
        $genre = Genre::find($id);

        if(!$genre) {
            return response()->json([ 
                "succcess" => false,
                "message" => "Resource not found!"
            ], 404);
        };

        return response()->json([ //ketika berhasil
            "success" => true,
            "message" => "Get all resource",
            "data" => $genre
        ], 200);
    }

    public function update(Request $request, string $id){
        //cari data genre
        $genre = Genre::find($id);

        if(!$genre) {
            return response()->json([ 
                "succcess" => false,
                "message" => "Resource not found!"
            ], 404);
        };

        //membuat validasi
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "description" => "required|string"            
        ]);

        // melakukan cek data yang bermasalah
        if($validator->fails()) {
            return response()->json([ 
                "succcess" => false,
                "message" => $validator->errors()
            ], 422);
        }

        //$genre->update($request->only("name", "description")); //salah satu cara atau pakai array kaya dibawah
        $genre->update([
            "name" => $request->name,
            "bio" => $request->bio
        ]);

        return response()->json([ 
           "succcess" => true,
            "message" => "Resource updated successfully!",
            "data" => $genre
        ], 200);

    }

    public function destroy(string $id){
        $genre = Genre::find($id);

        if(!$genre) {
            return response()->json([ 
                "succcess" => false,
                "message" => "Resource not found!",
            ], 404);
        };

        $genre->delete();

        return response()->json([ 
            "succcess" => true,
            "message" => "Resource deleted successfully",
        ], 200);

    }
}
