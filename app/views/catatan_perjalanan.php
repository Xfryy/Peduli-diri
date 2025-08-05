<?php
session_start();
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../models/catatan_model.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/controllers/auth.php?action=login');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = find_user_by_id($user_id);
$nama = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : ($user ? $user['nama_lengkap'] : 'User');

// Ambil data catatan perjalanan
$catatan_list = get_all_catatan($user_id);
$foto = null;

// Cek foto dari database dulu
if ($user && !empty($user['foto'])) {
    $foto = '/public/assets/Foto_Profile/' . $user['foto'];
} 
// Kalau tidak ada di database, gunakan avatar default
else {
    $foto = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=eee&color=222&size=80';
}
?>

<link rel="stylesheet" href="/public/css/catatan.css">

<!-- Main Content -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Daftar Catatan Perjalanan</h4>
    <div>
        <button class="btn btn-success me-2 btn-action" onclick="loadContent('/app/views/isi_data.php')">
            <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
        </button>
        <button class="btn btn-primary btn-action" onclick="printCatatan()">
            <i class="bi bi-printer me-1"></i> Cetak Catatan
        </button>
    </div>
</div>

<!-- Print Header (hidden until print) -->
<div class="print-section print-header">
    <h2>PEDULI DIRI</h2>
    <p>Catatan Perjalanan <?php echo htmlspecialchars($nama); ?></p>
    <p>Tanggal Cetak: <?php echo date('d-m-Y H:i'); ?></p>
    <hr>
</div>

<div id="printArea">

<?php if (empty($catatan_list)): ?>
    <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size: 5rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">Belum ada catatan perjalanan</h4>
        <p class="text-muted">Mulai catat perjalanan Anda dengan klik tombol "Tambah Catatan"</p>
        <button class="btn btn-success btn-lg mt-3 btn-action no-print" onclick="loadContent('/app/views/isi_data.php')">
            <i class="bi bi-plus-circle me-2"></i> Tambah Catatan Pertama
        </button>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover print-table">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Waktu</th>
                    <th>Lokasi</th>
                    <th>Nama Tempat</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Suhu Tubuh</th>
                    <th>Keterangan</th>
                    <th class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catatan_list as $index => $catatan): ?>
                    <tr>
                        <td class="text-center"><?php echo $index + 1; ?></td>
                        <td class="text-center"><?php echo date('d-m-Y', strtotime($catatan['tanggal'])); ?></td>
                        <td class="text-center"><?php echo date('H:i', strtotime($catatan['waktu'])); ?></td>
                        <td><?php echo htmlspecialchars($catatan['lokasi']); ?></td>
                        <td><?php echo htmlspecialchars($catatan['nama_tempat']); ?></td>
                        <td class="text-center">
                            <span class="badge bg-secondary">
                                <?php echo htmlspecialchars($catatan['kategori'] ?? 'Tidak ada'); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="<?php echo ($catatan['suhu_tubuh'] >= 37.5) ? 'suhu-tinggi' : 'suhu-normal'; ?>">
                                <?php echo number_format($catatan['suhu_tubuh'], 1); ?>°C
                            </span>
                        </td>
                        <td>
                            <?php 
                            $keterangan = htmlspecialchars($catatan['keterangan']);
                            echo strlen($keterangan) > 30 ? substr($keterangan, 0, 30) . '...' : $keterangan;
                            ?>
                        </td>
                        <td class="action-buttons no-print">
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm" 
                                        onclick="loadContent('/app/views/detail_catatan.php?id=<?php echo $catatan['id']; ?>')" 
                                        title="Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" 
                                        onclick="loadContent('/app/views/edit_catatan.php?id=<?php echo $catatan['id']; ?>')" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" 
                                        onclick="hapusCatatan(<?php echo $catatan['id']; ?>)" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Print Footer -->
    <div class="print-section print-footer">
        <p>Dokumen ini dicetak dari Aplikasi Peduli Diri pada <?php echo date('d-m-Y H:i'); ?></p>
    </div>
    
    <!-- Summary -->
</div> <!-- End of printArea -->

    <div class="row mt-4 no-print">
        <div class="col-md-3 mb-3">
            <div class="card text-center summary-card">
                <div class="card-body py-4">
                    <i class="bi bi-journal"></i>
                    <h5 class="card-title text-primary"><?php echo count($catatan_list); ?></h5>
                    <p class="card-text">Total Catatan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center summary-card">
                <div class="card-body py-4">
                    <i class="bi bi-thermometer-high" style="color: #dc3545;"></i>
                    <?php 
                    $suhu_tinggi = array_filter($catatan_list, function($c) { return $c['suhu_tubuh'] >= 37.5; });
                    ?>
                    <h5 class="card-title text-danger"><?php echo count($suhu_tinggi); ?></h5>
                    <p class="card-text">Suhu Tinggi (≥37.5°C)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center summary-card">
                <div class="card-body py-4">
                    <i class="bi bi-calendar-check" style="color: #198754;"></i>
                    <?php 
                    $hari_ini = date('Y-m-d');
                    $catatan_hari_ini = array_filter($catatan_list, function($c) use ($hari_ini) { 
                        return $c['tanggal'] == $hari_ini; 
                    });
                    ?>
                    <h5 class="card-title text-success"><?php echo count($catatan_hari_ini); ?></h5>
                    <p class="card-text">Catatan Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center summary-card">
                <div class="card-body py-4">
                    <i class="bi bi-clock-history" style="color: #0dcaf0;"></i>
                    <?php 
                    $tanggal_terakhir = !empty($catatan_list) ? $catatan_list[0]['tanggal'] : '-';
                    ?>
                    <h5 class="card-title text-info"><?php echo $tanggal_terakhir != '-' ? date('d/m', strtotime($tanggal_terakhir)) : '-'; ?></h5>
                    <p class="card-text">Terakhir Dicatat</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="/public/js/catatan.js"></script>