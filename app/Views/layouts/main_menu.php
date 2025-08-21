<?php
// Ambil user dari session
$user = $_SESSION['user'] ?? null;

// Kalau tidak ada user, isi default biar nggak error
if (!$user) {
    $user = [
        'role' => '',
        'nama' => ''
    ];
}

// Simpan role dalam huruf kecil biar mudah dicek
$role = strtolower($user['role']);
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <!-- Menu Dashboard -->
        <li class="nav-item">
            <a class="nav-link active" href="/dashboard/<?php echo $role; ?>">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <!-- ================= ADMIN ================= -->
        <?php if ($role === 'admin') : ?>

            <!-- Data Master -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#dataMaster" aria-expanded="false" aria-controls="dataMaster">
                    <i class="icon-head menu-icon"></i>
                    <span class="menu-title">Data Master</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="dataMaster">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/data-siswa">Data Siswa</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/data-user">Data User</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/data-kriteria">Data Kriteria</a></li>
						<li class="nav-item"><a class="nav-link" href="/dashboard/kriteria-metode">Kriteria Metode</a></li>
						<li class="nav-item"><a class="nav-link" href="/dashboard/periode">Periode</a></li>
						<li class="nav-item"><a class="nav-link" href="/dashboard/batas-masalah">Batas Masalah</a></li>
                    </ul>
                </div>
            </li>

        <?php endif; ?>

        <!-- ================= ADMIN & GURU BK ================= -->
        <?php if (in_array($role, ['guru_bk'])) : ?>

            <!-- Penilaian SAW -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#saw" aria-expanded="false" aria-controls="saw">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">Penilaian SAW</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="saw">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/penilaian-saw">Penilaian</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/hitung-saw">Hitung</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/rangking-saw">Rangking</a></li>
                    </ul>
                </div>
            </li>

            <!-- Penilaian WP -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#wp" aria-expanded="false" aria-controls="wp">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">Penilaian WP</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="wp">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/penilaian-wp">Penilaian</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/hitung-wp">Hitung</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/rangking-wp">Rangking</a></li>
                    </ul>
                </div>
            </li>

            <!-- Perbandingan -->
            <li class="nav-item">
                <a class="nav-link" href="/dashboard/perbandingan">
                    <i class="icon-graph menu-icon"></i>
                    <span class="menu-title">Perbandingan</span>
                </a>
            </li>

            <!-- Laporan -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laporan" aria-expanded="false" aria-controls="laporan">
                    <i class="icon-bar-graph menu-icon"></i>
                    <span class="menu-title">Laporan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="laporan">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/laporan-siswa">Siswa</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/laporan-saw">SAW</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/laporan-wp">WP</a></li>
                        <li class="nav-item"><a class="nav-link" href="/dashboard/laporan-perbandingan">Perbandingan</a></li>
                    </ul>
                </div>
            </li>

        <?php endif; ?>

        <!-- ================= WALI KELAS ================= -->
        <?php if ($role === 'wali_kelas') : ?>

            <!-- Penilaian SAW -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#saw" aria-expanded="false" aria-controls="saw">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">Penilaian SAW</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="saw">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/penilaian-saw">Penilaian</a></li> 
                    </ul>
                </div>
            </li>

            <!-- Penilaian WP -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#wp" aria-expanded="false" aria-controls="wp">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">Penilaian WP</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="wp">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/dashboard/penilaian-wp">Penilaian</a></li> 
                    </ul>
                </div>
            </li>

        <?php endif; ?>

    </ul>
</nav>
