<?php include __DIR__ . '/../layouts/top_depan.php'; ?>

<div class="container-fluid page-body-wrapper">
    <?php include __DIR__ . '/../layouts/main_menu.php'; ?>
    <div class="main-panel">
        <div class="content-wrapper">

            <h4 class="mb-3"><?= $title; ?></h4>

            <!-- Form Tambah Data 
            <form action="/dashboard/penilaian-saw/store" method="POST" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="id_siswa" class="form-control" required>
                            <option value="">Pilih Siswa</option>
                            <?php foreach ($siswa as $s): ?>
                                <option value="<?= $s['id_siswa']; ?>"><?= $s['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
					<div class="col-md-4">
						<select name="id_k_metode" class="form-control" required>
							<option value="">Pilih Kriteria</option>
							<?php foreach ($kriteria as $k): ?>
								<option value="<?= $k['id_k_metode']; ?>">
									<?= $k['nama_kriteria']; ?> (<?= $k['metode']; ?>)
								</option>
							<?php endforeach; ?>
						</select>
					</div>
                    <div class="col-md-2">
                        <input type="number" name="nilai" class="form-control" placeholder="Nilai" step="0.01" required>
                    </div>
		 
						<?php if (!empty($periode_aktif)): ?>
							<input type="hidden" name="periode" value="<?= $periode_aktif['periode']; ?>" class="form-control" readonly>
						<?php else: ?>
							<input type="hidden" name="periode" value="" class="form-control" placeholder="Tidak ada periode aktif" readonly>
						<?php endif; ?>
 
                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </div>
            </form>
			-->
			<!-- Form Import Excel -->
			<form action="/dashboard/penilaian-saw/import" method="POST" enctype="multipart/form-data" class="mb-4">
				<div class="row">
					<div class="col-md-6">
						<input type="file" name="file_excel" accept=".xls,.xlsx" class="form-control" required>
					</div>
					<div class="col-md-2">
						<button class="btn btn-success" type="submit">Import Excel</button>
					</div>
				</div>
				<small class="text-muted">
					Format Excel: NISN | Nama | Kode Kriteria | Nilai
				</small>
			</form>
				
			<?php if (!empty($_SESSION['success'])): ?>
			<div class="alert alert-success alert-dismissible fade show">
				<?= htmlspecialchars(is_array($_SESSION['success']) ? implode(', ', $_SESSION['success']) : $_SESSION['success']) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php unset($_SESSION['success']); endif; ?>

			<?php if (!empty($_SESSION['error'])): ?>
			<div class="alert alert-danger alert-dismissible fade show">
				<?= htmlspecialchars(is_array($_SESSION['error']) ? implode(', ', $_SESSION['error']) : $_SESSION['error']) ?>
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

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($nilai as $n): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $n['nama']; ?></td>
                            <td><?= $n['nama_kriteria']; ?></td>
                            <td><?= $n['nilai']; ?></td>
                            <td>
								<button class="btn btn-warning btn-sm" 
								  data-toggle="modal" 
								  data-target="#modalEdit<?= $n['id_nilai'] ?>"
								  title="Edit">
								  <i class="ti-pencil"></i>
								</button>
                                <form action="/dashboard/penilaian-saw/delete" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_nilai" value="<?= $n['id_nilai']; ?>">
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')"><i class="ti-trash"></i></button>
                                </form>
                            </td>

                        <!-- Modal Edit (per baris) -->
                        <div class="modal fade" id="modalEdit<?= $n['id_nilai'] ?>" tabindex="-1">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <form method="POST" action="/dashboard/penilaian-saw/update/<?= $n['id_nilai'] ?>">
								<input type="hidden" name="id_nilai" value="<?= $n['id_nilai'] ?>">
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Siswa</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" value="<?= htmlspecialchars($n['nama']) ?>" class="form-control form-control-sm" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label>Kriteria</label>
                                    <input type="text"value="<?= htmlspecialchars($n['nama_kriteria']) ?>" class="form-control form-control-sm" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label>Nilai</label>
                                    <input type="number" name="nilai" value="<?= htmlspecialchars($n['nilai']) ?>" class="form-control form-control-sm" required>
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

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>
