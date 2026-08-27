<?php
include 'includes/db-connect.php';
if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $page_title = $row['title'] . ' | ShajarDost Blog';
        $page_description = substr(strip_tags($row['content']), 0, 160);
        include 'includes/header.php';
    } else { header("Location: blog.php"); exit(); }
} else { header("Location: blog.php"); exit(); }
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li><a href="blog.php">Green Journal</a></li><li><?php echo $row['title']; ?></li></ol></div></nav>

<section class="section-padding">
    <div class="container">
        <div class="seo-content">
            <span class="card-badge"><?php echo $row['category']; ?></span>
            <h1 style="margin-top:10px;"><?php echo $row['title']; ?></h1>
            <p style="color:var(--text-lighter);margin-bottom:30px;"><i class="far fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($row['created_at'])); ?></p>
            <?php if(!empty($row['image'])): ?>
                <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" style="border-radius:8px;margin-bottom:30px;">
            <?php endif; ?>
            <div style="line-height:1.9;color:var(--text-light);font-size:1.05rem;"><?php echo nl2br($row['content']); ?></div>
        </div>
        <div class="text-center" style="margin-top:40px;">
            <a href="blog.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Journal</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
