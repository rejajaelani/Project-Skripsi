<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataVisualisasiController extends Controller
{

    private function getUserActive()
    {
        return Auth::user();
    }

    private function getHakAksesUser($selectData)
    {
        $user = $this->getUserActive();

        $hak_akses_selected = $selectData ?? $user->hak_akses;

        $hakAkses = "";

        if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
            if ($hak_akses_selected == "Admin" || $hak_akses_selected == "Rektor") {
                $hakAkses = "";
            } else if ($hak_akses_selected == "Teknologi dan Informatika") {
                $hakAkses = "WHERE nama_program_studi IN ('S1 Teknik Informatika', 'S1 Rekayasa Sistem Komputer', 'S1 Sistem Komputer')";
            } else if ($hak_akses_selected == "Bisnis dan Desain Kreatif") {
                $hakAkses = "WHERE nama_program_studi IN ('S1 Bisnis Digital', 'S1 Desain Komunikasi Visual')";
            } else {
                $hakAkses = "WHERE nama_program_studi = 'S1 " . $hak_akses_selected . "'";
            }
        } elseif ($user->hak_akses == "Teknologi dan Informatika") {
            if ($hak_akses_selected == "Teknologi dan Informatika") {
                $hakAkses = "WHERE nama_program_studi IN ('S1 Teknik Informatika', 'S1 Rekayasa Sistem Komputer', 'S1 Sistem Komputer')";
            } else {
                $hakAkses = "WHERE nama_program_studi = 'S1 " . $hak_akses_selected . "'";
            }
        } elseif ($user->hak_akses == "Bisnis dan Desain Kreatif") {
            if ($hak_akses_selected == "Bisnis dan Desain Kreatif") {
                $hakAkses = "WHERE nama_program_studi IN ('S1 Bisnis Digital', 'S1 Desain Komunikasi Visual')";
            } else {
                $hakAkses = "WHERE nama_program_studi = 'S1 " . $hak_akses_selected . "'";
            }
        } else {
            if ($user->hak_akses == $hak_akses_selected) {
                $hakAkses = "WHERE nama_program_studi = 'S1 " . $hak_akses_selected . "'";
            } else {
                $hakAkses = "WHERE nama_program_studi = '000'";
            }
        }

        return $hakAkses;
    }

    public function Visualisasi_Dashboard(Request $request)
    {

        $user = $this->getUserActive();
        $HakAkses = $this->getHakAksesUser($request->select_data);

        $hak_akses_selected = $request->select_data ?? $user->hak_akses;
        $isSelectData = $request->select_data == "" ? false : true;
        //var_dump($HakAkses) . die();

        $RekapJumlahMahasiswaPerProdi = DB::select('SELECT nama_program_studi AS Nama_Program_Studi, SUM(aktif) AS Mahasiswa_Aktif, SUM(cuti) AS Mahasiswa_Cuti, SUM(non_aktif) AS Mahasiswa_NonAktif, (SUM(aktif)+SUM(cuti)+SUM(non_aktif)) AS Total FROM tbgetrekapjumlahmahasiswa ' . $HakAkses . ' GROUP BY id_prodi, nama_program_studi ORDER BY nama_program_studi ASC');

        // $RekapJumlahMahasiswaTotal = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_Aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa GROUP BY SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) ORDER BY Tahun ASC');

        // Menginisialisasi objek untuk menyimpan data gabungan
        $GabunganDataRekapJumlahMahasiswaTotal = (object) [
            'Total_Mahasiswa_Aktif' => 0,
            'Total_Mahasiswa_Cuti' => 0,
            'Total_Mahasiswa_NonAktif' => 0,
            'TotalMahasiswa' => 0
        ];

        // Menjalankan kueri untuk mendapatkan data
        $RekapJumlahMahasiswaTotal = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_Aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa ' . $HakAkses . ' GROUP BY SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) ORDER BY Tahun ASC');

        // Menggabungkan data
        foreach ($RekapJumlahMahasiswaTotal as $data) {
            // Pastikan bahwa data yang diterima adalah numerik sebelum melakukan operasi penambahan
            $GabunganDataRekapJumlahMahasiswaTotal->Total_Mahasiswa_Aktif += $data->Total_Mahasiswa_Aktif;
            $GabunganDataRekapJumlahMahasiswaTotal->Total_Mahasiswa_Cuti += $data->Total_Mahasiswa_Cuti;
            $GabunganDataRekapJumlahMahasiswaTotal->Total_Mahasiswa_NonAktif += $data->Total_Mahasiswa_NonAktif;
            $GabunganDataRekapJumlahMahasiswaTotal->TotalMahasiswa += $data->TotalMahasiswa;
        }


        //var_dump($GabunganDataRekapJumlahMahasiswaTotal) . die();

        // SELECT nama_program_studi AS Nama_Program_Studi, SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa WHERE SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) = 2023 GROUP BY nama_program_studi, SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) ORDER BY Tahun ASC;

        $RekapJumlahMahasiswaTotalPerTahun = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa ' . $HakAkses . ' GROUP BY Tahun ORDER BY Tahun ASC');

        $ListProdi = DB::select('SELECT * FROM tbgetprodi');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $RekapPerProdi = [];

        foreach ($ListProdi as $prodi) {
            $RekapJumlahMahasiswaTotalPerProdi = DB::select(
                '
                SELECT 
                    SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, 
                    SUM(aktif) AS Total_Mahasiswa_Aktif, 
                    SUM(cuti) AS Total_Mahasiswa_Cuti, 
                    SUM(non_aktif) AS Total_Mahasiswa_NonAktif, 
                    (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa 
                FROM 
                    tbgetrekapjumlahmahasiswa 
                WHERE 
                    id_prodi = :id_prodi 
                GROUP BY 
                    SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1)',
                ['id_prodi' => $prodi->id_prodi]
            );

            $RekapPerProdi[$prodi->nama_program_studi] = $RekapJumlahMahasiswaTotalPerProdi;
        }


        //var_dump($RekapPerProdi) . die();

        return view('dashboard', [
            'pages_active' => 'dashboard',
            'isActiveMenu' => false,
            'HakAkses' => $hak_akses_selected,
            'UserAkses' => $user->hak_akses,
            'JumMhsPerProdi' => $RekapJumlahMahasiswaPerProdi,
            'JumMhsTotal' => $GabunganDataRekapJumlahMahasiswaTotal,
            'JumMhsTotalPerTahun' => $RekapJumlahMahasiswaTotalPerTahun,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'RekapPerProdi' => $RekapPerProdi
        ]);
    }

    public function Visualisasi_User()
    {

        $user = $this->getUserActive();

        $ListUser = DB::select('SELECT * FROM tbusers');
        $ListProdi = DB::select('SELECT * FROM tbgetprodi');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        return view('user', [
            'pages_active' => 'user',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
            'ListUser' => $ListUser,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
        ]);
    }

    public function Visualisasi_DataSync()
    {

        $user = $this->getUserActive();

        return view('pages/data-sync', [
            'pages_active' => 'data-sync',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
        ]);
    }

    public function Visualisasi_DataBebanDosen()
    {
        $user = $this->getUserActive();

        $DosenAktif = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE id_status_aktif = 1');
        $DosenTidakAktif = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE id_status_aktif = 2');
        $DosenIjin = DB::select("SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE nama_status_aktif LIKE '%IJIN%'");
        $TotalDosen = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen');

        $AktifitasDosen = DB::select('SELECT  id_dosen, nama_dosen AS Nama_Dosen, 
        COUNT(nama_mata_kuliah) AS Jumlah_Mengajar_Kelas, 
        SUM(rencana_minggu_pertemuan) AS Rencana_Pertemuan, 
        SUM(realisasi_minggu_pertemuan) AS Realisasi_Pertemuan
        FROM tbgetaktivitasmengajardosen 
        WHERE id_periode = 20231
        GROUP BY id_dosen, nama_dosen
        ORDER BY nama_dosen ASC');

        $TotalDosenCowok = DB::table('tbgetlistdosen')
            ->where('jenis_kelamin', 'L')
            ->count();

        $TotalDosenCewek = DB::table('tbgetlistdosen')
            ->where('jenis_kelamin', 'P')
            ->count();

        return view('pages/data-beban-dosen', [
            'pages_active' => 'data-beban-dosen',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
            'DosenAktif' => $DosenAktif,
            'DosenTidakAktif' => $DosenTidakAktif,
            'DosenIjin' => $DosenIjin,
            'TotalDosen' => $TotalDosen,
            'ListDosenAktifitas' => $AktifitasDosen,
            'TotalDosenCowok' => $TotalDosenCowok,
            'TotalDosenCewek' => $TotalDosenCewek,
        ]);
    }

    public function Visualisasi_DataKelasPerkuliahan()
    {
        $user = $this->getUserActive();

        $TotalMatkulWajib = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Wajib'");

        $TotalMatkulWajibPeminatan = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Wajib Peminatan'");

        $TotalMatkulPilihan = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Pilihan'");

        $TotalMatkulTA = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Tugas akhir/ Skripsi/ Thesis/ Disertasi'");

        $TotalMatkulNULL = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah IS NULL");

        $TotalMatkul = DB::select("SELECT COUNT(id) AS TotalMatkul
        FROM tbgetlistmatakuliah");

        $ListKelasPerkuliahan = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul, 
        nama_kelas_kuliah AS Kelas, 
        sks AS JumlahSKS, 
        nama_dosen AS NamaDosen
        FROM tbgetlistkelaskuliah 
        WHERE id_semester IN ('20231','20232') 
        ORDER BY nama_mata_kuliah ASC");

        $ListKelasPerkuliahanNULL = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul, 
        nama_kelas_kuliah AS Kelas, 
        sks AS JumlahSKS, 
        nama_dosen AS NamaDosen
        FROM tbgetlistkelaskuliah 
        WHERE id_semester IN ('20231','20232') AND nama_dosen IS NULL 
        ORDER BY nama_mata_kuliah ASC");

        $TotalKelas = DB::select("SELECT  COUNT(nama_mata_kuliah) AS TotalKelas
        FROM tbgetlistkelaskuliah 
        WHERE id_semester IN ('20232')");

        $TotalKelasNULL = DB::select("SELECT  COUNT(nama_mata_kuliah) AS TotalKelas
        FROM tbgetlistkelaskuliah 
        WHERE id_semester IN ('20232') AND nama_dosen IS NULL");

        $ListMataKuliahWajib = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Wajib' 
        ORDER BY nama_mata_kuliah ASC");

        $ListMataKuliahWajibPeminatan = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Wajib Peminatan' 
        ORDER BY nama_mata_kuliah ASC");

        $ListMataKuliahPilihan = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Pilihan' 
        ORDER BY nama_mata_kuliah ASC");

        $ListMataKuliahTA = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah = 'Tugas akhir/ Skripsi/ Thesis/ Disertasi' 
        ORDER BY nama_mata_kuliah ASC");

        $ListMataKuliahNULL = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        WHERE nama_jenis_mata_kuliah IS NULL 
        ORDER BY nama_mata_kuliah ASC");

        $ListTotalMataKuliah = DB::select("SELECT  nama_mata_kuliah AS NamaMatkul, 
        kode_mata_kuliah AS KodeMatkul,
        nama_program_studi AS NamaStudi, 
        IFNULL(sks_tatap_muka, 0.00) AS MateriSKS, 
        IFNULL(sks_praktek, 0.00) AS PraktekSKS, 
        IFNULL(sks_praktek_lapangan, 0.00) AS PraktekLapanganSKS, 
        IFNULL(sks_simulasi, 0.00) AS SimulasiSKS,  
        IFNULL(sks_mata_kuliah, 0.00) AS JumlahSKS
        FROM tbgetlistmatakuliah 
        ORDER BY nama_mata_kuliah ASC");

        return view('pages/data-kelas-perkuliahan', [
            'pages_active' => 'data-kelas-perkuliahan',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
            'TotalMatkulWajib' => $TotalMatkulWajib,
            'TotalMatkulWajibPeminatan' => $TotalMatkulWajibPeminatan,
            'TotalMatkulPilihan' => $TotalMatkulPilihan,
            'TotalMatkulTA' => $TotalMatkulTA,
            'TotalMatkulNULL' => $TotalMatkulNULL,
            'TotalMatkul' => $TotalMatkul,
            'ListKelasPerkuliahan' => $ListKelasPerkuliahan,
            'ListKelasPerkuliahanNULL' => $ListKelasPerkuliahanNULL,
            'TotalKelas' => $TotalKelas,
            'TotalKelasNULL' => $TotalKelasNULL,
            'ListMataKuliahWajib' => $ListMataKuliahWajib,
            'ListMataKuliahWajibPeminatan' => $ListMataKuliahWajibPeminatan,
            'ListMataKuliahPilihan' => $ListMataKuliahPilihan,
            'ListMataKuliahTA' => $ListMataKuliahTA,
            'ListMataKuliahNULL' => $ListMataKuliahNULL,
            'ListTotalMataKuliah' => $ListTotalMataKuliah
        ]);
    }

    public function Visualisasi_DataBebanDosenDetailAPI(Request $request)
    {
        $id = $request->input('id');
        // Ambil data dari database berdasarkan ID
        $data = DB::table('tbgetlistdosen')->where('id_dosen', $id)->first();

        // Kembalikan data sebagai JSON
        return response()->json($data);
    }
}
