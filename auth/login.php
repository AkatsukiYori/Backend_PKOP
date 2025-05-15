<?php
include '../koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    echo json_encode(["status" => false, "message" => "Username atau Password tidak boleh kosong"]);
    exit;
}

$query = "SELECT * FROM pengguna where username =  ? AND password = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $username = $data->username;
    $password = $data->password;

    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            echo json_encode(['status' => true, 'message' => 'Login Berhasil', 'data' => $id]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Username atau Password salah']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Username atau Password salah']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Masalah Koneksi']);
}
