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
?>

<link rel="stylesheet" href="/public/css/detail.css">

<div class="detail-container">
    <!-- Print Header (hidden until print) -->
    <div class="print-header">
        <h2>PEDULI DIRI</h2>
        <p>Detail Catatan Perjalanan</p>
        <p>Tanggal Cetak: <?php echo date('d-m-Y H:i'); ?></p>
        <p>Nama: <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User'; ?></p>
        <hr>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h4><i class="bi bi-journal-text me-2"></i>Detail Catatan Perjalanan</h4>
        <div>
            <button class="btn btn-warning btn-sm btn-action me-1" onclick="loadContent('/app/views/edit_catatan.php?id=<?php echo $catatan['id']; ?>')">
                <i class="bi bi-pencil me-1"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm btn-action me-1" onclick="hapusCatatan(<?php echo $catatan['id']; ?>)">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
            <button class="btn btn-primary btn-sm btn-action" onclick="printDetail()">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
        </div>
    </div>

    <div id="printArea">
        <div class="card detail-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Catatan</h5>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Tanggal:</div>
                        <div><?php echo date('d-m-Y', strtotime($catatan['tanggal'])); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Waktu:</div>
                        <div><?php echo date('H:i', strtotime($catatan['waktu'])); ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Lokasi:</div>
                        <div><?php echo htmlspecialchars($catatan['lokasi']); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Nama Tempat:</div>
                        <div><?php echo htmlspecialchars($catatan['nama_tempat']); ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Kategori:</div>
                        <div>
                            <span class="badge bg-secondary">
                                <?php echo htmlspecialchars($catatan['kategori'] ?? 'Tidak ada'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Suhu Tubuh:</div>
                        <div>
                            <span class="<?php echo ($catatan['suhu_tubuh'] >= 37.5) ? 'suhu-tinggi' : 'suhu-normal'; ?>">
                                <?php echo number_format($catatan['suhu_tubuh'], 1); ?>°C
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($catatan['keterangan'])): ?>
            <div class="detail-item">
                <div class="detail-label">Keterangan:</div>
                <div><?php echo nl2br(htmlspecialchars($catatan['keterangan'])); ?></div>
            </div>
            <?php endif; ?>

            <div class="detail-item">
                <div class="detail-label">Dibuat pada:</div>
                <div><?php echo date('d-m-Y H:i', strtotime($catatan['created_at'])); ?></div>
            </div>
        </div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="print-footer">
        <p>Dokumen ini dicetak dari Aplikasi Peduli Diri pada <?php echo date('d-m-Y H:i'); ?></p>
    </div>

    <div class="mt-4 no-print">
        <button class="btn btn-secondary btn-action" onclick="loadContent('/app/views/catatan_perjalanan.php')">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </button>
    </div>
</div>

<script>
function hapusCatatan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
        // Show loading state
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'text-center mt-3';
        loadingDiv.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.querySelector('.detail-container').appendChild(loadingDiv);
        
        fetch(`/app/controllers/catatan_controller.php?action=delete&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Catatan berhasil dihapus!');
                    loadContent('/app/views/catatan_perjalanan.php');
                } else {
                    alert('Gagal menghapus catatan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            })
            .finally(() => {
                // Remove loading indicator
                if (loadingDiv.parentNode) {
                    loadingDiv.parentNode.removeChild(loadingDiv);
                }
            });
    }
}

// Fungsi untuk mencetak detail catatan
function printDetail() {
    if (confirm('Apakah Anda ingin mencetak detail catatan ini?')) {
        window.print();
        
        // Opsi alternatif menggunakan html2pdf
        /*
        // Tampilkan loading
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75';
        loadingDiv.style.zIndex = '9999';
        loadingDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Generating PDF...</p></div>';
        document.body.appendChild(loadingDiv);
        
        // Generate PDF
        const element = document.getElementById('printArea');
        const opt = {
            margin: [10, 10, 10, 10],
            filename: 'detail_catatan_' + <?php echo $catatan['id']; ?> + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save().then(() => {
            document.body.removeChild(loadingDiv);
        });
        */
    }
}
</script>