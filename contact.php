<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Root Labs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/root-tab-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="path/to/particles.js"></script>
    <script src="script.js" defer></script>
    <script src="contact_form.js"></script>
</head>

<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <main class="contact-page">
        <section class="hero contact-hero">
            <div class="hero-content">
                <h1>Contact <span>Root Labs</span></h1>
                <p>Get in touch with us for innovative IT solutions</p>
            </div>
        </section>

        <section class="contact-content">
            <div class="container">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <p><i class="fas fa-map-marker-alt"></i>Evansville, IN</p>
                    <p><i class="fas fa-envelope"></i> help@rootlabs.com</p>
                    <div class="social-links">
                        <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <?php include 'contact_form.php';?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
