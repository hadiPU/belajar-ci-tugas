<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class AdminController extends BaseController
{
    protected $transaction;

    public function __construct()
    {
        $this->transaction = new TransactionModel();
    }

    public function transaksi()
    {
        $data['transaksi'] = $this->transaction->findAll();
        return view('admin/transaksi/index', $data); // Pastikan view ini ada
    }

    public function update_status($id)
    {
        $status = $this->request->getPost('status'); // Status berupa teks seperti "Diproses"

        $this->transaction->update($id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/transaksi')->with('success', 'Status transaksi berhasil diubah.');
    }
}
