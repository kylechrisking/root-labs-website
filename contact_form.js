document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault();

  // Disable form elements
  this.querySelectorAll('input, textarea, select, button').forEach(element => {
    element.disabled = true;
  });

  // Change submit button text
  document.getElementById('submit-button').textContent = 'Submitted successfully';

  // Send form data using fetch API (adjust URL and data as needed)
  fetch('send_email.php', {
    method: 'POST',
    body: new FormData(this)
  })
  .then(response => {
    if (response.ok) {
      console.log('Form submitted successfully');
    } else {
      console.error('Form submission failed');
      // Re-enable form and display error message
      this.querySelectorAll('input, textarea, select, button').forEach(element => {
        element.disabled = false;
      });
      document.getElementById('submit-button').textContent = 'Send Message';
      // Add error message to the form or display a pop-up
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Handle errors, e.g., network errors, server errors
    // Re-enable form and display error message
    this.querySelectorAll('input, textarea, select, button').forEach(element => {
      element.disabled = false;
    });
    document.getElementById('submit-button').textContent = 'Send Message';
    // Add error message to the form or display a pop-up
  });
  alert('Your message has been sent. Thank you!')
});