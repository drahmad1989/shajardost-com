<?php
$page_title = 'Gallery & Evidence - Tree Plantation Photos | ShajarDost';
$page_description = 'See real evidence of ShajarDost tree plantation drives across Pakistan. 433+ photos of Green Seekers activities, Miyawaki forests, and training sessions since 2011.';
include 'includes/header.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Gallery</li></ol></div></nav>

<section class="hero-banner dark-bg">
    <div class="container"><h1>Green Seekers Journey</h1><p>Real evidence of plantation drives. From 2011 to today. We don't just talk, we do.</p></div>
</section>

<section class="section-padding">
    <div class="container">
        <h2 class="section-title" style="margin-bottom:30px;">Green Seekers Gallery</h2>
        <div class="gallery-grid">
            <?php
            $files = glob("assets/images/gallery/greenseekers*.webp");
            if (count($files) > 0) {
                foreach ($files as $image) {
                    $filename = basename($image);
                    ?>
                    <div class="gallery-item fade-in">
                        <img src="assets/images/gallery/<?php echo $filename; ?>" alt="Green Seekers Plantation Activity - ShajarDost Pakistan" loading="lazy">
                        <div class="gallery-overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <?php
                }
            } else {
                echo '<p style="width:100%;text-align:center;font-size:2rem;color:#999;">No images found in gallery folder.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
