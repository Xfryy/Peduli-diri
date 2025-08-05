<?php
session_start();
require_once __DIR__ . '/../models/catatan_model.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? 'list';
$user_id = $_SESSION['user_id'];

switch ($action) {

        
    case 'list':
        header('Content-Type: application/json');
        echo json_encode(get_all_catatan($user_id));
        break;

    case 'get':
        if (isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode(get_catatan_by_id($_GET['id'], $user_id));
        }
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Validasi input
            $required_fields = ['tanggal', 'waktu', 'lokasi', 'nama_tempat', 'kategori_id', 'suhu_tubuh'];
            $missing_fields = [];
            
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $missing_fields[] = $field;
                }
            }
            
            if (!empty($missing_fields)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Field berikut harus diisi: ' . implode(', ', $missing_fields)
                ]);
                break;
            }
            
            // Validasi kategori_id
            $kategori_id = (int)$_POST['kategori_id'];
            if ($kategori_id <= 0) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Kategori tidak valid'
                ]);
                break;
            }
            
            $result = create_catatan(
                $user_id,
                $_POST['tanggal'],
                $_POST['waktu'],
                $_POST['lokasi'],
                $_POST['nama_tempat'],
                $kategori_id,
                $_POST['suhu_tubuh'],
                $_POST['keterangan'] ?? ''
            );
            
            if ($result) {
                // Redirect ke halaman catatan perjalanan
                header('Location: /app/dashboard.php?view=catatan_perjalanan');
                exit;
            } else {
                // Jika gagal, tampilkan error
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Gagal menyimpan catatan. Silakan coba lagi.'
                ]);
            }
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi input
            $required_fields = ['id', 'tanggal', 'waktu', 'lokasi', 'nama_tempat', 'kategori_id', 'suhu_tubuh'];
            $missing_fields = [];
            
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $missing_fields[] = $field;
                }
            }
            
            if (!empty($missing_fields)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Field berikut harus diisi: ' . implode(', ', $missing_fields)
                ]);
                break;
            }
            
            $result = update_catatan(
                $_POST['id'],
                $user_id,
                $_POST['tanggal'],
                $_POST['waktu'],
                $_POST['lokasi'],
                $_POST['nama_tempat'],
                $_POST['kategori_id'],
                $_POST['suhu_tubuh'],
                $_POST['keterangan'] ?? ''
            );
            
            if ($result) {
                // Redirect ke halaman catatan perjalanan
                header('Location: /app/dashboard.php?view=catatan_perjalanan');
                exit;
            } else {
                // Jika gagal, tampilkan error
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Gagal mengupdate catatan. Silakan coba lagi.'
                ]);
            }
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $result = delete_catatan($id, $user_id);
            
            if ($result) {
                header('Location: /app/dashboard.php?view=catatan_perjalanan');
                exit;
            } else {
                header('Location: /app/dashboard.php?view=catatan_perjalanan&error=delete_failed');
                exit;
            }
        } else {
            header('Location: /app/dashboard.php?view=catatan_perjalanan&error=no_id');
            exit;
        }
}
