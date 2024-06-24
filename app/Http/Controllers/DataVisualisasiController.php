<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

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
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'JumMhsPerProdi' => $RekapJumlahMahasiswaPerProdi,
            'JumMhsTotal' => $GabunganDataRekapJumlahMahasiswaTotal,
            'JumMhsTotalPerTahun' => $RekapJumlahMahasiswaTotalPerTahun,
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

    public function Visualisasi_DataBebanDosen(Request $request)
    {
        $user = $this->getUserActive();
        $HakAkses = $this->getHakAksesUser($request->select_data);

        $hak_akses_selected = $request->input('hak_akses', $user->hak_akses);
        $Semester_selected = $request->input('semester', 20231);

        $ListProdi = DB::select('SELECT * FROM tbgetprodi');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        // $DosenAktif = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE id_status_aktif = 1');
        $whereProgramStudi = $hak_akses_selected !== 'Admin' ? "amd.nama_program_studi = '$hak_akses_selected' AND" : "";

        $DosenAktif = DB::select("
        SELECT 
            COUNT(*) AS TotalDosen
        FROM (
            SELECT 
                COUNT(ld.id_dosen) AS JumlahDosen
            FROM 
                tbgetlistdosen ld 
            LEFT JOIN 
                tbgetaktivitasmengajardosen amd 
            ON 
                ld.id_dosen = amd.id_dosen 
            AND 
                amd.id_periode = ? 
            WHERE 
                $whereProgramStudi 
                ld.id_status_aktif = 1
            GROUP BY 
                ld.id_dosen, ld.nama_dosen
        ) AS subquery;", [$Semester_selected]);

        $DosenTidakAktif = DB::select("
        SELECT 
            COUNT(*) AS TotalDosen
        FROM (
            SELECT 
                COUNT(ld.id_dosen) AS JumlahDosen
            FROM 
                tbgetlistdosen ld 
            LEFT JOIN 
                tbgetaktivitasmengajardosen amd 
            ON 
                ld.id_dosen = amd.id_dosen 
            AND 
                amd.id_periode = ? 
            WHERE 
                $whereProgramStudi 
                ld.id_status_aktif = 2
            GROUP BY 
                ld.id_dosen, ld.nama_dosen
        ) AS subquery;", [$Semester_selected]);

        //$DosenTidakAktif = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE id_status_aktif = 2');
        $DosenIjin = DB::select("SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen WHERE nama_status_aktif LIKE '%IJIN%'");
        $TotalDosen = DB::select('SELECT COUNT(id) AS TotalDosen FROM tbgetlistdosen');

        $ListDosen = DB::select("SELECT 
            ld.id_dosen, 
            ld.nama_dosen AS Nama_Dosen, 
            COALESCE(COUNT(amd.nama_mata_kuliah), 0) AS Jumlah_Mengajar_Kelas, 
            COALESCE(SUM(amd.rencana_minggu_pertemuan), ' - ') AS Rencana_Pertemuan, 
            COALESCE(SUM(amd.realisasi_minggu_pertemuan), ' - ') AS Realisasi_Pertemuan
        FROM 
            tbgetlistdosen ld 
        LEFT JOIN 
            tbgetaktivitasmengajardosen amd 
        ON 
            ld.id_dosen = amd.id_dosen 
        AND 
            amd.id_periode = 20231 
        GROUP BY 
            ld.id_dosen, ld.nama_dosen
        ORDER BY 
            ld.nama_dosen ASC;");

        $ListDosenAktif = DB::select("SELECT 
            ld.id_dosen, 
            ld.nama_dosen AS Nama_Dosen, 
            COALESCE(COUNT(amd.nama_mata_kuliah), 0) AS Jumlah_Mengajar_Kelas, 
            COALESCE(SUM(amd.rencana_minggu_pertemuan), ' - ') AS Rencana_Pertemuan, 
            COALESCE(SUM(amd.realisasi_minggu_pertemuan), ' - ') AS Realisasi_Pertemuan
        FROM 
            tbgetlistdosen ld 
        LEFT JOIN 
            tbgetaktivitasmengajardosen amd 
        ON 
            ld.id_dosen = amd.id_dosen 
        AND 
            amd.id_periode = 20231 
        WHERE
            ld.nama_status_aktif = 'Aktif'
        GROUP BY 
            ld.id_dosen, ld.nama_dosen
        ORDER BY 
            ld.nama_dosen ASC;");

        $ListDosenTidakAktif = DB::select("SELECT 
            ld.id_dosen, 
            ld.nama_dosen AS Nama_Dosen, 
            COALESCE(COUNT(amd.nama_mata_kuliah), 0) AS Jumlah_Mengajar_Kelas, 
            COALESCE(SUM(amd.rencana_minggu_pertemuan), ' - ') AS Rencana_Pertemuan, 
            COALESCE(SUM(amd.realisasi_minggu_pertemuan), ' - ') AS Realisasi_Pertemuan
        FROM 
            tbgetlistdosen ld 
        LEFT JOIN 
            tbgetaktivitasmengajardosen amd 
        ON 
            ld.id_dosen = amd.id_dosen 
        AND 
            amd.id_periode = 20231 
        WHERE 
            ld.nama_status_aktif	= 'Tidak Aktif'
        GROUP BY 
            ld.id_dosen, ld.nama_dosen
        ORDER BY 
            ld.nama_dosen ASC;");

        $ListDosenIjin = DB::select("SELECT 
            ld.id_dosen, 
            ld.nama_dosen AS Nama_Dosen, 
            COALESCE(COUNT(amd.nama_mata_kuliah), 0) AS Jumlah_Mengajar_Kelas, 
            COALESCE(SUM(amd.rencana_minggu_pertemuan), ' - ') AS Rencana_Pertemuan, 
            COALESCE(SUM(amd.realisasi_minggu_pertemuan), ' - ') AS Realisasi_Pertemuan
        FROM 
            tbgetlistdosen ld 
        LEFT JOIN 
            tbgetaktivitasmengajardosen amd 
        ON 
            ld.id_dosen = amd.id_dosen 
        AND 
            amd.id_periode = 20231 
        WHERE 
            ld.nama_status_aktif LIKE '%Ijin%'
        GROUP BY 
            ld.id_dosen, ld.nama_dosen
        ORDER BY 
            ld.nama_dosen ASC;");

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
            'SelectedAkses' => $hak_akses_selected,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'DosenAktif' => $DosenAktif,
            'DosenTidakAktif' => $DosenTidakAktif,
            'DosenIjin' => $DosenIjin,
            'TotalDosen' => $TotalDosen,
            'ListDosen' => $ListDosen,
            'ListDosenAktif' => $ListDosenAktif,
            'ListDosenTidakAktif' => $ListDosenTidakAktif,
            'ListDosenIjin' => $ListDosenIjin,
            'TotalDosenCowok' => $TotalDosenCowok,
            'TotalDosenCewek' => $TotalDosenCewek,
        ]);
    }

    public function Visualisasi_DataKelasPerkuliahan(Request $request)
    {
        $user = $this->getUserActive();
        $HakAkses = $this->getHakAksesUser($request->select_data);

        $hak_akses_selected = $request->select_data ?? $user->hak_akses;

        $ListProdi = DB::select('SELECT * FROM tbgetprodi');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

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
            'SelectedAkses' => $hak_akses_selected,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
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

    public function Visualisasi_DataBebanDosenDetail(Request $request)
    {
        $encryptedId = $request->input('id');
        $id = Crypt::decrypt($encryptedId);
        $user = $this->getUserActive();

        // Ambil data dari database berdasarkan ID
        $DataDosen = DB::table('tbgetlistdosen')->where('id_dosen', $id)->first();

        $ListKelasDiajar = DB::select("SELECT 
            mk.id_matkul AS IdMatkul, 
            mk.nama_mata_kuliah AS NamaMatkul,  
            mk.kode_mata_kuliah AS KodeMatkul, 
            mk.nama_program_studi AS NamaProdi, 
            amd.nama_kelas_kuliah AS Kelas, 
            mk.sks_mata_kuliah AS JumlahSKS, 
            amd.rencana_minggu_pertemuan AS RencanaPertemuan, 
            amd.realisasi_minggu_pertemuan AS RealisasiPertemuan 
        FROM tbgetaktivitasmengajardosen amd 
        LEFT JOIN tbgetlistmatakuliah mk ON amd.id_matkul = mk.id_matkul 
        WHERE amd.id_dosen = ?", [$id]);

        $ListMahasiswaBimbingan = DB::select("SELECT  lm.nama_mahasiswa AS NamaMahasiswa, 
            lm.nim AS Nim, 
            lm.nama_program_studi AS ProdiMahasiswa, 
            dp.nama_dosen AS NamaDosen, 
            dp.pembimbing_ke AS PembimbingKe, 
            dp.jenis_aktivitas AS AktivitasBimbingan
        FROM tbgetdosenpembimbing dp 
        LEFT JOIN tbgetlistmahasiswa lm ON dp.id_registrasi_mahasiswa = lm.id_registrasi_mahasiswa 
        WHERE lm.id_periode = '20231' AND dp.id_dosen = ?", [$id]);

        // Kembalikan data sebagai JSON
        return view('pages/detail-data-beban-dosen', [
            'pages_active' => 'data-beban-dosen',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
            'DataDosen' => $DataDosen,
            'ListKelasDiajar' => $ListKelasDiajar,
            'ListMahasiswaBimbingan' => $ListMahasiswaBimbingan
        ]);
    }


    // public function Visualisasi_DataKRS()
    // {
    //     return view(, [
    //         'pages_active' => 'data-beban-dosen',
    //         'isActiveMenu' => false,
    //         'HakAkses' => $user->hak_akses,
    //         'SelectedAkses' => $hak_akses_selected,
    //         'ListProdi' => $ListProdi,
    //         'ListFakultas' => $ListFakultas,
    //     ])
    // }
}
