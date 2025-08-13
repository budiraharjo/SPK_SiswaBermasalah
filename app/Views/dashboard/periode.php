<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3">Input Data Periode</h4>

            <!-- Form Tambah Periode -->
            <form action="/dashboard/periode/store" method="POST" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="periode" class="form-control" placeholder="Contoh: 2024/2025" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </div>
            </form>

            <!-- Tabel Periode -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Status Aktif</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periode as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['periode']) ?></td>
                            <td>
                                <?php if ($p['is_active'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Form Edit -->
                                <form action="/dashboard/periode/update/<?= $p['id_periode'] ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="periode" value="<?= htmlspecialchars($p['periode']) ?>" required class="form-control mb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= $p['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Aktif</label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning mt-1">Update</button>
                                </form>

                                <!-- Hapus -->
                                <a href="/dashboard/periode/delete/<?= $p['id_periode'] ?>" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
