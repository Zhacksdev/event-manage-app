<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class UserController extends Controller
{
    // Helper response biar konsisten
    private function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    private function error($message = 'Error', $code = 500, $error = null)
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
            'error'   => $error
        ], $code);
    }

    // GET /api/users
    public function index()
    {
        try {
            $users = User::all();
            return $this->success($users, 'Berhasil mengambil semua user');
        } catch (\Exception $e) {
            return $this->error('Gagal mengambil data user', 500, $e->getMessage());
        }
    }

    // POST /api/users
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|email|unique:users,email',
                'nim'     => 'required|unique:users,nim',
                'phone'   => 'nullable|string',
                'faculty' => 'required|string'
            ]);

            $user = User::create($validated);

            return $this->success($user, 'User berhasil ditambahkan', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Gagal menambahkan user', 500, $e->getMessage());
        }
    }

    // GET /api/users/{id}
    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->error('User tidak ditemukan', 404);
            }

            return $this->success($user, 'Detail user ditemukan');
        } catch (\Exception $e) {
            return $this->error('Gagal mengambil detail user', 500, $e->getMessage());
        }
    }

    // PUT /api/users/{id}
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->error('User tidak ditemukan', 404);
            }

            $validated = $request->validate([
                'name'    => 'sometimes|string|max:255',
                'email' => 'sometimes|email',
                'nim'   => 'sometimes|string',
                'phone'   => 'nullable|string',
                'faculty' => 'sometimes|string'
            ]);

            $user->update($validated);

            return $this->success($user, 'User berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Gagal update user', 500, $e->getMessage());
        }
    }

    // DELETE /api/users/{id}
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->error('User tidak ditemukan', 404);
            }

            $user->delete();

            return $this->success(null, 'User berhasil dihapus');
        } catch (\Exception $e) {
            return $this->error('Gagal menghapus user', 500, $e->getMessage());
        }
    }

    // GET /api/users/{id}/registrations
    public function getRegistrations($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->error('User tidak ditemukan', 404);
            }

            $registrationServiceUrl = env('REGISTRATION_SERVICE_URL', 'http://localhost:8003/api');

            $response = Http::timeout(5)
                ->get("{$registrationServiceUrl}/registrations/user/{$id}");

            if ($response->failed()) {
                return $this->error(
                    'Gagal mengambil data dari RegistrationService',
                    502,
                    $response->body()
                );
            }

            return $this->success([
                'user'          => $user,
                'registrations' => $response->json()
            ], 'Berhasil mengambil data registrasi');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->error(
                'RegistrationService tidak dapat dihubungi',
                503,
                $e->getMessage()
            );
        } catch (\Exception $e) {
            return $this->error(
                'Terjadi kesalahan pada server',
                500,
                $e->getMessage()
            );
        }
    }
}
