-- -------------------------------------------------------------
-- TablePlus 6.0.8(562)
--
-- https://tableplus.com/
--
-- Database: db_penjualan
-- Generation Time: 2024-07-07 21:47:52.8160
-- -------------------------------------------------------------
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) NOT NULL,
  `harga_barang` decimal(10, 2) NOT NULL,
  `stok_barang` int NOT NULL,
  `foto_barang` varchar(255) DEFAULT NULL,
  `status_barang` enum('tersedia', 'habis') NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 14 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `detail_pembelian`;
CREATE TABLE `detail_pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pembelian` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_satuan` decimal(10, 2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pembelian` (`id_pembelian`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id`),
  CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 20 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `keranjang`;
CREATE TABLE `keranjang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 38 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `total_harga` decimal(10, 2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 35 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin', 'pelanggan') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
INSERT INTO `pembelian` (
    `id`,
    `id_user`,
    `tanggal_pembelian`,
    `total_harga`,
    `status`,
    `metode_pembayaran`
  )
VALUES (8, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (9, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (10, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (11, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (12, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (13, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (14, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (15, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (16, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (17, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (24, 3, '2024-07-07', 0.00, 'Pending', 'QRIS'),
  (
    28,
    3,
    '2024-07-07',
    32721665.00,
    'Pending',
    'QRIS'
  ),
  (
    31,
    3,
    '2024-07-07',
    6544333.00,
    'Pending',
    'QRIS'
  ),
  (
    32,
    1,
    '2024-07-07',
    60000.00,
    'Pending',
    'Metode Bayar...'
  ),
  (33, 1, '2024-07-07', 1999.00, 'Pending', 'QRIS'),
  (
    34,
    1,
    '2024-07-07',
    10000.00,
    'Pending',
    'TUNAI'
  );
INSERT INTO `users` (
    `id`,
    `nama`,
    `alamat`,
    `phone`,
    `username`,
    `password`,
    `role`,
    `created_at`
  )
VALUES (
    1,
    'amar',
    'amar',
    'amar',
    'amar',
    '$2y$10$2jq3EGT2EbbSq07ovuFciuI50DqThLN.rpkMudQAPbItNObf.giuy',
    'pelanggan',
    '2024-07-07 14:56:22'
  ),
  (
    2,
    'admin',
    'admin',
    'admin',
    'admin',
    '$2y$10$Kq0i1bWAfYQg.eElHa42fO8A4c3bVT56.wK8dLtF9OymKVAQMNMpO',
    'admin',
    '2024-07-07 15:21:21'
  ),
  (
    3,
    'dafa',
    'dafa',
    'dafa',
    'dafa',
    '$2y$10$IPwSTJVc0VceI2oA0pY3pelrof3V737vZb5MAK/o/JlsgKIFq42e2',
    'pelanggan',
    '2024-07-07 17:06:09'
  );
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;