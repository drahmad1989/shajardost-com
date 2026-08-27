<?php 
 $page_title = 'Image Checker | ShajarDost';
include 'includes/header.php'; 
?>

<section style="padding: 50px 0;">
    <div class="container">
        <h1 style="text-align: center;">Image File Checker Tool</h1>
        <p style="text-align: center; margin-bottom: 30px;">
            Yahan dikhai dega ke aapke folder mein files ka naam kya hai. Copy karke Index page mein lagayen.
        </p>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h3 style="color: var(--primary);">Files in 'assets/images/gallery/' folder:</h3>
            <ul style="font-family: monospace; font-size: 1.1rem; background: #f4f4f4; padding: 20px; border-radius: 5px;">
                <?php
                // Yeh list nikal ke dikhayega
                $files = glob("assets/images/gallery/*.webp");
                
                if ($files) {
                    // Sort files ke liye
                    sort($files);
                    
                    foreach ($files as $file) {
                        echo "<li style='padding: 5px; border-bottom: 1px dashed #ddd;'><strong>$file</strong></li>";
                    }
                } else {
                    echo "<li>No files found. Make sure folder path is correct.</li>";
                }
                ?>
            </ul>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <p><strong>Instruction:</strong></p>
            <p>Upar diye gaye naam ko copy karein.</p>
            <p>Phir <strong>index.php</strong> mein jahan likha hai `greenseekers(1).webp`, usay apka exact filename se replace karein.</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>