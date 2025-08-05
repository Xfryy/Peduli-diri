<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login
header('Location: /Catatan-perjalanan/Peduli-diri/index.php');
exit;
?>
