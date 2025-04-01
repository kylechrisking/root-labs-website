<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Root Labs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/root-tab-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.js"></script>
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <main class="about-page">
        <section class="hero about-hero">
            <div class="hero-content">
                <h1>About <span>Root Labs</span></h1>
                <p>Innovative IT solutions for the modern world</p>
            </div>
        </section>

        <section class="about-intro">
            <div class="container">
                <h2>Our Mission</h2>
                <p>Root Labs is a high tech umbrella founded by James Samford and Kyle King, dedicated to developing cutting-edge solutions that drive business success to positively impact your business and customers. By harnessing expertise in Web Development, Cybersecurity, Software Development, Emerging Technologies, Additive Manufacturing, and Engineering & Robotics, we create accessible and sustainable technologies that empower individuals and organizations to achieve their goals across the industrial and commercial spectrums.</p>
                <p>Rooted in the belief that technology empowers change, we strive to leverage our skills to transform small businesses as well as support continuous innovation in technological fields to take on problems that directly impact our local community.</p>
            </div>
        </section>

        <section class="team-section">
            <div class="container">
                <h2>Meet Our Team</h2>
                <div class="team-members">
                    <div class="team-member">
                        <img src="images/james.jfif" alt="James Samford">
                        <div class="member-info">
                            <h3>James Samford</h3>
                            <p class="role">Co-Founder & CEO</p>
                            <p class="bio">James is the Chief Executive and co-founder of Root Labs. With a deep background in IT, cybersecurity, and software development, he has been instrumental in transforming our dream into a reality. James's expertise and strategic leadership have been invaluable in guiding Root Labs' growth and success.</p>
                            <p>Email: <a href="mailto:james@rootlabs.us">james@rootlabs.us</a></p>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="images/kyle.jpg" alt="Kyle King">
                        <div class="member-info">
                            <h3><a href="portfolio/index.php" class="portfolio-link">Kyle King</a></h3>
                            <p class="role">Co-Founder & COO</p>
                            <p class="bio">Kyle, co-founder and COO of Root Labs, brings a solid foundation in IT, web development, and software development to the team. His experience in servicing business-quality IT products has equipped him with a deep understanding of industry standards and challenges. Kyle's technical expertise is crucial in driving Root Labs' innovation and delivering reliable solutions.</p>
                            <p>Email: <a href="mailto:kyle@rootlabs.us">kyle@rootlabs.us</a></p>
                        </div>
                    </div>
<!--                    <div class="team-member">
                        <img src="images/temp_nick.jpg" alt="Nick Ellerbusch">
                        <div class="member-info">
                            <h3>Nick Ellerbusch</h3>
                            <p class="role">Sales Engineer</p>
                            <p class="bio">Nick is a seasoned sales expert with a proven track record in B2B sales. His deep understanding of the local market, combined with his passion for staying ahead of the curve in IT, makes him a valuable asset to our team. Nick's ability to navigate complex sales cycles and build lasting relationships with clients sets him apart.</p>
                            <p>Email: <a href="mailto:nick@rootlabs.us">nick@rootlabs.us</a></p> -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>

    <script>
        // Initialize particles.js
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#3498db' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: false },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#3498db',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'repulse' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>