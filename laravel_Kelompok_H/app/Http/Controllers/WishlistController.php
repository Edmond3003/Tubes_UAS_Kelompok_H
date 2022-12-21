<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishlist = Wishlist::with('buku')->latest()->get();

        return new WishlistResource(
            true,
            'List Data Wishlist',
            $wishlist
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
            'id_buku' => 'required',
            'id_user' => 'required',
            'keterangan' => 'required',
            'status' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $wishlist = Wishlist::create([
            'id_buku' => $request->id_buku,
            'id_user' => $request->id_user,
            'keterangan' => $request->keterangan,
            'status' => $request->status

        ]);
        return new WishlistResource(true, 'Data Wistlist
        Berhasil Ditambahkan!', $wishlist);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find post by ID
        $wishlist = Wishlist::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Wishlist',
            'data'    => $wishlist
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required',
            'keterangan' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $wishlist = Wishlist::findOrFail($wishlist->id);

        if ($wishlist) {

            //update wishlist
            $wishlist->update([
            'id_buku' => $request->id_buku,
            'keterangan' => $request->keterangan,
            'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wishlist Updated',
                'data'    => $wishlist
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Wishlist Not Found',
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
        $wishlist = Wishlist::findOrfail($id);

        if ($wishlist) {

            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wishlist Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Wishlist Not Found',
        ], 404);
    }
}
