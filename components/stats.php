<?php
require_once __DIR__ . '/../app/core/koneksi.php';

// Get total users
$users_query = mysqli_query($conn, "SELECT COUNT(*) as total_users FROM users");
$users_count = mysqli_fetch_assoc($users_query)['total_users'];

// Get total catatan perjalanan
$catatan_query = mysqli_query($conn, "SELECT COUNT(*) as total_catatan FROM catatan_perjalanan");
$catatan_count = mysqli_fetch_assoc($catatan_query)['total_catatan'];
?>
<!-- Stats Section -->
<section class="section stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number" data-count="<?php echo $users_count; ?>">0</div>
                    <div class="stat-label">Pengguna</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number" data-count="<?php echo $catatan_count; ?>">0</div>
                    <div class="stat-label">Perjalanan Tercatat</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number" data-count="99">0</div>
                    <div class="stat-label">% Kepuasan</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number" data-count="24">0</div>
                    <div class="stat-label">Jam Dukungan</div>
                </div>
            </div>
        </div>
    </div>
</section>
