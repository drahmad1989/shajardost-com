<?php
// ============================================================
// SHAJARDOST.COM — Database Configuration
// File: includes/db-connect.php
// IMPORTANT: Is file ko public_html ke bahar rakho agar possible ho
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');   // <-- Apna database naam daalo
define('DB_USER', 'your_db_username');      // <-- Apna DB username daalo
define('DB_PASS', 'your_db_password');      // <-- Apna DB password daalo
define('DB_CHARSET', 'utf8mb4');

// Admin panel password (simple single password for admin login)
define('ADMIN_PASSWORD', 'shajardost2024'); // <-- Yeh zaroor change karo!
define('ADMIN_SESSION_KEY', 'shajardost_admin_logged_in');

// Site URL
define('SITE_URL', 'https://shajardost.com');

// ============================================================
// DATABASE CONNECTION (PDO)
// ============================================================
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Production mein error mat dikhao
    die(json_encode(['error' => 'Database connection failed. Please check config.']));
}

// ============================================================
// HELPER FUNCTIONS
// ============================================================

/**
 * Gaon ka status calculate karo
 */
function getGaonStatus($planted, $required) {
    if ($required == 0 || $required === null) return 'unknown';
    $pct = ($planted / $required) * 100;
    if ($pct >= 90) return 'green';   // Target met ✓
    if ($pct >= 60) return 'amber';   // Partial ⚠
    return 'red';                      // Shortfall ✗
}

/**
 * NDVI score se health label
 */
function getNDVIHealth($ndvi) {
    if ($ndvi === null) return 'Not checked yet';
    if ($ndvi >= 0.5) return 'Dense Vegetation';
    if ($ndvi >= 0.3) return 'Moderate Vegetation';
    if ($ndvi >= 0.1) return 'Sparse Vegetation';
    return 'Bare Soil / No Trees';
}

/**
 * Status ka Urdu/label
 */
function getStatusLabel($status) {
    $labels = [
        'green'   => '✓ Target Met',
        'amber'   => '⚠ Partial',
        'red'     => '✗ Shortfall',
        'unknown' => '? No Data'
    ];
    return $labels[$status] ?? '? Unknown';
}

/**
 * Percentage calculate karo
 */
function getPlantedPercent($planted, $required) {
    if (!$required) return 0;
    return min(100, round(($planted / $required) * 100, 1));
}

/**
 * Admin check karo
 */
function isAdminLoggedIn() {
    return isset($_SESSION[ADMIN_SESSION_KEY]) && $_SESSION[ADMIN_SESSION_KEY] === true;
}

/**
 * Admin redirect if not logged in
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
?>
