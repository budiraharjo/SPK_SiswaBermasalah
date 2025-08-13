<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3">Perhitungan WP - Periode <?= htmlspecialchars($periode) ?></h4>

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
                                <?php foreach ($kriteria as $k): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($k['id_kriteria']) ?></td>
                                        <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                        <td><?= htmlspecialchars(number_format($k['bobot'], 2)) ?></td>
                                        <td><?= htmlspecialchars($k['sifat']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil Perhitungan -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hasil Perhitungan WP</h5>
                    <div class="table-responsive">
						<table class="table table-striped table-hover table-bordered">
							<thead class="table-info text-center">
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Siswa</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1; foreach ($hasil as $h): ?>
                                    <tr>
                                        <td><?= $rank++ ?></td>
                                        <td><?= htmlspecialchars($h['nama']) ?></td>
                                        <td><?= htmlspecialchars(number_format($h['skor'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($hasil)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data perhitungan</td>
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
