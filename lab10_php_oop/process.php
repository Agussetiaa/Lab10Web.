<?php
include "Database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = isset($_POST["nim"]) ? $_POST["nim"] : "";
    $nama = isset($_POST["nama"]) ? $_POST["nama"] : "";
    $alamat = isset($_POST["alamat"]) ? $_POST["alamat"] : "";

    // Validasi data
    if (empty($nim) || empty($nama) || empty($alamat)) {
        echo "Semua field harus diisi.";
    } else {
        // Buat objek Database
        $db = new Database();

        // Contoh penggunaan metode insert
        $data = array(
            'nim' => $nim,
            'nama' => $nama,
            'alamat' => $alamat
        );

        $table = 'user_mobil';
        $insert_result = $db->insert($table, $data);

        if ($insert_result) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Gagal menyimpan data.";
        }
    }
} else {
    // Jika halaman diakses langsung, mungkin hendak redirect atau tampilkan pesan kesalahan.
    echo "Akses tidak valid.";
}
?>
