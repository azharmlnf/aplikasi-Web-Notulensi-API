<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID'); // Locale Indonesia
        $today = new \DateTime(); // Hari ini

        for ($i = 0; $i < 5; $i++) { // Generate 20 rows of data
            // Generate tanggal kegiatan minimal hari ini
            $tanggalKegiatan = $faker->dateTimeBetween('now', '+30 days', 'Asia/Jakarta'); // Maksimal 30 hari ke depan
            
            // Set tanggal reminder ke 1 hari sebelum tanggal kegiatan
            $tanggalReminder = (clone $tanggalKegiatan)->modify('-1 day');

            $data = [
                'judul'            => $faker->sentence(3), // Random title
                'nama_pelanggan'   => $faker->name, // Random name
                'lokasi'           => $faker->address, // Random address
                'tanggal_kegiatan' => $tanggalKegiatan->format('Y-m-d'), // Formatted date
                'jam_kegiatan'     => $faker->time('H:i:s'), // Random time
                'lampiran'         => $faker->boolean ? $faker->fileExtension : null, // Random file extension or null
                'link'             => $faker->boolean ? $faker->url : null, // Random URL or null
                'tgl_reminder'     => $tanggalReminder->format('Y-m-d'), // Reminder date
                'created_at'       => $faker->dateTimeThisYear('now', 'Asia/Jakarta')->format('Y-m-d H:i:s'), // Formatted datetime
                'updated_at'       => $faker->dateTimeThisYear('now', 'Asia/Jakarta')->format('Y-m-d H:i:s'), // Formatted datetime
            ];

            // Insert into database
            $this->db->table('agendas')->insert($data);
        }
    }
}
