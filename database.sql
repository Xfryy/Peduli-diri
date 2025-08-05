-- Buat tabel users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) NOT NULL UNIQUE,
  `nama_lengkap` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample users
INSERT INTO `users` (`nik`, `nama_lengkap`) VALUES
('1234567890123456', 'John Doe'),
('2345678901234567', 'Jane Smith'),
('3456789012345678', 'Bob Johnson');

-- Buat tabel kategori_tempat
CREATE TABLE `kategori_tempat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Buat tabel catatan_perjalanan
CREATE TABLE `catatan_perjalanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `nama_tempat` varchar(255) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `suhu_tubuh` decimal(4,1) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `catatan_perjalanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catatan_perjalanan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_tempat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data awal untuk kategori_tempat
INSERT INTO `kategori_tempat` (`kategori`, `deskripsi`) VALUES
('Toko/Mall', 'Tempat berbelanja seperti toko, mall, supermarket'),
('Restoran', 'Tempat makan seperti restoran, cafe, warung'),
('Kantor', 'Tempat bekerja seperti kantor, coworking space'),
('Sekolah', 'Tempat belajar seperti sekolah, kampus, tempat kursus'),
('Tempat Ibadah', 'Masjid, gereja, pura, vihara'),
('Fasilitas Umum', 'Taman, perpustakaan, terminal, stasiun'),
('Rumah Sakit', 'Rumah sakit, klinik, puskesmas'),
('Lainnya', 'Kategori lainnya');

-- Insert contoh data untuk catatan_perjalanan
INSERT INTO `catatan_perjalanan` (`user_id`, `tanggal`, `waktu`, `lokasi`, `nama_tempat`, `kategori_id`, `suhu_tubuh`, `keterangan`) VALUES
-- Catatan untuk user_id = 1
(1, '2021-04-16', '08:00:00', 'Jakarta', 'Toko Buku Harapan', 1, 36.2, 'Membeli buku pelajaran'),
(1, '2021-04-16', '10:30:00', 'Jakarta', 'Mall Central Park', 1, 36.5, 'Belanja bulanan'),
(1, '2021-04-16', '12:00:00', 'Jakarta', 'Warung Padang Sederhana', 2, 36.3, 'Makan siang'),

-- Catatan untuk user_id = 2
(2, '2021-04-16', '09:00:00', 'Bandung', 'RS Hasan Sadikin', 7, 36.1, 'Check up rutin'),
(2, '2021-04-16', '11:00:00', 'Bandung', 'Masjid Raya Bandung', 5, 36.4, 'Sholat Jumat'),
(2, '2021-04-16', '14:00:00', 'Bandung', 'Paris Van Java Mall', 1, 36.2, 'Meeting dengan client'),

-- Catatan untuk user_id = 3
(3, '2021-04-16', '07:30:00', 'Surabaya', 'SMA Negeri 5', 4, 36.0, 'Mengajar'),
(3, '2021-04-16', '13:00:00', 'Surabaya', 'Taman Bungkul', 6, 36.3, 'Istirahat siang'),
(3, '2021-04-16', '15:30:00', 'Surabaya', 'Perpustakaan Kota', 6, 36.1, 'Mencari referensi');
