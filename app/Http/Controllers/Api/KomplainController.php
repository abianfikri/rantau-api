<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KomplainModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $komplain = KomplainModel::where('id_fk_user', Auth::id())
                ->orderBy("id_pk_komplain", "desc")
                ->get();

            $response = array(
                'status' => "success",
                'message' => "Data Komplain",
                'data' => $komplain
            );
            $code = 200;
        } catch (Exception $e) {
            $response = array(
                'status' => "error",
                'message' => $e->getMessage()
            );
            $code = 500;
        }

        return response()->json($response, $code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $komplain = KomplainModel::create([
                'id_fk_user' => Auth::id(),
                'komplain_jenis' => $request->komplain_jenis,
                'komplain_deskripsi' => $request->komplain_deskripsi,
                'komplain_tanggal_buat' => now(),
            ]);

            $response = array(
                'status' => "success",
                'message' => "Komplain berhasil dibuat",
                'data' => $komplain
            );
            $code = 200;
        } catch (Exception $e) {
            $response = array(
                'status' => "error",
                'message' => $e->getMessage()
            );
            $code = 500;
        }

        return response()->json($response, $code);
    }

    /**
     * Display the specified resource.
     */
    public function show(KomplainModel $komplainModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KomplainModel $komplainModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KomplainModel $komplainModel)
    {
        //
    }
}
