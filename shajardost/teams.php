<?php
$page_title = 'Our Teams - Green Warriors Across Pakistan | ShajarDost';
$page_description = 'Meet the ShajarDost teams making a difference on the ground across Pakistan. Join our tree planting teams in Lahore, Kasur, Vehari, and more cities.';
include 'includes/header.php';
include 'includes/db-connect.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Our Teams</li></ol></div></nav>

<section class="hero-banner primary-bg">
    <div class="container"><h1>Our Teams - The Green Warriors</h1><p>Meet the heroes who are making a difference on the ground across Pakistan.</p></div>
</section>

<section class="section-padding">
    <div class="container">
        <?php
        $sql = "SELECT * FROM teams ORDER BY trees_planted DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid-3">';
            while($row = mysqli_fetch_assoc($result)) {
                $imgPath = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=300&h=200&fit=crop';
                ?>
                <div class="card fade-in">
                    <div class="card-img"><img src="<?php echo $imgPath; ?>" alt="<?php echo $row['team_name']; ?>"></div>
                    <div class="card-content">
                        <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                            <span style="background:var(--gold);color:white;padding:3px 8px;border-radius:4px;font-size:0.8rem;font-weight:bold;"><?php echo $row['city']; ?></span>
                            <span style="color:var(--secondary);font-weight:bold;"><i class="fas fa-tree"></i> <?php echo $row['trees_planted']; ?> Trees</span>
                        </div>
                        <h3><?php echo $row['team_name']; ?></h3>
                        <p><strong>Leader:</strong> <?php echo $row['leader_name']; ?></p>
                        <p><strong>Members:</strong> <?php echo $row['members_count']; ?></p>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="text-center" style="padding:60px 0;"><i class="fas fa-users" style="font-size:4rem;color:#ddd;margin-bottom:20px;"></i><h3 style="color:#999;">Teams will be displayed here.</h3><p>Join us and start a team in your city!</p></div>';
        }
        ?>
        <div class="text-center" style="margin-top:50px;">
            <p style="margin-bottom:20px;">Don't see your city? Start a team today and become a leader.</p>
            <a href="https://wa.me/923333197977?text=I%20want%20to%20start%20a%20new%20team" class="btn btn-primary btn-lg" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Start a New Team</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
