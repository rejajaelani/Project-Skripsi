<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataVisualisasiController extends Controller
{
    public function Visualisasi_Dashboard()
    {
        $RekapJumlahMahasiswaPerProdi = DB::select('SELECT nama_program_studi AS Nama_Program_Studi, SUM(aktif) AS Mahasiswa_Aktif, SUM(cuti) AS Mahasiswa_Cuti, SUM(non_aktif) AS Mahasiswa_NonAktif, (SUM(aktif)+SUM(cuti)+SUM(non_aktif)) AS Total FROM tbgetrekapjumlahmahasiswa GROUP BY id_prodi, nama_program_studi ORDER BY nama_program_studi ASC');

        $RekapJumlahMahasiswaTotal = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_Aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa GROUP BY SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) ORDER BY Tahun ASC');

        // Menginisialisasi objek untuk menyimpan data gabungan
        $GabunganDataRekapJumlahMahasiswaTotal = (object) [
            'Total_Mahasiswa_Aktif' => 0,
            'Total_Mahasiswa_Cuti' => 0,
            'Total_Mahasiswa_NonAktif' => 0,
            'TotalMahasiswa' => 0
        ];

        // Menjalankan kueri untuk mendapatkan data
        $RekapJumlahMahasiswaTotal = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_Aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa GROUP BY SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) ORDER BY Tahun ASC');

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

        $RekapJumlahMahasiswaTotalPerTahun = DB::select('SELECT SUBSTRING(id_periode, 1, LENGTH(id_periode) - 1) AS Tahun, SUM(aktif) AS Total_Mahasiswa_aktif, SUM(cuti) AS Total_Mahasiswa_Cuti, SUM(non_aktif) AS Total_Mahasiswa_NonAktif, (SUM(aktif) + SUM(cuti) + SUM(non_aktif)) AS TotalMahasiswa FROM tbgetrekapjumlahmahasiswa GROUP BY Tahun ORDER BY Tahun ASC');

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
            'JumMhsPerProdi' => $RekapJumlahMahasiswaPerProdi,
            'JumMhsTotal' => $GabunganDataRekapJumlahMahasiswaTotal,
            'JumMhsTotalPerTahun' => $RekapJumlahMahasiswaTotalPerTahun,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
            'RekapPerProdi' => $RekapPerProdi
        ]);
    }

    public function Visualisasi_User() {

        $ListUser = DB::select('SELECT * FROM tbusers');
        $ListProdi = DB::select('SELECT * FROM tbgetprodi');
        $ListFakultas = DB::select('SELECT * FROM tbgetfakultas');

        return view('user', [
            'pages_active' => 'user',
            'isActiveMenu' => false,
            'ListUser' => $ListUser,
            'ListProdi' => $ListProdi,
            'ListFakultas' => $ListFakultas,
        ]);
    }
}
