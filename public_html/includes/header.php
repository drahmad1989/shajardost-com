<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ShajarDost'; ?></title>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <img src="assets/images/Shajardost-logo.webp" alt="ShajarDost Logo">
                    <span>ShajarDost</span>
                </a>
                
                <!-- Navigation Links -->
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="mission-fifty.php">Mission 50</a></li>
                    <li><a href="green-scouts.php">Green Scouts</a></li>
                    <li><a href="mini-nursery.php">Mini Nursery</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="team-showcase.php" style="color: #ff0000;"><i class="fas fa-video"></i> Team   </a></li>
                    
                    <!-- Right Side Buttons Area -->
                    <li><a href="buy-plants.php" class="btn-nav" style="background: #25d366;">Buy Plants</a></li>
                    <li><a href="donate.php" class="btn-nav" style="background: var(--secondary);">Donate</a></li>
                    <li><a href="sponsorship.php" class="btn-nav">Sponsor Us</a></li>
                </ul>

                <div class="mobile-toggle" id="menuBtn">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Database Connection (Included globally so blog/teams work everywhere) -->
    <?php 
    if(file_exists('includes/db-connect.php')) {
        include 'includes/db-connect.php';
    }
    ?>

    <!-- Script for Mobile Menu Toggle -->
    <script>
        const menuBtn = document.getElementById('menuBtn');
        const navLinks = document.getElementById('navLinks');
        const icon = menuBtn.querySelector('i');

        if(menuBtn && navLinks){
            menuBtn.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                
                // Toggle Icon (Bars to Times)
                if (navLinks.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }
    </script>

    <!-- WhatsApp Chatbox Button (Floating Button) -->
    <a href="https://wa.me/923333197977" class="whatsapp-chat-btn" target="_blank">
        <i class="fab fa-whatsapp"></i>
        <span>Chat with Us</span>
    </a>
    
</body>
</html>