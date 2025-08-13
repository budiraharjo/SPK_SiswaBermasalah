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
                  <h3 class="font-weight-bold">Data User</h3>
                  <h6 class="font-weight-normal mb-0">Kelola data User SMPN 2 Malingping</h6>
                </div>
                <div class="col-md-6 text-right">
                  <form class="d-inline-block mr-2" action="/dashboard/data-user" method="GET">
                    <div class="input-group input-group-sm" style="max-width:360px;">
                      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / username" value="<?= htmlspecialchars($search ?? '') ?>">
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="ti-search"></i> Cari</button>
                      </div>
                    </div>
                  </form>

                  <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahUser">
                    <i class="ti-plus"></i> Tambah
                  </button>
                </div>
              </div>


			  <div class="table-responsive">
				<table class="table table-striped table-hover table-bordered">
				  <thead class="table-info text-center">
					  <tr>
						<th>No</th>
						<th>Username</th>
						<th>Nama</th>
						<th>Role</th>
						<th>Created</th>
						<th>Aksi</th>
					  </tr>
					</thead>
					<tbody>
					  <?php $no = $offset + 1; foreach ($users as $row): ?>
						<tr>
						  <td><?= $no++ ?></td>
						  <td><?= htmlspecialchars($row['username']) ?></td>
						  <td><?= htmlspecialchars($row['nama']) ?></td>
						  <td><?= htmlspecialchars($row['role']) ?></td>
						  <td><?= htmlspecialchars($row['created_at']) ?></td>
						  <td>
							<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $row['id_user'] ?>">
							  <i class="ti-pencil"></i>
							</button>
							<a href="/dashboard/data-user/delete/<?= $row['id_user'] ?>" onclick="return confirm('Hapus user ini?')" class="btn btn-danger btn-sm">
							  <i class="ti-trash"></i>
							</a>
						  </td>
						</tr>

						<!-- Modal Edit -->
						<div class="modal fade" id="modalEdit<?= $row['id_user'] ?>" tabindex="-1">
						  <div class="modal-dialog">
							<form method="POST" action="/dashboard/data-user/update/<?= $row['id_user'] ?>">
							  <div class="modal-content">
								<div class="modal-header">
								  <h5>Edit User</h5>
								  <button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
								  <div class="form-group">
									<label>Username</label>
									<input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" class="form-control" required>
								  </div>
								  <div class="form-group">
									<label>Password (kosongkan jika tidak diubah)</label>
									<input type="password" name="password" class="form-control">
								  </div>
								  <div class="form-group">
									<label>Nama</label>
									<input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" class="form-control" required>
								  </div>
								  <div class="form-group">
									<label>Role</label>
									<select name="role" class="form-control">
									  <option <?= $row['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
									  <option <?= $row['role'] == 'Guru_BK' ? 'selected' : '' ?>>Guru_BK</option>
									  <option <?= $row['role'] == 'Wali_Kelas' ? 'selected' : '' ?>>Wali_Kelas</option>
									</select>
								  </div>
								</div>
								<div class="modal-footer">
								  <button type="submit" class="btn btn-primary">Simpan</button>
								  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
								</div>
							  </div>
							</form>
						  </div>
						</div>
					  <?php endforeach; ?>
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

		<!-- Modal Tambah -->
		<div class="modal fade" id="modalTambahUser" tabindex="-1">
		  <div class="modal-dialog">
			<form method="POST" action="/dashboard/data-user/store">
			  <div class="modal-content">
				<div class="modal-header">
				  <h5>Tambah User</h5>
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
				  <div class="form-group">
					<label>Username</label>
					<input type="text" name="username" class="form-control" required>
				  </div>
				  <div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" required>
				  </div>
				  <div class="form-group">
					<label>Nama</label>
					<input type="text" name="nama" class="form-control" required>
				  </div>
				  <div class="form-group">
					<label>Role</label>
					<select name="role" class="form-control" required>
					  <option value="Admin">Admin</option>
					  <option value="Guru_BK">Guru_BK</option>
					  <option value="Wali_Kelas">Wali_Kelas</option>
					</select>
				  </div>
				</div>
				<div class="modal-footer">
				  <button type="submit" class="btn btn-primary">Simpan</button>
				  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				</div>
			  </div>
			</form>
		  </div>
		</div>

        <?php include __DIR__ . '/../layouts/footer_depan.php'; ?>