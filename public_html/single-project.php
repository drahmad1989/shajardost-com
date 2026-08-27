<?php 
include 'includes/db-connect.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM projects WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $page_title = $row['title'] . ' | ShajarDost';
        include 'includes/header.php';
    } else {
        echo "Project not found.";
        exit();
    }
} else {
    header("Location: projects.php");
    exit();
}
?>

<section class="section-padding">
    <div class="container">
        
        <a href="projects.php" style="color: var(--primary); font-weight: bold; margin-bottom: 20px; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Projects
        </a>

        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            
            <div class="grid-3" style="grid-template-columns: 2fr 1fr; margin-bottom: 0; gap: 0;">
                
                <!-- Main Content -->
                <div>
                    <h1 style="font-size: 2.5rem; color: var(--dark); margin-bottom: 15px;">
                        <?php echo $row['title']; ?>
                    </h1>
                    
                    <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 30px;">

                    <h3 style="color: var(--primary);">About the Project</h3>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                        <?php echo nl2br($row['description']); ?>
                    </p>

                    <div style="margin-top: 40px;">
                        <h3>Impact & Result</h3>
                        <p>This project has been instrumental in our goal to make Pakistan green.</p>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; height: fit-content;">
                    <h3 style="margin-bottom: 20px;">Project Info</h3>
                    
                    <div style="margin-bottom: 15px;">
                        <strong>Status:</strong>
                        <span style="background: #e8f5e9; color: var(--primary); padding: 2px 8px; border-radius: 4px;">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <strong>Started On:</strong><br>
                        <?php echo date("F j, Y", strtotime($row['created_at'])); ?>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <strong>Support Us:</strong>
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">Want to contribute to this specific project?</p>
                        <a href="donate.php" class="btn btn-primary" style="width: 100%; font-size: 0.9rem;">Donate for this Project</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>