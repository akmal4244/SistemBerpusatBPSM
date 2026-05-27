<?php
// Filename: config/db.php
// Version: 1.1

// --- Konfigurasi Pangkalan Data MySQL ---
// Butiran telah dikemas kini berdasarkan maklumat anda
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'akmalmar_admin'); // Gantikan dengan nama pengguna pangkalan data anda
define('DB_PASSWORD', 'Akmal@4244Asdf'); // Gantikan dengan kata laluan pangkalan data anda
define('DB_NAME', 'akmalmar_dashboardakmal'); // Gantikan dengan nama pangkalan data anda

/**
 * Fungsi untuk menyambung ke pangkalan data MySQL.
 * @return mysqli|false Objek sambungan mysqli jika berjaya, false jika gagal.
 */
function connect_db() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Semak sambungan
    if ($conn->connect_error) {
        // Jangan dedahkan butiran ralat di production
        error_log("Connection failed: " . $conn->connect_error);
        return false;
    }
    
    // Set charset kepada utf8mb4 untuk sokongan emoji dan karakter antarabangsa
    $conn->set_charset("utf8mb4");

    return $conn;
}
?>
