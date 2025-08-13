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
                  <h3 class="font-weight-bold">Kriteria — SAW dan WP</h3>
                  <h6 class="font-weight-normal mb-0">Atur bobot & sifat untuk metode SAW dan WP</h6>
                </div>
                <div class="col-md-6 text-right">
                  <form class="d-inline-block mr-2" action="/dashboard/kriteria-metode" method="GET">
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
			
			<?php if (!empty($_SESSION['success'])): ?>
			<div class="alert alert-success alert-dismissible fade show">
				<?= htmlspecialchars($_SESSION['success']) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php unset($_SESSION['success']); endif; ?>

			<?php if (!empty($_SESSION['error'])): ?>
			<div class="alert alert-danger alert-dismissible fade show">
				<?= htmlspecialchars($_SESSION['error']) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php unset($_SESSION['error']); endif; ?>

			<script>
				// Hilangkan alert setelah 3 detik
				setTimeout(function() {
					document.querySelectorAll('.alert').forEach(function(alert) {
						alert.classList.remove('show'); // hilangkan efek fade
						alert.classList.add('fade');
						setTimeout(() => alert.remove(), 500); // hapus elemen setelah fade-out
					});
				}, 3000);
			</script>

              <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                  <thead class="table-info text-center">
                    <tr>
                      <th style="width:5%;">No</th>
                      <th>Kode</th>
                      <th>Nama Kriteria</th>
                      <th>Bobot</th>
                      <th>Sifat</th>
                      <th>Urutan</th>
                      <th style="width:12%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $from = ($currentPage - 1) * 15 + 1;
                      if ($total == 0) $from = 0;
                      $no = $from;
                    ?>
                    <?php if (!empty($rows)): ?>
                      <?php foreach ($rows as $row): ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= htmlspecialchars($row['kode']) ?></td>
                          <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['bobot']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['sifat']) ?></td>
                          <td class="text-center"><?= htmlspecialchars($row['urutan']) ?></td>
                          <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $row['id_kriteria'] ?>" title="Edit">
                              <i class="ti-pencil"></i>
                            </button>
                            <a href="/dashboard/kriteria-metode/delete/<?= $row['id_kriteria'] ?>"
                               onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                               <i class="ti-trash"></i>
                            </a>
                          </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <form method="POST" action="/dashboard/kriteria-metode/update/<?= $row['id'] ?>">
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Bobot (<?= htmlspecialchars($row['kode']) ?>)</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <input type="hidden" name="metode" value="<?= htmlspecialchars($metodeName) ?>">
                                  <div class="form-group">
                                    <label>Kriteria</label>
                                    <select name="id_kriteria" class="form-control form-control-sm" required>
                                      <?php foreach ($allKriteria as $k): ?>
                                        <option value="<?= $k['id_kriteria'] ?>" <?= $k['id_kriteria'] == $row['id_kriteria'] ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($k['kode'] . ' - ' . $k['nama_kriteria']) ?>
                                        </option>
                                      <?php endforeach; ?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Bobot (contoh 0.4)</label>
                                    <input type="text" name="bobot" value="<?= htmlspecialchars($row['bobot']) ?>" class="form-control form-control-sm" required>
                                  </div>
                                  <div class="form-group">
                                    <label>Sifat</label>
                                    <select name="sifat" class="form-control form-control-sm" required>
                                      <option value="benefit" <?= $row['sifat'] == 'benefit' ? 'selected' : '' ?>>benefit</option>
                                      <option value="cost" <?= $row['sifat'] == 'cost' ? 'selected' : '' ?>>cost</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Urutan</label>
                                    <input type="number" name="urutan" value="<?= htmlspecialchars($row['urutan']) ?>" class="form-control form-control-sm">
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
                      <tr><td colspan="7" class="text-center">Belum ada pengaturan kriteria untuk <?= htmlspecialchars($metodeName) ?></td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- pagination (sama logic) -->
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
                      <a class="page-link" href="/dashboard/kriteria-metode?page=<?= $currentPage - 1 ?><?= $qSearch ?>">«</a>
                    </li>
                    <?php if ($start > 1): ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/kriteria-metode?page=1<?= $qSearch ?>">1</a></li>
                      <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <?php endif; ?>
                    <?php for ($p = $start; $p <= $end; $p++): ?>
                      <li class="page-item <?= ($p == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="/dashboard/kriteria-metode?page=<?= $p ?><?= $qSearch ?>"><?= $p ?></a>
                      </li>
                    <?php endfor; ?>
                    <?php if ($end < $totalPages): ?>
                      <?php if ($end < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                      <li class="page-item"><a class="page-link" href="/dashboard/kriteria-metode?page=<?= $totalPages ?><?= $qSearch ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                      <a class="page-link" href="/dashboard/kriteria-metode?page=<?= $currentPage + 1 ?><?= $qSearch ?>">»</a>
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
      <form method="POST" action="/dashboard/kriteria-metode/store">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Bobot Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">

          <!-- Pilih Metode -->
          <div class="form-group">
            <label>Metode</label>
            <select name="metode" class="form-control form-control-sm" required>
              <option value="">Pilih Metode</option>
              <option value="SAW">SAW</option>
              <option value="WP">WP</option>
            </select>
          </div>

          <!-- Pilih Kriteria -->
          <div class="form-group">
            <label>Kriteria</label>
            <select name="id_kriteria" class="form-control form-control-sm" required>
              <option value="">Pilih Kriteria</option>
              <?php foreach ($allKriteria as $k): ?>
                <option value="<?= $k['id_kriteria'] ?>">
                  <?= htmlspecialchars($k['kode'] . ' - ' . $k['nama_kriteria']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Bobot -->
          <div class="form-group">
            <label>Bobot (contoh 0.4)</label>
            <input type="text" name="bobot" class="form-control form-control-sm" required>
          </div>

          <!-- Sifat -->
          <div class="form-group">
            <label>Sifat</label>
            <select name="sifat" class="form-control form-control-sm" required>
              <option value="benefit">benefit</option>
              <option value="cost">cost</option>
            </select>
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
