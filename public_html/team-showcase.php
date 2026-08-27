<?php 
 $page_title = 'Green Seekers & Team Showcase | ShajarDost';
include 'includes/header.php'; 
?>

<!-- Banner -->
<section style="background: var(--dark); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1>Green Seekers & Team Showcase</h1>
        <p>Meet the heroes making Pakistan green. Watch our stories, missions, and events.</p>
    </div>
</section>

<!-- Shajar Kari Revolution (Featured) -->
<section class="section-padding">
    <div class="container">
        <div class="card" style="background: var(--primary); color: white;">
            <div class="card-content">
                <h2 style="margin-bottom: 20px;">Shajar Kari Revolution in College</h2>
                <p style="margin-bottom: 20px; opacity: 0.9;">Energy. Passion. Revolution. Watch our team ignite the spirit of plantation in a college.</p>
                
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/s5GfDURb5HM" title="Shajar Kari Revolution" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Stories Grid -->
<section class="section-padding" style="background: #f9f9f9;">
    <div class="container">
        <h2 class="section-title text-center">Our Team Stories</h2>
        <div class="grid-3">
            
            <!-- Video 1: CEO Planting -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/-6cP6vfuczQ" title="CEO Planting Tree" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>CEO Leading by Example</h3>
                    <p>Muhammad Ahmad (CEO) planting his 3rd tree. He has helped thousands start Mission 50.</p>
                </div>
            </div>

            <!-- Video 2: Seedball Donation -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/8-9lsbovWpA" title="Seedball Donation Drive" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>Seedball Donation Drive</h3>
                    <p>Support from across borders! Members donated seedballs via Seedballs.pk. Watch us throw them for nature.</p>
                </div>
            </div>

            <!-- Video 3: Mission Fifty Pledge -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/YrbdSo8e1UA" title="Mission Fifty Pledge" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>Mission Fifty Pledge</h3>
                    <p>Join the movement. Hundreds have pledged to plant 50 trees. You can too.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Playlists Section -->
<section class="section-padding">
    <div class="container">
        <div class="card text-center" style="padding: 40px; background: #fff7e6; border: 2px solid var(--gold);">
            <h2 style="color: var(--dark); margin-bottom: 20px;">Full Playlists on YouTube</h2>
            <p style="margin-bottom: 20px;">Don't miss a single update. Subscribe to our channel.</p>
            
            <a href="https://www.youtube.com/@iamgreenseeker/playlists" target="_blank" class="btn btn-primary" style="font-size: 1.2rem;">
                <i class="fab fa-youtube"></i> View All Playlists
            </a>

            <div style="margin-top: 30px; text-align: left; display: inline-block;">
                <h4>Available Playlists:</h4>
                <ul style="list-style: disc; padding-left: 20px;">
                    <li><strong>Mission Fifty:</strong> 86 Videos</li>
                    <li><strong>Green Seekers:</strong> 86 Videos</li>
                    <li><strong>How To:</strong> 15 Videos</li>
                    <li><strong>Training:</strong> 3 Videos</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Join Team CTA -->
<section class="section-padding" style="background: var(--primary); color: white; text-align: center;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Join the Green Seekers</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">
            Want your story to be featured here? Start Mission 50 or join a team near you.
        </p>
        <a href="contact.php" class="btn btn-gold">Join ShajarDost Team</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>