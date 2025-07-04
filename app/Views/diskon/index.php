<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h3>Diskon</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Tombol Tambah Diskon -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah Diskon
</button>

<!-- Tabel Data Diskon -->
<table class="table table-bordered" id="tableDiskon">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($diskon as $index => $d): ?>
            <tr data-id="<?= $d['id'] ?>" data-tanggal="<?= $d['tanggal'] ?>" data-nominal="<?= $d['nominal'] ?>">
                <td><?= $index + 1 ?></td>
                <td><?= $d['tanggal'] ?></td>
                <td>Rp <?= number_format($d['nominal'], 0, ',', '.') ?></td>
                <td>
                    <button class="btn btn-success btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit">Ubah</button>
                    <a href="<?= base_url('diskon/delete/' . $d['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Tambah Diskon -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('diskon/store') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal">Nominal</label>
                        <input type="number" name="nominal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Diskon -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="formEdit" action="">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nominal">Nominal</label>
                        <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#tableDiskon').DataTable();

        $('.btn-edit').on('click', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            const tanggal = row.data('tanggal');
            const nominal = row.data('nominal');

            $('#edit_id').val(id);
            $('#edit_tanggal').val(tanggal);
            $('#edit_nominal').val(nominal);
            $('#formEdit').attr('action', '<?= base_url('diskon/update/') ?>' + id);
        });
    });
</script>
<?= $this->endSection() ?>