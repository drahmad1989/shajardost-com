<?php 
 $page_title = 'Green Scouts Program | ShajarDost';
 $page_description = 'We have trained 600+ Green Scouts in 10+ schools across Pakistan. Join our environmental education program.';
include 'includes/header.php'; 
?>

<style>
    /* --- Green Scouts Specific Styles --- */
    .counter-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--primary);
        display: block;
        margin-bottom: 5px;
    }
    .counter-label {
        font-size: 0.9rem;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .stats-row {
        display: flex;
        gap: 50px;
        justify-content: center;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }
    .stat-box {
        text-align: center;
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        flex: 1;
        min-width: 250px;
    }
</style>

<!-- Banner -->
<section style="background: var(--secondary); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3rem;">Green Scouts Program</h1>
        <p style="font-size: 1.2rem; max-width: 700px; margin: 0 auto;">Training the next generation of environmental heroes across Pakistan.</p>
    </div>
</section>

<!-- DYNAMIC STATISTICS (Key Numbers) -->
<section class="section-padding" style="background: var(--white);">
    <div class="container">
        
        <div class="stats-row">
            <!-- Stat 1: Total Scouts -->
            <div class="stat-box">
                <i class="fas fa-users" style="font-size: 3rem; color: var(--primary); margin-bottom: 10px;"></i>
                <div class="counter-number">600+</div>
                <div class="counter-label">Green Scouts Trained</div>
            </div>

            <!-- Stat 2: Partner Schools -->
            <div class="stat-box">
                <i class="fas fa-school" style="font-size: 3rem; color: var(--secondary); margin-bottom: 10px;"></i>
                <div class="counter-number">10+</div>
                <div class="counter-label">Partner Schools</div>
            </div>

            <!-- Stat 3: Sessions -->
            <div class="stat-box">
                <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: #d97706; margin-bottom: 10px;"></i>
                <div class="counter-number">500+</div>
                <div class="counter-label">Training Sessions</div>
            </div>

            <!-- Stat 4: Impact -->
            <div class="stat-box">
                <i class="fas fa-leaf" style="font-size: 3rem; color: #ffb703; margin-bottom: 10px;"></i>
                <div class="counter-number">100%</div>
                <div class="counter-label">Practical Impact</div>
            </div>
        </div>
    </div>
</section>

<!-- PROGRAM CONTENT -->
<section class="section-padding">
    <div class="container">
        <div class="grid-3">
            
            <!-- Info Card 1 -->
            <div class="card">
                <div class="card-img">
                    <img src="assets/images/green-scouts-school.webp" alt="Green Scouts">
                </div>
                <div class="card-content">
                    <h3 style="color: var(--primary); margin-bottom: 15px;">What is Green Scouts?</h3>
                    <p style="color: #666; margin-bottom: 15px;">
                        A specialized training program for school students to learn about native trees, plantation techniques, and environmental preservation. We empower them to become stewards of nature.
                    </p>
                    <a href="#gallery" class="btn btn-primary">Watch Our Gallery</a>
                </div>
            </div>

            <!-- Curriculum Card -->
            <div class="card">
                <div class="card-content" style="text-align: center;">
                    <h3 style="color: var(--primary); margin-bottom: 20px;">Curriculum Highlights</h3>
                    <ul style="list-style: none; text-align: left;">
                        <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                            <i class="fas fa-check-circle" style="color: var(--secondary); margin-right: 10px;"></i>
                            <strong>Identification:</strong> Learning about native trees like Neem, Amaltas, and Kikar.
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                            <i class="fas fa-check-circle" style="color: var(--secondary); margin-right: 10px;"></i>
                            <strong>Plantation:</strong> The right way to dig, plant, and maintain a sapling.
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                            <i class="fas fa-check-circle" style="color: var(--secondary); margin-right: 10px;"></i>
                            <strong>Stewardship:</strong> Taking ownership of our environment.
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                            <i class="fas fa-certificate" style="color: var(--secondary); margin-right: 10px;"></i>
                            <strong>Certificates:</strong> Every student receives an official "Green Scout" badge.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Training Card -->
            <div class="card">
                <div class="card-img">
                    <img src="https://picsum.photos/seed/greenteam/600/400" alt="Training Session">
                </div>
                <div class="card-content">
                    <h3 style="color: var(--primary);">Interactive Sessions</h3>
                    <p style="color: #666;">
                        We conduct regular sessions in schools where students learn not just theory but practical plantation. They get their hands dirty in soil, water plants, and understand the importance of nature.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VIDEO GALLERY SECTION (Using Logic) -->
<section class="section-padding" style="background: #f0fdf4;">
    <div class="container">
        <div class="text-center">
            <h2 style="margin-bottom: 10px;">Green Scouts in Action</h2>
            <p>Check out our training sessions, plantation drives, and student success stories.</p>
        </div>

        <div class="grid-3">
            <?php
            // YAHAN VIDEO DATA KO ADD KAREIN YAHAN
            $videos_list = [
                // VIDEO 1: TBTP Vision
                [
                    'title' => 'Vision: Ten Billion Trees',
                    'desc' => 'PM Imran Khan launched this massive project. We are aligned.',
                    'url' => 'https://www.youtube.com/embed/F7veCGGqTbc', // PLACEHOLDER - Yahan original ID lagayen
                    'tag' => 'Official Project'
                ],
                
                // VIDEO 2: National Record
                [
                    'title' => 'National Record 2018',
                    'desc' => 'We planted 630,000 trees in one day. A historic achievement.',
                    'url' => 'https://www.youtube.com/embed/8RLw1M74LAo', // PLACEHOLDER
                    'tag' => 'History'
                ],

                // VIDEO 3: Green Scouts Intro
                [
                    'title' => 'Green Scouts Introduction',
                    'desc' => 'Join the movement. Become a Green Scout in your school today.',
                    'url' => 'https://www.youtube.com/embed/YrbdSo8e1UA', // PLACEHOLDER - Apne ID lagayen
                    'tag' => 'Training'
                ],

                // VIDEO 4: Plantation Guide (Generic for now)
                [
                    'title' => 'How to Plant a Tree',
                    'desc' => 'Simple guide for beginners on how to plant and care for saplings.',
                    'url' => 'https://www.youtube.com/embed/Vfv_YUBfqrk', // PLACEHOLDER
                    'tag' => 'Guide'
                ],
                
                // VIDEO 5: Tree Care (Generic)
                [
                    'title' => 'Caring for Your Saplings',
                    'desc' => 'Watering, fertilizing, and maintenance tips for newly planted trees.',
                    'url' => 'https://www.youtube.com/embed/JPHgyqQ_ATE', // PLACEHOLDER
                    'tag' => 'Maintenance'
                ]
            ];

            // LOOP TO RENDER VIDEOS
            foreach ($videos_list as $video) {
                ?>
                
                <div class="card">
                    <div class="video-wrapper">
                        <iframe src="<?php echo $video['url']; ?>" title="<?php echo $video['title']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="card-content">
                        <div style="margin-bottom: 10px;">
                            <span style="background: #e8f5e9; color: var(--primary); padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">
                                <?php echo $video['tag']; ?>
                            </span>
                            <h3 style="margin-bottom: 10px; font-size: 1.3rem;">
                                <?php echo $video['title']; ?>
                            </h3>
                            <p style="color: #666; font-size: 0.95rem;">
                                <?php echo $video['desc']; ?>
                            </p>
                        </div>
                        <a href="https://www.youtube.com/@ShajarDost" target="_blank" style="color: var(--primary); font-weight: bold; margin-top: 15px;">
                            <i class="fab fa-youtube"></i> Watch on Channel
                        </a>
                    </div>
                </div>

                <?php
            }
            ?>
            
            <!-- Add More Button -->
            <div class="text-center" style="margin-top: 40px;">
                <a href="#" class="btn btn-primary btn-lg">
                    Load More Videos
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section-padding" style="background: var(--primary); color: white; text-align: center;">
    <div class="container">
        <h2>Start a Green Scout Chapter</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">
            Want to bring this training to your school? Or want to contribute?
        </p>
        <a href="contact.php" class="btn btn-gold">Join the Movement</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>