<?php
// ============================================================
// SHAJARDOST.COM — Admin Panel Main Page
// File: admin/index.php
// ============================================================
session_start();
require_once '../includes/db-connect.php';
requireAdmin();

// ---- Handle Actions ----
$msg = '';
$msg_type = '';

// Delete gaon
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM gaon_data WHERE id = ?")->execute([$id]);
    $msg = 'Gaon successfully delete ho gaya.';
    $msg_type = 'success';
}

// Save gaon (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $d = $_POST;
    $fields = [
        'gaon_naam'      => trim($d['gaon_naam'] ?? ''),
        'district'       => trim($d['district'] ?? ''),
        'province'       => trim($d['province'] ?? 'Punjab'),
        'area_acres'     => (float)($d['area_acres'] ?? 0),
        'tree_species'   => $d['tree_species'] ?? 'Eucalyptus',
        'trees_per_acre' => (int)($d['trees_per_acre'] ?? 150),
        'trees_planted'  => (int)($d['trees_planted'] ?? 0),
        'trees_survived' => (int)($d['trees_survived'] ?? 0),
        'lat'            => $d['lat'] !== '' ? (float)$d['lat'] : null,
        'lng'            => $d['lng'] !== '' ? (float)$d['lng'] : null,
        'plantation_date'=> $d['plantation_date'] ?: null,
        'notes'          => trim($d['notes'] ?? ''),
        'added_by'       => $_SESSION['admin_username'] ?? 'admin',
    ];

    if (!$fields['gaon_naam'] || !$fields['area_acres']) {
        $msg = 'Gaon naam aur area zaruri hain.';
        $msg_type = 'error';
    } else {
        if ($d['action'] === 'add') {
            $sql = "INSERT INTO gaon_data (gaon_naam,district,province,area_acres,tree_species,
                    trees_per_acre,trees_planted,trees_survived,lat,lng,plantation_date,notes,added_by)
                    VALUES (:gaon_naam,:district,:province,:area_acres,:tree_species,
                    :trees_per_acre,:trees_planted,:trees_survived,:lat,:lng,:plantation_date,:notes,:added_by)";
            $pdo->prepare($sql)->execute($fields);
            $msg = 'Naya gaon successfully add ho gaya! 🌳';
            $msg_type = 'success';
        } elseif ($d['action'] === 'edit' && isset($d['id'])) {
            $fields['id'] = (int)$d['id'];
            $sql = "UPDATE gaon_data SET gaon_naam=:gaon_naam, district=:district, province=:province,
                    area_acres=:area_acres, tree_species=:tree_species, trees_per_acre=:trees_per_acre,
                    trees_planted=:trees_planted, trees_survived=:trees_survived, lat=:lat, lng=:lng,
                    plantation_date=:plantation_date, notes=:notes WHERE id=:id";
            $pdo->prepare($sql)->execute($fields);
            $msg = 'Gaon successfully update ho gaya! ✓';
            $msg_type = 'success';
        }
    }
}

