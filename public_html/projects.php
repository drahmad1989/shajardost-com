<?php 
 $page_title = 'Our Projects | ShajarDost';
include 'includes/header.php'; 
include 'includes/db-connect.php'; 
?>

<section style="background: var(--dark); color: white; padding: 60px 0; text-align: center;">
    <div class="container">
        <h1>Our Projects</h1>
        <p>From plantation drives to national records, here is what we have achieved.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        
        <?php
        $sql = "SELECT * FROM projects WHERE status='active' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            
            while($row = mysqli_fetch_assoc($result)) {
                // Image Fallback
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://picsum.photos/seed/project/300/200';
                ?>
                
                <div class="card">
                    <div class="card-img">
                        <img src="<?php echo $imgPath; ?>" alt="<?php echo $row['title']; ?>">
                    </div>
                    <div class="card-content">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo substr($row['description'], 0, 100); ?>...</p>
                        
                        <!-- UPDATED LINK HERE -->
                        <a href="single-project.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="width: 100%; margin-top: 15px;">View Details</a>
                    </div>
                </div>

                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="text-center"><h3>No projects found.</h3></div>';
        }
        ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>