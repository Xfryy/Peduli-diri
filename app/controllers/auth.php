
<?php
session_start();
require_once __DIR__ . '/../core/koneksi.php';
require_once __DIR__ . '/../models/user_model.php';

function show_login_form($error = '') {
    include __DIR__ . '/../views/login_form.php';
}

function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nik = $_POST['nik'] ?? '';
        $nama_lengkap = $_POST['nama_lengkap'] ?? '';
        
        $user = find_user_by_nik($nik);
        if ($user && $user['nama_lengkap'] === $nama_lengkap) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['nik'] = $user['nik'];
            $_SESSION['foto'] = $user['foto'];
            header('Location: /index.php');
            exit;
        } else {
            show_login_form('NIK atau Nama Lengkap salah!');
        }
    } else {
        show_login_form();
    }
}

function logout() {
    session_destroy();
    header('Location: /app/controllers/auth.php?action=login');
    exit;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /app/controllers/auth.php?action=login');
        exit;
    }
}

function show_register_form($error = '') {
    include __DIR__ . '/../views/register_form.php';
}

function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nik = $_POST['nik'] ?? '';
        $nama_lengkap = $_POST['nama_lengkap'] ?? '';
        if (empty($nik) || empty($nama_lengkap)) {
            show_register_form('NIK dan Nama Lengkap wajib diisi!');
            return;
        }
        if (find_user_by_nik($nik)) {
            show_register_form('NIK sudah terdaftar!');
            return;
        }
        if (register_user($nik, $nama_lengkap)) {
            header('Location: /app/controllers/auth.php?action=login');
            exit;
        } else {
            show_register_form('Gagal mendaftar, coba lagi!');
        }
    } else {
        show_register_form();
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        login();
    } elseif ($_GET['action'] === 'logout') {
        logout();
    } elseif ($_GET['action'] === 'register') {
        register();
    }
}
