document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  const submitButton = document.getElementById('submit-button');
  const formContainer = document.getElementById('form-container');

  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    console.log('Form submission event triggered'); // Debugging line

    // Disable the submit button and change its style
    submitButton.disabled = true;
    submitButton.style.backgroundColor = 'gray'; // Change button color to gray
    submitButton.textContent = 'Submitted Successfully'; // Change button text

    // Optionally, you can send the form data using fetch if needed
    fetch('send_email.php', {
      method: 'POST',
      body: new FormData(this)
    })
    .then(response => {
      if (response.ok) {
        console.log('Form submitted successfully');
        // Optionally, you can load a success message into the form container
        formContainer.innerHTML = '<h2>Thank you! Your message has been sent.</h2>';
      } else {
        console.error('Form submission failed');
        // Re-enable the button if submission fails
        submitButton.disabled = false;
        submitButton.style.backgroundColor = ''; // Reset button color
        submitButton.textContent = 'Send Message'; // Reset button text
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Re-enable the button if there's an error
      submitButton.disabled = false;
      submitButton.style.backgroundColor = ''; // Reset button color
      submitButton.textContent = 'Send Message'; // Reset button text
    });
  });
});