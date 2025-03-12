<?php

namespace App\Service\User;

use App\Models\User;
use Exception;

class UserService
{
    public function tambah(array $formData): array
    {
        try {
            if (!empty($formData['user_password'])) {
                $formData['user_password'] = bcrypt($formData['user_password']);
            }
            $result = User::create($formData);

            return [
                'status' => true,
                'message' => 'Registrasi Berhasil',
                'id' => $result->id_pk_user,
                'data' => [
                    'user_nama' => $result->user_nama,
                    'user_email' => $result->user_email,
                    'user_no_hp' => $result->user_no_hp,
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
