<?php 
 $page_title = 'Our Teams | ShajarDost';
include 'includes/header.php'; 
include 'includes/db-connect.php'; // Database connection
?>

<section style="background: var(--primary); color: white; padding: 60px 0; text-align: center;">
    <div class="container">
        <h1>Our Teams - The Green Warriors</h1>
        <p>Meet the heroes who are making a difference on the ground.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        
        <?php
        $sql = "SELECT * FROM teams ORDER BY trees_planted DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            
            while($row = mysqli_fetch_assoc($result)) {
                // Image fallback agar image set nahi hai
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://picsum.photos/seed/team/300/200';
                ?>
                
                <div class="card">
                    <div class="card-img">
                        <img src="<?php echo $imgPath; ?>" alt="<?php echo $row['team_name']; ?>">
                    </div>
                    <div class="card-content">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="background: var(--accent); padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">
                                <?php echo $row['city']; ?>
                            </span>
                            <span style="color: var(--secondary); font-weight: bold;">
                                <i class="fas fa-tree"></i> <?php echo $row['trees_planted']; ?> Trees
                            </span>
                        </div>
                        <h3><?php echo $row['team_name']; ?></h3>
                        <p><strong>Leader:</strong> <?php echo $row['leader_name']; ?></p>
                        <p><strong>Members:</strong> <?php echo $row['members_count']; ?></p>
                    </div>
                </div>

                <?php
            }
            echo '</div>'; // End grid-3
        } else {
            echo '<div class="text-center"><h3>No teams registered yet.</h3></div>';
        }
        ?>

        <!-- Become a Leader CTA -->
        <div class="text-center" style="margin-top: 50px;">
            <p style="margin-bottom: 20px;">Don't see your city? Start a team today.</p>
            <a href="https://wa.me/923333197977?text=I%20want%20to%20start%20a%20new%20team" class="btn btn-primary">Start a New Team</a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>