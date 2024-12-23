<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DepartemenModel;

class DepartemenController extends ResourceController
{
    protected $modelName = DepartemenModel::class;
    protected $format    = 'json';

    // Tambah Departemen
    public function addDepartemen()
    {
        // Validasi input
        $rules = [
            'nama_departemen' => 'required|max_length[100]',
            'deskripsi' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        // Ambil data input
        $data = $this->request->getVar();

        $departemenModel = new DepartemenModel();

        if ($departemenModel->insert($data)) {
            return $this->respondCreated([
                'status' => true,
                'message' => 'Departemen successfully added',
                'data' => $data
            ]);
        }

        return $this->respond([
            'status' => false,
            'message' => 'Failed to add departemen',
            'data' => []
        ], 500);
    }

    // List Departemen
    public function listDepartemen()
    {
        $departemens = $this->model->findAll();

        if (count($departemens) > 0) {
            return $this->respond([
                'status' => true,
                'message' => 'Departemen list retrieved',
                'data' => $departemens
            ]);
        }

        return $this->respond([
            'status' => false,
            'message' => 'No departemen found',
            'data' => []
        ], 404);
    }

    // Hapus Departemen
    public function deleteDepartemen($id)
    {
        if ($this->model->find($id)) {
            if ($this->model->delete($id)) {
                return $this->respondDeleted([
                    'status' => true,
                    'message' => 'Departemen successfully deleted',
                    'data' => []
                ]);
            }

            return $this->respond([
                'status' => false,
                'message' => 'Failed to delete departemen',
                'data' => []
            ], 500);
        }

        return $this->respond([
            'status' => false,
            'message' => 'Departemen not found',
            'data' => []
        ], 404);
    }

    // Update Departemen
    public function updateDepartemen($id)
    {
        // Validasi input
        $rules = [
            'nama_departemen' => 'required|max_length[100]',
            'deskripsi' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        // Ambil data input
        $data = $this->request->getVar();

        // Cek jika departemen ada
        $departemen = $this->model->find($id);
        if (!$departemen) {
            return $this->respond([
                'status' => false,
                'message' => 'Departemen not found',
                'data' => []
            ], 404);
        }

        // Update data
        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status' => true,
                'message' => 'Departemen successfully updated',
                'data' => $data
            ]);
        }

        return $this->respond([
            'status' => false,
            'message' => 'Failed to update departemen',
            'data' => []
        ], 500);
    }
}
