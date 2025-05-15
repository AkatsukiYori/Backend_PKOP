<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));

if (!str_contains($data->email, '@gmail.com')) {
    echo json_encode(["status" => false, "message" => "Format email salah"]);
    exit;
}

if ($data->nomor_hp < 10) {
    echo json_encode(["status" => false, "message" => "Nomor HP harus lebih dari 10 digit"]);
    exit;
}

$query = "UPDATE pengguna SET username = ?, email = ?, nomor_hp = ?, mtm = NOW() WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $username = $data->username;
    $email = $data->email;
    $nomor_hp = $data->nomor_hp;
    $id = $data->id;

    mysqli_stmt_bind_param($stmt, 'sssi', $username, $email, $nomor_hp, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => true, 'message' => 'Profile berhasil diupdate']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Update profile gagal']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
