<?php 
 $page_title = 'Donate & Plant | ShajarDost';
include 'includes/header.php'; 
?>

<!-- Banner -->
<section style="background: var(--primary); color: white; padding: 80px 0 50px; text-align: center;">
    <div class="container">
        <h1>Plant a Legacy, Not Just a Tree</h1>
        <p>Your contribution directly grows Pakistan. 100% Transparency.</p>
    </div>
</section>

<!-- Pricing Plans -->
<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Choose Your Contribution</h2>
            <p class="section-desc">Select a package that suits you. Maintenance & Care included where mentioned.</p>
        </div>

        <div class="grid-3">
            
            <!-- Plan 1: Basic -->
            <div class="card" style="border-top: 5px solid #888;">
                <div class="card-content text-center">
                    <h3>Starter Plant</h3>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333; margin: 15px 0;">200 PKR</div>
                    <ul style="text-align: left; margin-bottom: 20px; line-height: 2;">
                        <li><i class="fas fa-leaf" style="color: var(--secondary);"></i> Plant Size: 2-3 Feet</li>
                        <li><i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> General Plantation</li>
                        <li><i class="fas fa-camera" style="color: var(--secondary);"></i> Photo Update (Optional)</li>
                    </ul>
                    <button class="btn btn-secondary" style="width: 100%;" onclick="selectPlan('Starter Plant', 200)">Select Plan</button>
                </div>
            </div>

            <!-- Plan 2: Standard (Highlighted) -->
            <div class="card" style="border-top: 5px solid var(--primary); transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
                <div style="background: var(--primary); color: white; padding: 5px; font-weight: bold;">MOST POPULAR</div>
                <div class="card-content text-center">
                    <h3>Standard Plant</h3>
                    <div style="font-size: 2.5rem; font-weight: bold; color: var(--primary); margin: 15px 0;">500 PKR</div>
                    <ul style="text-align: left; margin-bottom: 20px; line-height: 2;">
                        <li><i class="fas fa-tree" style="color: var(--secondary);"></i> Plant Size: 4+ Feet</li>
                        <li><i class="fas fa-tools" style="color: var(--secondary);"></i> <strong>1 Year Maintenance</strong></li>
                        <li><i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> High Survival Rate Area</li>
                    </ul>
                    <button class="btn btn-primary" style="width: 100%;" onclick="selectPlan('Standard Plant', 500)">Select Plan</button>
                </div>
            </div>

            <!-- Plan 3: Premium -->
            <div class="card" style="border-top: 5px solid var(--gold);">
                <div class="card-content text-center">
                    <h3>Premium Gift</h3>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #d97706; margin: 15px 0;">2,000 PKR</div>
                    <ul style="text-align: left; margin-bottom: 20px; line-height: 2;">
                        <li><i class="fas fa-tag" style="color: var(--secondary);"></i> <strong>Personalized Name Tag</strong></li>
                        <li><i class="fas fa-certificate" style="color: var(--secondary);"></i> Official Certificate</li>
                        <li><i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> Prime Location</li>
                        <li><i class="fas fa-tools" style="color: var(--secondary);"></i> Full Maintenance</li>
                    </ul>
                    <button class="btn btn-gold" style="width: 100%;" onclick="selectPlan('Premium Gift', 2000)">Select Plan</button>
                </div>
            </div>

        </div>

        <!-- Bulk / Forest Options -->
        <div class="grid-3" style="margin-top: 30px;">
             <div class="card">
                <div class="card-content">
                    <h4><i class="fas fa-users"></i> Group Plantation</h4>
                    <p>Plant 10 trees together.</p>
                    <h3>5,000 PKR</h3>
                    <button class="btn btn-secondary" style="width: 100%; margin-top: 10px;" onclick="selectPlan('10 Trees Bundle', 5000)">Select</button>
                </div>
            </div>
             <div class="card">
                <div class="card-content">
                    <h4><i class="fas fa-medal"></i> Mission 50 Support</h4>
                    <p>Sponsor a volunteer's Mission 50.</p>
                    <h3>25,000 PKR</h3>
                    <button class="btn btn-secondary" style="width: 100%; margin-top: 10px;" onclick="selectPlan('Mission 50 Sponsor', 25000)">Select</button>
                </div>
            </div>
             <div class="card" style="background: #e0f2f1; border: 2px solid var(--primary);">
                <div class="card-content">
                    <h4><i class="fas fa-globe-asia"></i> Miyawaki Forest</h4>
                    <p>Sponsor a mini urban forest (100+ trees).</p>
                    <h3>50,000+ PKR</h3>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" onclick="selectPlan('Miyawaki Forest', 50000)">Contact for Forest</button>
                </div>
            </div>
        </div>

        <!-- Sponsor a Green Scout (New Idea) -->
        <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin-top: 40px; border-left: 5px solid #ffc107;">
            <h3><i class="fas fa-child"></i> Sponsor a Green Scout (New Idea)</h3>
            <p>Can't plant? Educate. Sponsor the training kit and uniform for one student.</p>
            <h4>Cost: 500 PKR per Student</h4>
            <button class="btn btn-secondary" style="margin-top: 10px; background: #fff3cd; color: #333;" onclick="selectPlan('Green Scout Sponsor', 500)">Sponsor a Child</button>
        </div>

    </div>
