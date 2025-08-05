<?php
session_start();
require_once __DIR__ . '/../models/catatan_model.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/controllers/auth.php?action=login');
    exit;
}

$user_id = $_SESSION['user_id'];
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
        <h4><i class="bi bi-plus-circle me-2"></i>Isi Catatan Perjalanan</h4>
    </div>
    
    <div class="card form-card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Form Pengisian Data</h5>
        </div>
        <div class="card-body">
            <form id="catatanForm" method="post" action="/app/controllers/catatan_controller.php?action=create">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tanggal" class="form-label required-field">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required 
                       value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="waktu" class="form-label required-field">Waktu</label>
                <input type="time" class="form-control" id="waktu" name="waktu" required 
                       value="<?php echo date('H:i'); ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="lokasi" class="form-label required-field">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" 
                       placeholder="Contoh: Jakarta, Bandung, Surabaya" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_tempat" class="form-label required-field">Nama Tempat</label>
                <input type="text" class="form-control" id="nama_tempat" name="nama_tempat" 
                       placeholder="Contoh: Mall Central Park, RS Hasan Sadikin" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="kategori_id" class="form-label required-field">Kategori</label>
                <select class="form-select" id="kategori_id" name="kategori_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?php echo $kategori['id']; ?>">
                            <?php echo htmlspecialchars($kategori['kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="suhu_tubuh" class="form-label required-field">Suhu Tubuh (°C)</label>
                <input type="number" class="form-control" id="suhu_tubuh" name="suhu_tubuh" 
                       step="0.1" min="35.0" max="42.0" placeholder="36.5" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                      placeholder="Tambahkan keterangan tambahan tentang perjalanan Anda..."></textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary btn-action" onclick="loadContent('/app/views/catatan_perjalanan.php')">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button type="submit" class="btn btn-success btn-action">
                <i class="bi bi-save"></i> Simpan Catatan
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
</script>