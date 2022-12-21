<?php

namespace App\Http\Controllers;
use App\Http\Resources\TransaksiResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksi = Transaksi::with('buku')->latest()->get();

        return new TransaksiResource(
            true,
            'List Data Transaksi',
            $transaksi
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

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = Transaksi::create([
            'id_buku' => $request->id_buku,
            'id_user' => $request->id_user,
        ]);
        return new TransaksiResource(true, 'Data Transaksi
        Berhasil Ditambahkan!', $transaksi);
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
         $transaksi = Transaksi::findOrfail($id);

         //make response JSON
         return response()->json([
             'success' => true,
             'message' => 'Detail Data Wishlist',
             'data'    => $transaksi
         ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transaksi = Transaksi::findOrFail($transaksi->id);

        if ($transaksi) {

            //update wishlist
            $transaksi->update([
            'id_buku' => $request->id_buku,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Updated',
                'data'    => $transaksi
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Transaksi Not Found',
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
        $transaksi = Transaksi::findOrfail($id);

        if ($transaksi) {

            $transaksi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Transaksi Not Found',
        ], 404);
    }
}
