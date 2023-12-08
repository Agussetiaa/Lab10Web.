# Praktikum 10: PHP OOP

## Instruksi Praktikum
1. Persiapkan text editor misalnya VSCode.
2. Buat folder baru dengan nama lab10_php_oop pada docroot webserver (htdocs)
3. Ikuti langkah-langkah praktikum yang akan dijelaskan berikutnya.

## Langkah-langkah Praktikum
Buat file baru dengan nama mobil.php
```
<?php
/**
* Program sederhana pendefinisian class dan pemanggilan class.
**/
class Mobil
{
    private $warna;
    private $merk;
    private $harga;
    public function __construct()
    {
        $this->warna = "Biru";
        $this->merk = "BMW";
        $this->harga = "10000000";
    }
    public function gantiWarna ($warnaBaru)
    {
        $this->warna = $warnaBaru;
    }
    public function tampilWarna ()
    {
        echo "Warna mobilnya : " . $this->warna;
    }
}
// membuat objek mobil
$a = new Mobil();
$b = new Mobil();
// memanggil objek
echo "<b>Mobil pertama</b><br>";
$a->tampilWarna();
echo "<br>Mobil pertama ganti warna<br>";
$a->gantiWarna("Merah");

$a->tampilWarna();
// memanggil objek
echo "<br><b>Mobil kedua</b><br>";
$b->gantiWarna("Hijau");
$b->tampilWarna();
?>
```

<img width="475" alt="image" src="https://github.com/Agussetiaa/Lab10Web./assets/115542822/c4cd1983-8bc0-4566-b19b-ce21ac515407">


## Class Library
Class library merupakan pustaka kode program yang dapat digunakan bersama pada beberapa
file yang berbeda (konsep modularisasi). Class library menyimpan fungsi-fungsi atau class
object komponen untuk memudahkan dalam proses development aplikasi.

## Contoh class library untuk membuat form.
Buat file baru dengan nama form.php
```
<?php
class Form {
    private $fields = array();
    private $action;
    private $submit = "Submit Form";
    private $jumField = 0;

    public function __construct($action, $submit) {
        $this->action = $action;
        $this->submit = $submit;
    }

    public function displayForm() {
        echo "<form action='".$this->action."' method='POST'>";
        echo '<table width="100%" border="0">';
        for ($j=0; $j<count($this->fields); $j++) {
            echo "<tr><td align='right'>".$this->fields[$j]['label']."</td>";
            echo "<td><input type='text' name='".$this->fields[$j]['name']."'></td></tr>";
        }
        echo "<tr><td colspan='2'>";
        echo "<input type='submit' value='".$this->submit."'></td></tr>";
        echo "</table>";
        echo "</form>";
    }

    public function addField($name, $label) {
        $this->fields [$this->jumField]['name'] = $name;
        $this->fields [$this->jumField]['label'] = $label;
        $this->jumField++;
    }
}
?>

```



File tersebut tidak dapat dieksekusi langsung, karena hanya berisi deklarasi class. Untuk
menggunakannya perlu dilakukan include pada file lain yang akan menjalankan dan harus
dibuat instance object terlebih dulu.

## Contoh implementasi pemanggilan class library form.php
Buat file baru dengan nama form_input.php
```
<?php
include "form.php";
include "Database.php";

echo "<html><head><title>Mahasiswa</title></head><body>";

$form = new Form("process.php", "Submit");

$form->addField("nim", "Nim");
$form->addField("nama", "Nama");
$form->addField("alamat", "Alamat");

echo "<h3>Silakan isi formulir berikut ini:</h3>";
$form->displayForm();

echo "</body></html>";
?>

```

<img width="541" alt="image" src="https://github.com/Agussetiaa/Lab10Web./assets/115542822/af7cd44b-042a-46d5-87d5-71726450deff">

## Buat file baru dengan nama config.php
```
<?php
return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db_name' => 'lab10_database',
];
?>
```

## Buat file baru dengan nama procces.php
```
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
```

<img width="338" alt="image" src="https://github.com/Agussetiaa/Lab10Web./assets/115542822/a8860e4b-187f-4273-8c3b-7c89d509c328">


## Contoh lainnya untuk database connection dan query. Buat file dengan nama database.php
```
<?php
class Database {
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct() {
        $this->getConfig();
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    private function getConfig() {
        $config = include("config.php");

        $this->host = $config['host'];
        $this->user = $config['username'];
        $this->password = $config['password'];
        $this->db_name = $config['db_name'];
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function get($table, $where = null) {
        if ($where) {
            $where = " WHERE " . $where;
        }
        $sql = "SELECT * FROM " . $table . $where;
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        return $data;
    }

    public function insert($table, $data) {
        if (is_array($data)) {
            $columns = [];
            $values = [];
            foreach ($data as $key => $val) {
                $columns[] = $key;
                $values[] = "'{$val}'";
            }
            $columns = implode(",", $columns);
            $values = implode(",", $values);

            $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
            $result = $this->conn->query($sql);

            return $result === true;
        }
        return false;
    }

    public function update($table, $data, $where) {
        if (is_array($data)) {
            $update_value = [];
            foreach ($data as $key => $val) {
                $update_value[] = "$key='{$val}'";
            }

            $update_value = implode(",", $update_value);

            $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where;
            $result = $this->conn->query($sql);

            return $result === true;
        }
        return false;
    }

    public function delete($table, $filter) {
        $sql = "DELETE FROM " . $table . " " . $filter;
        $result = $this->conn->query($sql);

        return $result === true;
    }
}
?>

```

## Pertanyaan dan Tugas
Implementasikan konsep modularisasi pada kode program pada praktukum sebelumnya
dengan menggunakan class library untuk form dan database connection.


