<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PembayaranModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // GET QRIS
    public function fetchPembayaran(Request $request): JsonResponse
    {
        try {
            $response = array(
                'status' => true,
                "message" => "Berhasil memproses data",
                "data" => array(
                    "qris" => "https://indonesiaberbagi.id/wp-content/uploads/2021/12/qr-code-dana-2.jpeg",
                    "nominal" => "Rp 2.100.000",
                    "nama_bank" => "BCA",
                    "logo_bank" => "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1280px-Bank_Central_Asia.svg.png",
                    "nama_rekening" => "PT Altrusights",
                    "no_rekening" => "1122334455"
                )
            );
            $code = 200;
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            $code = 500;
        }

        return response()->json($response, $code);
    }

    public function valdisiReservasi(Request $request): JsonResponse
    {
        try {
            $response = array(
                'status' => true,
                'message' => 'Data Reservasi Ditemukan',
                'data' => [
                    'kos' => array(
                        "aturan" => "# Aturan Kos\n\n1. Jangan telat bayar kos\n2. Jangan numpuk sampah di kamar, bau woi!\n3. Jangan lupa ibadah, hanya karna jauh dari rumah, enak aja bolos ibadah\n4. Cari kerja yang bener, biar berguna, ga jadi beban terus. Yang kuliah, tolong kuliah yang bener, jangan suka bolos kelas, apalagi titip absen. Ingat, ortu bayar mahal!\n5. Jangan pakai paylater, kita ga mau ada debt collector ke kos, merusak citra kos!\n6. Kalo baliknya malam banget, kabarin ke pengelola kos. Jangan keluar seenak jidat!",
                        "alamat" => "Jl. Maju Mundur Tabrak Tiang, Gg Ikhlasin Aja, No 01",
                        "pengelola" => array(
                            ["Asri", "081234567890", "asri@example.net"],
                            ["Isra", "089876543210", "isra@example.net"]
                        )
                    )
                ]
            );
            $code = 200;
        } catch (Exception $e) {
            $response =  array(
                'status' => false,
                'message' => $e->getMessage()
            );
            $code = 500;
        }
        return response()->json($response, $code);
    }

    // POST Pembayaran
    public function storePembayaran(Request $request): JsonResponse
    {
        try {
            // Pastikan direktori 'pembayaran' ada di storage/public
            if (!Storage::disk('public')->exists('pembayaran')) {
                Storage::disk('public')->makeDirectory('pembayaran');
            }

            $file = $request->file("pembayaran_bukti_bayar");
            $originalName = $file->getClientOriginalName();
            $fileName = time() . "_" . $originalName;

            $file->storeAs('pembayaran', $fileName, 'public');

            // Hitung tanggal jatuh tempo berdasarkan durasi
            $tanggalBuat = Carbon::now();
            $durasi = (int) $request->pembayaran_durasi;
            $tanggalJatuhTempo = $tanggalBuat->copy()->addMonths($durasi);

            $durasiNama = match ($durasi) {
                1 => "1 Bulan",
                3 => "3 Bulan",
                6 => "6 Bulan",
                default => "Durasi Tidak Valid"
            };

            $pembayaran = new PembayaranModel();
            $pembayaran->id_fk_user = $request->id_fk_user;
            $pembayaran->pembayaran_nama = $request->pembayaran_nama;
            $pembayaran->pembayaran_nominal = $request->pembayaran_nominal ?? 0;
            $pembayaran->pembayaran_durasi = $request->pembayaran_durasi;
            $pembayaran->pembayaran_durasi_nama = $durasiNama;
            $pembayaran->pembayaran_bukti_bayar = $fileName; // Hanya simpan nama file
            $pembayaran->pembayaran_tanggal_buat = $tanggalBuat;
            $pembayaran->pembayaran_tanggal_jatuh_tempo = $tanggalJatuhTempo;
            $pembayaran->save();

            $response = array(
                'status' => true,
                'message' => 'Pembayaran Berhasil Disimpan',
                'data' => $pembayaran
            );
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
        return response()->json($response);
    }
}
