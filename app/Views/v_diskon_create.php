<!DOCTYPE html>
<html>
<head>
    <title>Tambah Diskon</title>
</head>
<body>

<h2>Tambah Diskon</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form action="<?= site_url('diskon/store') ?>" method="post">
    <?= csrf_field() ?>
    <label for="tanggal">Tanggal:</label>
    <input type="date" name="tanggal" required><br><br>

    <label for="nominal">Nominal (Rp):</label>
    <input type="number" name="nominal" required><br><br>

    <button type="submit">Simpan</button>
    <a href="<?= site_url('diskon') ?>">Kembali</a>
</form>

</body>
</html>
