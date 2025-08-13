<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
  <?php include __DIR__ . '/../layouts/main_menu.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">

              <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                  <h3 class="font-weight-bold">Data Kriteria</h3>
                  <h6 class="font-weight-normal mb-0">Kelola Kriteria (C1, C2, ...)</h6>
                </div>
                <div class="col-md-6 text-right">
                  <form class="d-inline-block mr-2" action="/dashboard/data-kriteria" method="GET">
                    <div class="input-group input-group-sm" style="max-width:360px;">
                      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari (kode / nama)" value="<?= htmlspecialchars($search ?? '') ?>">
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="ti-search"></i> Cari</button>
                      </div>
                    </div>
                  </form>

                  <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="ti-plus"></i> Tambah
                  </button>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                  <thead class="table-info text-center">
                    <tr>
                      <th style="width:5%;">No</th>
                      <th>Kode</th>
                      <th>Nama Kriteria</th>
                      <th style="width:12%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $from = ($currentPage - 1) * 15 + 1;
                      if ($total == 0) $from = 0;
                      $no = $from;
                    ?>
                    <?php if (!empty($kriteria)): ?>
                      <?php foreach ($kriteria as $row): ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= htmlspecialchars($row['kode']) ?></td>
                          <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
                          <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $row['id_kriteria'] ?>" title="Edit">
                              <i class="ti-pencil"></i>
                            </button>
                            <a href="/dashboard/data-kriteria/delete/<?= $row['id_kriteria'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                              <i class="ti-trash"></i>
                            </a>
                          </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?= $row['id_kriteria'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <form method="POST" action="/dashboard/data-kriteria/update/<?= $row['id_kriteria'] ?>">
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Kriteria</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label>Kode</label>
                                    <input type="text" name="kode" value="<?= htmlspecialchars($row['kode']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                  <div class="form-group">
                                    <label>Nama Kriteria</label>
                                    <input type="text" name="nama_kriteria" value="<?= htmlspecialchars($row['nama_kriteria']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="4" class="text-center">Belum ada data kriteria</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- pagination (sama seperti Siswa, copy/paste) -->
              <?php
                $perPage = 15;
                $totalPages = max(1, $totalPages ?? 1);
                $offset = ($currentPage - 1) * $perPage;
                $showFrom = ($total > 0) ? $offset + 1 : 0;
                $showTo = min($offset + $perPage, $total);
                $window = 5;
                $start = max(1, $currentPage - (int)floor($window / 2));
                $end = $start + $window - 1;
                if ($end > $totalPages) {
                    $end = $totalPages;
                    $start = max(1, $end - $window + 1);
                }
                $qSearch = isset($search) && $search !== '' ? '&search=' . urlencode($search) : '';
              ?>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                  Menampilkan <strong><?= $showFrom ?></strong> - <strong><?= $showTo ?></strong> dari <strong><?= $total ?></strong> data
                </div>
                <nav>
                  <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="/dashboard/data-kriteria?page=<?= $currentPage - 1 ?><?= $qSearch ?>">«</a>
                    </li>
                    <?php if ($start > 1): ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/data-kriteria?page=1<?= $qSearch ?>">1</a></li>
                      <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <?php endif; ?>
                    <?php for ($p = $start; $p <= $end; $p++): ?>
                      <li class="page-item <?= ($p == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="/dashboard/data-kriteria?page=<?= $p ?><?= $qSearch ?>"><?= $p ?></a>
                      </li>
                    <?php endfor; ?>
                    <?php if ($end < $totalPages): ?>
                      <?php if ($end < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/data-kriteria?page=<?= $totalPages ?><?= $qSearch ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                      <a class="page-link" href="/dashboard/data-kriteria?page=<?= $currentPage + 1 ?><?= $qSearch ?>">»</a>
                    </li>
                  </ul>
                </nav>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <form method="POST" action="/dashboard/data-kriteria/store">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Kriteria</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode</label>
                <input type="text" name="kode" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label>Nama Kriteria</label>
                <input type="text" name="nama_kriteria" class="form-control form-control-sm" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
