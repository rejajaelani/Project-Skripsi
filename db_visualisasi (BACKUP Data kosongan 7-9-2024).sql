-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2024 at 03:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_visualisasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'personal-token', 'f4b7968e110f5496c63130884b6bcc4781d191a45dacdf10f6b8ffb9f2d79dd5', '[\"*\"]', NULL, NULL, '2024-05-03 23:20:23', '2024-05-03 23:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbgetaktivitaskuliahmahasiswa`
--

CREATE TABLE `tbgetaktivitaskuliahmahasiswa` (
  `id` int(11) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_semester` int(11) DEFAULT NULL,
  `nama_semester` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `angkatan` int(11) DEFAULT NULL,
  `id_prodi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_program_studi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_status_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_status_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ips` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ipk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sks_semester` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sks_total` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `biaya_kuliah_smt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_sync` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetaktivitasmengajardosen`
--

CREATE TABLE `tbgetaktivitasmengajardosen` (
  `id` int(11) NOT NULL,
  `id_registrasi_dosen` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(200) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `id_periode` varchar(255) DEFAULT NULL,
  `nama_periode` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_kelas` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `rencana_minggu_pertemuan` int(11) DEFAULT NULL,
  `realisasi_minggu_pertemuan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetdosenpengajarkelaskuliah`
--

CREATE TABLE `tbgetdosenpengajarkelaskuliah` (
  `id` int(11) NOT NULL,
  `id_aktivitas_mengajar` varchar(255) DEFAULT NULL,
  `id_registrasi_dosen` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_substansi` varchar(255) DEFAULT NULL,
  `sks_substansi_total` decimal(5,2) DEFAULT NULL,
  `rencana_minggu_pertemuan` int(11) DEFAULT NULL,
  `realisasi_minggu_pertemuan` int(11) DEFAULT NULL,
  `id_jenis_evaluasi` int(11) DEFAULT NULL,
  `nama_jenis_evaluasi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetfakultas`
--

CREATE TABLE `tbgetfakultas` (
  `id` int(11) NOT NULL,
  `id_fakultas` varchar(255) DEFAULT NULL,
  `nama_fakultas` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `id_jenjang_pendidikan` decimal(2,0) DEFAULT NULL,
  `nama_jenjang_pendidikan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetjenisaktivitasmahasiswa`
--

CREATE TABLE `tbgetjenisaktivitasmahasiswa` (
  `id` int(11) NOT NULL,
  `id_jenis_aktivitas_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_jenis_aktivitas_mahasiswa` varchar(255) DEFAULT NULL,
  `untuk_kampus_merdeka` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetjeniskeluar`
--

CREATE TABLE `tbgetjeniskeluar` (
  `id` int(11) NOT NULL,
  `id_jenis_keluar` int(11) DEFAULT NULL,
  `jenis_keluar` varchar(255) DEFAULT NULL,
  `apa_mahasiswa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetkrsmahasiswa`
--

CREATE TABLE `tbgetkrsmahasiswa` (
  `id` int(11) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_periode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_prodi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_program_studi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_matkul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_mata_kuliah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kelas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sks_mata_kuliah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `angkatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetlistaktivitasmahasiswa`
--

CREATE TABLE `tbgetlistaktivitasmahasiswa` (
  `id` int(11) NOT NULL,
  `asal_data` varchar(255) DEFAULT NULL,
  `nm_asaldata` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `program_mbkm` varchar(255) DEFAULT NULL,
  `nama_program_mbkm` varchar(255) DEFAULT NULL,
  `jenis_anggota` int(11) DEFAULT NULL,
  `nama_jenis_anggota` varchar(255) DEFAULT NULL,
  `id_jenis_aktivitas` int(11) DEFAULT NULL,
  `nama_jenis_aktivitas` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_prodi` varchar(255) DEFAULT NULL,
  `id_semester` int(11) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `judul` varchar(500) DEFAULT NULL,
  `keterangan` varchar(1000) DEFAULT NULL,
  `lokasi` varchar(1000) DEFAULT NULL,
  `sk_tugas` varchar(255) DEFAULT NULL,
  `sumber_data` varchar(255) DEFAULT NULL,
  `tanggal_sk_tugas` varchar(255) DEFAULT NULL,
  `tanggal_mulai` varchar(255) DEFAULT NULL,
  `tanggal_selesai` varchar(255) DEFAULT NULL,
  `untuk_kampus_merdeka` int(11) DEFAULT NULL,
  `status_sync` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetlistdosen`
--

CREATE TABLE `tbgetlistdosen` (
  `id` int(11) NOT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `id_agama` varchar(255) DEFAULT NULL,
  `nama_agama` varchar(255) DEFAULT NULL,
  `tanggal_lahir` varchar(255) DEFAULT NULL,
  `id_status_aktif` varchar(255) DEFAULT NULL,
  `nama_status_aktif` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetlistkelaskuliah`
--

CREATE TABLE `tbgetlistkelaskuliah` (
  `id` int(11) NOT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(5) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(5) DEFAULT NULL,
  `sks` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `jumlah_mahasiswa` varchar(255) DEFAULT NULL,
  `apa_untuk_pditt` decimal(1,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetlistmahasiswa`
--

CREATE TABLE `tbgetlistmahasiswa` (
  `id` int(11) NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(1) DEFAULT NULL,
  `tanggal_lahir` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `nipd` varchar(100) DEFAULT NULL,
  `ipk` decimal(10,0) DEFAULT NULL,
  `total_sks` int(11) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `id_agama` decimal(16,0) DEFAULT NULL,
  `nama_agama` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_status_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_status_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `id_periode` varchar(255) DEFAULT NULL,
  `nama_periode_masuk` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `id_periode_keluar` varchar(100) DEFAULT NULL,
  `tanggal_keluar` varchar(255) DEFAULT NULL,
  `last_update` varchar(255) DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `status_sync` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetlistmahasiswalulusdo`
--

CREATE TABLE `tbgetlistmahasiswalulusdo` (
  `id` int(11) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `tgl_masuk_sp` varchar(255) DEFAULT NULL,
  `tgl_keluar` varchar(255) DEFAULT NULL,
  `skhun` varchar(255) DEFAULT NULL,
  `no_peserta_ujian` varchar(255) DEFAULT NULL,
  `no_seri_ijazah` varchar(255) DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `sks_diakui` int(11) DEFAULT NULL,
  `jalur_skripsi` varchar(255) DEFAULT NULL,
  `judul_skripsi` text DEFAULT NULL,
  `bln_awal_bimbingan` varchar(255) DEFAULT NULL,
  `bln_akhir_bimbingan` varchar(255) DEFAULT NULL,
  `sk_yudisium` varchar(255) DEFAULT NULL,
  `tgl_sk_yudisium` varchar(255) DEFAULT NULL,
  `ipk` float DEFAULT NULL,
  `sert_prof` varchar(255) DEFAULT NULL,
  `a_pindah_mhs_asing` tinyint(1) DEFAULT NULL,
  `id_pt_asal` varchar(36) DEFAULT NULL,
  `id_prodi_asal` varchar(36) DEFAULT NULL,
  `nm_pt_asal` varchar(255) DEFAULT NULL,
  `nm_prodi_asal` varchar(255) DEFAULT NULL,
  `id_jns_daftar` varchar(255) DEFAULT NULL,
  `id_jns_keluar` varchar(255) DEFAULT NULL,
  `id_jalur_masuk` varchar(255) DEFAULT NULL,
  `id_pembiayaan` varchar(255) DEFAULT NULL,
  `id_minat_bidang` varchar(255) DEFAULT NULL,
  `bidang_mayor` varchar(255) DEFAULT NULL,
  `bidang_minor` varchar(255) DEFAULT NULL,
  `biaya_masuk_kuliah` decimal(10,2) DEFAULT NULL,
  `namapt` varchar(255) DEFAULT NULL,
  `id_jur` varchar(255) DEFAULT NULL,
  `nm_jns_daftar` varchar(255) DEFAULT NULL,
  `nm_smt` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `id_jenis_keluar` varchar(255) DEFAULT NULL,
  `nama_jenis_keluar` varchar(255) DEFAULT NULL,
  `tanggal_keluar` varchar(255) DEFAULT NULL,
  `id_periode_keluar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `no_sertifikat_profesi` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetprodi`
--

CREATE TABLE `tbgetprodi` (
  `id` int(11) NOT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `kode_program_studi` varchar(10) DEFAULT NULL,
  `nama_program_studi` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `id_jenjang_pendidikan` decimal(2,0) DEFAULT NULL,
  `nama_jenjang_pendidikan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetrekapjumlahmahasiswa`
--

CREATE TABLE `tbgetrekapjumlahmahasiswa` (
  `id` int(11) NOT NULL,
  `id_periode` int(11) DEFAULT NULL,
  `nama_periode` varchar(100) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(200) DEFAULT NULL,
  `aktif` varchar(255) DEFAULT NULL,
  `cuti` varchar(255) DEFAULT NULL,
  `non_aktif` varchar(255) DEFAULT NULL,
  `sedang_double_degree` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbgetstatusmahasiswa`
--

CREATE TABLE `tbgetstatusmahasiswa` (
  `id` int(11) NOT NULL,
  `id_status_mahasiswa` char(1) DEFAULT NULL,
  `nama_status_mahasiswa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbusers`
--

CREATE TABLE `tbusers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hak_akses` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbusers`
--

INSERT INTO `tbusers` (`id`, `username`, `password`, `nama_lengkap`, `email`, `hak_akses`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$GiMWKvJXuVmt6aHpG0YjZe7tvPfTUIOw48S9ePdMhtw16HoNji9rm', 'Admin', 'admin@example.com', 'Admin', NULL, '2024-05-03 23:20:23', '2024-06-10 05:42:18'),
(2, 'prodi', '$2y$12$bFG2OICpu5ommdzsg10hlu6FEjP7MF5UKAHhP4KANY/srIZFKI9FO', 'Test Prodi', 'prodi@gmail.com', 'Teknik Informatika', NULL, '2024-06-10 06:41:42', '2024-06-30 06:41:35'),
(4, 'fakultas', '$2y$12$WzX02GTLuYTyv0PR/8LEHe.OkhCqfiuIg7Rn4DqsEjIWxBYwbBXC.', 'Test Fakultas', 'fakultas@gmail.com', 'Teknologi dan Informatika', NULL, '2024-06-30 06:41:13', '2024-06-30 06:41:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `tbgetaktivitaskuliahmahasiswa`
--
ALTER TABLE `tbgetaktivitaskuliahmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetaktivitasmengajardosen`
--
ALTER TABLE `tbgetaktivitasmengajardosen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetdosenpengajarkelaskuliah`
--
ALTER TABLE `tbgetdosenpengajarkelaskuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetfakultas`
--
ALTER TABLE `tbgetfakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetjenisaktivitasmahasiswa`
--
ALTER TABLE `tbgetjenisaktivitasmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetjeniskeluar`
--
ALTER TABLE `tbgetjeniskeluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetkrsmahasiswa`
--
ALTER TABLE `tbgetkrsmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetlistaktivitasmahasiswa`
--
ALTER TABLE `tbgetlistaktivitasmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetlistdosen`
--
ALTER TABLE `tbgetlistdosen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetlistkelaskuliah`
--
ALTER TABLE `tbgetlistkelaskuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetlistmahasiswa`
--
ALTER TABLE `tbgetlistmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetlistmahasiswalulusdo`
--
ALTER TABLE `tbgetlistmahasiswalulusdo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetprodi`
--
ALTER TABLE `tbgetprodi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetrekapjumlahmahasiswa`
--
ALTER TABLE `tbgetrekapjumlahmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbgetstatusmahasiswa`
--
ALTER TABLE `tbgetstatusmahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbusers`
--
ALTER TABLE `tbusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tbusers_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbgetaktivitaskuliahmahasiswa`
--
ALTER TABLE `tbgetaktivitaskuliahmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetaktivitasmengajardosen`
--
ALTER TABLE `tbgetaktivitasmengajardosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetdosenpengajarkelaskuliah`
--
ALTER TABLE `tbgetdosenpengajarkelaskuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetfakultas`
--
ALTER TABLE `tbgetfakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetjenisaktivitasmahasiswa`
--
ALTER TABLE `tbgetjenisaktivitasmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetjeniskeluar`
--
ALTER TABLE `tbgetjeniskeluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetkrsmahasiswa`
--
ALTER TABLE `tbgetkrsmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetlistaktivitasmahasiswa`
--
ALTER TABLE `tbgetlistaktivitasmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetlistdosen`
--
ALTER TABLE `tbgetlistdosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetlistkelaskuliah`
--
ALTER TABLE `tbgetlistkelaskuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetlistmahasiswa`
--
ALTER TABLE `tbgetlistmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetlistmahasiswalulusdo`
--
ALTER TABLE `tbgetlistmahasiswalulusdo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetprodi`
--
ALTER TABLE `tbgetprodi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetrekapjumlahmahasiswa`
--
ALTER TABLE `tbgetrekapjumlahmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbgetstatusmahasiswa`
--
ALTER TABLE `tbgetstatusmahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbusers`
--
ALTER TABLE `tbusers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
