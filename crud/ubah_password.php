<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->oldPassword)) {
    echo json_encode(['status' => false, 'message' => 'Password lama tidak boleh kosong']);
    exit;
}

if (!isset($data->newPassword)) {
    echo json_encode(['status' => false, 'message' => 'Password baru tidak boleh kosong']);
    exit;
}

if (!isset($data->confirmationPassword)) {
    echo json_encode(['status' => false, 'message' => 'Konfirmasi password tidak boleh kosong']);
    exit;
}

if ($data->newPassword <> $data->confirmationPassword) {
    echo json_encode(['status' => false, 'message' => 'Konfirmasi password tidak sama']);
    exit;
}

if ($data->oldPassword < 8 || $data->newPassword < 8 || $data->confirmationPassword < 8) {
    echo json_encode(['status' => false, 'message' => 'Password minimal 8 karakter']);
    exit;
}

$query = "UPDATE pengguna SET password = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $password = $data->newPassword;
    $id = $data->id;

    mysqli_stmt_bind_param($stmt, 'si', $password, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => true, 'message' => 'Password berhasil diupdate']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Update password gagal']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
