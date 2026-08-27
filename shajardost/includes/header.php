<?php
// Ensure page variables have defaults
$page_title = $page_title ?? 'ShajarDost - Making Pakistan Green';
$page_description = $page_description ?? 'ShajarDost is a grassroots tree planting movement in Pakistan. Join us to plant trees, donate plants, and make Pakistan green. 1.2 Million+ trees planted.';
$current_url = "https://www.shajardost.com" . strtok($_SERVER['REQUEST_URI'], '?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="shajar dost, tree plantation pakistan, plant a tree pakistan, donate tree, green pakistan, miyawaki forest, urban forest, ten billion trees, billion tree tsunami, green scouts, environmental NGO pakistan">
    <meta name="author" content="ShajarDost">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $current_url; ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $current_url; ?>">
    <meta property="og:image" content="https://www.shajardost.com/assets/images/Shajardost-logo.webp">
    <meta property="og:site_name" content="ShajarDost">
    <meta property="og:locale" content="en_PK">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="https://www.shajardost.com/assets/images/Shajardost-logo.webp">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "ShajarDost",
        "url": "https://www.shajardost.com",
        "logo": "https://www.shajardost.com/assets/images/Shajardost-logo.webp",
        "description": "ShajarDost is Pakistan's grassroots tree planting movement. 1.2 Million+ trees planted. Join Mission 50, Green Scouts, and Miyawaki Forest projects.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Al-Falah Building, Bonga Balochan",
            "addressLocality": "Phool Nagar, Kasur",
            "addressRegion": "Punjab",
            "addressCountry": "PK"
        },
        "telephone": "+923333197977",
        "email": "shajardostcom@gmail.com",
        "sameAs": [
            "https://www.facebook.com/ShajarDostcom/",
            "https://www.youtube.com/@ShajarDost"
        ],
        "foundingDate": "2011",
        "founder": {
            "@type": "Person",
            "name": "Muhammad Ahmad Baloch"
        }
    }
    </script>
</head>
<body>

    <!-- Header -->
    <header class="site-header" id="siteHeader">
        <div class="container">
            <nav class="main-nav">
                <a href="index.php" class="logo">
                    <img src="assets/images/Shajardost-logo.webp" alt="ShajarDost Logo - Tree Friends of Pakistan" width="50" height="50">
                    <span>ShajarDost</span>
                </a>

                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li class="dropdown">
                        <a href="#">Programs <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="mission-fifty.php"><i class="fas fa-flag"></i> Mission 50</a></li>
                            <li><a href="green-scouts.php"><i class="fas fa-child"></i> Green Scouts</a></li>
                            <li><a href="mini-nursery.php"><i class="fas fa-seedling"></i> Mini Nursery</a></li>
                            <li><a href="miyawaki-forest.php"><i class="fas fa-tree"></i> Miyawaki Forest</a></li>
                        </ul>
                    </li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="buy-plants.php" class="btn-nav" style="background:#25d366;"><i class="fab fa-whatsapp"></i> Buy Plants</a></li>
                    <li><a href="donate.php" class="btn-nav" style="background:var(--secondary);">Donate</a></li>
                    <li><a href="sponsorship.php" class="btn-nav">Sponsor</a></li>
                </ul>

                <div class="mobile-toggle" id="menuBtn">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- WhatsApp Chat Button -->
    <a href="https://wa.me/923333197977" class="whatsapp-chat-btn" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span>Chat with Us</span>
    </a>
