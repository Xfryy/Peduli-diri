<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login
header('Location: /app/controllers/auth.php?action=login');
exit;
?>
