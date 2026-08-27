<?php
$page_title = 'Green Journal - Environmental News & Articles | ShajarDost';
$page_description = 'Read educational articles, news, and stories about Pakistan\'s environment, tree plantation, climate change, and green initiatives on ShajarDost Green Journal.';
include 'includes/header.php';
include 'includes/db-connect.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Green Journal</li></ol></div></nav>

<section class="hero-banner secondary-bg">
    <div class="container"><h1>The Green Journal</h1><p>Educational articles, news, and stories about Pakistan's environment and our green initiatives.</p></div>
</section>

<section class="section-padding">
    <div class="container">
        <?php
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            while($row = mysqli_fetch_assoc($result)) {
                $date = date("M j, Y", strtotime($row['created_at']));
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=300&h=200&fit=crop';
                ?>
                <div class="card blog-card fade-in">
                    <div class="card-img"><img src="<?php echo $imgPath; ?>" alt="<?php echo $row['title']; ?>"></div>
                    <div class="card-content">
                        <div class="blog-meta">
                            <span class="blog-category"><?php echo $row['category']; ?></span>
                            <span><i class="far fa-calendar-alt"></i> <?php echo $date; ?></span>
                        </div>
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo substr(strip_tags($row['content']), 0, 130); ?>...</p>
                        <a href="single-blog.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-block">Read Article</a>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="text-center" style="padding:60px 0;"><i class="fas fa-newspaper" style="font-size:4rem;color:#ddd;margin-bottom:20px;"></i><h3 style="color:#999;">No articles published yet.</h3><p>We are writing some great content for you. Check back soon!</p></div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
