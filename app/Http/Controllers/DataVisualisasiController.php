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
            'User' => $user,
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
            'User' => $user,
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
            'User' => $user,
            'pages_active' => 'data-sync',
            'isActiveMenu' => false,
            'HakAkses' => $user->hak_akses,
        ]);
    }

    public function Visualisasi_KelasPerkuliahan(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;

        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $queryTotalKelasPerkuliahan = "SELECT COUNT(id_kelas_kuliah) AS Total 
        FROM tbgetlistkelaskuliah 
        WHERE id_semester = ?";

        $queryTotalKelasPerkuliahanNULL = "SELECT COUNT(id_kelas_kuliah) AS Total 
        FROM tbgetlistkelaskuliah 
        WHERE id_semester = ? AND nama_dosen IS NULL";

        $queryTotalKelasPerkuliahanPerProdi = [];
        $queryListKelasPerkuliahanPerProdi = [];

        foreach ($ListProdi as $prodi) {
            $queryTotalKelasPerkuliahanPerProdi[$prodi->id_prodi] = "SELECT id_prodi AS IdProdi, REPLACE(nama_program_studi, 'S1 ', '') AS NamaProdi, COUNT(id_kelas_kuliah) AS Total 
            FROM tbgetlistkelaskuliah 
            WHERE id_semester = ? AND id_prodi = '{$prodi->id_prodi}' GROUP BY id_prodi, nama_program_studi ORDER BY nama_program_studi ASC";

            // Perulangan untuk mengambil data kelas per prodi
            $queryListKelasPerkuliahanPerProdi[$prodi->id_prodi] = DB::select(
                "SELECT nama_mata_kuliah AS NamaMatkul, 
                kode_mata_kuliah AS KodeMatkul, 
                nama_kelas_kuliah AS Kelas, 
                sks AS JumlahSKS, 
                nama_dosen AS NamaDosen
                FROM tbgetlistkelaskuliah 
                WHERE id_semester = ? AND id_prodi = ? ORDER BY nama_program_studi ASC",
                [$semester_selected, $prodi->id_prodi]
            );
        }

        $queryListKelasPerkuliahan = "SELECT nama_mata_kuliah AS NamaMatkul, 
            kode_mata_kuliah AS KodeMatkul, 
            nama_kelas_kuliah AS Kelas, 
            sks AS JumlahSKS, 
            nama_dosen AS NamaDosen
            FROM tbgetlistkelaskuliah 
            WHERE id_semester = ?";

        $queryListKelasPerkuliahanNULL = "SELECT nama_mata_kuliah AS NamaMatkul, 
            kode_mata_kuliah AS KodeMatkul, 
            nama_kelas_kuliah AS Kelas, 
            sks AS JumlahSKS, 
            nama_dosen AS NamaDosen
            FROM tbgetlistkelaskuliah 
            WHERE id_semester = ? AND nama_dosen IS NULL";

        if ($hak_akses_selected !== 'All Data') {
            $queryTotalKelasPerkuliahan .= " AND id_prodi = ?";
            $queryTotalKelasPerkuliahanNULL .= " AND id_prodi = ?";
            $queryListKelasPerkuliahan .= " AND id_prodi = ?";
            $queryListKelasPerkuliahanNULL .= " AND id_prodi = ?";
        }

        $queryListKelasPerkuliahanNULL .= " ORDER BY nama_mata_kuliah ASC";

        if ($hak_akses_selected !== 'All Data') {
            $TotalKelasPerkuliahan = DB::select($queryTotalKelasPerkuliahan, [$semester_selected, $hak_akses_selected]);
            $TotalKelasPerkuliahanNULL = DB::select($queryTotalKelasPerkuliahanNULL, [$semester_selected, $hak_akses_selected]);
            $ListKelasPerkuliahan = DB::select($queryListKelasPerkuliahan, [$semester_selected, $hak_akses_selected]);
            $ListKelasPerkuliahanNULL = DB::select($queryListKelasPerkuliahanNULL, [$semester_selected, $hak_akses_selected]);
        } else {
            $TotalKelasPerkuliahan = DB::select($queryTotalKelasPerkuliahan, [$semester_selected]);
            $TotalKelasPerkuliahanNULL = DB::select($queryTotalKelasPerkuliahanNULL, [$semester_selected]);
            $ListKelasPerkuliahan = DB::select($queryListKelasPerkuliahan, [$semester_selected]);
            $ListKelasPerkuliahanNULL = DB::select($queryListKelasPerkuliahanNULL, [$semester_selected]);
        }

        $ListTotalKelasPerkuliahanPerProdi = [];

        foreach ($ListProdi as $prodi) {
            $resultTotal = DB::select($queryTotalKelasPerkuliahanPerProdi[$prodi->id_prodi], [$semester_selected]);
            $resultKelas = $queryListKelasPerkuliahanPerProdi[$prodi->id_prodi];

            $ListTotalKelasPerkuliahanPerProdi[] = (object) [
                'IdProdi' => $prodi->id_prodi,
                'NamaProdi' => str_replace('S1 ', '', $prodi->nama_program_studi),
                'Total' => empty($resultTotal) ? 0 : $resultTotal[0]->Total,
                'ListKelas' => $resultKelas
            ];
        }

        //var_dump($ListTotalKelasPerkuliahanPerProdi).die();

        return view('pages/kelas-perkuliahan', [
            'User' => $user,
            'pages_active' => 'kelas-perkuliahan',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' && $hak_akses_selected == 'All Data' || $request->akses == 'All Data' && $hak_akses_selected == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'TotalKelasPerkuliahan' => $TotalKelasPerkuliahan,
            'TotalKelasPerkuliahanNULL' => $TotalKelasPerkuliahanNULL,
            'ListKelasPerkuliahan' => $ListKelasPerkuliahan,
            'ListKelasPerkuliahanNULL' => $ListKelasPerkuliahanNULL,
            'ListTotalKelasPerkuliahanPerProdi' => $ListTotalKelasPerkuliahanPerProdi,
        ]);
    }

    public function Visualisasi_KelulusanDO(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;
        $ListJenisKeluar = DB::select('SELECT * FROM tbgetjeniskeluar ORDER BY id_jenis_keluar ASC');

        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $queryTotalMahasiswaLulusDO = [];
        $queryListMahasiswaLulusDO = [];

        $whereProdiSelected = "";

        if ($request->akses != "" && $request->akses != "All Data") {
            $whereProdiSelected = "AND id_prodi = '{$request->akses}'";
        } else if ($hak_akses_selected != 'All Data') {
            $whereProdiSelected = "AND id_prodi = '{$hak_akses_selected}'";
        }

        foreach ($ListJenisKeluar as $jenis) {
            $queryTotalMahasiswaLulusDO[$jenis->id_jenis_keluar] = "SELECT  nama_jenis_keluar AS Jenis, 
                COUNT(id) AS Total 
            FROM tbgetlistmahasiswalulusdo 
            WHERE id_jenis_keluar = '{$jenis->id_jenis_keluar}' {$whereProdiSelected} AND id_periode_keluar = {$semester_selected}
            GROUP BY nama_jenis_keluar";

            $queryListMahasiswaLulusDO[$jenis->id_jenis_keluar] = DB::select(
                "SELECT  nim AS Nim, 
                    nama_mahasiswa AS NamaMahasiswa, 
                    nama_program_studi AS NamaProdi, 
                    angkatan AS Angkatan, 
                    nama_jenis_keluar AS JenisKeluar
                FROM tbgetlistmahasiswalulusdo
                WHERE id_periode_keluar = ? {$whereProdiSelected} AND id_jenis_keluar = ? ORDER BY nama_mahasiswa ASC",
                [$semester_selected, $jenis->id_jenis_keluar]
            );
        }

        $ListKelulusanDO = [];

        foreach ($ListJenisKeluar as $jenis) {
            $resultTotal = DB::select($queryTotalMahasiswaLulusDO[$jenis->id_jenis_keluar]);
            $resultMahasiswa = $queryListMahasiswaLulusDO[$jenis->id_jenis_keluar];

            $ListKelulusanDO[] = (object) [
                'Jenis' => $jenis->jenis_keluar,
                'Total' => empty($resultTotal) ? 0 : $resultTotal[0]->Total,
                'ListMahasiswa' => $resultMahasiswa
            ];
        }

        //var_dump($ListKelulusanDO) . die();

        return view('pages/kelulusan-do', [
            'User' => $user,
            'pages_active' => 'kelulusan-do',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'ListJenisKeluar' => $ListJenisKeluar,
            'ListKelulusanDO' => $ListKelulusanDO
        ]);
    }

    public function Visualisasi_AKM(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;
        $ListStatusMhs = DB::select('SELECT * FROM tbgetstatusmahasiswa');

        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $queryTotalMahasiswa = [];
        $queryListMahasiswa = [];

        $whereProdiSelected = "";
        $whereProdiSelectedAKM = "";


        if ($request->akses != "" && $request->akses != "All Data") {
            $whereProdiSelected = "AND id_prodi = '{$request->akses}'";
            $whereProdiSelectedAKM = "AND akm.id_prodi = '{$request->akses}'";
        } else if ($hak_akses_selected != 'All Data') {
            $whereProdiSelected = "AND id_prodi = '{$hak_akses_selected}'";
            $whereProdiSelectedAKM = "AND akm.id_prodi = '{$hak_akses_selected}'";
        }

        $TotalMahasiswaAKM = DB::select(
            "SELECT 
                COUNT(lm.id) AS Total
            FROM 
                tbgetlistmahasiswa lm 
            JOIN 
                tbgetaktivitaskuliahmahasiswa akm
            ON
                lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            WHERE 
                lm.id_periode = ?
            {$whereProdiSelectedAKM}",
            [$semester_selected]
        );

        $ListMahasiswaALLStatus = DB::select(
            "SELECT 
                lm.nama_mahasiswa AS NamaMahasiswa, 
                lm.nim AS NIM, 
                lm.jenis_kelamin AS JenisKelamin, 
                lm.nama_agama AS NamaAgama, 
                lm.nama_program_studi AS NamaProdi, 
                akm.nama_semester AS Semester, 
                akm.angkatan AS Angkatan, 
                akm.nama_status_mahasiswa AS StatusMahasiswa, 
                akm.ips AS IPS,
                akm.ipk AS IPK,
                akm.sks_semester AS SKSPerSemester,
                akm.sks_total AS SKSKeseluruhan, 
                akm.biaya_kuliah_smt AS BiayaKuliahPerSemester
            FROM 
                tbgetlistmahasiswa lm 
            JOIN 
                tbgetaktivitaskuliahmahasiswa akm
            ON
                lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            WHERE 
                lm.id_periode = ?
            {$whereProdiSelectedAKM}",
            [$semester_selected]
        );

        foreach ($ListStatusMhs as $status) {
            $queryTotalMahasiswa[$status->nama_status_mahasiswa] = "
                SELECT 
                    COUNT(lm.id) AS Total 
                FROM 
                    tbgetlistmahasiswa lm
                JOIN
                    tbgetaktivitaskuliahmahasiswa akm
                ON
                    lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
                WHERE 
                    akm.id_semester = ?
                AND
                    akm.nama_status_mahasiswa = ?
                    {$whereProdiSelectedAKM}";

            $params = [$semester_selected, $status->nama_status_mahasiswa];
            if ($whereProdiSelectedAKM != "") {
                $params[] = $request->akses;
            }

            $queryListMahasiswa[$status->nama_status_mahasiswa] = DB::select(
                "SELECT 
                    lm.nama_mahasiswa AS NamaMahasiswa, 
                    lm.nim AS NIM, 
                    lm.jenis_kelamin AS JenisKelamin, 
                    lm.nama_agama AS NamaAgama, 
                    lm.nama_program_studi AS NamaProdi, 
                    akm.nama_semester AS Semester, 
                    akm.angkatan AS Angkatan, 
                    akm.nama_status_mahasiswa AS StatusMahasiswa, 
                    akm.ips AS IPS,
                    akm.ipk AS IPK,
                    akm.sks_semester AS SKSPerSemester,
                    akm.sks_total AS SKSKeseluruhan, 
                    akm.biaya_kuliah_smt AS BiayaKuliahPerSemester
                FROM 
                    tbgetlistmahasiswa lm 
                JOIN 
                    tbgetaktivitaskuliahmahasiswa akm
                ON
                    lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
                WHERE 
                    akm.nama_status_mahasiswa = ?
                AND
                    lm.id_periode = ?
                {$whereProdiSelectedAKM}",
                [$status->nama_status_mahasiswa, $semester_selected]
            );
        }

        $ListMahasiswaAKM = [];

        foreach ($ListStatusMhs as $status) {
            $params = [$semester_selected, $status->nama_status_mahasiswa];
            if ($whereProdiSelectedAKM != "") {
                $params[] = $request->akses;
            }
            $resultTotal = DB::select($queryTotalMahasiswa[$status->nama_status_mahasiswa], [$semester_selected, $status->nama_status_mahasiswa]);
            $resultMahasiswa = $queryListMahasiswa[$status->nama_status_mahasiswa];

            $ListMahasiswaAKM[] = (object) [
                'Status' => $status->nama_status_mahasiswa,
                'Total' => empty($resultTotal) ? 0 : $resultTotal[0]->Total,
                'ListMahasiswa' => !empty($resultMahasiswa) ? $resultMahasiswa : []
            ];
        }

        //var_dump($ListMahasiswaAKM) . die();
        //var_dump($ListMahasiswaALLStatus) . die();

        return view('pages/akm', [
            'User' => $user,
            'pages_active' => 'akm',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'ListMahasiswaAKM' => $ListMahasiswaAKM,
            'ListMahasiswaALLStatus' => $ListMahasiswaALLStatus,
            'TotalMahasiswaAKM' => $TotalMahasiswaAKM,
        ]);
    }

    public function Visualisasi_KRS(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;

        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $whereProdiSelectedAKM = "";
        $whereProdiSelectedLM = "";
        $whereProdiSelectedKR = "";


        if ($request->akses != "" && $request->akses != "All Data") {
            $whereProdiSelectedAKM = "AND akm.id_prodi = '{$request->akses}'";
            $whereProdiSelectedLM = "AND lm.id_prodi = '{$request->akses}'";
            $whereProdiSelectedKR = "AND kr.id_prodi = '{$request->akses}'";
        } else if ($hak_akses_selected != 'All Data') {
            $whereProdiSelectedAKM = "AND akm.id_prodi = '{$hak_akses_selected}'";
            $whereProdiSelectedLM = "AND lm.id_prodi = '{$hak_akses_selected}'";
            $whereProdiSelectedKR = "AND kr.id_prodi = '{$hak_akses_selected}'";
        }

        $TotalMahasiswaAktif = DB::select("SELECT 
                COUNT(lm.id) AS Total
            FROM 
                tbgetlistmahasiswa lm 
            JOIN 
                tbgetaktivitaskuliahmahasiswa akm
            ON
                lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            WHERE 
                akm.id_semester = ?
            AND
                akm.nama_status_mahasiswa = 'AKTIF'
            {$whereProdiSelectedAKM}", [$semester_selected]);

        $TotalMahasiswaKRS = DB::select("SELECT 
            COUNT(*) AS Total
        FROM (
            SELECT 
            COUNT(kr.id) AS JumlahKRS
            FROM 
            tbgetkrsmahasiswa kr
            JOIN 
            tbgetlistmahasiswa lm 
            ON 
                kr.id_registrasi_mahasiswa = lm.id_registrasi_mahasiswa
            JOIN
                tbgetaktivitaskuliahmahasiswa akm 
            ON 
                kr.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            AND
            akm.nama_status_mahasiswa = 'AKTIF'
            WHERE 
            akm.id_semester = ? {$whereProdiSelectedAKM}
            GROUP BY 
            kr.id_registrasi_mahasiswa
        ) AS subquery;", [$semester_selected]);

        $TotalMahasiswaBelumKRS = DB::select("SELECT 
            COUNT(*) AS Total
        FROM 
            tbgetlistmahasiswa lm
        JOIN
            tbgetaktivitaskuliahmahasiswa akm 
        ON 
            lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
        WHERE 
            akm.nama_status_mahasiswa = 'AKTIF'
            AND akm.id_registrasi_mahasiswa NOT IN (
                SELECT 
                    kr.id_registrasi_mahasiswa
                FROM 
                    tbgetkrsmahasiswa kr
                WHERE 
                    kr.id_periode = ?
            ) 
            AND akm.id_semester = ? {$whereProdiSelectedAKM};
        ", [$semester_selected, $semester_selected]);

        $TotalMahsiswaSKS = DB::select("SELECT 
            SUM(JumlahSKS) AS Total
        FROM (
            SELECT 
            SUM(CAST(sks_mata_kuliah AS FLOAT)) AS JumlahSKS
            FROM 
            tbgetkrsmahasiswa kr
            JOIN 
            tbgetlistmahasiswa lm 
            ON 
                kr.id_registrasi_mahasiswa = lm.id_registrasi_mahasiswa
            JOIN
                tbgetaktivitaskuliahmahasiswa akm 
            ON 
                kr.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            AND
                akm.nama_status_mahasiswa = 'AKTIF'
            WHERE 
                akm.id_semester = ? {$whereProdiSelectedAKM}
            GROUP BY 
                kr.id_registrasi_mahasiswa
        ) AS subquery;", [$semester_selected]);

        $TotalMahsiswaSKSMIN = DB::select("SELECT 
            MIN(JumlahSKS) AS Total
        FROM (
            SELECT 
            SUM(CAST(sks_mata_kuliah AS FLOAT)) AS JumlahSKS
            FROM 
            tbgetkrsmahasiswa kr
            JOIN 
            tbgetlistmahasiswa lm 
            ON 
                kr.id_registrasi_mahasiswa = lm.id_registrasi_mahasiswa
            JOIN
                tbgetaktivitaskuliahmahasiswa akm 
            ON 
                kr.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            AND
                akm.nama_status_mahasiswa = 'AKTIF'
            WHERE 
                akm.id_semester = ? {$whereProdiSelectedAKM}
            GROUP BY 
            kr.id_registrasi_mahasiswa
        ) AS subquery;", [$semester_selected]);

        $TotalMahsiswaSKSMAX = DB::select("SELECT 
            MAX(JumlahSKS) AS Total
        FROM (
            SELECT 
            SUM(CAST(sks_mata_kuliah AS FLOAT)) AS JumlahSKS
            FROM 
            tbgetkrsmahasiswa kr
            JOIN 
            tbgetlistmahasiswa lm 
            ON 
                kr.id_registrasi_mahasiswa = lm.id_registrasi_mahasiswa
            JOIN
                tbgetaktivitaskuliahmahasiswa akm 
            ON 
                kr.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
            AND
                akm.nama_status_mahasiswa = 'AKTIF'
            WHERE 
                akm.id_semester = ? {$whereProdiSelectedAKM}
            GROUP BY 
            kr.id_registrasi_mahasiswa
        ) AS subquery;", [$semester_selected]);

        $ListMahasiswaAktif = DB::select("SELECT  
            lm.nama_mahasiswa AS NamaMahasiswa, 
            lm.nim AS NIM, 
            lm.jenis_kelamin AS JenisKelamin, 
            lm.nama_agama AS Agama, 
            lm.nama_program_studi AS NamaProdi 
        FROM 
            tbgetlistmahasiswa lm
        JOIN
            tbgetaktivitaskuliahmahasiswa akm
        ON
            lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
        WHERE
            akm.nama_status_mahasiswa = 'AKTIF'
        AND
            akm.id_semester = ? 
        {$whereProdiSelectedLM}", [$semester_selected]);

        $ListMahasiswaAktifSudahKRS = DB::select("SELECT  
            lm.nama_mahasiswa AS NamaMahasiswa, 
            lm.nim AS NIM, 
            lm.jenis_kelamin AS JenisKelamin, 
            lm.nama_agama AS Agama, 
            lm.nama_program_studi AS NamaProdi,
            COALESCE(SUM(kr.sks_mata_kuliah), 0) AS TotalSKS
        FROM 
            tbgetlistmahasiswa lm
        JOIN
            tbgetaktivitaskuliahmahasiswa akm 
        ON 
            lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
        LEFT JOIN 
            tbgetkrsmahasiswa kr 
        ON 
            lm.id_registrasi_mahasiswa = kr.id_registrasi_mahasiswa
        AND 
            kr.id_periode = ?
        WHERE
            akm.nama_status_mahasiswa = 'AKTIF'
        AND
            akm.id_semester = ?
        {$whereProdiSelectedAKM}
        AND 
            lm.id_registrasi_mahasiswa IN (
                SELECT 
                    kr.id_registrasi_mahasiswa
                FROM 
                    tbgetkrsmahasiswa kr
                WHERE 
                    kr.id_periode = ?
            )
        GROUP BY
            lm.nama_mahasiswa, 
            lm.nim, 
            lm.jenis_kelamin, 
            lm.nama_agama, 
            lm.nama_program_studi;

        ", [$semester_selected, $semester_selected, $semester_selected]);

        $ListMahasiswaAktifBelumKRS = DB::select("SELECT  
            lm.nama_mahasiswa AS NamaMahasiswa, 
            lm.nim AS NIM, 
            lm.jenis_kelamin AS JenisKelamin, 
            lm.nama_agama AS Agama, 
            lm.nama_program_studi AS NamaProdi 
        FROM 
            tbgetlistmahasiswa lm
        JOIN
            tbgetaktivitaskuliahmahasiswa akm 
        ON 
            lm.id_registrasi_mahasiswa = akm.id_registrasi_mahasiswa
        WHERE
            akm.nama_status_mahasiswa = 'AKTIF'
        AND
            akm.id_semester = ? 
        {$whereProdiSelectedAKM}
        AND 
            lm.id_registrasi_mahasiswa NOT IN (
                SELECT 
                    kr.id_registrasi_mahasiswa
                FROM 
                    tbgetkrsmahasiswa kr
                WHERE 
                    kr.id_periode = ?
            ) ", [$semester_selected, $semester_selected]);

        return view('pages/krs', [
            'User' => $user,
            'pages_active' => 'krs',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'TotalMahasiswaAktif' => $TotalMahasiswaAktif,
            'TotalMahasiswaKRS' => $TotalMahasiswaKRS,
            'TotalMahasiswaBelumKRS' => $TotalMahasiswaBelumKRS,
            'TotalMahsiswaSKS' => $TotalMahsiswaSKS,
            'TotalMahsiswaSKSMIN' => $TotalMahsiswaSKSMIN,
            'TotalMahsiswaSKSMAX' => $TotalMahsiswaSKSMAX,
            'ListMahasiswaAktif' => $ListMahasiswaAktif,
            'ListMahasiswaAktifSudahKRS' => $ListMahasiswaAktifSudahKRS,
            'ListMahasiswaAktifBelumKRS' => $ListMahasiswaAktifBelumKRS,
        ]);
    }

    public function Visualisasi_AktivitasMahasiswa(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;
        $ListJenisAktivitasMahasiswa = DB::select('SELECT * FROM tbgetjenisaktivitasmahasiswa ORDER BY id_jenis_aktivitas_mahasiswa ASC');

        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $queryTotalAktivitasMahasiswa = [];
        $queryListAktivitasMahasiswa = [];

        $whereProdiSelected = "";

        if ($request->akses != "" && $request->akses != "All Data") {
            $whereProdiSelected = "AND id_prodi = '{$request->akses}'";
        } else if ($hak_akses_selected != 'All Data') {
            $whereProdiSelected = "AND id_prodi = '{$hak_akses_selected}'";
        }

        foreach ($ListJenisAktivitasMahasiswa as $jenis) {
            $queryTotalAktivitasMahasiswa[$jenis->id_jenis_aktivitas_mahasiswa] = "SELECT  nama_jenis_aktivitas AS Jenis, 
            COUNT(id) AS Total 
        FROM tbgetlistaktivitasmahasiswa 
        WHERE id_jenis_aktivitas = '{$jenis->id_jenis_aktivitas_mahasiswa}' {$whereProdiSelected} AND id_semester = {$semester_selected}
        GROUP BY nama_jenis_aktivitas";

            $queryListAktivitasMahasiswa[$jenis->id_jenis_aktivitas_mahasiswa] = DB::select(
                "SELECT judul AS Judul, 
                nama_jenis_anggota AS JenisAnggota, 
                keterangan AS Keterangan, 
                nama_prodi AS NamaProdi, 
                lokasi AS Lokasi, 
                nama_jenis_aktivitas AS JenisAktivitas, 
                sk_tugas AS SKTugas, 
                untuk_kampus_merdeka AS isKampusMerdeka
            FROM tbgetlistaktivitasmahasiswa
            WHERE id_semester = ? {$whereProdiSelected} AND id_jenis_aktivitas = ? ORDER BY judul ASC",
                [$semester_selected, $jenis->id_jenis_aktivitas_mahasiswa]
            );
        }

        $ListAktivitasMahasiswa = [];

        foreach ($ListJenisAktivitasMahasiswa as $jenis) {
            $resultTotal = DB::select($queryTotalAktivitasMahasiswa[$jenis->id_jenis_aktivitas_mahasiswa]);
            $resultAktivitas = $queryListAktivitasMahasiswa[$jenis->id_jenis_aktivitas_mahasiswa];

            $ListAktivitasMahasiswa[] = (object) [
                'Jenis' => $jenis->nama_jenis_aktivitas_mahasiswa,
                'Total' => empty($resultTotal) ? 0 : $resultTotal[0]->Total,
                'ListAktivitas' => $resultAktivitas
            ];
        }

        //var_dump($ListAktivitasMahasiswa) . die();

        return view('pages/aktivitas-mahasiswa', [
            'User' => $user,
            'pages_active' => 'aktivitas-mahasiswa',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'ListJenisAktivitasMahasiswa' => $ListJenisAktivitasMahasiswa,
            'ListAktivitasMahasiswa' => $ListAktivitasMahasiswa,
        ]);
    }


    public function Visualisasi_BebanDosen(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;
        
        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        } else {
            if ($request->akses == "") {
                $hak_akses_selected = DB::table('tbusers as us')
                ->leftJoin('tbgetprodi as pr', 'us.hak_akses', '=', 'pr.nama_program_studi')
                ->where('us.id', $user->id)
                ->value('pr.id_prodi'); 
            } else {
                $hak_akses_selected = $request->akses;
            }
        }

        $whereProdi = "";

        if ($hak_akses_selected != "" && $hak_akses_selected != "All Data") {
            if ($user->hak_akses == "Admin" || $user->hak_akses == "Rektor") {
                $whereProdi = "";
            } else {
                $whereProdi = "WHERE id_prodi = '{$hak_akses_selected}'";
            }
        }

        $ListProdi = DB::select("SELECT * FROM tbgetprodi {$whereProdi} ORDER BY nama_program_studi ASC");
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        $whereProdiSelected = "";
        $whereProdiSelectedDPK = "";
        $whereProdiSelectedDPK2 = "";

        if ($request->akses != "" && $request->akses != "All Data") {
            $whereProdiSelected = "AND id_prodi = '{$request->akses}'";
            $whereProdiSelectedDPK = "AND dpk.id_prodi = '{$request->akses}'";
            $whereProdiSelectedDPK2 = "AND dpk2.id_prodi = '{$request->akses}'";
        } else if ($hak_akses_selected != 'All Data') {
            $whereProdiSelected = "AND id_prodi = '{$hak_akses_selected}'";
            $whereProdiSelectedDPK = "AND dpk.id_prodi = '{$hak_akses_selected}'";
            $whereProdiSelectedDPK2 = "AND dpk2.id_prodi = '{$hak_akses_selected}'";
        }

        $TotalDosenMengajar = DB::select("SELECT COUNT(ld.id) AS Total FROM tbgetlistdosen ld WHERE ld.id_dosen IN (SELECT dpk.id_dosen FROM tbgetdosenpengajarkelaskuliah dpk WHERE id_semester = ? {$whereProdiSelected} GROUP BY dpk.id_dosen)", [$semester_selected]);

        $TotalDosenTidakMengajar = DB::select("SELECT COUNT(ld.id) AS Total FROM tbgetlistdosen ld WHERE ld.id_dosen NOT IN (SELECT dpk.id_dosen FROM tbgetdosenpengajarkelaskuliah dpk WHERE id_semester = ? {$whereProdiSelected} GROUP BY dpk.id_dosen)", [$semester_selected]);

        $TotalDosenMengajar = $TotalDosenMengajar[0]->Total ?? 0;
        $TotalDosenTidakMengajar = $TotalDosenTidakMengajar[0]->Total ?? 0;

        $TotalDosen = $TotalDosenMengajar + $TotalDosenTidakMengajar;

        $ListDosen = DB::select("SELECT 
            ld.id,  
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif,
            COALESCE(SUM(CAST(dpk.sks_substansi_total AS FLOAT)), 0) AS TotalSKS
        FROM 
            tbgetlistdosen ld 
        LEFT OUTER JOIN 
            tbgetdosenpengajarkelaskuliah dpk 
        ON 
            ld.id_dosen = dpk.id_dosen
        AND 
            dpk.id_semester = ? {$whereProdiSelectedDPK} 
        GROUP BY 
            ld.id, 
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif
        ORDER BY
            ld.id 
        ASC;", [$semester_selected]);

        $ListDosenMengajar = DB::select("SELECT 
            ld.id,  
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif,
            COALESCE(SUM(CAST(dpk.sks_substansi_total AS FLOAT)), 0) AS TotalSKS
        FROM 
            tbgetlistdosen ld 
        LEFT OUTER JOIN 
            tbgetdosenpengajarkelaskuliah dpk 
        ON 
            ld.id_dosen = dpk.id_dosen
        AND 
            dpk.id_semester = ? {$whereProdiSelectedDPK} 
        WHERE
            ld.id_dosen IN (SELECT dpk2.id_dosen FROM tbgetdosenpengajarkelaskuliah dpk2 WHERE dpk2.id_semester = ? {$whereProdiSelectedDPK2} GROUP BY dpk2.id_dosen) 
        GROUP BY 
            ld.id, 
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif
        ORDER BY
            ld.id 
        ASC;", [$semester_selected, $semester_selected]);

        $ListDosenTidakMengajar = DB::select("SELECT 
            ld.id,  
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif
        FROM 
            tbgetlistdosen ld 
        LEFT OUTER JOIN 
            tbgetdosenpengajarkelaskuliah dpk 
        ON 
            ld.id_dosen = dpk.id_dosen
        AND 
            dpk.id_semester = ? {$whereProdiSelectedDPK} 
        WHERE
            ld.id_dosen NOT IN (SELECT dpk2.id_dosen FROM tbgetdosenpengajarkelaskuliah dpk2 WHERE dpk2.id_semester = ? {$whereProdiSelectedDPK2} GROUP BY dpk2.id_dosen) 
        GROUP BY 
            ld.id, 
            ld.nama_dosen,
            ld.nidn,
            ld.jenis_kelamin,
            ld.nama_agama,
            ld.nama_status_aktif
        ORDER BY
            ld.id 
        ASC;", [$semester_selected, $semester_selected]);

        return view('pages/beban-dosen', [
            'User' => $user,
            'pages_active' => 'beban-dosen',
            'HakAkses' => $user->hak_akses,
            'SelectedAkses' => $hak_akses_selected,
            'SelectedSemester' => $semester_selected,
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'TotalDosenMengajar' => $TotalDosenMengajar,
            'TotalDosenTidakMengajar' => $TotalDosenTidakMengajar,
            'TotalDosen' => $TotalDosen,
            'ListDosen' => $ListDosen,
            'ListDosenMengajar' => $ListDosenMengajar,
            'ListDosenTidakMengajar' => $ListDosenTidakMengajar,
        ]);
    }
}
