
<?php
// Model for user data
require_once __DIR__ . '/../core/koneksi.php';

function find_user_by_nik($nik) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE nik = ? LIMIT 1");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function find_user_by_id($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function register_user($nik, $nama_lengkap) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (nik, nama_lengkap) VALUES (?, ?)");
    $stmt->bind_param("ss", $nik, $nama_lengkap);
    return $stmt->execute();
}
function update_user_profile($user_id, $nama_lengkap, $nik, $foto = null) {
    global $conn;
    if ($foto) {
        $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, nik = ?, foto = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama_lengkap, $nik, $foto, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, nik = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nama_lengkap, $nik, $user_id);
    }
    return $stmt->execute();
}
