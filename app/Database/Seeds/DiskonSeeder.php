<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $today = new \DateTime();

        for ($i = 0; $i < 10; $i++) {
            $tanggal = clone $today;
            $tanggal->modify("+$i day");

            $data[] = [
                'tanggal'    => $tanggal->format('Y-m-d'),
                'nominal'    => rand(10000, 100000),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->table('diskon')->insertBatch($data);
    }
}
