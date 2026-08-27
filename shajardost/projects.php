<?php
$page_title = 'Our Projects - Tree Plantation Projects Pakistan | ShajarDost';
$page_description = 'Explore ShajarDost tree plantation projects across Pakistan. From plantation drives to national records, see what we have achieved for the environment.';
include 'includes/header.php';
include 'includes/db-connect.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Projects</li></ol></div></nav>

<section class="hero-banner dark-bg">
    <div class="container"><h1>Our Projects</h1><p>From plantation drives to national records, here is what we have achieved for Pakistan's environment.</p></div>
</section>

<section class="section-padding">
    <div class="container">
        <?php
        $sql = "SELECT * FROM projects WHERE status='active' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            while($row = mysqli_fetch_assoc($result)) {
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=300&h=200&fit=crop';
                ?>
                <div class="card fade-in">
                    <div class="card-img"><img src="<?php echo $imgPath; ?>" alt="<?php echo $row['title']; ?>"></div>
                    <div class="card-content">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo substr($row['description'], 0, 120); ?>...</p>
                        <a href="single-project.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-block">View Details</a>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="text-center" style="padding:60px 0;"><i class="fas fa-project-diagram" style="font-size:4rem;color:#ddd;margin-bottom:20px;"></i><h3 style="color:#999;">Projects will be displayed here.</h3><p>Stay tuned for exciting environmental projects!</p></div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
