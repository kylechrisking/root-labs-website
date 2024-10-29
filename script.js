function initCarousel() {
  console.log("Initializing carousel");
  const carousel = document.querySelector('.carousel');
  const items = carousel ? carousel.querySelectorAll('.carousel-item') : [];
  const buttonPrev = document.querySelector('.carousel-button.prev');
  const buttonNext = document.querySelector('.carousel-button.next');

  if (!carousel || items.length === 0 || !buttonPrev || !buttonNext) {
    console.error("Carousel elements not found");
    return;
  }

  let currentIndex = 0;
  const totalItems = items.length;

  function updateCarousel() {
    const translateValue = -currentIndex * 100;
    carousel.style.transform = `translateX(${translateValue}%)`;
    updateIndicators();
  }

  function updateIndicators() {
    document.querySelectorAll('.indicator').forEach((indicator, index) => {
      indicator.classList.toggle('active', index === currentIndex);
    });
  }

  function showNext() {
    currentIndex = (currentIndex + 1) % totalItems;
    updateCarousel();
  }

  function showPrev() {
    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
    updateCarousel();
  }

  buttonNext.addEventListener('click', showNext);
  buttonPrev.addEventListener('click', showPrev);

  // Create indicators
  const indicatorContainer = document.querySelector('.carousel-indicators');
  items.forEach((_, index) => {
    const indicator = document.createElement('div');
    indicator.classList.add('indicator');
    indicator.addEventListener('click', () => {
      currentIndex = index;
      updateCarousel();
    });
    indicatorContainer.appendChild(indicator);
  });

  const carouselContainer = document.querySelector('.carousel-container');
  let autoSlideInterval;

  function startAutoSlide() {
    autoSlideInterval = setInterval(showNext, 5000);
  }

  function stopAutoSlide() {
    clearInterval(autoSlideInterval);
  }

  carouselContainer.addEventListener('mouseenter', stopAutoSlide);
  carouselContainer.addEventListener('mouseleave', startAutoSlide);

  // Start the auto-slide
  startAutoSlide();

  // Initial display
  updateCarousel();
}

// Run the initialization function when the DOM is fully loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCarousel);
} else {
  initCarousel();
}

document.addEventListener('DOMContentLoaded', function() {
    const loadMoreButton = document.getElementById('loadMoreProjects');
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', loadMoreProjects);
    }
});

function loadMoreProjects() {
    const projectsGrid = document.querySelector('.projects-grid');
    const newProjects = [
        {
            title: "Cloud Migration",
            description: "Seamless transition of legacy systems to cloud infrastructure.",
            link: "#"
        },
        {
            title: "AI-Powered Analytics",
            description: "Implementing machine learning for business intelligence.",
            link: "#"
        }
    ];

    newProjects.forEach(project => {
        const projectItem = document.createElement('div');
        projectItem.className = 'project-item';
        projectItem.innerHTML = `
            <h3>${project.title}</h3>
            <p>${project.description}</p>
            <a href="${project.link}" class="project-link">Learn More <i class="fas fa-arrow-right"></i></a>
        `;
        projectsGrid.appendChild(projectItem);
    });

    // Hide the button after loading all projects
    this.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                contactForm.reset();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }
});

// Smooth Scrolling and Parallax Effects
const parallaxSections = document.querySelectorAll('.parallax-section');

parallaxSections.forEach(section => {
  const parallaxBg = section.querySelector('.parallax-bg');
  const content = section.querySelector('.content');

  section.addEventListener('scroll', () => {
    const scrollPosition = section.scrollTop;
    parallaxBg.style.transform = `translateY(${scrollPosition * 0.5}px)`;
    content.style.transform = `translateY(${scrollPosition * -0.5}px)`;
  });
});

// Animated SVG Logo
const animatedLogo = document.getElementById('animated-logo');

if (animatedLogo) {
  const paths = animatedLogo.querySelectorAll('path');
  paths.forEach(path => {
    path.style.strokeDasharray = path.getTotalLength();
    path.style.strokeDashoffset = path.getTotalLength();
    path.style.animation = 'draw 2s linear forwards';
  });
}

// Animated Section Transitions
const animatedSections = document.querySelectorAll('.animate-on-scroll');

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('animate');
    }
  });
}, { threshold: 0.1 });

animatedSections.forEach(section => {
  observer.observe(section);
});

// Particle Background
particlesJS('particles-js', {
  particles: {
    number: { value: 80, density: { enable: true, value_area: 800 } },
    color: { value: "#ffffff" },
    shape: { type: "circle" },
    opacity: { value: 0.5, random: false },
    size: { value: 3, random: true },
    line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
    move: { enable: true, speed: 6, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
  },
  interactivity: {
    detect_on: "canvas",
    events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
    modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
  },
  retina_detect: true
});

// Animated Call-to-Action Buttons
const ctaButtons = document.querySelectorAll('.cta-button');

ctaButtons.forEach(button => {
  const buttonBefore = button.querySelector(':before');
  button.addEventListener('hover', () => {
    buttonBefore.style.left = '100%';
  });
});

// Animated Counters
const counters = document.querySelectorAll('.counter');

counters.forEach(counter => {
  const updateCount = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;
    const increment = target / 200;

    if (count < target) {
      counter.innerText = Math.ceil(count + increment);
      setTimeout(updateCount, 1);
    } else {
      counter.innerText = target;
    }
  };
  updateCount();
});

// Add this to your existing script.js file
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("back-to-top").style.display = "block";
  } else {
    document.getElementById("back-to-top").style.display = "none";
  }
}

document.getElementById("back-to-top").onclick = function() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

// Static Header
fetch('header.html')
  .then(response => response.text())
  .then(data => {
    const headerElement = document.getElementById('header');
    headerElement.innerHTML = data;
  })