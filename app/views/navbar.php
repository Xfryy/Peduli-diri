<?php
require_once __DIR__ . '/../models/user_model.php';

// Get user data for navbar
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user = $user_id ? find_user_by_id($user_id) : null;
$nama = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : ($user ? $user['nama_lengkap'] : 'User');
$nik = isset($_SESSION['nik']) ? $_SESSION['nik'] : ($user ? $user['nik'] : '-');

// Get photo properly
if (isset($_SESSION['foto']) && $_SESSION['foto']) {
    $foto = '/public/assets/Foto_Profile/' . $_SESSION['foto'];
} elseif ($user && !empty($user['foto'])) {
    $foto = '/public/assets/Foto_Profile/' . $user['foto']; 
} else {
    $foto = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=eee&color=222&size=80';
}
?>

        <div class="card shadow mb-4" style="border-radius: 15px; overflow: hidden;">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="logo-box p-0 overflow-hidden" style="width: 90px; height: 90px; border-radius: 50%; border: 3px solid #007bff; box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto Profil" class="img-fluid rounded-circle" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">NIK: <?php echo htmlspecialchars($nik); ?></small>
                        </div>
                    </div>
                    <div class="col">
                        <h2 class="mb-0 text-primary fw-bold">PEDULI DIRI</h2>
                        <p class="text-muted mb-0">Aplikasi Catatan Perjalanan</p>
                    </div>
                    <div class="col-auto">
                        <a href="#" onclick="loadContent('/app/views/profile_view.php')" class="btn btn-outline-primary btn-action">
                            <i class="bi bi-person-circle me-1"></i> Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-3 shadow mb-4" style="border-radius: 15px !important; overflow: hidden;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link px-3 mx-1" href="/index.php"">
                                <i class="bi bi-house-door-fill me-1"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 mx-1" href="#" onclick="loadContent('/app/views/catatan_perjalanan.php')">
                                <i class="bi bi-journal-text me-1"></i> Catatan Perjalanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 mx-1" href="#" onclick="loadContent('/app/views/isi_data.php')">
                                <i class="bi bi-plus-circle-fill me-1"></i> Isi Data
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <a href="/app/controllers/auth.php?action=logout" class="btn btn-danger btn-action">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>