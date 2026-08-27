<?php
include 'includes/db-connect.php';
if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM projects WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $page_title = $row['title'] . ' | ShajarDost Project';
        $page_description = substr(strip_tags($row['description']), 0, 160);
        include 'includes/header.php';
    } else { header("Location: projects.php"); exit(); }
} else { header("Location: projects.php"); exit(); }
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li><a href="projects.php">Projects</a></li><li><?php echo $row['title']; ?></li></ol></div></nav>

<section class="section-padding">
    <div class="container">
        <div class="card" style="padding:30px;">
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:30px;flex-wrap:wrap;">
                <div style="min-width:280px;">
                    <h1 style="font-size:2.5rem;color:var(--dark);margin-bottom:15px;"><?php echo $row['title']; ?></h1>
                    <?php if(!empty($row['image'])): ?>
                        <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" style="width:100%;border-radius:8px;margin-bottom:30px;">
                    <?php endif; ?>
                    <h3 style="color:var(--primary);">About the Project</h3>
                    <p style="font-size:1.1rem;line-height:1.8;color:#555;"><?php echo nl2br($row['description']); ?></p>
                </div>
                <div style="background:#f9f9f9;padding:20px;border-radius:8px;height:fit-content;">
                    <h3 style="margin-bottom:20px;">Project Info</h3>
                    <div style="margin-bottom:15px;"><strong>Status:</strong> <span style="background:#e8f5e9;color:var(--primary);padding:2px 8px;border-radius:4px;"><?php echo ucfirst($row['status']); ?></span></div>
                    <div style="margin-bottom:15px;"><strong>Started On:</strong><br><?php echo date("F j, Y", strtotime($row['created_at'])); ?></div>
                    <div style="margin-bottom:15px;"><strong>Support Us:</strong><p style="font-size:0.9rem;color:#666;margin-bottom:10px;">Want to contribute to this project?</p><a href="donate.php" class="btn btn-primary btn-block">Donate for this Project</a></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
