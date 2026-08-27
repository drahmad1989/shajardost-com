<?php
$page_title = 'Donate & Plant a Tree in Pakistan | ShajarDost';
$page_description = 'Donate to plant a tree in Pakistan. Starting from 200 PKR. Get a certificate, name tag, and photo updates. 100% transparency. Plant a legacy, not just a tree.';
include 'includes/header.php';
?>

<nav class="breadcrumb"><div class="container"><ol class="breadcrumb-list"><li><a href="index.php">Home</a></li><li>Donate & Plant</li></ol></div></nav>

<section class="hero-banner primary-bg">
    <div class="container"><h1>Plant a Legacy, Not Just a Tree</h1><p>Your contribution directly grows Pakistan. 100% Transparency with photo updates and certificates.</p></div>
</section>

<!-- Pricing Plans -->
<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Choose Your Contribution</h2>
            <p class="section-desc">Select a package that suits you. Maintenance and care included where mentioned.</p>
        </div>
        <div class="grid-3">
            <div class="card pricing-card fade-in" style="border-top:5px solid #888;">
                <div class="card-content text-center">
                    <h3 style="color:#888;">Starter Plant</h3>
                    <div class="price">200<span style="font-size:1rem;color:var(--text-light);"> PKR</span></div>
                    <p class="price-label">per tree</p>
                    <ul>
                        <li><i class="fas fa-leaf"></i> Plant Size: 2-3 Feet</li>
                        <li><i class="fas fa-map-marker-alt"></i> General Plantation</li>
                        <li><i class="fas fa-camera"></i> Photo Update (Optional)</li>
                    </ul>
                    <button class="btn btn-secondary btn-block" onclick="selectPlan('Starter Plant', 200)">Select Plan</button>
                </div>
            </div>
            <div class="card pricing-card popular fade-in">
                <div class="popular-badge">MOST POPULAR</div>
                <div class="card-content text-center">
                    <h3 style="color:var(--primary);">Standard Plant</h3>
                    <div class="price">500<span style="font-size:1rem;color:var(--text-light);"> PKR</span></div>
                    <p class="price-label">per tree</p>
                    <ul>
                        <li><i class="fas fa-tree"></i> Plant Size: 4+ Feet</li>
                        <li><i class="fas fa-tools"></i> <strong>1 Year Maintenance</strong></li>
                        <li><i class="fas fa-map-marker-alt"></i> High Survival Rate Area</li>
                    </ul>
                    <button class="btn btn-primary btn-block" onclick="selectPlan('Standard Plant', 500)">Select Plan</button>
                </div>
            </div>
            <div class="card pricing-card fade-in" style="border-top:5px solid var(--gold);">
                <div class="card-content text-center">
                    <h3 style="color:#d97706;">Premium Gift</h3>
                    <div class="price">2,000<span style="font-size:1rem;color:var(--text-light);"> PKR</span></div>
                    <p class="price-label">per tree</p>
                    <ul>
                        <li><i class="fas fa-tag"></i> <strong>Personalized Name Tag</strong></li>
                        <li><i class="fas fa-certificate"></i> Official Certificate</li>
                        <li><i class="fas fa-map-marker-alt"></i> Prime Location</li>
                        <li><i class="fas fa-tools"></i> Full Maintenance</li>
                    </ul>
                    <button class="btn btn-gold btn-block" onclick="selectPlan('Premium Gift', 2000)">Select Plan</button>
                </div>
            </div>
        </div>

        <!-- Bulk Options -->
        <div class="grid-3" style="margin-top:40px;">
            <div class="card fade-in"><div class="card-content text-center"><h4><i class="fas fa-users"></i> Group Plantation</h4><p>Plant 10 trees together</p><h3 style="color:var(--primary);">5,000 PKR</h3><button class="btn btn-secondary btn-block" onclick="selectPlan('10 Trees Bundle', 5000)">Select</button></div></div>
            <div class="card fade-in"><div class="card-content text-center"><h4><i class="fas fa-medal"></i> Mission 50 Support</h4><p>Sponsor a volunteer's Mission 50</p><h3 style="color:var(--primary);">25,000 PKR</h3><button class="btn btn-secondary btn-block" onclick="selectPlan('Mission 50 Sponsor', 25000)">Select</button></div></div>
            <div class="card fade-in" style="background:#e0f2f1;border:2px solid var(--primary);"><div class="card-content text-center"><h4><i class="fas fa-globe-asia"></i> Miyawaki Forest</h4><p>Sponsor a mini urban forest (100+ trees)</p><h3 style="color:var(--primary);">50,000+ PKR</h3><button class="btn btn-primary btn-block" onclick="selectPlan('Miyawaki Forest', 50000)">Contact for Forest</button></div></div>
        </div>

        <!-- Green Scout Sponsor -->
        <div class="warning-box" style="margin-top:40px;">
            <h3><i class="fas fa-child"></i> Sponsor a Green Scout</h3>
            <p>Can't plant? Educate. Sponsor the training kit and uniform for one student. Cost: <strong>500 PKR per student</strong>.</p>
            <button class="btn btn-primary btn-sm" onclick="selectPlan('Green Scout Sponsor', 500)">Sponsor a Child</button>
        </div>
    </div>
</section>

<!-- Payment Instructions -->
<section class="section-padding" style="background:#f0f4f8;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">How to Donate?</h2>
            <p class="section-desc">Simple, trust-based manual system with full transparency.</p>
        </div>
        <div class="grid-3">
            <div class="card text-center fade-in"><div class="card-content">
                <div style="background:var(--primary);color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:1.2rem;">1</div>
                <h3>Select & Transfer</h3>
                <p>Transfer the amount via <strong>JS Bank / Zindagi App</strong> to: <strong>0333-3197977</strong></p>
            </div></div>
            <div class="card text-center fade-in"><div class="card-content">
                <div style="background:var(--primary);color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:1.2rem;">2</div>
                <h3>WhatsApp Us</h3>
                <p>Send a screenshot of the transaction receipt to: <strong>0333-3197977</strong></p>
                <a href="https://wa.me/923333197977" target="_blank" rel="noopener" class="btn btn-gold btn-sm" style="margin-top:10px;">Open WhatsApp</a>
            </div></div>
            <div class="card text-center fade-in"><div class="card-content">
                <div style="background:var(--primary);color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:1.2rem;">3</div>
                <h3>Verification</h3>
                <p>We confirm your donation and update the status on our website within 24 hours. You receive photo evidence and certificate.</p>
            </div></div>
        </div>

        <!-- Registration Form -->
        <div class="card" style="max-width:600px;margin:40px auto;border-top:5px solid var(--primary);">
            <div class="card-content">
                <h3 class="text-center">Donor Details</h3>
                <p class="text-center">Fill this out so we can issue the certificate or tag.</p>
                <form action="submit_donation.php" method="POST" style="display:grid;gap:15px;margin-top:20px;">
                    <input type="text" name="donor_name" placeholder="Full Name" required class="form-control">
                    <input type="text" name="contact_number" placeholder="Mobile Number (e.g., 0300...)" required class="form-control">
                    <input type="email" name="email" placeholder="Email Address (For Certificate)" class="form-control">
                    <input type="text" id="selected_plan" name="package" placeholder="Select a plan above" required readonly class="form-control" style="background:#f9f9f9;font-weight:bold;">
                    <textarea name="custom_message" placeholder="Dedication Message (Optional) - e.g., 'In loving memory of...'" class="form-control" style="height:80px;"></textarea>
                    <button type="submit" class="btn btn-primary btn-block">Submit Donation Details</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
