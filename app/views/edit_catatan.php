<?php
session_start();
require_once __DIR__ . '/../models/catatan_model.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/controllers/auth.php?action=login');
    exit;
}

$user_id = $_SESSION['user_id'];
$catatan_id = $_GET['id'] ?? null;

if (!$catatan_id) {
    echo '<div class="alert alert-danger">ID catatan tidak ditemukan</div>';
    exit;
}

$catatan = get_catatan_by_id($catatan_id, $user_id);

if (!$catatan) {
    echo '<div class="alert alert-danger">Catatan tidak ditemukan</div>';
    exit;
}

$kategori_list = get_all_kategori();
?>

<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }
    .form-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    .form-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    .form-control, .form-select {
        border-radius: 5px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-action {
        border-radius: 5px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil-square me-2"></i>Edit Catatan Perjalanan</h4>
    </div>
    
    <div class="card form-card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Form Edit Data</h5>
        </div>
        <div class="card-body">
            <form id="editCatatanForm" method="post" action="/app/controllers/catatan_controller.php?action=update">
                <input type="hidden" name="id" value="<?php echo $catatan['id']; ?>">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tanggal" class="form-label required-field">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required 
                       value="<?php echo $catatan['tanggal']; ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="waktu" class="form-label required-field">Waktu</label>
                <input type="time" class="form-control" id="waktu" name="waktu" required 
                       value="<?php echo $catatan['waktu']; ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="lokasi" class="form-label required-field">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" 
                       value="<?php echo htmlspecialchars($catatan['lokasi']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_tempat" class="form-label required-field">Nama Tempat</label>
                <input type="text" class="form-control" id="nama_tempat" name="nama_tempat" 
                       value="<?php echo htmlspecialchars($catatan['nama_tempat']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="kategori_id" class="form-label required-field">Kategori</label>
                <select class="form-select" id="kategori_id" name="kategori_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?php echo $kategori['id']; ?>" 
                                <?php echo ($kategori['id'] == $catatan['kategori_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kategori['kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="suhu_tubuh" class="form-label required-field">Suhu Tubuh (°C)</label>
                <input type="number" class="form-control" id="suhu_tubuh" name="suhu_tubuh" 
                       step="0.1" min="35.0" max="42.0" 
                       value="<?php echo $catatan['suhu_tubuh']; ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($catatan['keterangan']); ?></textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary btn-action" onclick="loadContent('/app/views/detail_catatan.php?id=<?php echo $catatan['id']; ?>')">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button type="submit" class="btn btn-warning btn-action">
                <i class="bi bi-save"></i> Update Catatan
            </button>
        </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validasi suhu tubuh
document.getElementById('suhu_tubuh').addEventListener('input', function() {
    const suhu = parseFloat(this.value);
    if (suhu >= 37.5) {
        this.style.color = '#dc3545';
        this.style.fontWeight = 'bold';
    } else {
        this.style.color = '#28a745';
        this.style.fontWeight = 'normal';
    }
});

// Set warna awal suhu tubuh
document.addEventListener('DOMContentLoaded', function() {
    const suhuInput = document.getElementById('suhu_tubuh');
    const suhu = parseFloat(suhuInput.value);
    if (suhu >= 37.5) {
        suhuInput.style.color = '#dc3545';
        suhuInput.style.fontWeight = 'bold';
    } else {
        suhuInput.style.color = '#28a745';
        suhuInput.style.fontWeight = 'normal';
    }
});
</script>