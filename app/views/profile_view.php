<!-- Profile view -->
<?php
session_start();
require_once __DIR__ . '/../models/user_model.php';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user = $user_id ? find_user_by_id($user_id) : null;
$nama = isset($_SESSION['nama_lengkap']) && $_SESSION['nama_lengkap'] ? $_SESSION['nama_lengkap'] : ($user ? $user['nama_lengkap'] : '');
$nik = isset($_SESSION['nik']) && $_SESSION['nik'] ? $_SESSION['nik'] : ($user ? $user['nik'] : '');
if (isset($_SESSION['foto']) && $_SESSION['foto']) {
    $foto = '/public/assets/Foto_Profile/' . $_SESSION['foto'];
} elseif ($user && !empty($user['foto'])) {
    $foto = '/public/assets/Foto_Profile/' . $user['foto'];
} else {
    $foto = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=eee&color=222&size=80';
}
?>

<style>
    .profile-container {
        max-width: 700px;
        margin: 0 auto;
    }
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .profile-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #007bff;
        transition: all 0.3s ease;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    .profile-photo:hover {
        transform: scale(1.05);
    }
    .form-control {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-action {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
</style>

<div class="profile-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profile Diri</h4>
        <button class="btn btn-secondary btn-action" onclick="loadContent('/app/views/catatan_perjalanan.php')">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </button>
    </div>

    <div class="card profile-card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profil</h5>
        </div>
        <div class="card-body">
            <form action="/app/controllers/profile_controller.php" method="post" enctype="multipart/form-data" class="mt-3">
        <div class="text-center mb-4">
            <img src="<?php echo $foto; ?>" alt="Foto Profil" class="profile-photo mb-3" id="preview-foto">
            <div class="mb-3">
                <input type="file" class="form-control" name="foto" accept="image/*" id="input-foto" onchange="previewImage(this);">
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 5MB</small>
            </div>
        </div>

        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($nama); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" value="<?php echo htmlspecialchars($nik); ?>" required>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-action">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview-foto');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '<?php echo $foto; ?>';
    }
}


</script>
