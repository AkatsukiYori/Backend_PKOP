<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password) || !isset($data->email)) {
    echo json_encode(["status" => false, "message" => "Email atau Username atau Password tidak boleh kosong"]);
    exit;
}

if (!str_contains($data->email, '@gmail.com')) {
    echo json_encode(["status" => false, "message" => "Format email salah"]);
    exit;
}

if ($data->password < 8) {
    echo json_encode(["status" => false, "message" => "Minimal password harus 8 karakter"]);
    exit;
}

$query = "INSERT INTO pengguna (username, email, password, del, ctm, usr) VALUES (?,?,?, 0, NOW(), 0)";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $username = $data->username;
    $password = $data->password;
    $email = $data->email;

    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => true, 'message' => 'Register berhasil']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Register gagal']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah koneksi']);
}
