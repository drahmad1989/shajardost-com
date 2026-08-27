<?php 
 $page_title = 'Contact Us | ShajarDost';
include 'includes/header.php'; 
?>

<!-- Banner -->
<section style="background: var(--dark); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1>Get in Touch</h1>
        <p>We'd love to hear from you. Visit us, call us, or drop a message.</p>
    </div>
</section>

<!-- Contact Grid -->
<section class="section-padding">
    <div class="container">
        <div class="grid-3" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            
            <!-- Contact Info -->
            <div class="card">
                <div class="card-content text-center">
                    <i class="fas fa-phone-alt" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 15px;"></i>
                    <h3>Call Us</h3>
                    <p><strong>0333-3197977</strong></p>
                    <p>Mon-Sat: 9AM - 6PM</p>
                    <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">Also available on JS Bank / Zindagi App</p>
                </div>
            </div>

            <div class="card">
                <div class="card-content text-center">
                    <i class="fab fa-whatsapp" style="font-size: 2.5rem; color: #25D366; margin-bottom: 15px;"></i>
                    <h3>WhatsApp</h3>
                    <p>For quick support & donations</p>
                    <a href="https://wa.me/923333197977" class="btn btn-primary" style="margin-top: 15px;">Chat Now</a>
                </div>
            </div>

            <div class="card">
                <div class="card-content text-center">
                    <i class="fas fa-envelope" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 15px;"></i>
                    <h3>Email</h3>
                    <p>shajardostcom@gmail.com</p>
                    <p>We reply within 24 hours.</p>
                </div>
            </div>

        </div>

        <!-- Address & Map Section -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 50px;">
            
            <!-- Address Card -->
            <div class="card" style="display: flex; align-items: center; justify-content: center;">
                <div class="card-content">
                    <h3 style="margin-bottom: 20px;">Headquarters</h3>
                    <div style="margin-bottom: 20px; font-size: 1.1rem; line-height: 1.8;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 10px;"></i>
                        <strong>Al-Falah Building,</strong><br>
                        Bonga Balochan, p/o Phool Nagar,<br>
                        Tehsil Pattoki, District Kasur.<br>
                        <strong>Pakistan</strong>
                    </div>
                    <p style="color: #666;">
                        Available for volunteer meetings by appointment.
                    </p>
                </div>
            </div>

            <!-- Google Map -->
            <div class="card" style="padding: 0; overflow: hidden; height: 100%;">
                <iframe 
                    width="100%" 
                    height="100%" 
                    style="min-height: 350px; border:0;" 
                    loading="lazy" 
                    allowfullscreen
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108908.5484456411!2d74.0557!3d31.1833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3919073c00000001%3A0x619404028f4e434!2sPhool%20Nagar%2C%20Kasur%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000">
                </iframe>
            </div>
        </div>

        <!-- Message Form -->
        <div class="card" style="margin-top: 50px; border-top: 4px solid var(--primary);">
            <div class="card-content text-center">
                <h3>Send us a Message</h3>
                <p style="margin-bottom: 25px;">Have a question about Mission 50 or Sponsorship? Write to us.</p>
                
                <form action="mailto:shajardostcom@gmail.com" method="post" enctype="text/plain" style="max-width: 600px; margin: 0 auto; text-align: left; display: grid; gap: 15px;">
                    <div>
                        <label style="font-weight: bold;">Your Name</label>
                        <input type="text" name="name" placeholder="John Doe" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                    </div>
                    
                    <div>
                        <label style="font-weight: bold;">Your Email</label>
                        <input type="email" name="email" placeholder="you@example.com" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                    </div>

                    <div>
                        <label style="font-weight: bold;">Subject</label>
                        <select name="subject" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                            <option>General Inquiry</option>
                            <option>Donate / Payment</option>
                            <option>Join Mission 50</option>
                            <option>Corporate Sponsorship</option>
                            <option>Volunteer Inquiry</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-weight: bold;">Message</label>
                        <textarea name="message" rows="5" placeholder="How can we help?" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                </form>

            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>