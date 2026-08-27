<?php
$page_title = 'Green Scouts Program Pakistan | Environmental Education | ShajarDost';
$page_description = 'ShajarDost Green Scouts Program trains students in environmental education. 600+ Green Scouts trained in 10+ schools. Join our environmental education program across Pakistan.';
include 'includes/header.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Green Scouts</li></ol></div></nav>

<section class="hero-banner secondary-bg">
    <div class="container"><h1>Green Scouts Program</h1><p>Training the next generation of environmental heroes across Pakistan. 600+ scouts trained and counting.</p></div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="grid-4">
            <div class="stat-box fade-in" style="background:white;"><i class="fas fa-users stat-icon"></i><span class="stat-number" data-target="600" data-suffix="+">0</span><span class="stat-label">Green Scouts Trained</span></div>
            <div class="stat-box fade-in" style="background:white;"><i class="fas fa-school stat-icon"></i><span class="stat-number" data-target="10" data-suffix="+">0</span><span class="stat-label">Partner Schools</span></div>
            <div class="stat-box fade-in" style="background:white;"><i class="fas fa-chalkboard-teacher stat-icon" style="color:#d97706;"></i><span class="stat-number" data-target="500" data-suffix="+">0</span><span class="stat-label">Training Sessions</span></div>
            <div class="stat-box fade-in" style="background:white;"><i class="fas fa-leaf stat-icon" style="color:#ffb703;"></i><span class="stat-number" data-target="100" data-suffix="%">0</span><span class="stat-label">Practical Impact</span></div>
        </div>
    </div>
</section>

<section class="section-padding" style="background:#f9f9f9;">
    <div class="container">
        <div class="text-center"><h2 class="section-title">What is Green Scouts?</h2><p class="section-desc">A specialized training program for school students to learn about native trees, plantation techniques, and environmental preservation.</p></div>
        <div class="grid-3">
            <div class="card fade-in"><div class="card-content">
                <h3 style="color:var(--primary);">Identification</h3>
                <p>Students learn about native Pakistani trees like Neem, Amaltas, Kikar, Shisham, and Sukh Chain. They understand each tree's environmental benefits, growth patterns, and ideal planting conditions.</p>
            </div></div>
            <div class="card fade-in"><div class="card-content">
                <h3 style="color:var(--primary);">Plantation</h3>
                <p>Hands-on training in the right way to dig, plant, and maintain a sapling. Students get their hands dirty in soil, water plants, and understand the science behind successful tree planting.</p>
            </div></div>
            <div class="card fade-in"><div class="card-content">
                <h3 style="color:var(--primary);">Stewardship</h3>
                <p>Taking ownership of our environment. Every Green Scout becomes a guardian of nature, responsible for spreading awareness and leading plantation drives in their communities.</p>
            </div></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="text-center"><h2 class="section-title">Curriculum Highlights</h2></div>
        <div class="grid-2" style="max-width:800px;margin:0 auto;">
            <div class="info-box fade-in"><h4><i class="fas fa-check-circle" style="color:var(--secondary);"></i> Tree Identification</h4><p>Learning about native trees like Neem, Amaltas, and Kikar and their ecological roles.</p></div>
            <div class="info-box fade-in"><h4><i class="fas fa-check-circle" style="color:var(--secondary);"></i> Plantation Techniques</h4><p>The right way to dig, plant, and maintain a sapling for maximum survival rate.</p></div>
            <div class="info-box fade-in"><h4><i class="fas fa-check-circle" style="color:var(--secondary);"></i> Environmental Stewardship</h4><p>Taking ownership of our environment and leading community green initiatives.</p></div>
            <div class="info-box fade-in"><h4><i class="fas fa-check-circle" style="color:var(--secondary);"></i> Official Certification</h4><p>Every student receives an official "Green Scout" badge and certificate upon completion.</p></div>
        </div>
    </div>
</section>

<!-- Video Gallery -->
<section class="section-padding" style="background:#f0fdf4;">
    <div class="container">
        <div class="text-center"><h2 class="section-title">Green Scouts in Action</h2><p class="section-desc">Watch our training sessions, plantation drives, and student success stories.</p></div>
        <div class="grid-3">
            <?php
            $videos_list = [
                ['title' => 'Vision: Ten Billion Trees', 'desc' => 'PM Imran Khan launched this massive project. We are aligned.', 'url' => 'https://www.youtube.com/embed/F7veCGGqTbc', 'tag' => 'Official Project'],
                ['title' => 'National Record 2018', 'desc' => 'We planted 630,000 trees in one day. A historic achievement.', 'url' => 'https://www.youtube.com/embed/8RLw1M74LAo', 'tag' => 'History'],
                ['title' => 'Green Scouts Introduction', 'desc' => 'Join the movement. Become a Green Scout in your school today.', 'url' => 'https://www.youtube.com/embed/YrbdSo8e1UA', 'tag' => 'Training'],
            ];
            foreach ($videos_list as $video) { ?>
                <div class="card fade-in">
                    <div class="video-wrapper"><iframe src="<?php echo $video['url']; ?>" title="<?php echo $video['title']; ?>" frameborder="0" allowfullscreen loading="lazy"></iframe></div>
                    <div class="card-content">
                        <span class="card-badge"><?php echo $video['tag']; ?></span>
                        <h3><?php echo $video['title']; ?></h3>
                        <p><?php echo $video['desc']; ?></p>
                        <a href="https://www.youtube.com/@ShajarDost" target="_blank" rel="noopener" style="color:var(--primary);font-weight:bold;"><i class="fab fa-youtube"></i> Watch on YouTube</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="cta-section primary-bg">
    <div class="container">
        <h2>Start a Green Scout Chapter</h2>
        <p>Want to bring this training to your school? Or want to contribute as a sponsor? Contact us today.</p>
        <a href="contact.php" class="btn btn-gold btn-lg">Join the Movement</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