</section>

<!-- Payment & Verification Instructions -->
<section class="section-padding" style="background: #f0f4f8;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">How to Donate?</h2>
            <p class="section-desc">It's simple. We work on a trust-based manual system.</p>
        </div>

        <div class="grid-3">
            <div class="card" style="text-align: center;">
                <div style="background: var(--primary); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; font-size: 1.2rem;">1</div>
                <h3>Select & Transfer</h3>
                <p>Transfer the amount via **JS Bank / Zindagi App** to:<br><strong>0333-3197977</strong></p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="background: var(--primary); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; font-size: 1.2rem;">2</div>
                <h3>WhatsApp Us</h3>
                <p>Send a screenshot of the transaction receipt to:<br><strong>0333-3197977</strong></p>
                <a href="https://wa.me/923333197977" target="_blank" class="btn btn-gold" style="margin-top: 10px;">Open WhatsApp</a>
            </div>
            <div class="card" style="text-align: center;">
                <div style="background: var(--primary); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; font-size: 1.2rem;">3</div>
                <h3>Verification</h3>
                <p>We will confirm your donation and update the status on our website within 24 hours.</p>
            </div>
        </div>

        <!-- Registration Form (For Manual Entry) -->
        <div class="card" style="max-width: 600px; margin: 40px auto; border-top: 5px solid var(--primary);">
            <div class="card-content">
                <h3>Donor Details</h3>
                <p style="margin-bottom: 20px;">Fill this out so we can issue the certificate or tag.</p>
                
                <form action="submit_donation.php" method="POST" style="display: grid; gap: 15px;">
                    <input type="text" name="donor_name" placeholder="Full Name" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    
                    <input type="text" name="contact_number" placeholder="Mobile Number (e.g., 0300...)" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    
                    <input type="email" name="email" placeholder="Email Address (For Certificate)" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    
                    <input type="text" id="selected_plan" name="package" placeholder="Select a plan above" required readonly style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%; background: #f9f9f9; font-weight: bold;">
                    
                    <textarea name="custom_message" placeholder="Dedication Message (Optional) - e.g., 'In loving memory of...'" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%; height: 80px;"></textarea>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Donation Details</button>
                </form>
            </div>
        </div>

    </div>
</section>

<!-- Simple Script to auto-fill the plan in form -->
<script>
    function selectPlan(planName, price) {
        // Scroll to form
        document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        
        // Fill the input
        var input = document.getElementById('selected_plan');
        input.value = planName + " - " + price + " PKR";
        
        // Visual feedback
        input.style.borderColor = "green";
        input.style.backgroundColor = "#e8f5e9";
    }
</script>

<?php include 'includes/footer.php'; ?>