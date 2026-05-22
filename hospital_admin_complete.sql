-- ============================================================
--  SISTEM ADMIN RUMAH SAKIT
--  Database: hospital_admin
--  Laravel 11 + MariaDB/MySQL
--  Generated: 2026
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";
SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Buat & gunakan database
-- ------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `hospital_admin`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `hospital_admin`;

-- ============================================================
-- 1. TABEL: agama (lookup)
-- ============================================================
CREATE TABLE `agama` (
  `id`   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(30)      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agama` (`nama`) VALUES
  ('Islam'),('Kristen'),('Katholik'),('Hindu'),('Buddha'),('Konghucu');

-- ============================================================
-- 2. TABEL: poli
-- ============================================================
CREATE TABLE `poli` (
  `id_poli`    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nama_poli`  VARCHAR(50)     NOT NULL,
  `kode_poli`  VARCHAR(10)     NOT NULL UNIQUE,
  `keterangan` TEXT,
  `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_poli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `poli` (`nama_poli`,`kode_poli`,`keterangan`) VALUES
  ('Jantung',         'PLI-JTG', 'Poli Jantung dan Pembuluh Darah'),
  ('Umum',            'PLI-UMM', 'Poli Umum / General Practitioner'),
  ('Penyakit Dalam',  'PLI-PDL', 'Poli Penyakit Dalam / Interna'),
  ('Gigi dan Mulut',  'PLI-GGI', 'Poli Gigi, Mulut dan Bedah Mulut'),
  ('Mata',            'PLI-MTA', 'Poli Kesehatan Mata'),
  ('Anak',            'PLI-ANK', 'Poli Kesehatan Anak / Pediatri'),
  ('Kandungan',       'PLI-KDG', 'Poli Obstetri dan Ginekologi'),
  ('THT',             'PLI-THT', 'Poli Telinga Hidung Tenggorokan');

-- ============================================================
-- 3. TABEL: spesialis (lookup)
-- ============================================================
CREATE TABLE `spesialis` (
  `id`   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(60)       NOT NULL,
  `kode` VARCHAR(10)       NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `spesialis` (`nama`,`kode`) VALUES
  ('Umum',                       'SP-UMM'),
  ('Penyakit Dalam',             'SP-PDL'),
  ('Jantung dan Pembuluh Darah', 'SP-JTG'),
  ('Gigi dan Mulut',             'SP-GGI'),
  ('Mata',                       'SP-MTA'),
  ('Anak',                       'SP-ANK'),
  ('Obstetri dan Ginekologi',    'SP-OBG'),
  ('THT',                        'SP-THT');

-- ============================================================
-- 4. TABEL: dokter
-- ============================================================
CREATE TABLE `dokter` (
  `id_dokter`    INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `kode_dokter`  VARCHAR(12)       NOT NULL UNIQUE,
  `nama_dokter`  VARCHAR(100)      NOT NULL,
  `id_spesialis` SMALLINT UNSIGNED NOT NULL,
  `id_poli`      INT UNSIGNED      NOT NULL,
  `sip_number`   VARCHAR(30)                COMMENT 'Nomor SIP / STR Dokter',
  `telepon`      VARCHAR(20),
  `email`        VARCHAR(100),
  `foto`         VARCHAR(255),
  `is_active`    TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokter`),
  CONSTRAINT `fk_dokter_spesialis` FOREIGN KEY (`id_spesialis`) REFERENCES `spesialis`(`id`),
  CONSTRAINT `fk_dokter_poli`      FOREIGN KEY (`id_poli`)      REFERENCES `poli`(`id_poli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dokter` (`kode_dokter`,`nama_dokter`,`id_spesialis`,`id_poli`,`sip_number`,`telepon`,`email`) VALUES
  ('DKT-0001','drg. Juwita Rahmadhini, Sp.KG', 4, 4, 'SIP/2021/001', '08111000001','juwita@rshospital.id'),
  ('DKT-0002','dr. Vincent VRK, Sp.PD',         2, 3, 'SIP/2020/002', '08111000002','vincent@rshospital.id'),
  ('DKT-0003','dr. Vior Santoso',                1, 2, 'SIP/2022/003', '08111000003','vior@rshospital.id'),
  ('DKT-0004','dr. Jea Kurniawan, Sp.JP',        3, 1, 'SIP/2019/004', '08111000004','jea@rshospital.id'),
  ('DKT-0005','dr. Anita Wulandari, Sp.M',       5, 5, 'SIP/2021/005', '08111000005','anita@rshospital.id'),
  ('DKT-0006','dr. Budi Prasetyo, Sp.A',         6, 6, 'SIP/2020/006', '08111000006','budi@rshospital.id');

-- ============================================================
-- 5. TABEL: jadwal_dokter
-- ============================================================
CREATE TABLE `jadwal_dokter` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `id_dokter`   INT UNSIGNED  NOT NULL,
  `hari`        TINYINT       NOT NULL COMMENT '1=Senin ... 7=Minggu',
  `jam_mulai`   TIME          NOT NULL,
  `jam_selesai` TIME          NOT NULL,
  `kuota`       SMALLINT      NOT NULL DEFAULT 20 COMMENT 'Maks pasien per sesi',
  `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_jadwal_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter`(`id_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `jadwal_dokter` (`id_dokter`,`hari`,`jam_mulai`,`jam_selesai`,`kuota`) VALUES
  (1,1,'08:00','12:00',15),(1,3,'08:00','12:00',15),(1,5,'08:00','12:00',15),
  (2,1,'13:00','17:00',20),(2,2,'13:00','17:00',20),(2,4,'13:00','17:00',20),
  (3,1,'08:00','16:00',30),(3,2,'08:00','16:00',30),(3,3,'08:00','16:00',30),
  (3,4,'08:00','16:00',30),(3,5,'08:00','16:00',30),
  (4,2,'08:00','12:00',15),(4,4,'08:00','12:00',15),
  (5,1,'09:00','13:00',18),(5,3,'09:00','13:00',18),(5,5,'09:00','13:00',18),
  (6,2,'08:00','14:00',25),(6,4,'08:00','14:00',25);

-- ============================================================
-- 6. TABEL: pasien
-- ============================================================
CREATE TABLE `pasien` (
  `no_rm`         VARCHAR(15)       NOT NULL COMMENT 'Nomor Rekam Medis',
  `nik`           CHAR(16)                   UNIQUE COMMENT 'NIK KTP',
  `nama_pasien`   VARCHAR(100)      NOT NULL,
  `tgl_lahir`     DATE              NOT NULL,
  `tempat_lahir`  VARCHAR(50),
  `jenkel`        ENUM('Laki-Laki','Perempuan') NOT NULL,
  `id_agama`      TINYINT UNSIGNED  NOT NULL,
  `golongan_darah`ENUM('A','B','AB','O','Tidak Diketahui') DEFAULT 'Tidak Diketahui',
  `alamat`        TEXT              NOT NULL,
  `rt`            VARCHAR(5),
  `rw`            VARCHAR(5),
  `kelurahan`     VARCHAR(50),
  `kecamatan`     VARCHAR(50),
  `kota`          VARCHAR(50),
  `kode_pos`      VARCHAR(10),
  `telepon`       VARCHAR(20),
  `no_bpjs`       VARCHAR(20)                UNIQUE,
  `foto`          VARCHAR(255),
  `created_at`    TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_rm`),
  CONSTRAINT `fk_pasien_agama` FOREIGN KEY (`id_agama`) REFERENCES `agama`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pasien`
  (`no_rm`,`nik`,`nama_pasien`,`tgl_lahir`,`tempat_lahir`,`jenkel`,`id_agama`,`golongan_darah`,`alamat`,`kota`,`telepon`)
VALUES
  ('RM-000001','3471010012040001','Juwita Rahmadhini','2004-12-19','Yogyakarta','Perempuan',1,'O','Jl. Malioboro No. 12','Yogyakarta','08122000001'),
  ('RM-000002','3171020513030002','Vincent VRK','2003-05-13','Jakarta','Laki-Laki',2,'A','Jl. Sudirman Kav. 52','Jakarta','08122000002'),
  ('RM-000003','3372031704000003','Vior Santoso','2000-04-17','Surakarta','Perempuan',3,'B','Jl. Slamet Riyadi No. 88','Solo','08122000003'),
  ('RM-000004','3374020101990004','Bima Arjuna','1999-01-01','Semarang','Laki-Laki',1,'AB','Jl. Pemuda No. 20','Semarang','08122000004'),
  ('RM-000005','3471055501980005','Dewi Sartika','1998-01-15','Bandung','Perempuan',1,'O','Jl. Asia Afrika No. 5','Bandung','08122000005');

-- ============================================================
-- 7. TABEL: pendaftaran
-- ============================================================
CREATE TABLE `pendaftaran` (
  `no_reg`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `kode_reg`        VARCHAR(20)     NOT NULL UNIQUE COMMENT 'Format: REG-YYYYMMDD-NNN',
  `no_rm`           VARCHAR(15)     NOT NULL,
  `id_poli`         INT UNSIGNED    NOT NULL,
  `id_dokter`       INT UNSIGNED    NOT NULL,
  `tgl_pendaftaran` DATE            NOT NULL,
  `jam_pendaftaran` TIME            NOT NULL DEFAULT (CURRENT_TIME),
  `no_antrian`      SMALLINT        NOT NULL DEFAULT 0,
  `status`          ENUM('Menunggu','Dipanggil','Dalam Pemeriksaan','Selesai','Batal')
                    NOT NULL DEFAULT 'Menunggu',
  `keluhan`         TEXT                     COMMENT 'Keluhan utama pasien',
  `catatan_admin`   TEXT,
  `jenis_pembayaran`ENUM('Umum','BPJS','Asuransi Lain') NOT NULL DEFAULT 'Umum',
  `no_bpjs`         VARCHAR(20),
  `created_by`      BIGINT UNSIGNED          COMMENT 'FK ke users Laravel',
  `created_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_reg`),
  CONSTRAINT `fk_daftar_pasien` FOREIGN KEY (`no_rm`)      REFERENCES `pasien`(`no_rm`),
  CONSTRAINT `fk_daftar_poli`   FOREIGN KEY (`id_poli`)    REFERENCES `poli`(`id_poli`),
  CONSTRAINT `fk_daftar_dokter` FOREIGN KEY (`id_dokter`)  REFERENCES `dokter`(`id_dokter`),
  INDEX `idx_tgl_pendaftaran` (`tgl_pendaftaran`),
  INDEX `idx_status`          (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pendaftaran`
  (`kode_reg`,`no_rm`,`id_poli`,`id_dokter`,`tgl_pendaftaran`,`jam_pendaftaran`,`no_antrian`,`status`,`keluhan`,`jenis_pembayaran`)
VALUES
  ('REG-20260521-001','RM-000001',4,1,'2026-05-21','08:05',1,'Selesai',  'Sakit gigi kiri bawah','Umum'),
  ('REG-20260521-002','RM-000002',3,2,'2026-05-21','08:30',1,'Selesai',  'Demam dan mual 3 hari','BPJS'),
  ('REG-20260521-003','RM-000003',2,3,'2026-05-21','09:00',1,'Selesai',  'Batuk pilek','Umum'),
  ('REG-20260521-004','RM-000004',1,4,'2026-05-21','09:15',1,'Selesai',  'Nyeri dada kiri','BPJS'),
  ('REG-20260522-001','RM-000005',2,3,'2026-05-22','08:00',1,'Menunggu', 'Pusing dan lemas','Umum'),
  ('REG-20260522-002','RM-000001',3,2,'2026-05-22','08:10',2,'Dipanggil','Kontrol tekanan darah','BPJS');

-- ============================================================
-- 8. TABEL: rekam_medis
-- ============================================================
CREATE TABLE `rekam_medis` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_reg`          INT UNSIGNED    NOT NULL,
  `no_rm`           VARCHAR(15)     NOT NULL,
  `id_dokter`       INT UNSIGNED    NOT NULL,
  `tgl_periksa`     DATETIME        NOT NULL,
  `anamnesis`       TEXT            COMMENT 'Riwayat keluhan',
  `diagnosis`       VARCHAR(255)    COMMENT 'Diagnosis dokter',
  `kode_icd`        VARCHAR(10)     COMMENT 'Kode ICD-10',
  `terapi`          TEXT            COMMENT 'Tindakan / terapi',
  `resep`           TEXT            COMMENT 'Resep obat',
  `tekanan_darah`   VARCHAR(15)     COMMENT 'contoh: 120/80',
  `nadi`            TINYINT UNSIGNED,
  `suhu`            DECIMAL(4,1),
  `berat_badan`     DECIMAL(5,2),
  `tinggi_badan`    DECIMAL(5,2),
  `catatan_dokter`  TEXT,
  `created_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_rm_reg`    FOREIGN KEY (`no_reg`)    REFERENCES `pendaftaran`(`no_reg`),
  CONSTRAINT `fk_rm_pasien` FOREIGN KEY (`no_rm`)     REFERENCES `pasien`(`no_rm`),
  CONSTRAINT `fk_rm_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter`(`id_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABEL: antrian
-- ============================================================
CREATE TABLE `antrian` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `id_poli`       INT UNSIGNED    NOT NULL,
  `id_dokter`     INT UNSIGNED    NOT NULL,
  `tanggal`       DATE            NOT NULL,
  `no_antrian`    SMALLINT        NOT NULL,
  `no_reg`        INT UNSIGNED    NOT NULL,
  `status`        ENUM('Menunggu','Dipanggil','Selesai','Lewat') NOT NULL DEFAULT 'Menunggu',
  `jam_panggil`   TIME,
  `jam_selesai`   TIME,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_antrian` (`id_poli`,`id_dokter`,`tanggal`,`no_antrian`),
  CONSTRAINT `fk_antrian_poli`   FOREIGN KEY (`id_poli`)   REFERENCES `poli`(`id_poli`),
  CONSTRAINT `fk_antrian_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter`(`id_dokter`),
  CONSTRAINT `fk_antrian_reg`    FOREIGN KEY (`no_reg`)    REFERENCES `pendaftaran`(`no_reg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. TABEL: users (Laravel Auth — disesuaikan)
-- ============================================================
CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(100)    NOT NULL,
  `email`             VARCHAR(150)    NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP,
  `password`          VARCHAR(255)    NOT NULL,
  `id_dokter`         INT UNSIGNED             COMMENT 'NULL jika bukan dokter',
  `is_active`         TINYINT(1)      NOT NULL DEFAULT 1,
  `remember_token`    VARCHAR(100),
  `created_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter`(`id_dokter`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password = "password" (bcrypt — ganti via Laravel seeder)
INSERT INTO `users` (`name`,`email`,`password`,`id_dokter`,`email_verified_at`) VALUES
  ('Super Admin',    'admin@rshospital.id',    '$2y$12$placeholderHashSuperAdmin000000000000000000000000000', NULL,       NOW()),
  ('Admin Poli',     'adminpoli@rshospital.id','$2y$12$placeholderHashAdminPoli0000000000000000000000000000', NULL,       NOW()),
  ('dr. Jea Kurniawan','jea@rshospital.id',    '$2y$12$placeholderHashDokterJea00000000000000000000000000000', 4,         NOW());

-- ============================================================
-- 11. TABEL: roles & permissions (Spatie Laravel Permission)
--     (Dibuat manual agar bisa dijalankan tanpa artisan)
-- ============================================================
CREATE TABLE `roles` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(125)    NOT NULL,
  `guard_name` VARCHAR(125)    NOT NULL DEFAULT 'web',
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`) VALUES ('super_admin'),('admin'),('dokter');

CREATE TABLE `permissions` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(125)    NOT NULL,
  `guard_name` VARCHAR(125)    NOT NULL DEFAULT 'web',
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`name`) VALUES
  ('view dashboard'),
  ('manage pasien'),('create pasien'),('edit pasien'),('delete pasien'),
  ('manage dokter'),('create dokter'),('edit dokter'),('delete dokter'),
  ('manage poli'),
  ('manage pendaftaran'),('create pendaftaran'),('edit pendaftaran'),
  ('manage antrian'),('call antrian'),
  ('view rekam medis'),('create rekam medis'),('edit rekam medis'),
  ('view laporan'),('export laporan'),
  ('manage users'),('manage roles'),
  ('manage settings');

CREATE TABLE `role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id`       BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  CONSTRAINT `fk_rhp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rhp_role`       FOREIGN KEY (`role_id`)       REFERENCES `roles`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- super_admin dapat semua permission (id 1 - 23)
INSERT INTO `role_has_permissions` SELECT p.id, 1 FROM `permissions` p;

-- admin: semua kecuali manage users, roles, settings (id 22,23,24)
INSERT INTO `role_has_permissions`
  SELECT p.id, 2 FROM `permissions` p WHERE p.id NOT IN (21,22,23);

-- dokter: view dashboard, view rekam medis, create/edit rekam medis, view laporan
INSERT INTO `role_has_permissions`
  SELECT p.id, 3 FROM `permissions` p WHERE p.id IN (1,16,17,18,19);

CREATE TABLE `model_has_roles` (
  `role_id`    BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255)    NOT NULL,
  `model_id`   BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  CONSTRAINT `fk_mhr_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`,`model_type`,`model_id`) VALUES
  (1,'App\\Models\\User',1),
  (2,'App\\Models\\User',2),
  (3,'App\\Models\\User',3);

-- ============================================================
-- 12. TABEL: activity_log (Spatie Laravel ActivityLog)
-- ============================================================
CREATE TABLE `activity_log` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name`     VARCHAR(255)             DEFAULT 'default',
  `description`  TEXT            NOT NULL,
  `subject_type` VARCHAR(255),
  `subject_id`   BIGINT UNSIGNED,
  `causer_type`  VARCHAR(255),
  `causer_id`    BIGINT UNSIGNED,
  `properties`   JSON,
  `batch_uuid`   CHAR(36),
  `event`        VARCHAR(255),
  `created_at`   TIMESTAMP                DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP                DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_log_name`     (`log_name`),
  INDEX `idx_subject`      (`subject_type`,`subject_id`),
  INDEX `idx_causer`       (`causer_type`,`causer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. TABEL: notifications (Laravel built-in)
-- ============================================================
CREATE TABLE `notifications` (
  `id`              CHAR(36)        NOT NULL,
  `type`            VARCHAR(255)    NOT NULL,
  `notifiable_type` VARCHAR(255)    NOT NULL,
  `notifiable_id`   BIGINT UNSIGNED NOT NULL,
  `data`            TEXT            NOT NULL,
  `read_at`         TIMESTAMP,
  `created_at`      TIMESTAMP                DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP                DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_notifiable` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. TABEL: settings (konfigurasi RS)
-- ============================================================
CREATE TABLE `settings` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `key`        VARCHAR(100)    NOT NULL UNIQUE,
  `value`      TEXT,
  `group`      VARCHAR(50)     NOT NULL DEFAULT 'general',
  `label`      VARCHAR(100),
  `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`,`value`,`group`,`label`) VALUES
  ('rs_nama',         'RS Harapan Sehat',          'general',  'Nama Rumah Sakit'),
  ('rs_alamat',       'Jl. Kesehatan No. 1, Solo',  'general',  'Alamat'),
  ('rs_telepon',      '0271-123456',                'general',  'Telepon'),
  ('rs_email',        'info@rshospital.id',          'general',  'Email'),
  ('rs_website',      'https://rshospital.id',       'general',  'Website'),
  ('logo_path',       'images/logo.png',             'general',  'Logo'),
  ('jam_buka',        '07:00',                       'jadwal',   'Jam Buka'),
  ('jam_tutup',       '17:00',                       'jadwal',   'Jam Tutup'),
  ('max_antrian',     '50',                          'jadwal',   'Maks Antrian / Hari'),
  ('format_no_rm',    'RM-######',                   'sistem',   'Format No. RM'),
  ('format_kode_reg', 'REG-YYYYMMDD-###',            'sistem',   'Format Kode Registrasi');

-- ============================================================
-- 15. VIEWS berguna untuk laporan & dashboard
-- ============================================================

-- View: kunjungan harian lengkap
CREATE OR REPLACE VIEW `v_kunjungan_harian` AS
SELECT
  p.no_reg,
  p.kode_reg,
  p.tgl_pendaftaran,
  p.jam_pendaftaran,
  p.no_antrian,
  p.status,
  p.jenis_pembayaran,
  p.keluhan,
  ps.no_rm,
  ps.nama_pasien,
  ps.jenkel,
  TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) AS umur,
  po.nama_poli,
  d.nama_dokter,
  sp.nama AS spesialis
FROM `pendaftaran` p
JOIN `pasien`    ps ON ps.no_rm     = p.no_rm
JOIN `poli`      po ON po.id_poli   = p.id_poli
JOIN `dokter`    d  ON d.id_dokter  = p.id_dokter
JOIN `spesialis` sp ON sp.id        = d.id_spesialis;

-- View: statistik kunjungan per poli per bulan
CREATE OR REPLACE VIEW `v_statistik_poli_bulanan` AS
SELECT
  po.nama_poli,
  YEAR(p.tgl_pendaftaran)  AS tahun,
  MONTH(p.tgl_pendaftaran) AS bulan,
  COUNT(*)                 AS total_kunjungan,
  SUM(p.status = 'Selesai') AS kunjungan_selesai,
  SUM(p.status = 'Batal')   AS kunjungan_batal
FROM `pendaftaran` p
JOIN `poli` po ON po.id_poli = p.id_poli
GROUP BY po.nama_poli, YEAR(p.tgl_pendaftaran), MONTH(p.tgl_pendaftaran);

-- View: ringkasan dokter (pasien hari ini)
CREATE OR REPLACE VIEW `v_dokter_summary` AS
SELECT
  d.id_dokter,
  d.nama_dokter,
  sp.nama      AS spesialis,
  po.nama_poli,
  d.is_active,
  COUNT(CASE WHEN p.tgl_pendaftaran = CURDATE() THEN 1 END)                          AS pasien_hari_ini,
  COUNT(CASE WHEN p.tgl_pendaftaran = CURDATE() AND p.status = 'Selesai' THEN 1 END) AS selesai_hari_ini,
  COUNT(CASE WHEN p.tgl_pendaftaran = CURDATE() AND p.status = 'Menunggu' THEN 1 END) AS menunggu_hari_ini
FROM `dokter`    d
JOIN `spesialis` sp ON sp.id      = d.id_spesialis
JOIN `poli`      po ON po.id_poli = d.id_poli
LEFT JOIN `pendaftaran` p ON p.id_dokter = d.id_dokter
GROUP BY d.id_dokter;

-- ============================================================
-- 16. STORED PROCEDURES
-- ============================================================

DELIMITER $$

-- Procedure: generate nomor antrian otomatis
CREATE PROCEDURE `sp_get_next_antrian`(
  IN  p_id_poli   INT UNSIGNED,
  IN  p_id_dokter INT UNSIGNED,
  IN  p_tanggal   DATE,
  OUT p_no_antrian SMALLINT
)
BEGIN
  SELECT IFNULL(MAX(no_antrian), 0) + 1
    INTO p_no_antrian
  FROM `pendaftaran`
  WHERE id_poli   = p_id_poli
    AND id_dokter = p_id_dokter
    AND tgl_pendaftaran = p_tanggal
    AND status   != 'Batal';
END$$

-- Procedure: generate kode registrasi
CREATE PROCEDURE `sp_generate_kode_reg`(
  IN  p_tanggal DATE,
  OUT p_kode    VARCHAR(20)
)
BEGIN
  DECLARE v_seq INT;
  SELECT COUNT(*) + 1
    INTO v_seq
  FROM `pendaftaran`
  WHERE tgl_pendaftaran = p_tanggal;
  SET p_kode = CONCAT('REG-', DATE_FORMAT(p_tanggal,'%Y%m%d'), '-', LPAD(v_seq,3,'0'));
END$$

-- Procedure: generate nomor rekam medis
CREATE PROCEDURE `sp_generate_no_rm`(
  OUT p_no_rm VARCHAR(15)
)
BEGIN
  DECLARE v_last INT;
  SELECT IFNULL(
    MAX(CAST(REPLACE(no_rm,'RM-','') AS UNSIGNED)), 0
  ) + 1 INTO v_last FROM `pasien`;
  SET p_no_rm = CONCAT('RM-', LPAD(v_last, 6, '0'));
END$$

DELIMITER ;

-- ============================================================
-- 17. INDEXES tambahan untuk performa query
-- ============================================================
ALTER TABLE `pasien`       ADD INDEX `idx_nama_pasien`  (`nama_pasien`);
ALTER TABLE `pasien`       ADD INDEX `idx_tgl_lahir`    (`tgl_lahir`);
ALTER TABLE `dokter`       ADD INDEX `idx_nama_dokter`  (`nama_dokter`);
ALTER TABLE `dokter`       ADD INDEX `idx_is_active`    (`is_active`);
ALTER TABLE `pendaftaran`  ADD INDEX `idx_no_rm_tgl`    (`no_rm`,`tgl_pendaftaran`);
ALTER TABLE `rekam_medis`  ADD INDEX `idx_rm_pasien`    (`no_rm`);
ALTER TABLE `activity_log` ADD INDEX `idx_al_created`   (`created_at`);

-- ============================================================
-- Selesai — jalankan: mysql -u root -p < hospital_admin_complete.sql
-- ============================================================
COMMIT;