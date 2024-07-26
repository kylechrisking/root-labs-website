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