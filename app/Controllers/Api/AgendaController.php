<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AgendaModel;

class AgendaController extends ResourceController
{
    protected $modelName = AgendaModel::class;

    // Tambahkan Agenda
    public function addAgenda()
{
    // Validasi input
    $rules = [
        "judul" => "required",
        "nama_pelanggan" => "required",
        "lokasi" => "required",
        "tanggal_kegiatan" => "required|valid_date",
        "jam_kegiatan" => "required",
        "lampiran" => "permit_empty",
        "link" => "permit_empty"
    ];

    if (!$this->validate($rules)) {
        log_message('error', 'Validation Errors: ' . json_encode($this->validator->getErrors()));
        return $this->respond([
            "status" => false,
            "message" => $this->validator->getErrors(),
            "data" => []
        ], 400);
    }
    

    // Ambil data input dari request
    $data = json_decode(json_encode($this->request->getVar()), true);

    // Hitung tgl_reminder secara otomatis
    $tanggalKegiatan = $data['tanggal_kegiatan']; // Format: YYYY-MM-DD
    $tglReminder = date('Y-m-d', strtotime($tanggalKegiatan . ' -1 day'));
    $data['tgl_reminder'] = $tglReminder;

    $agendaModel = new AgendaModel();

    if ($agendaModel->insert($data)) {
        return $this->respondCreated([
            "status" => true,
            "message" => "Agenda successfully added",
            "data" => []
        ]);
    }

    return $this->respond([
        "status" => false,
        "message" => "Failed to add agenda",
        "data" => []
    ], 500);
    
}


    // List Agenda
    public function listAgenda()
    {
        $agendas = $this->model->findAll();

        if (count($agendas) > 0) {
            return $this->respond([
                "status" => true,
                "message" => "Agenda list retrieved",
                "data" => $agendas
            ]);
        }

        return $this->respond([
            "status" => false,
            "message" => "No agenda found",
            "data" => []
        ], 404);
    }

    // Update Agenda
    public function updateAgenda($id)
{
    // Validasi input
    $rules = [
        "judul" => "required",
        "nama_pelanggan" => "required",
        "lokasi" => "required",
        "tanggal_kegiatan" => "required|valid_date",
        "jam_kegiatan" => "required",
        "lampiran" => "permit_empty",
        "link" => "permit_empty",
        "tgl_reminder" => "permit_empty",  // tgl_reminder tidak wajib diisi oleh user
    ];

    if (!$this->validate($rules)) {
        return $this->respond([
            "status" => false,
            "message" => $this->validator->getErrors(),
            "data" => []
        ], 400);
    }

    // Ambil data input dari semua format, ubah menjadi array
    $data = json_decode(json_encode($this->request->getVar()), true);

    // Log data input untuk debugging
    log_message('debug', 'Input data: ' . print_r($data, true));

    // Cek jika agenda ada
    $agenda = $this->model->find($id);
    if (!$agenda) {
        return $this->respond([
            "status" => false,
            "message" => "Agenda not found",
            "data" => []
        ], 404);
    }

    // Jika tgl_reminder tidak diberikan, hitung otomatis
    if (empty($data['tgl_reminder'])) {
        $tanggalKegiatan = $data['tanggal_kegiatan']; // Format: YYYY-MM-DD
        $tglReminder = date('Y-m-d', strtotime($tanggalKegiatan . ' -1 day'));
        $data['tgl_reminder'] = $tglReminder;
    }

    // Log data yang akan disimpan ke database
    log_message('debug', 'Data to update: ' . print_r($data, true));

    // Update data
    if ($this->model->update($id, $data)) {
        return $this->respond([
            "status" => true,
            "message" => "Agenda successfully updated",
            "data" => []
        ]);
    }

    return $this->respond([
        "status" => false,
        "message" => "Failed to update agenda",
        "data" => []
    ], 500);
}


    // Hapus Agenda
    public function deleteAgenda($id)
    {
        if ($this->model->find($id)) {
            if ($this->model->delete($id)) {
                return $this->respondDeleted([
                    "status" => true,
                    "message" => "Agenda successfully deleted",
                    "data" => []
                ]);
            }

            return $this->respond([
                "status" => false,
                "message" => "Failed to delete agenda",
                "data" => []
            ], 500);
        }

        return $this->respond([
            "status" => false,
            "message" => "Agenda not found",
            "data" => []
        ], 404);
    }

    public function groupedAgendas()
{
    $currentDate = date('Y-m-d');
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Rekap agenda bulan ini
    $monthAgendas = $this->model->where('MONTH(tanggal_kegiatan)', $currentMonth)
        ->where('YEAR(tanggal_kegiatan)', $currentYear)
        ->findAll();

    // Agenda terdekat
    $upcomingAgendas = $this->model->where('tanggal_kegiatan >=', $currentDate)
    ->orderBy('tanggal_kegiatan', 'ASC')
    ->limit(5)
    ->findAll();


    // Agenda yang sudah selesai
    $finishedAgendas = $this->model->where('tanggal_kegiatan <', $currentDate)
        ->orderBy('tanggal_kegiatan', 'DESC')
        ->findAll();

    return $this->respond([
        "status" => true,
        "message" => "Grouped agendas retrieved",
        "monthAgendas" => $monthAgendas,
        "upcomingAgendas" => $upcomingAgendas,
        "finishedAgendas" => $finishedAgendas,
    ]);
}

}
