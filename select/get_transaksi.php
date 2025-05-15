<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$query = "SELECT * FROM transaksi WHERE pengguna_id = ? ORDER BY tanggal DESC";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(['status' => true, 'data' => $data]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
