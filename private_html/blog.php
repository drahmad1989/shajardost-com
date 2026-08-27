<?php 
 $page_title = 'The Green Journal | ShajarDost';
include 'includes/header.php'; 
include 'includes/db-connect.php'; 
?>

<!-- Journal Banner -->
<section style="background: var(--secondary); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1>The Green Journal</h1>
        <p>Educational articles, news, and stories about Pakistan's environment.</p>
    </div>
</section>

<!-- Blog Content -->
<section class="section-padding">
    <div class="container">
        
        <?php
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            
            while($row = mysqli_fetch_assoc($result)) {
                // Format Date
                $date = date("M j, Y", strtotime($row['created_at']));
                
                // Image Fallback
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://picsum.photos/seed/blog/300/200';
                ?>
                
                <!-- Blog Card Design -->
                <div class="card">
                    <div class="card-img">
                        <img src="<?php echo $imgPath; ?>" alt="<?php echo $row['title']; ?>">
                    </div>
                    <div class="card-content">
                        <!-- Meta Info: Date & Category -->
                        <div style="margin-bottom: 12px; font-size: 0.8rem; color: #777;">
                            <span style="background: #e8f5e9; color: var(--primary); padding: 2px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase;">
                                <?php echo $row['category']; ?>
                            </span>
                            <span style="margin-left: 10px;"><i class="far fa-calendar-alt"></i> <?php echo $date; ?></span>
                        </div>
                        
                        <h3><?php echo $row['title']; ?></h3>
                        
                        <p style="color: #555; margin-bottom: 15px;">
                            <?php echo substr(strip_tags($row['content']), 0, 130); ?>...
                        </p>
                        
                        <!-- UPDATED LINK HERE -->
                        <a href="single-blog.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="width: 100%; margin-top: 15px;">Read Article</a>
                    </div>
                </div>

                <?php
            }
            echo '</div>'; // End grid
        } else {
            // Empty State
            echo '
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-newspaper" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #999;">No articles published yet.</h3>
                <p>We are writing some great content for you. Check back soon!</p>
            </div>
            ';
        }
        ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>