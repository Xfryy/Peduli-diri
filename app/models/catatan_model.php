<?php
require_once __DIR__ . '/../core/koneksi.php';

function get_all_catatan($user_id) {
    global $conn;
    $sql = "SELECT c.*, k.kategori 
            FROM catatan_perjalanan c 
            LEFT JOIN kategori_tempat k ON c.kategori_id = k.id 
            WHERE c.user_id = ? 
            ORDER BY c.tanggal DESC, c.waktu DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_catatan_by_id($id, $user_id) {
    global $conn;
    $sql = "SELECT c.*, k.kategori 
            FROM catatan_perjalanan c 
            LEFT JOIN kategori_tempat k ON c.kategori_id = k.id 
            WHERE c.id = ? AND c.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function create_catatan($user_id, $tanggal, $waktu, $lokasi, $nama_tempat, $kategori_id, $suhu_tubuh, $keterangan = '') {
    global $conn;
    $sql = "INSERT INTO catatan_perjalanan (user_id, tanggal, waktu, lokasi, nama_tempat, kategori_id, suhu_tubuh, keterangan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssids", $user_id, $tanggal, $waktu, $lokasi, $nama_tempat, $kategori_id, $suhu_tubuh, $keterangan);
    return $stmt->execute();
}

function update_catatan($id, $user_id, $tanggal, $waktu, $lokasi, $nama_tempat, $kategori_id, $suhu_tubuh, $keterangan) {
    global $conn;
    $sql = "UPDATE catatan_perjalanan 
            SET tanggal = ?, waktu = ?, lokasi = ?, nama_tempat = ?, 
                kategori_id = ?, suhu_tubuh = ?, keterangan = ? 
            WHERE id = ? AND user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdsii", $tanggal, $waktu, $lokasi, $nama_tempat, $kategori_id, $suhu_tubuh, $keterangan, $id, $user_id);
    return $stmt->execute();
}

function delete_catatan($id, $user_id) {
    global $conn;
    
    // Cek apakah catatan ada dan milik user yang benar
    $check_sql = "SELECT id FROM catatan_perjalanan WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        return false; // Catatan tidak ditemukan atau bukan milik user
    }
    
    $sql = "DELETE FROM catatan_perjalanan WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    return $stmt->execute();
}

function get_all_kategori() {
    global $conn;
    $sql = "SELECT * FROM kategori_tempat ORDER BY kategori";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
