<?php 
 $page_title = 'Our Members | ShajarDost';
include 'includes/header.php'; 
?>

<style>
    /* CSS specifically for Member Cards (Static) */
    .member-card {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 25px;
        text-align: center;
        transition: 0.3s;
        border-top: 5px solid var(--primary);
        position: relative; /* For absolute positioning of star icon if needed */
    }
    .member-card:hover { transform: translateY(-5px); }

    .member-img {
        width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 3px solid #eee; margin: 0 auto 15px auto;
    }
    
    .badge {
        display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; color: white;
    }

    /* Special Styling for STARS / CERTIFIED MEMBERS */
    .type-mission_50 { border-top-color: #ffb703; } /* Gold Border */
    
    /* The "Star" Badge Styling */
    .star-badge {
        background: linear-gradient(45deg, #ffb703, #d97706); /* Gold Gradient */
        box-shadow: 0 4px 12px rgba(255, 183, 3, 0.4); /* Glow effect */
        border-radius: 30px; /* Pill shape */
        padding: 6px 15px;
        margin-bottom: 10px;
    }

    /* Certified Tag under name */
    .certified-tag {
        color: #d97706; font-weight: bold; font-size: 0.8rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;
    }
</style>

<!-- Banner -->
<section style="background: var(--dark); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1>Our Green Family</h1>
        <p>Honoring those who have completed the challenge and set a benchmark.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        
        <!-- Filter Buttons (Simplified since only 2 members for now) -->
        <div style="text-align: center; margin-bottom: 40px;">
            <button onclick="filterMembers('all')" class="btn btn-primary">All Members</button>
            <button onclick="filterMembers('mission_50')" class="btn btn-gold" style="margin: 0 5px;">Mission 50 Stars</button>
            <!-- Paid/Honorary buttons removed as per request -->
        </div>

        <!-- Members Grid -->
        <div class="grid-3">

            <!-- MEMBER 1: MUHAMMAD AHMAD (THE STAR) -->
            <div class="member-card type-mission_50">
                <!-- IMAGE NAME: muhammad-ahmad-ceo.webp -->
                <img src="assets/images/muhammad-ahmad-ceo.webp" class="member-img" alt="Muhammad Ahmad">
                
                <!-- STAR BADGE -->
                <div style="margin-bottom: 10px;">
                    <span class="badge star-badge">
                        <i class="fas fa-crown" style="margin-right: 5px;"></i> MISSION 50 STAR
                    </span>
                </div>

                <h3>Muhammad Ahmad</h3>
                <div class="certified-tag"><i class="fas fa-check-circle"></i> Certified ShajarDost</div>
                
                <p style="font-size: 0.85rem; color: var(--primary); font-weight: bold;">Bonga Balochan, District Kasur</p>

                <div class="member-bio">
                    <p><strong>Started:</strong> Plantation started 2011. Team work started 26 June 2018.</p>
                    <ul>
                        <li>Completed Mission 50 <strong>twice+</strong>.</li>
                        <li>Launched 5+ major initiatives (Green Seekers, TBTP, Mini Nursery, etc).</li>
                        <li>Mentored <strong>50+ people</strong> to start Mission 50.</li>
                        <li>Built & distributed plants from <strong>10 Mini Nurseries</strong>.</li>
                        <li>Participated in <strong>10+ villages</strong> plantation drives.</li>
                        <li><strong>Goal 2026:</strong> To make 100 people complete Mission 50.</li>
                    </ul>
                </div>
            </div>

            <!-- MEMBER 2: FIAZ HUSSAIN (THE STAR) -->
            <div class="member-card type-mission_50">
                <!-- IMAGE NAME: fiaz-hussain-okara.webp -->
                <img src="assets/images/fiaz-hussain-okara.webp" class="member-img" alt="Fiaz Hussain">
                
                <!-- STAR BADGE -->
                <div style="margin-bottom: 10px;">
                    <span class="badge star-badge">
                        <i class="fas fa-crown" style="margin-right: 5px;"></i> MISSION 50 STAR
                    </span>
                </div>

                <h3>Fiaz Hussain</h3>
                <div class="certified-tag"><i class="fas fa-check-circle"></i> Certified ShajarDost</div>

                <p style="font-size: 0.85rem; color: var(--primary); font-weight: bold;">Okara Chapter</p>

                <div class="member-bio">
                    <p><strong>Joined:</strong> August 2018.</p>
                    <p style="margin-bottom: 5px;"><strong>Address:</strong> Thatta Ghulam, PO Sadar Gogera, Dist Okara.</p>
                    <ul>
                        <li>Built <strong>2 Mini Nurseries</strong>.</li>
                        <li>Gifted <strong>250 plants</strong> to village community.</li>
                        <li>Planted <strong>100 plants</strong> at GD School.</li>
                        <li>Planted <strong>100 plants</strong> at Massan School.</li>
                    </ul>
                </div>
            </div>

        </div> <!-- End Grid -->

        <!-- Note: More members will be added here manually by you -->

        <!-- Join CTA -->
        <div class="card text-center" style="margin-top: 50px; background: #e8f5e9; border: 1px dashed var(--primary);">
            <div class="card-content">
                <h2>Do you want to be here?</h2>
                <p style="margin-bottom: 20px;">Join the Mission 50 challenge, complete your plantation, and get your name listed here as a Certified ShajarDost.</p>
                <a href="contact.php" class="btn btn-primary">Start Mission 50</a>
            </div>
        </div>
    </div>
</section>

<script>
    function filterMembers(type) {
        var cards = document.getElementsByClassName('member-card');
        for (var i = 0; i < cards.length; i++) {
            if (type === 'all' || cards[i].classList.contains('type-' + type)) {
                cards[i].style.display = 'block';
            } else {
                cards[i].style.display = 'none';
            }
        }
    }
</script>

<?php include 'includes/footer.php'; ?>