// ---- Fetch Stats ----
$stats = $pdo->query("SELECT
    COUNT(*) as total_gaon,
    SUM(area_acres) as total_acres,
    SUM(trees_required) as total_required,
    SUM(trees_planted) as total_planted,
    SUM(trees_survived) as total_survived,
    SUM(CASE WHEN (trees_planted/NULLIF(trees_required,0)) >= 0.9 THEN 1 ELSE 0 END) as green_count,
    SUM(CASE WHEN (trees_planted/NULLIF(trees_required,0)) >= 0.6 AND (trees_planted/NULLIF(trees_required,0)) < 0.9 THEN 1 ELSE 0 END) as amber_count,
    SUM(CASE WHEN (trees_planted/NULLIF(trees_required,0)) < 0.6 THEN 1 ELSE 0 END) as red_count
    FROM gaon_data")->fetch();

// ---- Fetch All Gaon ----
$district_filter = $_GET['district'] ?? '';
$search = $_GET['q'] ?? '';

$where = [];
$params = [];
if ($district_filter) { $where[] = "district = ?"; $params[] = $district_filter; }
if ($search) { $where[] = "(gaon_naam LIKE ? OR district LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }

$sql = "SELECT * FROM gaon_data" . ($where ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY district, gaon_naam";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$gaon_list = $stmt->fetchAll();

// Districts for filter
$districts = $pdo->query("SELECT DISTINCT district FROM gaon_data WHERE district IS NOT NULL ORDER BY district")->fetchAll(PDO::FETCH_COLUMN);

// Edit mode?
$edit_gaon = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM gaon_data WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_gaon = $stmt->fetch();
}

$species_options = ['Eucalyptus'=>150,'Shisham'=>100,'Poplar'=>200,'Mango'=>120,'Peepal'=>80,'Other'=>100];
$provinces = ['Punjab','KPK','Sindh','Balochistan','Gilgit-Baltistan','Azad Kashmir'];
?>
<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — ShajarDost Tree Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #2E7D32; --dark: #1B5E20; --secondary: #4CAF50;
            --white: #fff; --gray: #f4f4f4;
            --green: #1D9E75; --amber: #EF9F27; --red: #E24B4A; --unknown: #888780;
        }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f0; color: #333; }

        /* Sidebar */
        .admin-wrap { display: flex; min-height: 100vh; }
        .sidebar {
            width: 240px; background: var(--dark); color: #fff;
            padding: 0; position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-logo {
            padding: 20px; background: rgba(0,0,0,0.2);
            font-weight: 800; font-size: 1.2rem; display: flex;
            align-items: center; gap: 10px;
        }
        .sidebar-logo i { color: var(--secondary); font-size: 1.4rem; }
        .sidebar-logo small { display: block; font-size: 0.7rem; font-weight: 400; opacity: 0.7; }
        .sidebar-nav { padding: 20px 0; flex: 1; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px; color: rgba(255,255,255,0.75);
            font-weight: 500; transition: all 0.2s; font-size: 0.95rem;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.1); color: #fff;
            border-left: 3px solid var(--secondary);
        }
        .sidebar-nav a i { width: 18px; text-align: center; }
        .sidebar-bottom { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar-bottom a { color: rgba(255,255,255,0.6); font-size: 0.85rem; display: flex; align-items: center; gap: 8px; }
        .sidebar-bottom a:hover { color: #fff; }

        /* Main content */
        .main-content { margin-left: 240px; flex: 1; padding: 25px; }

        /* Top bar */
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 25px;
        }
        .topbar h1 { font-size: 1.5rem; color: var(--dark); }
        .topbar .admin-info { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #666; }
        .admin-avatar {
            width: 36px; height: 36px; background: var(--primary); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px; margin-bottom: 25px;
        }
        .stat-card {
            background: #fff; border-radius: 10px; padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); text-align: center;
        }
        .stat-card .stat-num { font-size: 2rem; font-weight: 800; color: var(--primary); }
        .stat-card .stat-label { font-size: 0.8rem; color: #888; margin-top: 4px; }
        .stat-card.green .stat-num { color: var(--green); }
        .stat-card.amber .stat-num { color: var(--amber); }
        .stat-card.red .stat-num { color: var(--red); }

        /* Alert */
        .alert {
            padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px; font-weight: 500;
        }
        .alert.success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert.error   { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

        /* Form Card */
        .form-card {
            background: #fff; border-radius: 12px; padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 25px;
        }
        .form-card h2 {
            font-size: 1.1rem; color: var(--dark); margin-bottom: 20px;
            padding-bottom: 10px; border-bottom: 2px solid #e8f5e9;
            display: flex; align-items: center; gap: 8px;
        }
        .form-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #555; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 12px; border: 1.5px solid #ddd;
            border-radius: 7px; font-size: 0.95rem; font-family: inherit;
            transition: border-color 0.2s; outline: none;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--primary);
        }
        .form-group.full { grid-column: 1 / -1; }
        .form-actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn { padding: 10px 22px; border-radius: 7px; font-weight: 600; cursor: pointer; border: none; font-size: 0.95rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--dark); }
        .btn-secondary { background: #eee; color: #555; }
        .btn-secondary:hover { background: #ddd; }
        .btn-danger { background: #ffebee; color: var(--red); }
        .btn-danger:hover { background: #ffcdd2; }
        .btn-sm { padding: 6px 12px; font-size: 0.82rem; }
        .btn-satellite { background: #1565C0; color: #fff; }
        .btn-satellite:hover { background: #0d47a1; }

        /* Gaon Table */
        .table-card {
            background: #fff; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        }
        .table-header {
            padding: 18px 20px; border-bottom: 1px solid #eee;
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .table-header h2 { font-size: 1.05rem; color: var(--dark); flex: 1; display: flex; align-items: center; gap: 8px; }
        .search-box {
            position: relative; display: flex; align-items: center;
        }
        .search-box i { position: absolute; left: 10px; color: #aaa; }
        .search-box input {
            padding: 8px 10px 8px 32px; border: 1.5px solid #ddd; border-radius: 7px;
            font-size: 0.9rem; width: 200px; outline: none;
        }
        .search-box input:focus { border-color: var(--primary); }
        .filter-select {
            padding: 8px 12px; border: 1.5px solid #ddd; border-radius: 7px;
            font-size: 0.9rem; outline: none; cursor: pointer;
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th {
            background: #f8fdf8; color: var(--dark); font-weight: 700;
            padding: 12px 14px; text-align: left; border-bottom: 2px solid #e8f5e9;
            white-space: nowrap;
        }
        td { padding: 11px 14px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
        tr:hover td { background: #fafff8; }
        .gaon-name { font-weight: 600; color: var(--dark); }
        .district-badge {
            display: inline-block; background: #e8f5e9; color: var(--primary);
            padding: 2px 8px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
        }
        .status-green  { background: #e8f5e9; color: #1D9E75; }
        .status-amber  { background: #fff8e1; color: #EF9F27; }
        .status-red    { background: #ffebee; color: #E24B4A; }
        .status-unknown{ background: #f5f5f5; color: #888; }

        /* Progress bar */
        .progress-wrap { display: flex; align-items: center; gap: 8px; min-width: 130px; }
        .progress-bar-bg {
            flex: 1; height: 7px; background: #eee; border-radius: 10px; overflow: hidden;
        }
        .progress-bar-fill { height: 100%; border-radius: 10px; transition: width 0.5s; }
        .progress-pct { font-size: 0.82rem; font-weight: 700; white-space: nowrap; }

        /* NDVI */
        .ndvi-score {
            font-weight: 700; font-size: 0.9rem;
        }
        .ndvi-dense   { color: #1B5E20; }
        .ndvi-moderate{ color: #4CAF50; }
        .ndvi-sparse  { color: #FF9800; }
        .ndvi-bare    { color: #f44336; }
        .ndvi-na      { color: #aaa; }

        /* Actions */
        .action-btns { display: flex; gap: 5px; }

        /* Table responsive */
        .table-wrap { overflow-x: auto; }

        /* Satellite refresh status */
        .refresh-result {
            display: none; font-size: 0.82rem; margin-top: 4px;
            padding: 4px 8px; border-radius: 4px;
        }

        /* Pagination / count info */
        .table-footer {
            padding: 12px 20px; color: #888; font-size: 0.85rem;
            border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 15px; }
            .admin-wrap { flex-direction: column; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="admin-wrap">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-tree"></i>
            <div>ShajarDost <small>Admin Panel</small></div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="index.php?form=add"><i class="fas fa-plus-circle"></i> Gaon Add Karo</a>
            <a href="../map.php" target="_blank"><i class="fas fa-map-marked-alt"></i> Public Map</a>
            <a href="../dashboard.php" target="_blank"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="../index.php" target="_blank"><i class="fas fa-home"></i> Website</a>
        </nav>
        <div class="sidebar-bottom">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?>)</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <h1><i class="fas fa-seedling" style="color:var(--secondary)"></i> Tree Plantation Dashboard</h1>
            <div class="admin-info">
                <span>Assalam o Alaikum, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
            </div>
        </div>

        <!-- Alert Message -->
        <?php if ($msg): ?>
        <div class="alert <?= $msg_type ?>">
            <i class="fas fa-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num"><?= number_format($stats['total_gaon']) ?></div>
                <div class="stat-label">🏘️ Total Gaon</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= number_format($stats['total_acres'], 1) ?></div>
                <div class="stat-label">🌾 Total Acres</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= number_format($stats['total_planted']) ?></div>
                <div class="stat-label">🌱 Trees Planted</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= number_format($stats['total_survived']) ?></div>
                <div class="stat-label">🌳 Trees Survived</div>
            </div>
            <div class="stat-card green">
                <div class="stat-num"><?= $stats['green_count'] ?></div>
                <div class="stat-label">✓ Target Met</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-num"><?= $stats['amber_count'] ?></div>
                <div class="stat-label">⚠ Partial</div>
            </div>
            <div class="stat-card red">
                <div class="stat-num"><?= $stats['red_count'] ?></div>
                <div class="stat-label">✗ Shortfall</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">
                    <?php
                    $pct = $stats['total_required'] > 0
                        ? round(($stats['total_planted'] / $stats['total_required']) * 100, 1)
                        : 0;
                    echo $pct . '%';
                    ?>
                </div>
                <div class="stat-label">📊 Overall Progress</div>
            </div>
        </div>

        <!-- Add / Edit Form -->
        <?php $show_form = isset($_GET['form']) || $edit_gaon; ?>
        <?php if (!$show_form): ?>
        <div style="margin-bottom: 15px;">
            <a href="?form=add" class="btn btn-primary"><i class="fas fa-plus"></i> Naya Gaon Add Karo</a>
        </div>
        <?php endif; ?>

        <?php if ($show_form): ?>
        <div class="form-card">
            <h2>
                <i class="fas fa-<?= $edit_gaon ? 'edit' : 'plus-circle' ?>"></i>
                <?= $edit_gaon ? 'Gaon Edit Karo — ' . htmlspecialchars($edit_gaon['gaon_naam']) : 'Naya Gaon Add Karo' ?>
            </h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit_gaon ? 'edit' : 'add' ?>">
                <?php if ($edit_gaon): ?>
                <input type="hidden" name="id" value="<?= $edit_gaon['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Gaon Naam *</label>
                        <input type="text" name="gaon_naam" required placeholder="Chak 47"
                               value="<?= htmlspecialchars($edit_gaon['gaon_naam'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> District *</label>
                        <input type="text" name="district" placeholder="Faisalabad"
                               value="<?= htmlspecialchars($edit_gaon['district'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-flag"></i> Province</label>
                        <select name="province">
                            <?php foreach ($provinces as $p): ?>
                            <option value="<?= $p ?>" <?= ($edit_gaon['province'] ?? 'Punjab') === $p ? 'selected' : '' ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-ruler-combined"></i> Area (Acres) *</label>
                        <input type="number" step="0.01" name="area_acres" required placeholder="5.0"
                               value="<?= $edit_gaon['area_acres'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tree"></i> Tree Species</label>
                        <select name="tree_species" id="species_select">
                            <?php foreach ($species_options as $sp => $tpa): ?>
                            <option value="<?= $sp ?>" data-tpa="<?= $tpa ?>"
                                <?= ($edit_gaon['tree_species'] ?? 'Eucalyptus') === $sp ? 'selected' : '' ?>>
                                <?= $sp ?> (<?= $tpa ?>/acre)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-sort-numeric-up"></i> Trees per Acre</label>
                        <input type="number" name="trees_per_acre" id="tpa_input" placeholder="150"
                               value="<?= $edit_gaon['trees_per_acre'] ?? 150 ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-seedling"></i> Trees Planted</label>
                        <input type="number" name="trees_planted" placeholder="0"
                               value="<?= $edit_gaon['trees_planted'] ?? 0 ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check-circle"></i> Trees Survived</label>
                        <input type="number" name="trees_survived" placeholder="0"
                               value="<?= $edit_gaon['trees_survived'] ?? 0 ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-globe"></i> Latitude (Google Maps se)</label>
                        <input type="text" name="lat" placeholder="31.4504"
                               value="<?= $edit_gaon['lat'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-globe"></i> Longitude (Google Maps se)</label>
                        <input type="text" name="lng" placeholder="73.1350"
                               value="<?= $edit_gaon['lng'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Plantation Date</label>
                        <input type="date" name="plantation_date"
                               value="<?= $edit_gaon['plantation_date'] ?? '' ?>">
                    </div>
                    <div class="form-group full">
                        <label><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea name="notes" rows="2" placeholder="Koi zaruri notes..."><?= htmlspecialchars($edit_gaon['notes'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <?= $edit_gaon ? 'Update Karo' : 'Save Karo' ?>
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Gaon List Table -->
        <div class="table-card">
            <div class="table-header">
                <h2><i class="fas fa-list"></i> Tamam Gaon (<?= count($gaon_list) ?>)</h2>

                <!-- Search & Filter -->
                <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" placeholder="Gaon ya district..."
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="district" class="filter-select">
                        <option value="">Tamam Districts</option>
                        <?php foreach ($districts as $d): ?>
                        <option value="<?= $d ?>" <?= $district_filter === $d ? 'selected' : '' ?>><?= $d ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="index.php" class="btn btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gaon Naam</th>
                            <th>District</th>
                            <th>Acres</th>
                            <th>Species</th>
                            <th>Required</th>
                            <th>Planted</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>NDVI</th>
                            <th>Satellite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($gaon_list as $g):
                        $status = getGaonStatus($g['trees_planted'], $g['trees_required']);
                        $pct = getPlantedPercent($g['trees_planted'], $g['trees_required']);
                        $bar_color = match($status) { 'green' => '#1D9E75', 'amber' => '#EF9F27', 'red' => '#E24B4A', default => '#ccc' };
                        $ndvi_class = 'ndvi-na';
                        if ($g['ndvi_score'] !== null) {
                            if ($g['ndvi_score'] >= 0.5) $ndvi_class = 'ndvi-dense';
                            elseif ($g['ndvi_score'] >= 0.3) $ndvi_class = 'ndvi-moderate';
                            elseif ($g['ndvi_score'] >= 0.1) $ndvi_class = 'ndvi-sparse';
                            else $ndvi_class = 'ndvi-bare';
                        }
                    ?>
                    <tr>
                        <td><?= $g['id'] ?></td>
                        <td><span class="gaon-name"><?= htmlspecialchars($g['gaon_naam']) ?></span></td>
                        <td><span class="district-badge"><?= htmlspecialchars($g['district'] ?? '—') ?></span></td>
                        <td><?= number_format($g['area_acres'], 1) ?></td>
                        <td><?= htmlspecialchars($g['tree_species']) ?></td>
                        <td><?= number_format($g['trees_required']) ?></td>
                        <td><?= number_format($g['trees_planted']) ?></td>
                        <td>
                            <div class="progress-wrap">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width:<?= $pct ?>%;background:<?= $bar_color ?>"></div>
                                </div>
                                <span class="progress-pct" style="color:<?= $bar_color ?>"><?= $pct ?>%</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $status ?>">
                                <?= getStatusLabel($status) ?>
                            </span>
                        </td>
                        <td>
                            <span class="ndvi-score <?= $ndvi_class ?>">
                                <?= $g['ndvi_score'] !== null ? number_format($g['ndvi_score'], 3) : '—' ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-satellite btn-sm" onclick="refreshNDVI(<?= $g['id'] ?>, this)">
                                <i class="fas fa-satellite"></i>
                            </button>
                            <div class="refresh-result" id="ndvi-result-<?= $g['id'] ?>"></div>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="?edit=<?= $g['id'] ?>" class="btn btn-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?= $g['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('<?= htmlspecialchars($g['gaon_naam']) ?> delete karna chahte hain?')"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($gaon_list)): ?>
                    <tr><td colspan="12" style="text-align:center;color:#aaa;padding:40px;">Koi gaon nahi mila. Upar se add karo.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <span><?= count($gaon_list) ?> gaon dikha rahe hain</span>
                <a href="../map.php" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-map"></i> Public Map Dekho</a>
            </div>
        </div>

    </div><!-- /.main-content -->
</div><!-- /.admin-wrap -->

<script>
// Auto-fill trees_per_acre when species changes
document.getElementById('species_select')?.addEventListener('change', function() {
    const tpa = this.options[this.selectedIndex].dataset.tpa;
    if (tpa) document.getElementById('tpa_input').value = tpa;
});

// Satellite NDVI refresh via AJAX
function refreshNDVI(gaonId, btn) {
    const resultDiv = document.getElementById('ndvi-result-' + gaonId);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    resultDiv.style.display = 'none';

    fetch('../api/update_ndvi.php?gaon_id=' + gaonId)
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-satellite"></i>';
            if (data.success) {
                resultDiv.style.display = 'block';
                resultDiv.style.background = '#e8f5e9';
                resultDiv.style.color = '#2e7d32';
                resultDiv.textContent = 'NDVI: ' + data.ndvi_score + ' — ' + data.ndvi_health;
                // Reload after 2 seconds to show new values
                setTimeout(() => location.reload(), 1500);
            } else {
                resultDiv.style.display = 'block';
                resultDiv.style.background = '#ffebee';
                resultDiv.style.color = '#c62828';
                resultDiv.textContent = 'Error: ' + (data.error || 'Failed');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-satellite"></i>';
            resultDiv.style.display = 'block';
            resultDiv.textContent = 'Connection error';
        });
}
</script>
</body>
</html>
