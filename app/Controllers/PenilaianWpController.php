<?php
namespace App\Controllers;

use App\Models\PenilaianWp;

class PenilaianWpController
{
    protected $penilaianWp;

    public function __construct()
    {
        $this->penilaianWp = new PenilaianWp();
    }

    public function index()
    {
        $data['title'] = "Penilaian WP";
        $data['nilai'] = $this->penilaianWp->getAll();
        $data['siswa'] = $this->penilaianWp->getSiswa();
        $data['kriteria'] = $this->penilaianWp->getKriteria();

		// Ambil periode aktif
		$periodeModel = new \App\Models\Periode();
		$data['periode_aktif'] = $periodeModel->getActive();
	
        extract($data);
		require __DIR__ . '/../Views/dashboard/penilaian_wp.php';

    }

	public function store()
	{
		$cek = $this->penilaianWp->findBySiswaKriteria($_POST['id_siswa'], $_POST['id_k_metode']);

		if ($cek) {
			$_SESSION['error'] = 'Data dengan siswa dan kriteria tersebut sudah ada!';
			header('Location: /dashboard/penilaian-wp');
			exit;
		}

		$result = $this->penilaianWp->insert([
			'id_siswa'    => $_POST['id_siswa'],
			'id_k_metode' => $_POST['id_k_metode'],
			'periode'     => $_POST['periode'],
			'nilai'       => $_POST['nilai']
		]);

		if ($result) {
			$_SESSION['success'] = 'Data berhasil ditambahkan!';
		} else {
			$_SESSION['error'] = 'Gagal menambahkan data.';
		}

		header('Location: /dashboard/penilaian-wp');
		exit;
	}

    public function update()
    {
        $this->penilaianWp->updateData([
            'id_nilai'    	=> $_POST['id_nilai'],
            'nilai' 		=> $_POST['nilai']
        ]);
		
		if ($result) {
			$_SESSION['error'] = 'Gagal memperbarui data.';
		} else {
			$_SESSION['success'] = 'Data berhasil diperbarui!';
		}

        header('Location: /dashboard/penilaian-wp');
		exit;
    }

