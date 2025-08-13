<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3">Laporan Data Siswa</h4>

            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select name="periode" class="form-control">
                            <option value="">-- Semua Tahun Ajaran --</option>
                            <?php foreach ($periodes as $p): ?>
                                <option value="<?= $p['periode'] ?>" <?= ($periode_selected == $p['periode']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['periode']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / NISN / kelas..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="/dashboard/laporan-siswa/download?periode=<?= urlencode($periode_selected) ?>&search=<?= urlencode($search) ?>" class="btn btn-success">Download PDF</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($siswa)): ?>
                                    <?php $no = ($page - 1) * $limit + 1; ?>
                                    <?php foreach ($siswa as $row): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nisn']) ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                                            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                                            <td><?= htmlspecialchars($row['tahun_ajaran']) ?></td>
                                            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data siswa</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            Menampilkan <?= count($siswa) ?> data dari total <?= $total ?> data siswa.
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <?php
                                $totalPages = ceil($total / $limit);
                                for ($i = 1; $i <= $totalPages; $i++):
                                ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?periode=<?= urlencode($periode_selected) ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
