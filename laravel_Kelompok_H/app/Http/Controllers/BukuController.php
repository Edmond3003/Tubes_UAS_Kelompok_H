<?php

namespace App\Http\Controllers;
use App\Http\Resources\BukuResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buku = Buku::latest()->get();

        return new BukuResource(
            true,
            'List Data Buku',
            $buku
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_buku' => 'required',
            'jenis_buku' => 'required',
            'genre_buku' => 'required',
            'harga_buku' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $buku = Buku::create([
            'judul_buku' => $request->judul_buku,
            'jenis_buku' => $request->jenis_buku,
            'genre_buku' => $request->genre_buku,
            'harga_buku' => $request->harga_buku
        ]);
        return new BukuResource(true, 'Data Buku
        Berhasil Ditambahkan!', $buku);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $buku = Buku::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Buku',
            'data'    => $buku
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buku $buku)
    {
        $validator = Validator::make($request->all(), [
            'judul_buku' => 'required',
            'jenis_buku' => 'required',
            'genre_buku' => 'required',
            'harga_buku' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $buku = Buku::findOrFail($buku->id);

        if ($buku) {

            //update buku
            $buku->update([
                'judul_buku' => $request->judul_buku,
                'jenis_buku' => $request->jenis_buku,
                'genre_buku' => $request->genre_buku,
                'harga_buku' => $request->harga_buku
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departemen Updated',
                'data'    => $buku
            ], 200);
        }


        return response()->json([
            'success' => false,
            'message' => 'Departemen Not Found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $buku = Buku::findOrfail($id);

        if ($buku) {

            $buku->delete();

            return response()->json([
                'success' => true,
                'message' => 'Buku Deleted',
            ], 200);
        }


        return response()->json([
            'success' => false,
            'message' => 'Buku Not Found',
        ], 404);
    }
}
