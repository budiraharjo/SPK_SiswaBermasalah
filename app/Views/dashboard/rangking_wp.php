<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3">Rangking WP - Periode <?= htmlspecialchars($periode) ?></h4>

            <!-- Tabel Kriteria -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Kriteria</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Bobot</th>
                                    <th>Sifat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kriteria)): ?>
                                    <?php foreach ($kriteria as $k): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($k['id_kriteria']) ?></td>
                                            <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                            <td><?= htmlspecialchars(number_format($k['bobot'], 2)) ?></td>
                                            <td><?= htmlspecialchars($k['sifat']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data kriteria</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil Rangking -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hasil Rangking WP</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-info text-center">
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Akhir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hasil)): ?>
                                    <?php foreach ($hasil as $h): ?>
                                        <tr>
                                            <td class="text-center"><?= htmlspecialchars($h['peringkat']) ?></td>
                                            <td><?= htmlspecialchars($h['nama']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars(number_format($h['nilai_akhir'], 2)) ?></td>
                                            <td class="text-center">
                                                <?php if ($h['status'] === 'Bermasalah'): ?>
                                                    <span class="badge bg-danger"><?= htmlspecialchars($h['status']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><?= htmlspecialchars($h['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data rangking</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
 