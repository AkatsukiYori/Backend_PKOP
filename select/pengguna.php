<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$query = "SELECT * FROM pengguna WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        echo json_encode(['status' => true, 'data' => mysqli_fetch_assoc($result)]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
