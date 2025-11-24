# Troubleshooting Guide

## ⚠️ Masalah: OpenSSL Compatibility Error

### Error Message
```bash
php: /lib/x86_64-linux-gnu/libcrypto.so.1.1: version `OPENSSL_1_1_1' not found (required by php)
```

### 📋 Penyebab
- PHP yang terinstall via Oryx (`/home/codespace/.php/current/bin/php`) dikompilasi dengan OpenSSL 1.1.1
- Ubuntu 24.04 LTS menggunakan OpenSSL 3.0
- Ketidakcocokan versi library menyebabkan PHP tidak dapat dijalankan

### ✅ Solusi

#### Langkah 1: Identifikasi PHP yang Bermasalah
```bash
which php
# Output: /home/codespace/.php/current/bin/php (versi Oryx yang bermasalah)
```

#### Langkah 2: Install/Reinstall PHP 8.3 Sistem
```bash
# Update package list
sudo apt update

# Reinstall PHP 8.3 dengan OpenSSL 3.0 compatibility
sudo apt reinstall php8.3-cli php8.3-common -y
```

#### Langkah 3: Update PATH (Sementara)
```bash
# Prioritaskan /usr/bin di PATH untuk menggunakan PHP sistem
export PATH="/usr/bin:$PATH"

# Verifikasi PHP berjalan normal
php -v
```

**Output yang diharapkan:**
```
PHP 8.3.28 (cli) (built: Nov 20 2025 11:56:43) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.3.28, Copyright (c) Zend Technologies
    with Zend OPcache v8.3.28, Copyright (c), by Zend Technologies
```

#### Langkah 4: Buat Perubahan Permanen
```bash
# Tambahkan ke .bashrc
echo '' >> ~/.bashrc
echo '# Prioritize system PHP 8.3' >> ~/.bashrc
echo 'export PATH="/usr/bin:$PATH"' >> ~/.bashrc

# Reload konfigurasi
source ~/.bashrc
```

### 🚀 Quick Fix (One-Liner)
```bash
export PATH="/usr/bin:$PATH" && echo 'export PATH="/usr/bin:$PATH"' >> ~/.bashrc && php -v
```

### 🔍 Verifikasi Instalasi
```bash
# Cek versi PHP
php -v

# Cek lokasi PHP yang digunakan
which php
# Output: /usr/bin/php

# Cek library OpenSSL yang digunakan
ldd /usr/bin/php8.3 | grep -i ssl
# Output: libssl.so.3 => /lib/x86_64-linux-gnu/libssl.so.3

# Test Composer
composer --version

# Test Laravel Artisan
php artisan --version
```

### 📝 Catatan Penting
- Solusi ini memprioritaskan PHP sistem (Ubuntu) yang sudah kompatibel dengan OpenSSL 3.0
- PHP Oryx tetap ada di sistem tetapi tidak akan digunakan secara default
- Semua project Laravel akan menggunakan PHP 8.3 dengan OpenSSL 3.0

### 🔧 Alternatif Solusi
Jika masih mengalami masalah, coba:

```bash
# Hapus PHP cache
php artisan config:clear
php artisan cache:clear

# Install ulang dependencies
composer install

# Restart terminal atau reload shell
exec bash
```

### 📚 Referensi
- Ubuntu 24.04 menggunakan OpenSSL 3.0.13
- PHP 8.3 mendukung OpenSSL 3.0+
- Oryx buildpack menggunakan OpenSSL 1.1 legacy
