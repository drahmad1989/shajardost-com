<?php 
include 'includes/db-connect.php';

// Check if ID is passed in URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $page_title = $row['title'] . ' | ShajarDost';
        include 'includes/header.php';
    } else {
        echo "Article not found.";
        exit();
    }
} else {
    header("Location: blog.php"); // Redirect if no ID
    exit();
}
?>

<!-- Single Article Styling -->
<section class="section-padding" style="background: #f9f9f9;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            
            <!-- Back Button -->
            <a href="blog.php" style="color: var(--primary); font-weight: bold; margin-bottom: 20px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Back to Journal
            </a>

            <!-- Category & Date -->
            <div style="margin-bottom: 15px; font-size: 0.9rem; color: #888;">
                <span style="background: #e8f5e9; color: var(--primary); padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                    <?php echo $row['category']; ?>
                </span>
                <span style="margin-left: 15px;">
                    <i class="far fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($row['created_at'])); ?>
                </span>
            </div>

            <!-- Title -->
            <h1 style="font-size: 2.5rem; color: var(--dark); margin-bottom: 20px;">
                <?php echo $row['title']; ?>
            </h1>

            <!-- Image -->
            <?php if(!empty($row['image'])): ?>
                <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" style="width: 100%; height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 30px;">
            <?php endif; ?>

            <!-- Content -->
            <div style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                <?php echo nl2br($row['content']); ?>
            </div>

            <!-- Share Section (Visual only) -->
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
                <p style="font-weight: bold;">Share this article:</p>
                <div class="social-icons-footer" style="justify-content: flex-start; margin-top: 10px;">
                    <a href="https://www.facebook.com/ShajarDostcom/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.facebook.com/ShajarDostcom/" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>