<?php include __DIR__ . '/../layouts/top_depan.php'; ?> 

<div class="container-fluid page-body-wrapper">

  <!-- Panel Pengaturan Tema -->
  <div class="theme-setting-wrapper">
    <div id="settings-trigger"><i class="ti-settings"></i></div>
    <div id="theme-settings" class="settings-panel">
      <i class="settings-close ti-close"></i>
      <p class="settings-heading">SIDEBAR SKINS</p>
      <div class="sidebar-bg-options selected" id="sidebar-light-theme">
        <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
      </div>
      <div class="sidebar-bg-options" id="sidebar-dark-theme">
        <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
      </div>
      <p class="settings-heading mt-2">HEADER SKINS</p>
      <div class="color-tiles mx-0 px-4">
        <div class="tiles success"></div>
        <div class="tiles warning"></div>
        <div class="tiles danger"></div>
        <div class="tiles info"></div>
        <div class="tiles dark"></div>
        <div class="tiles default"></div>
      </div>
    </div>
  </div> 
  
  <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
  
  <div class="main-panel">
    <div class="content-wrapper">

      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">

              <!-- Header & Tombol Tambah + Search -->
              <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                  <h3 class="font-weight-bold">Data Siswa</h3>
                  <h6 class="font-weight-normal mb-0">Kelola data siswa SMPN 2 Malingping</h6>
                </div>
                <div class="col-md-6 text-right">
                  <form class="d-inline-block mr-2" action="/dashboard/data-siswa" method="GET">
                    <div class="input-group input-group-sm" style="max-width:360px;">
                      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari (nama / NISN / kelas)" value="<?= htmlspecialchars($search ?? '') ?>">
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

              <!-- Tabel Data -->
              <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                  <thead class="table-info text-center">
                    <tr>
                      <th style="width:5%;">No</th>
                      <th>NISN</th>
                      <th>Nama</th>
                      <th style="width:10%;">Kelas</th>
                      <th style="width:12%;">Jenis Kelamin</th>
                      <th style="width:12%;">Tahun Ajaran</th>
                      <th style="width:12%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // nomor awal sesuai halaman
                    $from = ($currentPage - 1) * 15 + 1;
                    if ($total == 0) $from = 0;
                    $no = $from;
                    ?>
                    <?php if (!empty($siswa)): ?>
                      <?php foreach ($siswa as $row): ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= htmlspecialchars($row['nisn']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['kelas']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['tahun_ajaran']) ?></td>
                          <td class="text-center">
                            <button class="btn btn-warning btn-sm" 
                              data-toggle="modal" 
                              data-target="#modalEdit<?= $row['id_siswa'] ?>"
                              title="Edit">
                              <i class="ti-pencil"></i>
                            </button>
                            <a href="/dashboard/data-siswa/delete/<?= $row['id_siswa'] ?>" 
                               onclick="return confirm('Hapus data ini?')" 
                               class="btn btn-danger btn-sm" title="Hapus">
                               <i class="ti-trash"></i>
                            </a>
                          </td>
                        </tr>

                        <!-- Modal Edit (per baris) -->
                        <div class="modal fade" id="modalEdit<?= $row['id_siswa'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <form method="POST" action="/dashboard/data-siswa/update/<?= $row['id_siswa'] ?>">
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Siswa</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label>NISN</label>
                                    <input type="text" name="nisn" value="<?= htmlspecialchars($row['nisn']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                  <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                  <div class="form-group">
                                    <label>Kelas</label>
                                    <input type="text" name="kelas" value="<?= htmlspecialchars($row['kelas']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                  <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control form-control-sm" required>
                                      <option value="Laki-laki" <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                      <option value="Perempuan" <?= $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Tahun Ajaran</label>
                                    <input type="text" name="tahun_ajaran" value="<?= htmlspecialchars($row['tahun_ajaran']) ?>" class="form-control form-control-sm" required>
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
                      <tr>
                        <td colspan="7" class="text-center">Belum ada data siswa</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Info dan Pagination -->
              <?php
                $perPage = 15;
                $totalPages = max(1, $totalPages ?? 1);
                $offset = ($currentPage - 1) * $perPage;
                $showFrom = ($total > 0) ? $offset + 1 : 0;
                $showTo = min($offset + $perPage, $total);
                // Hitung window 5 nomor
                $window = 5;
                $start = max(1, $currentPage - (int)floor($window / 2));
                $end = $start + $window - 1;
                if ($end > $totalPages) {
                    $end = $totalPages;
                    $start = max(1, $end - $window + 1);
                }

                // helper buat parameter query (preserve search)
                $qSearch = isset($search) && $search !== '' ? '&search=' . urlencode($search) : '';
              ?>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                  Menampilkan <strong><?= $showFrom ?></strong> - <strong><?= $showTo ?></strong> dari <strong><?= $total ?></strong> data
                </div>
                <nav>
                  <ul class="pagination pagination-sm mb-0">
                    <!-- Prev -->
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="/dashboard/data-siswa?page=<?= $currentPage - 1 ?><?= $qSearch ?>" aria-label="Previous">«</a>
                    </li>

                    <!-- If not in range, show first -->
                    <?php if ($start > 1): ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/data-siswa?page=1<?= $qSearch ?>">1</a></li>
                      <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                      <?php endif; ?>
                    <?php endif; ?>

                    <!-- Window page numbers -->
                    <?php for ($p = $start; $p <= $end; $p++): ?>
                      <li class="page-item <?= ($p == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="/dashboard/data-siswa?page=<?= $p ?><?= $qSearch ?>"><?= $p ?></a>
                      </li>
                    <?php endfor; ?>

                    <!-- If end < totalPages show ellipsis and last -->
                    <?php if ($end < $totalPages): ?>
                      <?php if ($end < $totalPages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                      <?php endif; ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/data-siswa?page=<?= $totalPages ?><?= $qSearch ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>

                    <!-- Next -->
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                      <a class="page-link" href="/dashboard/data-siswa?page=<?= $currentPage + 1 ?><?= $qSearch ?>" aria-label="Next">»</a>
                    </li>
                  </ul>
                </nav>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <form method="POST" action="/dashboard/data-siswa/store">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Siswa</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>NISN</label>
                <input type="text" name="nisn" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control form-control-sm" required>
                  <option value="">Pilih</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control form-control-sm" required>
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

