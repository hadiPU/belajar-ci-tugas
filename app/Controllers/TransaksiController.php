<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;
use App\Models\DiskonModel;


class TransaksiController extends BaseController
{
    protected $cart;
    protected $client;
    protected $apiKey;
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        helper(['number', 'form']);
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_KEY');
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    public function add()
    {
        $produkId = $this->request->getPost('produk_id');
        $jumlah   = $this->request->getPost('qty');

        $produkModel = new ProductModel();
        $produk = $produkModel->find($produkId);

        $diskon = session('diskon') ?? 0;
        $hargaAsli = $produk['harga'];
        $hargaSetelahDiskon = max(0, $hargaAsli - $diskon);

        $this->cart->insert([
            'id'    => $produk['id'],
            'qty'   => $jumlah,
            'price' => $hargaSetelahDiskon,
            'name'  => $produk['nama_produk'],
            'options' => ['foto' => $produk['foto'] ?? '']
        ]);

        return redirect()->to('/keranjang')->with('success', 'Produk ditambahkan ke keranjang dengan diskon.');
    }

    public function cart_add()
    {
        $diskon = session('diskon') ?? 0;
        $hargaAsli = $this->request->getPost('harga');
        $hargaSetelahDiskon = max(0, $hargaAsli - $diskon);

        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $hargaSetelahDiskon,
            'name'    => $this->request->getPost('nama'),
            'options' => ['foto' => $this->request->getPost('foto')]
        ]);

        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $this->cart->update([
                'rowid' => $value['rowid'],
                'qty'   => $this->request->getPost('qty' . $i++)
            ]);
        }

        session()->setFlashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_checkout', $data);
    }

    public function getLocation()
    {
        $search = $this->request->getGet('search');

        $response = $this->client->request(
            'GET',
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . $search . '&limit=50',
            [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body['data']);
    }

    public function getCost()
    {
        $destination = $this->request->getGet('destination');

        $response = $this->client->request(
            'POST',
            'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost',
            [
                'multipart' => [
                    ['name' => 'origin', 'contents' => '64999'],
                    ['name' => 'destination', 'contents' => $destination],
                    ['name' => 'weight', 'contents' => '1000'],
                    ['name' => 'courier', 'contents' => 'jne']
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body['data']);
    }

    public function buy()
    {
        if ($this->request->getPost()) {
            // ✅ Ambil diskon langsung dari DB sesuai tanggal hari ini
            $diskonModel = new \App\Models\DiskonModel();
            $today = date('Y-m-d');
            $diskonData = $diskonModel->where('tanggal', $today)->first();
            $diskon = $diskonData['nominal'] ?? 0;

            $dataForm = [
                'username'     => $this->request->getPost('username'),
                'total_harga'  => $this->request->getPost('total_harga'),
                'alamat'       => $this->request->getPost('alamat'),
                'ongkir'       => $this->request->getPost('ongkir') ?? 0,
                'status'       => 'Menunggu Pembayaran',
                'created_at'   => date("Y-m-d H:i:s"),
                'updated_at'   => date("Y-m-d H:i:s")
            ];

            $this->transaction->insert($dataForm);
            $last_insert_id = $this->transaction->getInsertID();

            $productModel = new \App\Models\ProductModel();

            foreach ($this->cart->contents() as $item) {
                $produk = $productModel->find($item['id']);
                $hargaAsli = $produk['harga'];
                $hargaDiskon = max(0, $hargaAsli - $diskon);
                $subtotal = $item['qty'] * $hargaDiskon;

                $dataFormDetail = [
                    'transaction_id'  => $last_insert_id,
                    'product_id'      => $item['id'],
                    'harga_asli'      => $hargaAsli,
                    'jumlah'          => $item['qty'],
                    'diskon'          => $diskon,
                    'subtotal_harga'  => $subtotal,
                    'created_at'      => date("Y-m-d H:i:s"),
                    'updated_at'      => date("Y-m-d H:i:s")
                ];

                $this->transaction_detail->insert($dataFormDetail);
            }

            $this->cart->destroy();
            return redirect()->to(base_url())->with('success', 'Transaksi berhasil disimpan!');
        }

        return redirect()->to(base_url())->with('error', 'Gagal menyimpan transaksi.');
    }
}
