<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NotulenModel;

class NotulenController extends ResourceController
{
    protected $modelName = NotulenModel::class;

    // Tambah Notulen
    public function addNotulen()
    {
        $rules = [
            'agenda_id'   => 'required|integer',
            'isi_notulens' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $agendaId = $this->request->getVar('agenda_id');
        $isiNotulens = $this->request->getVar('isi_notulens');

        // Cek apakah notulen untuk agenda ini sudah ada
        $existingNotulen = $this->model->where('agenda_id', $agendaId)->first();

        if ($existingNotulen) {
            return $this->respond([
                'status' => false,
                'message' => 'Notulen untuk agenda ini sudah ada.',
                'data' => []
            ], 400);
        }

        try {
            // Tambahkan notulen baru
            $this->model->insert([
                'agenda_id' => $agendaId,
                'isi_notulens' => $isiNotulens
            ]);

            return $this->respondCreated([
                'status' => true,
                'message' => 'Notulen berhasil ditambahkan',
                'data' => [
                    'agenda_id' => $agendaId,
                    'isi_notulens' => $isiNotulens
                ]
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal menambahkan notulen: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function listNotulen()
{
    try {
        // Ambil data notulen dengan relasi ke tabel agenda
        $notulen = $this->model
            ->select('notulens.*, agendas.judul AS judulAgenda')
            ->join('agendas', 'notulens.agenda_id = agendas.id', 'left') // Relasi dengan tabel agendas
            ->findAll();
        
        // Jika data ditemukan
        if (!empty($notulen)) {
            return $this->respond([
                'status' => true,
                'message' => 'Semua data notulen berhasil ditemukan',
                'data' => $notulen
            ], 200);
        }

        // Jika tidak ada data
        return $this->respond([
            'status' => false,
            'message' => 'Tidak ada data notulen yang tersedia',
            'data' => []
        ], 404);

    } catch (\Exception $e) {
        // Tangani error jika terjadi
        return $this->respond([
            'status' => false,
            'message' => 'Terjadi kesalahan saat mengambil data notulen',
            'error' => $e->getMessage()
        ], 500);
    }
}



    // Update Notulen
    public function updateNotulen($id)
    {
        $rules = [
            'isi_notulens' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $isiNotulens = $this->request->getVar('isi_notulens');

        $notulen = $this->model->find($id);

        if (!$notulen) {
            return $this->respond([
                'status' => false,
                'message' => 'Notulen tidak ditemukan',
                'data' => []
            ], 404);
        }

        try {
            // Update isi notulen
            $this->model->update($id, [
                'isi_notulens' => $isiNotulens
            ]);

            return $this->respond([
                'status' => true,
                'message' => 'Notulen berhasil diperbarui',
                'data' => [
                    'id' => $id,
                    'isi_notulens' => $isiNotulens
                ]
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal memperbarui notulen: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // Hapus Notulen
    public function deleteNotulen($id)
    {
        $notulen = $this->model->find($id);

        if (!$notulen) {
            return $this->respond([
                'status' => false,
                'message' => 'Notulen tidak ditemukan',
                'data' => []
            ], 404);
        }

        try {
            $this->model->delete($id);

            return $this->respondDeleted([
                'status' => true,
                'message' => 'Notulen berhasil dihapus',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal menghapus notulen: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
