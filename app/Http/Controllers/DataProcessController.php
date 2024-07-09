<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;

class DataProcessController extends Controller
{
    public function runWs($data)
    {
        $url = "https://feeder.instiki.ac.id/ws/live2.php";
        //$url = "https://feeder.instiki.ac.id/ws/sandbox2.php";
        $ch = curl_init();

        if ($data) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Inisialisasi $data_length sebelum digunakan dalam strlen()
        $data_length = isset($data) ? strlen($data) : 0;
        $headers[] = 'Content-Length: ' . $data_length;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING,  '');
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $salah = curl_error($ch);
            $hasil = [
                "error_code" => 1,
                "error_desc" => $salah,
                "data" => $data
            ];
            $result = (object)$hasil;
            curl_close($ch);
            return json_encode($result);
        }

        curl_close($ch);

        return $result;
    }

    public function token()
    {
        $data['act'] = "GetToken";
        $data['username'] = "kmaryagw@gmail.com";
        $data['password'] = "!nst1ki*J4y4";
        $token = json_decode($this->runWs($data));
        //var_dump($token);
        $_SESSION['feeder_token'] = $token->data->token;
        return $token;
    }

    public function generateTable(Request $request)
    {

        $tokenResponse = $this->token();

        // Memeriksa apakah token berhasil diperoleh
        if (isset($tokenResponse->data->token)) {
            $tableArray = [
                "GetProdi", "GetFakultas", "GetListMahasiswa", "GetBiodataMahasiswa", "GetKRSMahasiswa", "GetRekapKRSMahasiswa", "GetRekapKHSMahasiswa", "GetAktivitasKuliahMahasiswa", "GetAktivitasMengajarDosen", "GetRiwayatNilaiMahasiswa", "GetListDosen", "GetListPenugasanDosen", "GetRiwayatPendidikanDosen", "GetMahasiswaBimbinganDosen", "GetRekapJumlahDosen", "GetRekapJumlahMahasiswa", "GetRekapIPSMahasiswa", "GetDataTerhapus", "GetListBimbingMahasiswa", "GetListMataKuliah", "GetDetailMataKuliah", "GetListKurikulum", "GetDetailKurikulum", "GetListKelasKuliah", "GetDetailKelasKuliah", "GetDosenPengajarKelasKuliah", "GetPerhitunganSKS",  "GetListPrestasiMahasiswa", "GetListMahasiswaLulusDO", "GetDetailMahasiswaLulusDO", "GetDosenPembimbing", "GetListPeriodePerkuliahan", "GetDetailPeriodePerkuliahan", "GetDetailPerkuliahanMahasiswa"
            ];

            $index = 1;
            foreach ($tableArray as $key => $fungsi) {
                // Menampilkan loading setiap 10 tabel
                if ($index % 10 === 0) {
                    echo "Creating table $index... Please wait...\n";
                    sleep(2); // Jeda 2 detik
                }

                // Mengambil token dari response
                $feeder_token = $tokenResponse->data->token;

                // Membuat data untuk request
                $sync['act'] = "GetDictionary";
                $sync['fungsi'] = $fungsi;
                $sync['token'] = $feeder_token;

                // Memanggil WS untuk mendapatkan data
                $hasil = $this->runWs($sync);

                // Mendekode hasil JSON ke dalam array
                $array = json_decode($hasil, true);

                // Memeriksa apakah dekode JSON berhasil
                if ($array !== null) {
                    // Menampilkan bagian 'response' dari array
                    $arrayNew = $array['data']['response'];
                    $tableName = "tb" . $sync['fungsi']; // Nama tabel sesuai dengan fungsi
                    try {
                        Schema::create($tableName, function (Blueprint $table) use ($arrayNew) {
                            $primaryColumns = [];

                            foreach ($arrayNew as $key => $value) {
                                // Jika nilai tersebut bukan array, lanjutkan ke iterasi selanjutnya
                                if (!is_array($value)) {
                                    continue;
                                }

                                // Mengambil kunci sebenarnya tanpa data
                                $actualKey = str_replace(['data[', ']'], '', $key);

                                // Mengonversi informasi kolom menjadi string sesuai format yang diinginkan
                                $columnName = $actualKey;

                                // Memisahkan tipe data dan panjang kolom dari value['type']
                                $parts = explode('(', $value['type']);
                                preg_match('/\((.*?)\)/', $value['type'], $matches);
                                $columnType = $parts[0];

                                // Mengubah tipe data 'numeric' menjadi 'integer'
                                if ($columnType === 'numeric' || $columnType === 'smallint' || $columnType === 'double precision') {
                                    $columnType = 'decimal';
                                }

                                // Mengatur tipe data menjadi 'string' jika tipe tidak terdefinisi atau adalah karakter
                                if (empty($columnType) || $columnType === 'uuid' || $columnType === 'character' || $columnType === 'character varying') {
                                    $columnType = 'string';
                                }

                                // Menghilangkan ",0" dari panjang string jika ada
                                $columnLength = isset($matches[1]) ? $matches[1] : null;

                                $nullable = !empty($value['nullable']) && $value['nullable'] == 'not null' ? false : true;

                                if (($columnType === 'string' || $columnType === 'decimal') && $columnLength !== null) {
                                    // Jika tipe kolom adalah 'decimal', ubah format panjang sesuai dengan yang diinginkan
                                    if ($columnType === 'decimal') {
                                        // Pisahkan precision dan scale
                                        $precisionScale = explode(',', $columnLength);
                                        // Ambil precision dan scale yang benar
                                        $precision = (int)$precisionScale[0];
                                        $scale = isset($precisionScale[1]) ? (int)$precisionScale[1] : 0;
                                        $table->$columnType($columnName, $precision, $scale)->nullable($nullable);
                                    } else {
                                        // Jika bukan tipe kolom decimal, lanjutkan seperti biasa
                                        $table->$columnType($columnName, $columnLength)->nullable($nullable);
                                    }
                                } else {
                                    // Atau, jika tidak, atur kolom tanpa panjang
                                    $table->$columnType($columnName)->nullable($nullable);
                                }

                                // Jika 'primary' tidak kosong, maka ditambahkan ke string       
                                // Memeriksa apakah kolom adalah primary key
                                if (!empty($value['primary'])) {
                                    if (count($primaryColumns) == 1) {
                                        $table->primary($columnName);
                                    }
                                }
                            }
                        });
                    } catch (QueryException $e) {
                        // Jika terjadi kesalahan saat membuat tabel
                        $errorMessage = $e->getMessage();
                        return Redirect::route('dashboard')->with('error', 'There was an error when creating the table: ' . $errorMessage);
                    }

                    $index++;
                } else {
                    return Redirect::route('dashboard')->with('error', 'Table failed created');
                }
            }
            return Redirect::route('dashboard')->with('success', 'The table was created successfully');
        } else {
            return Redirect::route('dashboard')->with('error', 'The token is failed');
        }
    }


    // TEST API FUNCTIION
    public function testGet(Request $request)
    {
        $tokenResponse = $this->token();

        // Memeriksa apakah token berhasil diperoleh
        if (isset($tokenResponse->data->token)) {

            // Mengambil token dari response
            $feeder_token = $tokenResponse->data->token;

            // Membuat data untuk request
            $sync['act'] = "GetDictionary";
            $sync['fungsi'] = $request->table;
            $sync['token'] = $feeder_token;

            // Memanggil WS untuk mendapatkan data
            $hasil = $this->runWs($sync);

            // Mendekode hasil JSON ke dalam array
            $array = json_decode($hasil, true);

            // Memeriksa apakah dekode JSON berhasil
            if ($array !== null) {
                // Menampilkan bagian 'response' dari array
                $arrayNew = $array['data']['response'];
                $tableName = "tb" . $sync['fungsi']; // Nama tabel sesuai dengan fungsi
                try {
                    Schema::create($tableName, function (Blueprint $table) use ($arrayNew) {
                        $primaryColumns = [];

                        foreach ($arrayNew as $key => $value) {
                            // Jika nilai tersebut bukan array, lanjutkan ke iterasi selanjutnya
                            if (!is_array($value)) {
                                continue;
                            }

                            // Mengambil kunci sebenarnya tanpa data
                            $actualKey = str_replace(['data[', ']'], '', $key);

                            // Mengonversi informasi kolom menjadi string sesuai format yang diinginkan
                            $columnName = $actualKey;

                            // Memisahkan tipe data dan panjang kolom dari value['type']
                            $parts = explode('(', $value['type']);
                            preg_match('/\((.*?)\)/', $value['type'], $matches);
                            $columnType = $parts[0];

                            // Mengubah tipe data 'numeric' menjadi 'integer'
                            if ($columnType === 'numeric' || $columnType === 'smallint' || $columnType === 'double precision') {
                                $columnType = 'decimal';
                            }

                            // Mengatur tipe data menjadi 'string' jika tipe tidak terdefinisi atau adalah karakter
                            if (empty($columnType) || $columnType === 'uuid' || $columnType === 'character' || $columnType === 'character varying') {
                                $columnType = 'string';
                            }

                            // Menghilangkan ",0" dari panjang string jika ada
                            $columnLength = isset($matches[1]) ? $matches[1] : null;

                            $nullable = !empty($value['nullable']) && $value['nullable'] == 'not null' ? false : true;

                            if (($columnType === 'string' || $columnType === 'decimal') && $columnLength !== null) {
                                // Jika tipe kolom adalah 'decimal', ubah format panjang sesuai dengan yang diinginkan
                                if ($columnType === 'decimal') {
                                    // Pisahkan precision dan scale
                                    $precisionScale = explode(',', $columnLength);
                                    // Ambil precision dan scale yang benar
                                    $precision = (int)$precisionScale[0];
                                    $scale = isset($precisionScale[1]) ? (int)$precisionScale[1] : 0;
                                    $table->$columnType($columnName, $precision, $scale)->nullable($nullable);
                                } else {
                                    // Jika bukan tipe kolom decimal, lanjutkan seperti biasa
                                    $table->$columnType($columnName, $columnLength)->nullable($nullable);
                                }
                            } else {
                                // Atau, jika tidak, atur kolom tanpa panjang
                                $table->$columnType($columnName)->nullable($nullable);
                            }

                            // Jika 'primary' tidak kosong, maka ditambahkan ke string       
                            // Memeriksa apakah kolom adalah primary key
                            if (!empty($value['primary'])) {
                                if (count($primaryColumns) == 1) {
                                    $table->primary($columnName);
                                }
                            }
                        }
                    });
                } catch (QueryException $e) {
                    // Jika terjadi kesalahan saat membuat tabel
                    $errorMessage = $e->getMessage();
                    $response = [
                        "name_table" => $request->table,
                        "status" => $errorMessage
                    ];
                    $response = ['data' => $response];
                    return response()->json(
                        $response,
                        400
                    );
                }
            } else {
                $response = [
                    "name_table" => $request->table,
                    "status" => "Table failed created"
                ];
                $response = ['data' => $response];
                return response()->json($response, 400);
            }

            $response = [
                "name_table" => $request->table,
                "status" => "success"
            ];
            $response = ['data' => $response];
            return response()->json($response, 200);
        } else {
            $response = [
                "name_table" => $request->table,
                "status" => "The token is failed"
            ];
            $response = ['data' => $response];
            return response()->json($response, 400);
        }
    }

    public function synchronizeData_test1()
    {
        // Set waktu maksimum eksekusi
        ini_set('max_execution_time', 300000);

        // Mendapatkan token
        $tokenResponse = $this->token();

        // Memeriksa apakah token berhasil diperoleh
        if (isset($tokenResponse->data->token)) {

            // Mengambil token dari response
            $feeder_token = $tokenResponse->data->token;

            $name_testget = "GetListDosen";
            // Membuat data untuk request
            $sync['act'] = $name_testget;
            $sync['token'] = $feeder_token;
            $sync['filter'] = "";
            $sync['order'] = "";
            $sync['limit'] = "";
            $sync['offset'] = "";

            $tableName = "tb" . $sync['act'];

            // Memanggil WS untuk mendapatkan data
            $hasil = $this->runWs($sync);

            //var_dump($hasil) . die();

            // Mendekode hasil JSON ke dalam array
            $array = json_decode($hasil, true);

            // Memeriksa apakah dekode JSON berhasil
            if ($array !== null && isset($array['error_code']) && $array['error_code'] === 0) {
                // Menyimpan data ke dalam tabel
                if (isset($array['data']) && is_array($array['data'])) {
                    foreach ($array['data'] as $data) {
                        DB::table($tableName)->insert($data);
                    }
                }
            } else {
                return Redirect::route('dashboard')->with('error', 'Failed to synchronize data');
            }
            return Redirect::route('dashboard')->with('status', 'success');
        } else {
            return Redirect::route('dashboard')->with('error', 'The token is failed');
        }
    }

    public function synchronizeData_test1_1()
    {
        try {
            // Set waktu maksimum eksekusi
            ini_set('max_execution_time', 300000);

            // Mendapatkan token
            $tokenResponse = $this->token();

            // Memeriksa apakah token berhasil diperoleh
            if (isset($tokenResponse->data->token)) {
                // Mengambil token dari response
                $feeder_token = $tokenResponse->data->token;

                // Membuat data untuk request
                $sync['act'] = 'GetListDosen';
                $sync['token'] = $feeder_token;
                $sync['filter'] = "";
                $sync['order'] = "";
                $sync['limit'] = 1000; // Atur batasan jumlah data per permintaan
                $sync['offset'] = 0; // Mulai dari offset 0

                $tableName = "tb" . $sync['act'];

                do {
                    // Memanggil WS untuk mendapatkan data
                    $hasil = $this->runWs($sync);

                    // Mendekode hasil JSON ke dalam array
                    $array = json_decode($hasil, true);

                    if (empty($array['data'])) {
                        // Jika tidak ada data, hentikan perulangan dan kembalikan respons
                        $response = [
                            "name_table" => $tableName,
                            "status" => "success"
                        ];
                        return response()->json(['data' => $response], 200);
                    } else {
                        // Menyimpan data ke dalam tabel
                        if (isset($array['data']) && is_array($array['data'])) {
                            foreach ($array['data'] as $data) {
                                DB::table($tableName)->insert($data);
                            }
                        }
                    }

                    // Update offset untuk permintaan berikutnya
                    $sync['offset'] += $sync['limit'];
                } while (!empty($array['data']));
            } else {
                $response = [
                    "name_table" => "error",
                    "status" => "The token is failed"
                ];
                return response()->json(['data' => $response], 400);
            }
        } catch (\Exception $e) {
            // Log eksepsi
            \Log::error('Error in synchronizeData: ' . $e->getMessage());
            // Berikan respons kesalahan yang umum
            $response = [
                "name_table" => "error",
                "status" => "Internal Server Error"
            ];
            return response()->json(['data' => $response], 500);
        }
    }

    public function synchronizeData_test2(Request $request)
    {
        try {
            // Set waktu maksimum eksekusi
            ini_set('max_execution_time', 3000);

            // Mendapatkan token
            $tokenResponse = $this->token();

            // Memeriksa apakah token berhasil diperoleh
            if (isset($tokenResponse->data->token)) {
                // Mengambil token dari response
                $feeder_token = $tokenResponse->data->token;

                // Membuat data untuk request
                $sync['act'] = $request->table;
                $sync['token'] = $feeder_token;
                $sync['filter'] = "";
                $sync['order'] = "";
                $sync['limit'] = 1000; // Atur batasan jumlah data per permintaan
                $sync['offset'] = 0; // Mulai dari offset 0

                $tableName = "tb" . $sync['act'];

                do {
                    // Memanggil WS untuk mendapatkan data
                    $hasil = $this->runWs($sync);

                    // Mendekode hasil JSON ke dalam array
                    $array = json_decode($hasil, true);

                    if (empty($array['data'])) {
                        // Jika tidak ada data, hentikan perulangan dan kembalikan respons
                        $response = [
                            "name_table" => $request->table,
                            "status" => "success"
                        ];
                        return response()->json(['data' => $response], 200);
                    } else {
                        // Menyimpan data ke dalam tabel
                        if (isset($array['data']) && is_array($array['data'])) {
                            foreach ($array['data'] as $data) {
                                DB::table($tableName)->insert($data);
                            }
                        }
                    }

                    // Update offset untuk permintaan berikutnya
                    $sync['offset'] += $sync['limit'];
                } while (!empty($array['data']));
            } else {
                $response = [
                    "name_table" => $request->table,
                    "status" => "The token is failed"
                ];
                return response()->json(['data' => $response], 400);
            }
        } catch (\Exception $e) {
            // Log eksepsi
            \Log::error('Error in synchronizeData: ' . $e->getMessage());
            // Berikan respons kesalahan yang umum
            $response = [
                "name_table" => $request->table,
                "status" => "Internal Server Error"
            ];
            return response()->json(['data' => $response], 500);
        }
    }

    public function synchronizeData_test3(Request $request)
    {
        try {
            // Set waktu maksimum eksekusi
            ini_set('max_execution_time', 3000);

            // Mendapatkan token
            $tokenResponse = $this->token();

            // Memeriksa apakah token berhasil diperoleh
            if (isset($tokenResponse->data->token)) {
                // Mengambil token dari response
                $feeder_token = $tokenResponse->data->token;

                // Membuat data untuk request
                $sync['act'] = $request->table;
                $sync['token'] = $feeder_token;

                $table_check = $request->table;
                $fillter = '';
                $is_data_master = false;

                $id_periode_check = [
                    "GetListMahasiswa", "GetKRSMahasiswa", "GetRekapKRSMahasiswa",
                    "GetRekapKHSMahasiswa", "GetAktivitasMengajarDosen", "GetRiwayatNilaiMahasiswa",
                    "GetMahasiswaBimbinganDosen", "GetRekapIPSMahasiswa", "GetListBimbingMahasiswa",
                    "GetDetailPerkuliahanMahasiswa"
                ];
                $id_semester_check = [
                    "GetAktivitasKuliahMahasiswa", "GetListKurikulum", "GetDosenPengajarKelasKuliah"
                ];
                $id_periode_keluar_check = [
                    "GetListMahasiswaLulusDO", "GetDetailMahasiswaLulusDO"
                ];
                if (in_array($table_check, $id_periode_check)) {
                    $fillter = "id_periode = '20231'"; //jika ada pada array id_periode_check maka di fillter dengan id_periode
                } elseif (in_array($table_check, $id_semester_check)) {
                    $fillter = "id_semester = '20231'"; //jika ada pada array id_semester_check maka di fillter dengan id_semester
                } elseif (in_array($table_check, $id_periode_keluar_check)) {
                    $fillter = "id_periode_keluar = '20231'"; //jika ada pada array id_periode_keluar_check maka di fillter dengan id_periode_keluar
                } else {
                    $fillter = ""; //jika tidak ada pada array maka tidak menggunakan fillter
                    $is_data_master = true; //untuk menentukan ini adalah data master
                }

                $sync['filter'] = $fillter;
                $sync['order'] = "";
                $sync['limit'] = 1; // Atur batasan jumlah data per permintaan
                $sync['offset'] = 0; // Mulai dari offset 0

                $tableName = "tb" . $sync['act'];

                do {
                    // Memanggil WS untuk mendapatkan data
                    $hasil = $this->runWs($sync);

                    // Mendekode hasil JSON ke dalam array
                    $array = json_decode($hasil, true);

                    if (empty($array['data'])) {
                        // Jika tidak ada data, hentikan perulangan dan kembalikan respons
                        $response = [
                            "name_table" => $request->table,
                            "status" => "success",
                        ];
                        return response()->json(['data' => $response], 200);
                    } else {
                        // Cek apakah data master
                        if ($is_data_master == true) {
                            // Menghapus semua data dalam tabel
                            DB::table($tableName)->truncate();
                        }

                        //Menyimpan data ke dalam tabel
                        if (isset($array['data']) && is_array($array['data'])) {
                            foreach ($array['data'] as $data) {
                                DB::table($tableName)->insert($data);
                            }
                        }
                    }

                    // Update offset untuk permintaan berikutnya
                    $sync['offset'] += $sync['limit'];
                } while (!empty($array['data']));
            } else {
                $response = [
                    "name_table" => $request->table,
                    "status" => "The token is failed"
                ];
                return response()->json(['data' => $response], 400);
            }
        } catch (\Exception $e) {
            // Log eksepsi
            \Log::error('Error in synchronizeData: ' . $e->getMessage());
            // Berikan respons kesalahan yang umum
            $response = [
                "name_table" => $request->table,
                "status" => "Internal Server Error"
            ];
            return response()->json(['data' => $response], 500);
        }
    }

    public function synchronizeData(Request $request)
    {
        try {
            // Set waktu maksimum eksekusi
            ini_set('max_execution_time', 3000);

            // Mendapatkan token
            $tokenResponse = $this->token();

            // Memeriksa apakah token berhasil diperoleh
            if (isset($tokenResponse->data->token)) {
                // Mengambil token dari response
                $feeder_token = $tokenResponse->data->token;

                // Membuat data untuk request
                $sync['act'] = $request->table;
                $sync['token'] = $feeder_token;

                $table_check = $request->table;
                $fillter = '';
                $is_data_master = false;

                $id_periode_check = [
                    "GetListMahasiswa", "GetKRSMahasiswa", "GetRekapKRSMahasiswa",
                    "GetRekapKHSMahasiswa", "GetAktivitasMengajarDosen", "GetRiwayatNilaiMahasiswa",
                    "GetMahasiswaBimbinganDosen", "GetRekapIPSMahasiswa", "GetListBimbingMahasiswa",
                    "GetDetailPerkuliahanMahasiswa"
                ];
                $id_semester_check = [
                    "GetAktivitasKuliahMahasiswa", "GetListKurikulum", "GetDosenPengajarKelasKuliah"
                ];
                $id_periode_keluar_check = [
                    "GetListMahasiswaLulusDO", "GetDetailMahasiswaLulusDO"
                ];
                if (in_array($table_check, $id_periode_check)) {
                    $fillter = "id_periode = '20231'"; //jika ada pada array id_periode_check maka di fillter dengan id_periode
                } elseif (in_array($table_check, $id_semester_check)) {
                    $fillter = "id_semester = '20231'"; //jika ada pada array id_semester_check maka di fillter dengan id_semester
                } elseif (in_array($table_check, $id_periode_keluar_check)) {
                    $fillter = "id_periode_keluar = '20231'"; //jika ada pada array id_periode_keluar_check maka di fillter dengan id_periode_keluar
                } else {
                    $fillter = ""; //jika tidak ada pada array maka tidak menggunakan fillter
                    $is_data_master = true; //untuk menentukan ini adalah data master
                }

                $sync['filter'] = $fillter;
                $sync['order'] = "";
                $sync['limit'] = "";
                $sync['offset'] = "";

                $tableName = "tb" . $sync['act'];

                $hasil = $this->runWs($sync);

                // Mendekode hasil JSON ke dalam array
                $array = json_decode($hasil, true);

                if ($is_data_master == true) {
                    // Menghapus semua data dalam tabel
                    DB::table($tableName)->truncate();
                }

                // //untuk menghapus semua data pada tabel tanpa detect is_tabel_master
                // DB::table($tableName)->truncate();

                //Menyimpan data ke dalam tabel
                if (isset($array['data']) && is_array($array['data'])) {
                    foreach ($array['data'] as $data) {
                        DB::table($tableName)->insert($data);
                    }
                }
                $response = [
                    "name_table" => $request->table,
                    "status" => "success",
                ];
                return response()->json(['data' => $response], 200);
            } else {
                $response = [
                    "name_table" => $request->table,
                    "status" => "The token is failed"
                ];
                return response()->json(['data' => $response], 400);
            }
        } catch (\Exception $e) {
            // Log eksepsi
            \Log::error('Error in synchronizeData: ' . $e->getMessage());
            // Berikan respons kesalahan yang umum
            $response = [
                "name_table" => $request->table,
                "status" => "Internal Server Error"
            ];
            return response()->json(['data' => $response], 500);
        }
    }
}
