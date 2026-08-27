<?php 
 $page_title = 'Gallery & Evidence | ShajarDost';
include 'includes/header.php'; 
?>

<style>
    /* Gallery CSS */
    .gallery-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        transition: 0.3s;
    }
    .gallery-item:hover .gallery-img {
        transform: scale(1.05);
    }
    .caption {
        font-size: 0.8rem;
        color: #666;
        margin-top: 10px;
        text-align: center;
    }
</style>

<!-- Banner -->
<section style="background: var(--dark); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1>Green Seekers Journey</h1>
        <p>Real evidence of plantation drives. From 2011 to today.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        
        <!-- DYNAMIC GREEN SEEKERS SECTION -->
        <h2 class="section-title" style="margin-bottom: 30px;">Green Seekers Gallery</h2>
        
        <div class="grid-3">
            <?php
            // Yeh line SABHI FILES ko automatically khud dhoond legi
            $files = glob("assets/images/gallery/greenseekers*.webp");
            
            // File count check
            if (count($files) > 0) {
                // Loop har image par
                foreach ($files as $image) {
                    // Sirf file naam nikaalne ke liye
                    $filename = basename($image);
                    ?>
                    
                    <div class="card gallery-item">
                        <!-- Image Load -->
                        <img src="assets/images/gallery/<?php echo $filename; ?>" class="gallery-img" alt="Green Seekers Activity">
                        
                        <div class="card-content">
                            <p class="caption">Plantation Activity</p>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo '<p style="width: 100%; text-align: center; font-size: 2rem; color: #999;">No images found in gallery folder. Please upload files.</p>';
            }
            ?>
        </div>

        <!-- Note: Agar aapko future mein Miyawaki ya Team ki photos alag categories mein dalni hain, 
             to neeche ka pattern use kar lena. Abhi Green Seekers automatic load ho rahi hain. -->

    </div>
</section>

<!-- Manual Section (Example for other categories if needed later) -->
<!--
<section class="section-padding" style="background: #f9f9f9;">
    <div class="container">
        <h2 class="section-title">Miyawaki Forests</h2>
        <div class="grid-3">
             <div class="card gallery-item">
                <img src="assets/images/gallery/miyawaki-001.webp" class="gallery-img" alt="Miyawaki">
                <div class="card-content"><p class="caption">Forest Plantation</p></div>
            </div>
        </div>
    </div>
</section>
-->

<?php include 'includes/footer.php'; ?>