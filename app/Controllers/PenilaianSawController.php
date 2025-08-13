<?php
namespace App\Controllers;

use App\Models\PenilaianSaw;

class PenilaianSawController
{
    protected $penilaianSaw;

    public function __construct()
    {
        $this->penilaianSaw = new PenilaianSaw();
    }

    public function index()
    {
        $data['title'] = "Penilaian SAW";
        $data['nilai'] = $this->penilaianSaw->getAll();
        $data['siswa'] = $this->penilaianSaw->getSiswa();
        $data['kriteria'] = $this->penilaianSaw->getKriteria();

		// Ambil periode aktif
		$periodeModel = new \App\Models\Periode();
		$data['periode_aktif'] = $periodeModel->getActive();
	
        extract($data);
		require __DIR__ . '/../Views/dashboard/penilaian_saw.php';

    }

	public function store()
	{
		$cek = $this->penilaianSaw->findBySiswaKriteria($_POST['id_siswa'], $_POST['id_k_metode']);

		if ($cek) {
			$_SESSION['error'] = 'Data dengan siswa dan kriteria tersebut sudah ada!';
			header('Location: /dashboard/penilaian-saw');
			exit;
		}

		$result = $this->penilaianSaw->insert([
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

		header('Location: /dashboard/penilaian-saw');
		exit;
	}

    public function update()
    {
        $this->penilaianSaw->updateData([
            'id_nilai'    	=> $_POST['id_nilai'],
            'nilai' 		=> $_POST['nilai']
        ]);
		
		if ($result) {
			$_SESSION['error'] = 'Gagal memperbarui data.';
		} else {
			$_SESSION['success'] = 'Data berhasil diperbarui!';
		}

        header('Location: /dashboard/penilaian-saw');
		exit;
    }

	public function delete()
	{
		$id = $_POST['id_nilai'] ?? null;

		if ($id) {
			$model = new PenilaianSaw();
			$model->deleteById($id);
		}

		header('Location: /dashboard/penilaian-saw');
		exit;
	}

	public function hitung()
	{
		// 1) Ambil periode aktif
		$periodeModel = new \App\Models\Periode();
		$periodeAktif = $periodeModel->getActive();
		$periode = $periodeAktif['periode'] ?? null;

		$data['title'] = "Perhitungan SAW";
		$data['periode'] = $periode;

		if (!$periode) {
			// tidak ada periode aktif
			$data['kriteria'] = [];
			$data['hasil'] = [];
			$data['matrix'] = [];
			extract($data);
			require __DIR__ . '/../Views/dashboard/hitung_saw.php';
			return;
		}

		// 2) Ambil kriteria SAW dan nilai siswa untuk periode itu
		$kriteria = $this->penilaianSaw->getKriteriaSAW();
		$nilaiRaw = $this->penilaianSaw->getNilaiByPeriodeSAW($periode);

		// 3) Hitung max/min per id_k_metode
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

		// 4) Normalisasi & hitung kontribusi per siswa
		$matrix = [];
		$scores = [];
		foreach ($nilaiRaw as $r) {
			$id_siswa = $r['id_siswa'];
			$id_km = $r['id_k_metode'];
			$nilai = floatval($r['nilai']);
			$bobot = floatval($r['bobot']);
			$sifat = $r['sifat'];

			// normalisasi
			$normal = 0.0;
			if ($sifat === 'benefit') {
				$normal = (!empty($max[$id_km]) && $max[$id_km] > 0) ? $nilai / $max[$id_km] : 0;
			} else { // cost
				$normal = (!empty($min[$id_km]) && $nilai > 0) ? $min[$id_km] / $nilai : 0;
			}

			$kontrib = $normal * $bobot;

			$matrix[$id_siswa][$id_km] = [
				'nilai' => $nilai,
				'normal' => $normal,
				'bobot' => $bobot,
				'kontrib' => $kontrib
			];

			if (!isset($scores[$id_siswa])) {
				$scores[$id_siswa] = [
					'id_siswa' => $id_siswa,
					'nama' => $r['nama'],
					'skor' => 0.0
				];
			}
			$scores[$id_siswa]['skor'] += $kontrib;
		}

		// 5) Ubah ke list dan urutkan
		$hasil = array_values($scores);
		usort($hasil, function($a, $b) {
			return $b['skor'] <=> $a['skor'];
		});

		// 6) Ambil batas_min untuk SAW via model
		$batasMin = $this->penilaianSaw->getBatasMasalah('SAW');

		// 7) Simpan hasil: bersihkan hasil lama untuk periode lalu insert baru
		$this->penilaianSaw->deleteHasilByPeriode($periode);

		$rank = 1;
		// ambil id_user dari session (jika ada), agar tersimpan di tb_hasil_saw

		$id_user = $_SESSION['user']['id_user'] ?? null; // sesuaikan dengan nama session user di aplikasi kamu

		foreach ($hasil as &$h) {
			$nilaiAkhir = round($h['skor'], 6);
			// gunakan nilai enum yang sesuai DB: 'bermasalah' atau 'tidak_bermasalah'
			$status = ($nilaiAkhir < $batasMin) ? 'bermasalah' : 'tidak_bermasalah';

			// simpan via model
			$this->penilaianSaw->simpanHasilSAW([
				'id_siswa'     => $h['id_siswa'],
				'nilai_akhir'  => $nilaiAkhir,
				'peringkat'    => $rank,
				'status'       => $status,
				'id_user'      => $id_user,
				'periode'      => $periode,
				'created_at'   => date('Y-m-d H:i:s')
			]);

			$h['peringkat'] = $rank++;
			$h['nilai_akhir'] = $nilaiAkhir;
			$h['status'] = $status;
		}
		unset($h);

		$data['kriteria'] = $kriteria;
		$data['matrix'] = $matrix;
		$data['hasil'] = $hasil;

		extract($data);
		require __DIR__ . '/../Views/dashboard/hitung_saw.php';
	}

	public function rangking()
	{
		$periodeModel = new \App\Models\Periode();
		$periodeAktif = $periodeModel->getActive();
		$periode = $periodeAktif['periode'] ?? null;

		$data['title'] = "Rangking SAW";
		$data['periode'] = $periode;

		$data['kriteria'] = $this->penilaianSaw->getKriteria();
		$data['hasil'] = $this->penilaianSaw->getHasilByPeriodeSAW($periode);

		extract($data);
		require __DIR__ . '/../Views/dashboard/rangking_saw.php';
	}

}
