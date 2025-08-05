
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Catatan Perjalanan</title>
  <link rel="icon" type="image/x-icon" href="/public/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: controllers/auth.php?action=login');
    exit;
}

// Set user data from session
$nama = $_SESSION['nama_lengkap'] ?? 'User';
$foto = $_SESSION['foto'] ?? '/public/assets/Foto_Profile/default.jpg';

?>
    <link rel="stylesheet" href="/public/css/main.css">

<body>
<div class="container py-4">
        <?php include __DIR__ . '/views/navbar.php'; ?>
        <div class="main-box" id="mainContent">
            <div id="welcomeContent" class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-journal-check" style="font-size: 4rem; color: #0d6efd;"></i>
                </div>
                <h2 class="mb-3">Selamat Datang, <?php echo htmlspecialchars($nama); ?>!</h2>
                <p class="lead text-muted">di Aplikasi Peduli Diri - Catatan Perjalanan Anda</p>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-journal-text mb-3" style="font-size: 2.5rem; color: #0d6efd;"></i>
                                <h5 class="card-title">Catatan Perjalanan</h5>
                                <p class="card-text">Lihat semua catatan perjalanan Anda</p>
                                <button class="btn btn-primary mt-2" onclick="loadContent('views/catatan_perjalanan.php', this)">
                                    <i class="bi bi-arrow-right"></i> Lihat Catatan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-plus-circle mb-3" style="font-size: 2.5rem; color: #198754;"></i>
                                <h5 class="card-title">Isi Data Baru</h5>
                                <p class="card-text">Tambahkan catatan perjalanan baru</p>
                                <button class="btn btn-success mt-2" onclick="loadContent('views/isi_data.php', this)">
                                    <i class="bi bi-plus-lg"></i> Tambah Catatan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/views/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="/public/js/main.js"></script>
</body>
</html>