<?php 
 $page_title = 'Donate Plants | ShajarDost';
include 'includes/header.php'; 
?>

<!-- Banner -->
<section style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://picsum.photos/seed/plantation/1920/1080'); background-size: cover; background-position: center; color: white; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.5rem;">Not Money? Donate Plants!</h1>
        <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto;">
            We have the land (Schools, Colleges, Hospitals).<br>
            We have the teams. All we need is the plants.
        </p>
    </div>
</section>

<!-- The Concept -->
<section class="section-padding" style="background: #f9f9f9;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">How it Works</h2>
            <p class="section-desc">You buy the saplings, we do the hard work of planting them.</p>
        </div>

        <div class="grid-3">
            <div class="card text-center">
                <div class="card-content">
                    <h3>1. You Buy</h3>
                    <p>Purchase plants (e.g., from PodayOnline or your local nursery). Or select a donation tier below.</p>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-content">
                    <h3>2. You Give</h3>
                    <p>Deliver the plants to us at our HQ in Phool Nagar, or we coordinate pickup.</p>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-content">
                    <h3>3. We Plant</h3>
                    <p>We plant them in schools, colleges, hospitals, and community parks in your name.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Packages -->
<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Donation Tiers</h2>
            <p class="section-desc">Choose how many plants you want to donate.</p>
        </div>

        <div class="grid-3">
            
            <!-- 100 Plants -->
            <div class="card text-center">
                <div class="card-content">
                    <h3 style="color: var(--primary);">Starter Pack</h3>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--dark); margin: 15px 0;">100</div>
                    <p>Plants</p>
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                    <p>Suitable for a small playground.</p>
                    <a href="contact.php" class="btn btn-secondary" style="width: 100%; margin-top: 15px;">Pledge 100 Plants</a>
                </div>
            </div>

            <!-- 500 - 1000 Plants -->
            <div class="card text-center" style="transform: scale(1.05); border-top: 5px solid var(--gold);">
                <div style="background: var(--gold); color: white; padding: 5px; font-weight: bold;">POPULAR</div>
                <div class="card-content">
                    <h3 style="color: var(--primary);">Community Pack</h3>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--dark); margin: 15px 0;">500 - 1,000</div>
                    <p>Plants</p>
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                    <p>Ideal for a school garden or a street plantation.</p>
                    <a href="contact.php" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Pledge 1,000 Plants</a>
                </div>
            </div>

            <!-- 10,000+ Plants -->
            <div class="card text-center">
                <div class="card-content">
                    <h3 style="color: var(--primary);">Forest Pack</h3>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--dark); margin: 15px 0;">10,000+</div>
                    <p>Plants</p>
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                    <p>For a full-scale Miyawaki forest or corporate park.</p>
                    <a href="contact.php" class="btn btn-secondary" style="width: 100%; margin-top: 15px;">Pledge 10,000 Plants</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Where to Buy Plants? -->
<section class="section-padding" style="background: #e8f5e9;">
    <div class="container">
        <div class="card" style="display: flex; align-items: center; gap: 30px; padding: 30px;">
            <div style="flex: 1;">
                <h3 style="color: var(--dark); margin-bottom: 15px;">Where to get plants?</h3>
                <p style="margin-bottom: 20px;">
                    We recommend high-quality saplings to ensure survival. You can buy from <strong>PodayOnline</strong> or any local nursery you trust.
                </p>
                <p style="font-size: 0.9rem; color: #666;">
                    <strong>Note:</strong> We suggest PodayOnline because they provide healthy saplings for plantation drives.
                </p>
            </div>
            <div style="flex: 0 0 300px; text-align: center; border-left: 1px solid #ccc; padding-left: 30px;">
                <p style="margin-bottom: 10px;">Get Quality Plants:</p>
                <a href="https://wa.me/923360000597" target="_blank" class="btn btn-primary" style="width: 100%; background: #25d366;">
                    <i class="fab fa-whatsapp"></i> Contact PodayOnline
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>