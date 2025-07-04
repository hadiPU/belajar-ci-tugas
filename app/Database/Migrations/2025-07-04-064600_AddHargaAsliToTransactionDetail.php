<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaAsliToTransactionDetail extends Migration
{
    public function up()
{
    $this->forge->addColumn('transaction_detail', [
        'harga_asli' => [
            'type' => 'DOUBLE',
            'null' => true,
            'after' => 'product_id'
        ],
    ]);
}

public function down()
{
    $this->forge->dropColumn('transaction_detail', 'harga_asli');
}

}
