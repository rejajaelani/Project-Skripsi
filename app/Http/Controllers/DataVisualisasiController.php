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

    public function Visualisasi_KelasPerkuliahan(Request $request)
    {
        $user = $this->getUserActive();
        $hak_akses_selected = $request->akses ?? $user->hak_akses;
        $semester_selected = $request->semester ?? 20231;
        $ListProdi = DB::select('SELECT * FROM tbgetprodi ORDER BY nama_program_studi ASC');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');
    
        if ($hak_akses_selected == 'Admin' || $hak_akses_selected == 'Rektor') {
            $hak_akses_selected = 'All Data';
        }
    
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
                WHERE id_semester = ? AND id_prodi = ? ORDER BY nama_program_studi ASC", [$semester_selected, $prodi->id_prodi]
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
            'IsFillter' => $request->akses == '' || $request->akses == 'All Data' ? false : true,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'TotalKelasPerkuliahan' => $TotalKelasPerkuliahan,
            'TotalKelasPerkuliahanNULL' => $TotalKelasPerkuliahanNULL,
            'ListKelasPerkuliahan' => $ListKelasPerkuliahan,
            'ListKelasPerkuliahanNULL' => $ListKelasPerkuliahanNULL,
            'ListTotalKelasPerkuliahanPerProdi' => $ListTotalKelasPerkuliahanPerProdi,
        ]);
    }
    
}
