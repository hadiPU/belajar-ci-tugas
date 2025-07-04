<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Diskon</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border: 1px solid #888;
            background-color: #f0f0f0;
            margin-right: 5px;
        }
        .btn:hover {
            background-color: #ddd;
        }
        .flash {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<h2>Manajemen Data Diskon</h2>

<!-- Tampilkan flash message jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <p class="flash"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <p class="error"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<!-- Tombol tambah diskon -->
<a href="<?= site_url('diskon/create') ?>" class="btn">+ Tambah Diskon</a>

<!-- Tabel data diskon -->
<table>
    <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>Nominal</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($diskon as $d): ?>
    <tr>
        <td><?= $d['id'] ?></td>
        <td><?= $d['tanggal'] ?></td>
        <td>Rp <?= number_format($d['nominal'], 0, ',', '.') ?></td>
        <td>
            <a href="<?= site_url('diskon/edit/'.$d['id']) ?>" class="btn">Edit</a>
            <a href="<?= site_url('diskon/delete/'.$d['id']) ?>" class="btn" onclick="return confirm('Yakin ingin menghapus diskon ini?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
<?= $this->endSection() ?>
