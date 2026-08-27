<?php 
 $page_title = 'Video Gallery | ShajarDost';
 $page_description = 'Watch our official videos, plantation guides, and success stories.';
include 'includes/header.php'; 
?>

<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Our Video Gallery</h1>
            <p class="section-desc">Watch evidence of our work, training sessions, and success stories.</p>
        </div>

        <div class="grid-3">
            
            <!-- VIDEO 1: Ten Billion Trees Vision (Official Project) -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/3L-6aJ9Q5lA" title="Ten Billion Trees Vision - PM Imran Khan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>Ten Billion Trees Vision</h3>
                    <p style="font-size: 0.9rem;">PM Imran Khan's official vision for a greener Pakistan. A movement that is shaping the future of our climate.</p>
                    <span style="background: #1e3c72; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Official Project</span>
                </div>
            </div>

            <!-- VIDEO 2: National Record 2018 -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/s5GfDURb5HM" title="ShajarDost National Record 2018" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>Breaking the Record</h3>
                    <p style="font-size: 0.9rem;">On August 14th, 2018, ShajarDost set a national record by planting 630,000 trees in one day.</p>
                    <span style="background: #FFD700; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">National Record</span>
                </div>
            </div>

            <!-- VIDEO 3: Annual Event (Green Scouts) -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/8RLw1M74LAo" title="Green Scouts Annual Event" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>Green Scouts Annual Gathering</h3>
                    <p style="font-size: 0.9rem;">Our annual gathering where hundreds of Green Scouts took the pledge to protect their environment.</p>
                    <span style="background: var(--primary); color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Training</span>
                </div>
            </div>

            <!-- VIDEO 4: Plantation Guide (Generic) -->
            <div class="card">
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/YrbdSo8e1UA" title="How to Plant a Tree" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" web-share" referrerpolicy="strict-origin- cross-origin" allowfullscreen></iframe>
                </div>
                <div class="card-content">
                    <h3>How to Plant</h3>
                    <p style="font-size: 0.9rem;">Basic guide for beginners. Learn the right way to dig, water, and care for your sapling.</p>
                    <span style="background: #e8f5e9; color: #333; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Guide</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="section-padding" style="background: var(--dark); color: white; text-align: center;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Subscribe to our Channel</h2>
        <p style="margin-bottom: 30px; font-size: 1.1rem;">Don't miss a single update. Join our mission to make Pakistan green.</p>
        <a href="https://www.youtube.com/@iamgreenseeker" target="_blank" class="btn btn-gold" style="font-size: 1.2rem;">
            <i class="fab fa-youtube"></i> Subscribe
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>