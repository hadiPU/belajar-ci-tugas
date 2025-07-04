<!DOCTYPE html>
<html>
<head>
    <title>Edit Diskon</title>
</head>
<body>

<h2>Edit Diskon</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form action="<?= site_url('diskon/update/' . $diskon['id']) ?>" method="post">
    <?= csrf_field() ?>
    <label for="tanggal">Tanggal:</label>
    <input type="date" name="tanggal" value="<?= $diskon['tanggal'] ?>" readonly><br><br>

    <label for="nominal">Nominal (Rp):</label>
    <input type="number" name="nominal" value="<?= $diskon['nominal'] ?>" required><br><br>

    <button type="submit">Update</button>
    <a href="<?= site_url('diskon') ?>">Kembali</a>
</form>

</body>
</html>
