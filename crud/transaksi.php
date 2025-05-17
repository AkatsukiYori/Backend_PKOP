<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));
$stat = [];

if (empty($data->jenis_transaksi)) {
    echo json_encode(["status" => false, "message" => "Jenis transaksi tidak boleh kosong"]);
    exit;
} else if (empty($data->kategori)) {
    echo json_encode(["status" => false, "message" => "Kategori tidak boleh kosong"]);
    exit;
} else if (empty($data->judul_transaksi)) {
    echo json_encode(["status" => false, "message" => "Judul transaksi tidak boleh kosong"]);
    exit;
} else if (empty($data->tanggal)) {
    echo json_encode(["status" => false, "message" => "Tanggal tidak boleh kosong"]);
    exit;
} else if (empty($data->nominal)) {
    echo json_encode(["status" => false, "message" => "Nominal tidak boleh kosong"]);
    exit;
} else {
    if ($data->jenis_transaksi == 'pemasukan') {
        $query = 'UPDATE pengguna SET total_pemasukan = ? WHERE id = ?';
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'di', $data->pemasukan, $data->pengguna_id);
            mysqli_stmt_execute($stmt);
        }
    } else {
        $query = 'UPDATE pengguna SET total_pengeluaran = ? WHERE id = ?';
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'di', $data->pengeluaran, $data->pengguna_id);
            mysqli_stmt_execute($stmt);
        }
    }

    $query = "INSERT INTO transaksi (pengguna_id, jenis_transaksi, kategori, judul_transaksi, keterangan, tanggal, nominal) VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        $pengguna_id = $data->pengguna_id;
        $jenis_transaksi = $data->jenis_transaksi;
        $kategori = $data->kategori;
        $judul_transaksi = $data->judul_transaksi;
        $keterangan = $data->keterangan ?? NULL;
        $tanggal = $data->tanggal;
        $nominal = $data->nominal;

        mysqli_stmt_bind_param($stmt, 'isssssd', $pengguna_id, $jenis_transaksi, $kategori, $judul_transaksi, $keterangan, $tanggal, $nominal);
        mysqli_stmt_execute($stmt);
    }
}

echo json_encode([
    'status' => true,
]);
