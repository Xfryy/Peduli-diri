<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Peduli Diri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        .login-card:hover {
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            padding: 1.5rem;
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
            padding: 0.6rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        .app-title {
            font-weight: 700;
            color: #007bff;
            margin-bottom: 0.5rem;
        }
        .app-subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h1 class="app-title">PEDULI DIRI</h1>
                    <p class="app-subtitle">Aplikasi Catatan Perjalanan</p>
                </div>
                
                <div class="card login-card">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Login</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="nik" class="form-label"><i class="bi bi-person-badge me-1"></i>NIK</label>
                                <input type="number" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label"><i class="bi bi-person me-1"></i>Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-action w-100 mt-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </form>
                        <div class="mt-4 text-center">
                            <a href="/app/controllers/auth.php?action=register" class="text-decoration-none">Belum punya akun? <span class="text-primary">Register</span></a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted">
                    <small>&copy; <?php echo date('Y'); ?> Peduli Diri - Aplikasi Catatan Perjalanan</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
