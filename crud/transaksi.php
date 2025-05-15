<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->jenis_transaksi)) {
    echo json_encode(["status" => false, "message" => "Jenis transaksi tidak boleh kosong"]);
    exit;
}

if (!isset($data->kategori)) {
    echo json_encode(["status" => false, "message" => "Kategori tidak boleh kosong"]);
    exit;
}

if (!isset($data->judul_transaksi)) {
    echo json_encode(["status" => false, "message" => "Judul transaksi tidak boleh kosong"]);
    exit;
}

if (!isset($data->tanggal)) {
    echo json_encode(["status" => false, "message" => "Tanggal tidak boleh kosong"]);
    exit;
}

if (!isset($data->nominal)) {
    echo json_encode(["status" => false, "message" => "Nominal tidak boleh kosong"]);
    exit;
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

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => true, 'message' => 'Transaksi berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Transaksi gagal']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
