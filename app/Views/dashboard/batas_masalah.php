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
                  <h3 class="font-weight-bold">Master Batas Masalah</h3>
                  <h6 class="font-weight-normal mb-0">Atur batas minimum untuk metode SAW dan WP</h6>
                </div>
                <div class="col-md-6 text-right">
                  <form class="d-inline-block mr-2" action="/dashboard/batas-masalah" method="GET">
                    <div class="input-group input-group-sm" style="max-width:360px;">
                      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari metode" value="<?= htmlspecialchars($search ?? '') ?>">
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
			<?php if (!empty($_SESSION['success'])): ?>
				swal("Berhasil", "<?= $_SESSION['success'] ?>", "success");
			<?php unset($_SESSION['success']); endif; ?>

			<?php if (!empty($_SESSION['error'])): ?>
				swal("Gagal", "<?= $_SESSION['error'] ?>", "error");
			<?php unset($_SESSION['error']); endif; ?>
			</script>

              <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                  <thead class="table-info text-center">
                    <tr>
                      <th style="width:5%;">No</th>
                      <th>Metode</th>
                      <th>Batas Minimum</th> 
                      <th>Dibuat</th>
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
                          <td><?= htmlspecialchars($row['metode']) ?></td>
                          <td class="text-center"><?= number_format((float)$row['batas_min'], 2) ?></td> 
                          <td class="text-center"><?= htmlspecialchars($row['created_at']) ?></td>
                          <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $row['id_batas'] ?>">
                              <i class="ti-pencil"></i>
                            </button>
                            <a href="/dashboard/batas-masalah/delete/<?= $row['id_batas'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm">
                              <i class="ti-trash"></i>
                            </a>
                          </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit<?= $row['id_batas'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <form method="POST" action="/dashboard/batas-masalah/update/<?= $row['id_batas'] ?>">
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Batas Masalah</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label>Metode</label>
                                    <select name="metode" class="form-control form-control-sm" required>
                                      <option value="SAW" <?= $row['metode'] == 'SAW' ? 'selected' : '' ?>>SAW</option>
                                      <option value="WP" <?= $row['metode'] == 'WP' ? 'selected' : '' ?>>WP</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Batas Minimum</label>
                                    <input type="number" step="0.01" name="batas_min" value="<?= htmlspecialchars($row['batas_min']) ?>" class="form-control form-control-sm" required>
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
                      <tr><td colspan="6" class="text-center">Belum ada data batas masalah</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
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
          <form method="POST" action="/dashboard/batas-masalah/store">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Batas Masalah</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Metode</label>
                <select name="metode" class="form-control form-control-sm" required>
                  <option value="">Pilih Metode</option>
                  <option value="SAW">SAW</option>
                  <option value="WP">WP</option>
                </select>
              </div>
              <div class="form-group">
                <label>Batas Minimum</label>
                <input type="number" step="0.01" name="batas_min" class="form-control form-control-sm" required>
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
