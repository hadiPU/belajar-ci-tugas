<h3>Data Transaksi</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Alamat</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transaksi as $i => $t): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $t['username'] ?></td>
                <td><?= $t['alamat'] ?></td>
                <td><?= number_format($t['total_harga']) ?></td>
                <td>
                    <?php
                    $statusList = [
                        0 => 'Menunggu Pembayaran',
                        1 => 'Diproses',
                        2 => 'Dikirim',
                        3 => 'Selesai',
                        9 => 'Dibatalkan',
                    ];
                    echo $statusList[$t['status']] ?? 'Tidak diketahui';
                    ?>
                </td>
                <td>
                    <form action="<?= base_url('admin/transaksi/update_status/' . $t['id']) ?>" method="post">
                        <select name="status" class="form-select form-select-sm">
                            <option <?= $t['status'] == 'Menunggu Pembayaran' ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                            <option <?= $t['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option <?= $t['status'] == 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
                            <option <?= $t['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option <?= $t['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                        <button class="btn btn-sm btn-primary mt-1">Ubah</button>
                    </form>


                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>