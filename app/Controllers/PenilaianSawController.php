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

public function importExcel()
{
    // Cek file upload
    if (empty($_FILES['file_excel']['tmp_name'])) {
        $_SESSION['error'] = 'File Excel tidak ditemukan!';
        header('Location: /dashboard/penilaian-saw');
        exit;
    }

    // Load PHPExcel manual (gunakan path sesuai struktur folder kamu)
    require_once __DIR__ . '/../libraries/PHPExcel/PHPExcel.php';
    require_once __DIR__ . '/../libraries/PHPExcel/IOFactory.php';

    try {
        // Baca file excel
        $inputFileName = $_FILES['file_excel']['tmp_name'];
        $objPHPExcel   = \PHPExcel_IOFactory::load($inputFileName); // tambahkan backslash
        $sheetData     = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        // Ambil periode aktif
        $periodeModel = new \App\Models\Periode();
        $periodeAktif = $periodeModel->getActive();
        if (!$periodeAktif) {
            $_SESSION['error'] = 'Tidak ada periode aktif!';
            header('Location: /dashboard/penilaian-saw');
            exit;
        }
        $periode = $periodeAktif['periode'];

        // DB connection
        $db = \Core\Database::getConnection();

        $totalInsert = 0;
        $totalUpdate = 0;

        // Lewati baris header (mulai dari baris ke-2)
        foreach ($sheetData as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // header

            $nisn         = trim($row['A']);
            $namaExcel    = trim($row['B']);
            $kodeKriteria = trim($row['C']);
            $nilai        = floatval($row['D']);

            if (empty($nisn) || empty($kodeKriteria) || $nilai === null) {
                continue;
            }

            // Cari id_siswa
            $stmt = $db->prepare("SELECT id_siswa FROM tb_siswa WHERE nisn = ?");
            $stmt->execute([$nisn]);
            $siswa = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$siswa) continue;
            $id_siswa = $siswa['id_siswa'];

            // Cari id_kriteria
            $stmt = $db->prepare("SELECT id_kriteria FROM tb_kriteria WHERE kode = ?");
            $stmt->execute([$kodeKriteria]);
            $kriteria = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$kriteria) continue;
            $id_kriteria = $kriteria['id_kriteria'];

            // Cari id_k_metode (SAW)
            $stmt = $db->prepare("SELECT id_k_metode FROM tb_kriteria_metode WHERE id_kriteria = ? AND metode = 'SAW'");
            $stmt->execute([$id_kriteria]);
            $kMetode = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$kMetode) continue;
            $id_k_metode = $kMetode['id_k_metode'];

            // Cek apakah sudah ada data
            $stmt = $db->prepare("SELECT id_nilai FROM tb_nilai WHERE id_siswa = ? AND id_k_metode = ? AND periode = ?");
            $stmt->execute([$id_siswa, $id_k_metode, $periode]);
            $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($existing) {
                // Update
                $stmt = $db->prepare("UPDATE tb_nilai SET nilai = ?, sumber = 'import' WHERE id_nilai = ?");
                $stmt->execute([$nilai, $existing['id_nilai']]);
                $totalUpdate++;
            } else {
                // Insert
                $stmt = $db->prepare("INSERT INTO tb_nilai (id_siswa, id_k_metode, periode, nilai, sumber) VALUES (?, ?, ?, ?, 'import')");
                $stmt->execute([$id_siswa, $id_k_metode, $periode, $nilai]);
                $totalInsert++;
            }
        }

        $_SESSION['success'] = "Import selesai! Tambah: {$totalInsert}, Update: {$totalUpdate}";
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Gagal membaca file: ' . $e->getMessage();
    }

    header('Location: /dashboard/penilaian-saw');
    exit;
}


}
