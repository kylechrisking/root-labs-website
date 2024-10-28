<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Root Labs - Innovative IT Solutions</title>
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
    <section class="hero home-hero">
      <div class="hero-content">
        <h1>Innovative IT Solutions for the <span>Modern World</span></h1>
        <p>Web Development | 3D Printing | Cybersecurity | Software Development</p>
        <a href="contact.php" class="cta-button">Get Started</a>
      </div>
    </section>

    <section id="our-services" class="content-section">
      <h2 class="section-title">Our Services</h2>
      <div class="services-grid">
          <div class="service-item">
              <i class="fas fa-code"></i>
              <h3>Web Development</h3>
              <p>Custom websites and web applications tailored to your needs.</p>
              <a href="contact.php" class="service-button">Contact Us</a> 
          </div>
          <div class="service-item">
              <i class="fas fa-cubes"></i>
              <h3>3D Printing</h3>
              <p>Rapid prototyping and custom manufacturing solutions.</p>
              <a href="contact.php" class="service-button">Contact Us</a> 
          </div>
          <div class="service-item">
              <i class="fas fa-shield-alt"></i>
              <h3>Cybersecurity</h3>
              <p>Protecting your digital assets with advanced security measures.</p>
              <a href="contact.php" class="service-button">Contact Us</a> 
          </div>
          <div class="service-item">
              <i class="fas fa-laptop-code"></i>
              <h3>Software Development</h3>
              <p>Bespoke software solutions to streamline your business processes.</p>
              <a href="contact.php" class="service-button">Contact Us</a> 
          </div>
      </div>
  </section>

    <section id="featured-projects" class="content-section">
      <h2 class="section-title">Featured Projects</h2>
      <div class="project-grid">
        <div class="project-item">
          <img src="images/project1.jpg" alt="E-commerce Platform">
          <h3>E-commerce Platform</h3>
          <p>Scalable online store with advanced features</p>
        </div>
        <div class="project-item">
          <img src="images/3dprinting.jpg" alt="Custom Medical Devices">
          <h3>Custom 3D Design & Fabrication</h3>
          <p>3D printed tools, parts, accessories, and more.</p>
        </div>
        <div class="project-item">
          <img src="images/project2.jpg" alt="Network Security Overhaul">
          <h3>Network Security Overhaul</h3>
          <p>Comprehensive security upgrade for corporate networks</p>
        </div>
      </div>
    </section>

    <section id="testimonials" class="content-section">
      <h2 class="section-title">Client Testimonials</h2>
      <div class="testimonial-container">
          <div class="testimonial">
              <p>"Great service, always prompt and does what he says he will do."</p>
              <cite>- Brandon Cooper, Newburgh Group</cite>
              <div class="testimonial-rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
              </div>
          </div>
      </div>
  </section>
  </main>

  <footer>
    <?php include 'footer.html'; ?>
  </footer>
</body>
</html>