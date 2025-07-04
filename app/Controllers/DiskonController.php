<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskonModel;

class DiskonController extends BaseController
{
    protected $diskonModel;

    public function __construct()
    {
        $this->diskonModel = new DiskonModel();
        helper(['form', 'url']);
    }

    /* ───────────────────────── INDEX ───────────────────────── */
    public function index()
    {
        return view('diskon/index', [
            'diskon'  => $this->diskonModel->findAll(),
            'error'   => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ]);
    }

    /* ──────────────────────── STORE / CREATE ───────────────── */
    public function store()
    {
        // Validasi sederhana
        $rules = [
            'tanggal' => 'required|valid_date',
            'nominal' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/diskon')
                             ->with('error', $this->validator->listErrors())
                             ->withInput();
        }

        $tanggal = $this->request->getPost('tanggal');
        $nominal = $this->request->getPost('nominal');

        // Pastikan tanggal unik
        if ($this->diskonModel->where('tanggal', $tanggal)->first()) {
            return redirect()->to('/diskon')
                             ->with('error', 'Diskon untuk tanggal ini sudah ada.')
                             ->withInput();
        }

        $this->diskonModel->save([
            'tanggal'    => $tanggal,
            'nominal'    => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil ditambahkan.');
    }

    /* ───────────────────────── UPDATE ──────────────────────── */
    public function update($id)
    {
        // Pastikan data ada
        if (!$this->diskonModel->find($id)) {
            return redirect()->to('/diskon')->with('error', 'Diskon tidak ditemukan.');
        }

        $rules = [
            'nominal' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/diskon')
                             ->with('error', $this->validator->listErrors())
                             ->withInput();
        }

        $this->diskonModel->update($id, [
            'nominal'    => $this->request->getPost('nominal'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil diubah.');
    }

    /* ───────────────────────── DELETE ──────────────────────── */
    public function delete($id)
    {
        // Pastikan data ada
        if (!$this->diskonModel->find($id)) {
            return redirect()->to('/diskon')->with('error', 'Diskon tidak ditemukan.');
        }

        $this->diskonModel->delete($id);
        return redirect()->to('/diskon')->with('success', 'Diskon berhasil dihapus.');
    }
}
