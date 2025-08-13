<?php include __DIR__ . '/../layouts/top_depan.php'; ?> <div class="container-fluid page-body-wrapper">
  <div class="theme-setting-wrapper">
    <div id="settings-trigger">
      <i class="ti-settings"></i>
    </div>
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
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome Back</h3>
              <h6 class="font-weight-normal mb-0"><?php echo htmlspecialchars($_SESSION['user']['nama'] ?? 'Pengguna'); ?> <span class="text-primary"> SMPN 2 Malingping</span>
              </h6>
            </div>
            <div class="col-12 col-xl-4">
              <div class="justify-content-end d-flex">
                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                  <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2">
                    <i class="mdi mdi-calendar"></i>
                    <span id="currentTime"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card tale-bg">
            <div class="card-people mt-auto">
              <img src="/../assets/images/dashboard/people.jpg" alt="people">
              <div class="weather-info">
                <div class="d-flex">
                  <div>
                    <h2 class="mb-0 font-weight-normal">
                      <span id="temperature">SMP</span>
                    </h2>
                  </div>
                  <div class="ml-2">
                    <h4 class="location font-weight-normal" id="city">Negeri</h4>
                    <p class="font-weight-normal" id="description">2 Malingping</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Jumlah Admin Walikelas/BK</p>
                  <p class="fs-30 mb-2"><p class="fs-30 mb-2"><?= isset($jumlah_guru) ? $jumlah_guru : 0 ?></p>
                  <p>Guru</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Jumlah Siswa</p>
                  <p class="fs-30 mb-2"><p class="fs-30 mb-2"><p class="fs-30 mb-2"><?= isset($jumlah_siswa) ? $jumlah_siswa : 0 ?></p>
                  <p>Data selalu realtime</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Jumlah Siswa Laki-laki</p>
                  <p class="fs-30 mb-2"><p class="fs-30 mb-2"><?= isset($jumlah_laki) ? $jumlah_laki : 0 ?></p>
                  <p><?= isset($persen_laki) ? $persen_laki : 0 ?>%</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Jumlah Siswa Perempuan</p>
                  <p class="fs-30 mb-2"><p class="fs-30 mb-2"><?= isset($jumlah_perempuan) ? $jumlah_perempuan : 0 ?></p>
                  <p><?= isset($persen_perempuan) ? $persen_perempuan : 0 ?>%</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="display expandable-table" style="width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>
                            <marquee>Pendidikan Adalah Kunci Masa Depan</marquee>
                          </th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
	<?php include __DIR__ . '/../layouts/footer_depan.php'; ?>