<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Labs - Projects</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/root-tab-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="path/to/particles.js"></script>
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <main>
        <section class="hero projects-hero">
            <div class="hero-content">
                <h1>Our <span>Projects</span></h1>
                <p>Showcasing our innovative solutions</p>
            </div>
        </section>

        <section class="content-section projects-section">
            <h2 class="section-title">Featured Projects</h2>
            <div class="projects-grid">
                <div class="project-item">
                    <h3>E-commerce Platform</h3>
                    <p>A scalable online store with integrated payment systems.</p>
                    <a href="ecom_project.php" class="project-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="project-item">
                    <h3>Network Security Upgrade</h3>
                    <p>Comprehensive security overhaul for a large corporation.</p>
                    <a href="network_project.php" class="project-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="project-item">
                    <h3>3D Printed Prototypes</h3>
                    <p>Rapid prototyping for almost any application.</p>
                    <a href="3dprints_project.php" class="project-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="project-item">
                    <h3>IoT Smart Home System</h3>
                    <p>Developing a comprehensive smart home automation solution.</p>
                    <a href="iot_project.php" class="project-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>

    <script src="script.js"></script>
</body>
</html>