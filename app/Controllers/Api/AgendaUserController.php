<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AgendaUserModel;

class AgendaUserController extends ResourceController
{
    protected $modelName = AgendaUserModel::class;

    // Tambah Personil
    public function addPersonil()
    {
        // Validasi input
        $rules = [
            "agenda_id" => "required|integer",
            "user_id"   => "required|integer" // Validasi user_id sebagai integer
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                "status" => false,
                "message" => $this->validator->getErrors(),
                "data" => []
            ], 400);
        }

        $agendaId = $this->request->getVar('agenda_id');
        $userId = $this->request->getVar('user_id'); // Ambil satu user_id

        try {
            $agendaUserModel = new AgendaUserModel();

            // Cek apakah user sudah terdaftar di agenda yang sama
            $existingPersonil = $agendaUserModel->where('agenda_id', $agendaId)
                                                 ->where('user_id', $userId)
                                                 ->first();

            if ($existingPersonil) {
                return $this->respond([
                    "status" => false,
                    "message" => "User already added to this agenda.",
                    "data" => []
                ], 400);
            }

            // Jika tidak ada, tambahkan user
            $agendaUserModel->insert([
                "agenda_id" => $agendaId,
                "user_id"   => $userId
            ]);

            return $this->respondCreated([
                "status" => true,
                "message" => "User successfully added to the agenda",
                "data" => [
                    "agenda_id" => $agendaId,
                    "user_id"   => $userId
                ]
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                "status" => false,
                "message" => "Failed to add personil: " . $e->getMessage(),
                "data" => []
            ], 500);
        }
    }

    // List Personil berdasarkan agenda_id
    public function listPersonil($agendaId)
    {
        $personil = $this->model->where('agenda_id', $agendaId)->findAll();

        if (!$personil) {
            return $this->respond([
                'status' => false,
                'message' => 'Personil tidak ditemukan untuk agenda ini',
                'data' => []
            ], 404);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Personil ditemukan',
            'data' => $personil
        ]);
    }

    // Hapus Personil berdasarkan agenda_user_id
    public function deletePersonil($agendaUserId)
    {
        try {
            // Cek apakah data dengan agenda_user_id ada
            $personil = $this->model->find($agendaUserId);

            if (!$personil) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Personil tidak ditemukan',
                    'data' => []
                ], 404);
            }

            // Hapus personil
            if ($this->model->delete($agendaUserId)) {
                return $this->respondDeleted([
                    'status' => true,
                    'message' => 'Personil berhasil dihapus',
                    'data' => []
                ]);
            }

            return $this->respond([
                'status' => false,
                'message' => 'Gagal menghapus personil',
                'data' => []
            ], 500);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // Update Personil
    public function updatePersonil($agendaUserId)
    {
        // Validasi input
        $rules = [
            'agenda_id' => 'required|integer', // Validasi agenda_id sebagai integer
            'user_id'   => 'required|integer'  // Validasi user_id sebagai integer
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $agendaId = $this->request->getVar('agenda_id');
        $userId = $this->request->getVar('user_id');

        // Periksa apakah data dengan agendaUserId ada
        $existingPersonil = $this->model->where('id', $agendaUserId)->first();

        if (!$existingPersonil) {
            return $this->respond([
                'status' => false,
                'message' => 'Personil tidak ditemukan.',
                'data' => []
            ], 404);
        }

        // Cek apakah kombinasi agenda_id dan user_id sudah ada di database
        $existingCombination = $this->model->where('agenda_id', $agendaId)
                                           ->where('user_id', $userId)
                                           ->where('id !=', $agendaUserId) // Menghindari memeriksa diri sendiri
                                           ->first();

        if ($existingCombination) {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal mengubah karena kombinasi agenda_id dan user_id sudah ada.',
            ], 400);
        }

        try {
            // Update hanya agenda_id dan user_id
            $this->model->update($agendaUserId, [
                'agenda_id' => $agendaId,
                'user_id'   => $userId
            ]);

            return $this->respond([
                'status' => true,
                'message' => 'Personil berhasil diperbarui',
                'data' => [
                    'agenda_id' => $agendaId,
                    'user_id'   => $userId
                ]
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                "status" => false,
                "message" => "Gagal memperbarui personil: " . $e->getMessage(),
                "data" => []
            ], 500);
        }
    }
}
