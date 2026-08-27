<?php
http_response_code(404);
$page_title = 'Page Not Found - 404 | ShajarDost';
$page_description = 'The page you are looking for could not be found. Explore ShajarDost tree planting programs, donate a tree, or learn about our green initiatives in Pakistan.';
include 'includes/header.php';
?>

<section style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:80px 20px;">
    <div>
        <div style="font-size:8rem;font-weight:900;color:var(--primary);line-height:1;margin-bottom:10px;">404</div>
        <h1 style="font-size:2rem;color:var(--dark);margin-bottom:15px;">Page Not Found</h1>
        <p style="font-size:1.1rem;color:var(--text-light);max-width:500px;margin:0 auto 30px auto;">The page you are looking for might have been moved, deleted, or never existed. Let us help you find what you need.</p>
        <div style="display:flex;gap:15px;justify-content:center;flex-wrap:wrap;">
            <a href="index.php" class="btn btn-primary btn-lg"><i class="fas fa-home"></i> Go Home</a>
            <a href="contact.php" class="btn btn-secondary btn-lg"><i class="fas fa-envelope"></i> Contact Us</a>
            <a href="donate.php" class="btn btn-gold btn-lg"><i class="fas fa-seedling"></i> Donate a Tree</a>
        </div>
        <div style="margin-top:40px;padding:20px;background:#f0fdf4;border-radius:8px;max-width:500px;margin-left:auto;margin-right:auto;">
            <p style="margin-bottom:10px;font-weight:600;color:var(--dark);">Popular Pages:</p>
            <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
                <a href="about.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">About Us</a>
                <a href="mission-fifty.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">Mission 50</a>
                <a href="green-scouts.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">Green Scouts</a>
                <a href="miyawaki-forest.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">Miyawaki Forest</a>
                <a href="gallery.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">Gallery</a>
                <a href="blog.php" style="background:white;padding:6px 14px;border-radius:20px;font-size:0.85rem;color:var(--primary);border:1px solid var(--primary);">Blog</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
