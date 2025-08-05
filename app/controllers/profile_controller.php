<?php
session_start();
require_once __DIR__ . '/../models/user_model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $nama = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $nik = isset($_POST['nik']) ? trim($_POST['nik']) : '';
    $fotoBaru = null;
    
    // Ambil data user dari database
    $user = find_user_by_id($user_id);
    $fotoLama = isset($_SESSION['foto']) ? $_SESSION['foto'] : ($user && !empty($user['foto']) ? $user['foto'] : null);

    // Set target directory ke public/assets/Foto_Profile
    $target_dir = dirname(__DIR__, 2) . '/public/assets/Foto_Profile/';
    
    // Pastikan direktori ada
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal membuat direktori']);
            exit;
        }
    }
    
    // Cek permission
    if (!is_writable($target_dir)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Direktori tidak dapat ditulis']);
        exit;
    }

    // Upload foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Validasi file
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, GIF yang diperbolehkan.']);
            exit;
        }
        
        // Validasi ukuran (max 5MB)
        if ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB.']);
            exit;
        }
        
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
        $target = $target_dir . $filename;
        
        // Hapus foto lama jika ada dan bukan URL avatar
        if ($fotoLama && !str_contains($fotoLama, 'ui-avatars.com')) {
            $old_photo_path = $target_dir . $fotoLama;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $fotoBaru = $filename;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Upload foto gagal. Silakan coba lagi.']);
            exit;
        }
    } else if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Ada error dalam upload
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'File terlalu besar (php.ini)',
            UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (form)',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_TMP_DIR => 'Direktori temp tidak ada',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi'
        ];
        
        $error_message = isset($upload_errors[$_FILES['foto']['error']]) ? 
            $upload_errors[$_FILES['foto']['error']] : 'Error upload tidak diketahui';
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error upload: ' . $error_message]);
        exit;
    }

    // Validasi sederhana
    if ($nama && $nik) {
        // Update ke database
        if (update_user_profile($user_id, $nama, $nik, $fotoBaru ? $fotoBaru : $fotoLama)) {
            // Ambil data terbaru dari database
            $userBaru = find_user_by_id($user_id);
            $_SESSION['nama_lengkap'] = $userBaru['nama_lengkap'];
            $_SESSION['nik'] = $userBaru['nik'];
            $_SESSION['foto'] = $userBaru['foto'];
            
            // Redirect ke halaman dashboard
            header('Location: /app/dashboard.php');
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal update database']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Nama dan NIK harus diisi']);
    }
}
?>