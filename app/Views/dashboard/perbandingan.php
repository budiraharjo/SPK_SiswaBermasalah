<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3"><?= $title; ?></h4>

            <?php if (!empty($periode)): ?>
                <div class="alert alert-info">
                    Periode Aktif: <strong><?= htmlspecialchars($periode) ?></strong>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Tidak ada periode aktif.
                </div>
            <?php endif; ?>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2" style="vertical-align: middle;">No</th>
                            <th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>
                            <th colspan="2">SAW</th>
                            <th colspan="2">WP</th>
                        </tr>
                        <tr class="text-center">
                            <th>Nilai Akhir</th>
                            <th>Status</th>
                            <th>Nilai Akhir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($perbandingan)): ?>
                            <?php $no=1; foreach($perbandingan as $p): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($p['nama']); ?></td>
                                    <td class="text-center"><?= number_format($p['nilai_saw'] ?? 0, 4) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($p['status_saw'])): ?>
                                            <span class="badge badge-<?= $p['status_saw'] === 'Bermasalah' ? 'danger' : 'success'; ?>">
                                                <?= htmlspecialchars($p['status_saw']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= number_format($p['nilai_wp'] ?? 0, 4) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($p['status_wp'])): ?>
                                            <span class="badge badge-<?= $p['status_wp'] === 'Bermasalah' ? 'danger' : 'success'; ?>">
                                                <?= htmlspecialchars($p['status_wp']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data perbandingan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
