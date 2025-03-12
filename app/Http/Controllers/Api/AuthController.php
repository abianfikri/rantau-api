<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_email' => 'required|email',
                'user_password' => 'nullable|string',
                'firebase_uid' => 'nullable|string',
            ]);

            if ($request->has('firebase_uid')) {
                $user = User::where('user_email', $request->user_email)->first();

                if (!$user) {
                    // Jika user belum ada, buat akun baru dengan data Google
                    $user = User::create([
                        'user_nama' => $request->user_nama,
                        'user_email' => $request->user_email,
                        'firebase_uid' => $request->firebase_uid,
                        'user_password' => null,
                    ]);
                } elseif (!$user->firebase_uid) {
                    // Jika email sudah ada tapi belum punya Firebase UID, update UID
                    $user->update(['firebase_uid' => $request->firebase_uid]);
                }
            } else {
                // Login manual dengan email & password
                $user = User::where('user_email', $request->user_email)->first();

                if (!$user || !Hash::check($request->user_password, $user->user_password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Login gagal! Email atau password salah.',
                    ], 401);
                }
            }

            if ($user->tokens) {
                $user->tokens()->delete();
            }

            $token = $user->createToken('Personal Access Token')->plainTextToken;

            $response = array(
                'status' => 'success',
                'message' => 'Login Berhasil!',
                'data' => [
                    'type' => 'Bearer',
                    'token' => $token,
                    'user_nama' => $user->user_nama,
                    'user_email' => $user->user_email,
                    'user_no_hp' => $user->user_no_hp,
                ]
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

    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_nama' => 'required|string|max:255',
                'user_email' => 'required|email|unique:users,user_email',
                'user_password' => 'nullable|string',
                'user_no_hp' => 'nullable|string',
            ]);

            // Cek apakah email sudah ada di database
            $existingUser = User::where('user_email', $request->user_email)->first();

            if ($existingUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email sudah terdaftar.',
                ], 409);
            }

            // Jika email belum ada, buat akun baru
            $user = User::create([
                'user_nama' => $request->user_nama,
                'user_email' => $request->user_email,
                'user_no_hp' => $request->user_no_hp,
                'firebase_uid' => $request->firebase_uid,
                'user_password' => $request->user_password ? Hash::make($request->user_password) : null,
            ]);

            $response = array(
                'status' => true,
                'message' => 'Registrasi Berhasil!',
            );

            $code = 201;
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            $code = 500;
        }

        return response()->json($response, $code);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            $response = array(
                'status' => 'success',
                'message' => 'Logout Berhasil!',
            );
            $code = 200;
        } catch (\Throwable $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage(),
            );
            $code = 500;
        }

        return response()->json($response, $code);
    }

    public function checkAuth(Request $request): JsonResponse
    {
        try {
            $user = $request->user(); // Ambil user dari token
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Token tidak valid'], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Token valid',
                'data' => [
                    'user_nama' => $user->user_nama,
                    'user_email' => $user->user_email,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }
}