	public function delete()
	{
		$id = $_POST['id_nilai'] ?? null;

		if ($id) {
			$model = new PenilaianWp();
			$model->deleteById($id);
		}

		header('Location: /dashboard/penilaian-wp');
		exit;
	}

public function hitung()
{
    // 1) Ambil periode aktif
    $periodeModel = new \App\Models\Periode();
    $periodeAktif = $periodeModel->getActive();
    $periode = $periodeAktif['periode'] ?? null;

    $data['title'] = "Perhitungan WP";
    $data['periode'] = $periode;

    if (!$periode) {
        $data['kriteria'] = [];
        $data['hasil'] = [];
        $data['matrix'] = [];
        extract($data);
        require __DIR__ . '/../Views/dashboard/hitung_wp.php';
        return;
    }

    // 2) Ambil kriteria WP dan nilai siswa
    $kriteria = $this->penilaianWp->getKriteriaWp();
    $nilaiRaw = $this->penilaianWp->getNilaiByPeriodeWP($periode);

    // 3) Normalisasi bobot (w_i = bobot / total_bobot)
    $totalBobot = array_sum(array_column($kriteria, 'bobot'));
    $bobotNorm = [];
    foreach ($kriteria as $k) {
        $idk = $k['id_k_metode'];
        $bobotNorm[$idk] = $totalBobot > 0 ? $k['bobot'] / $totalBobot : 0;
    }

    // 4) Hitung nilai max/min untuk normalisasi nilai (benefit/cost)
    $max = []; $min = [];
    foreach ($kriteria as $k) {
        $max[$k['id_k_metode']] = null;
        $min[$k['id_k_metode']] = null;
    }
    foreach ($nilaiRaw as $r) {
        $idk = $r['id_k_metode'];
        $val = floatval($r['nilai']);
        if (!isset($max[$idk]) || $val > $max[$idk]) $max[$idk] = $val;
        if (!isset($min[$idk]) || $val < $min[$idk]) $min[$idk] = $val;
    }

    // 5) Hitung Vektor S untuk setiap siswa
    $matrix = [];
    $vektorS = [];
    $namaSiswa = [];

    foreach ($nilaiRaw as $r) {
        $id_siswa = $r['id_siswa'];
        $id_km = $r['id_k_metode'];
        $nilai = floatval($r['nilai']);
        $sifat = $r['sifat'];
        $bobotW = $bobotNorm[$id_km] ?? 0;

        // Normalisasi nilai sesuai sifat
        if ($sifat === 'benefit') {
            $normal = (!empty($max[$id_km]) && $max[$id_km] > 0) ? $nilai / $max[$id_km] : 0;
        } else { // cost
            $normal = (!empty($min[$id_km]) && $nilai > 0) ? $min[$id_km] / $nilai : 0;
        }

        // WP → nilai dipangkatkan bobot
        $kontrib = pow($normal, $bobotW);

        $matrix[$id_siswa][$id_km] = [
            'nilai'   => $nilai,
            'normal'  => $normal,
            'bobot'   => $bobotW,
            'kontrib' => $kontrib
        ];

        if (!isset($vektorS[$id_siswa])) {
            $vektorS[$id_siswa] = 1;
        }
        $vektorS[$id_siswa] *= $kontrib;

        // Simpan nama siswa
        $namaSiswa[$id_siswa] = $r['nama'];
    }

    // 6) Hitung Vektor V
    $totalS = array_sum($vektorS);
    $hasil = [];
    foreach ($vektorS as $id_siswa => $S) {
        $V = ($totalS > 0) ? $S / $totalS : 0;
        $hasil[] = [
            'id_siswa'    => $id_siswa,
            'nama'        => $namaSiswa[$id_siswa] ?? '',
            'nilai_s'     => $S,
            'nilai_akhir' => round($V, 6), // ✅ nilai_akhir sudah ada di sini
            'skor'        => $V
        ];
    }

    // 7) Urutkan dari skor tertinggi ke terendah
    usort($hasil, function($a, $b) {
        return $b['skor'] <=> $a['skor'];
    });

    // 8) Ambil batas_min untuk WP
    $batasMin = $this->penilaianWp->getBatasMasalah('WP');

    // 9) Simpan hasil ke database
    $this->penilaianWp->deleteHasilByPeriode($periode);
    $id_user = $_SESSION['user']['id_user'] ?? null;
    $rank = 1;
    foreach ($hasil as &$h) {
        $status = ($h['nilai_akhir'] < $batasMin) ? 'bermasalah' : 'tidak_bermasalah';

        $this->penilaianWp->simpanHasilWP([
            'id_siswa'     => $h['id_siswa'],
            'nilai_s'      => $h['nilai_s'],
            'nilai_akhir'  => $h['nilai_akhir'],
            'nilai_akhir'  => $h['nilai_akhir'], // ✅ kirim ke DB
            'peringkat'    => $rank,
            'status'       => $status,
            'id_user'      => $id_user,
            'periode'      => $periode,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        $h['peringkat'] = $rank++;
        $h['status'] = $status;
    }
    unset($h);

    // 10) Kirim ke view
    $data['kriteria'] = $kriteria;
    $data['matrix'] = $matrix;
    $data['hasil'] = $hasil;

    extract($data);
    require __DIR__ . '/../Views/dashboard/hitung_wp.php';
}


	public function rangking()
	{
		$periodeModel = new \App\Models\Periode();
		$periodeAktif = $periodeModel->getActive();
		$periode = $periodeAktif['periode'] ?? null;

		$data['title'] = "Rangking WP";
		$data['periode'] = $periode;

		$data['kriteria'] = $this->penilaianWp->getKriteria();
		$data['hasil'] = $this->penilaianWp->getHasilByPeriodeWP($periode);

		extract($data);
		require __DIR__ . '/../Views/dashboard/rangking_wp.php';
	}

}
