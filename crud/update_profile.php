<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));
$response = [];
$id = $data->id;

if (!empty($data->username)) {
    $query = "UPDATE pengguna SET username = ?, mtm = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $data->username, $id);
        mysqli_stmt_execute($stmt);
        $response[] = ['field' => 'username', 'status' => true];
    }
}

if (!empty($data->email)) {
    if (!str_contains($data->email, '@gmail.com')) {
        echo json_encode(["status" => false, "message" => "Format email salah"]);
        exit;
    }
    $query = "UPDATE pengguna SET email = ?, mtm = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $data->email, $id);
        mysqli_stmt_execute($stmt);
        $response[] = ['field' => 'email', 'status' => true];
    }
}

if (!empty($data->nomor_hp)) {
    if ($data->nomor_hp < 10) {
        echo json_encode(["status" => false, "message" => "Nomor HP harus lebih dari 10 digit"]);
        exit;
    }

    $query = "UPDATE pengguna SET nomor_hp = ?, mtm = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $nomor_hp, $id);
        mysqli_stmt_execute($stmt);
        $response[] = ['field' => 'nomor_hp', 'status' => true];
    }
}

echo json_encode([
    'status' => true,
    'message' => 'Profile berhasil diupdate'
]);
