<?php

namespace App\Controllers;

use App\Models\ProductCategoryModel;

class Product_CategoryController extends BaseController
{
    public function index()
    {
        $model = new ProductCategoryModel();
        $data = [
            'kategori' => $model->findAll(),
            'pageTitle' => 'Produk Kategori',
        ];
        return view('v_product_category', $data);
    }

    public function store()
    {
        $model = new ProductCategoryModel();

        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($model->insert($data)) {
            return redirect()->to(base_url('product-category'))->with('success', 'Data berhasil disimpan.');
        } else {
            return redirect()->to(base_url('product-category'))->with('failed', 'Gagal menyimpan data.');
        }
    }

    public function update($id)
    {
        $model = new ProductCategoryModel();

        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($model->update($id, $data)) {
            return redirect()->to(base_url('product-category'))->with('success', 'Data berhasil diperbarui.');
        } else {
            return redirect()->to(base_url('product-category'))->with('failed', 'Gagal memperbarui data.');
        }
    }

    public function delete($id)
    {
        $model = new ProductCategoryModel();

        if ($model->delete($id)) {
            return redirect()->to(base_url('product-category'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(base_url('product-category'))->with('failed', 'Gagal menghapus data.');
        }
    }
}
