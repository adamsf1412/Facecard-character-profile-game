<?php
// deletechar.php
include('config.php');

function bersihkan_input($conn, $data) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    $data = $conn->real_escape_string($data);
    return $data;
}

// Menangkap ID Primary Key
$char_id    = (int)bersihkan_input($conn, $_POST['char_id']);
$input_pass = bersihkan_input($conn, $_POST['password']);

// 1. Ambil password asli dari database berdasarkan ID Primary Key
$check_stmt = $conn->prepare("SELECT password FROM characters WHERE id = ?");
$check_stmt->bind_param("i", $char_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $db_password = $row['password'];

    // 2. Validasi kecocokan password
    if ($input_pass === $db_password) {
        
        // Eksekusi hapus baris data berdasarkan ID Primary Key
        $stmt = $conn->prepare("DELETE FROM characters WHERE id = ?");
        $stmt->bind_param("i", $char_id);
        
        if ($stmt->execute()) {
            http_response_code(200); // Sukses Terhapus
        } else {
            http_response_code(500);
        }
        $stmt->close();

    } else {
        http_response_code(403); // Password Salah
    }
} else {
    http_response_code(404);
}

$check_stmt->close();
$conn->close();
?>