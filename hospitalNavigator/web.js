let isLoggedIn = false; // This variable simulates the login state

// Function to open the login modal
function openLoginPage() {
    document.getElementById('loginModal').style.display = 'block';
}

// Function to close the login modal
function closeLoginPage() {
    document.getElementById('loginModal').style.display = 'none';
}

// Function to show the signup form within the login modal
function showSignupForm() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('signupForm').style.display = 'block';
}

// Function to show the login form within the login modal
function showLoginForm() {
    document.getElementById('signupForm').style.display = 'none';
    document.getElementById('loginForm').style.display = 'block';
}

// Function to handle login form submission
function handleLogin(event) {
    event.preventDefault(); // Prevent default form submission

    // Create a FormData object from the login form
    let formData = new FormData(document.getElementById('loginForm'));

    // Send the login data to login.php using fetch
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Login successful")) {
            alert('You are now logged in!');
            isLoggedIn = true;
            closeLoginPage();
            window.location.href = 'search.html'; // Redirect to search.html
        } else {
            alert('Invalid email or password!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to handle signup form submission
function handleSignup(event) {
    event.preventDefault(); // Prevent default form submission

    // Create a FormData object from the signup form
    let formData = new FormData(document.getElementById('signupForm'));

    // Send the signup data to register.php using fetch
    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log('Response from register.php:', data); // Log the response to console for debugging

        if (data.includes("OTP has been sent")) {
            // Show OTP verification form
            showOtpVerificationForm();
        } else if (data.includes("User registered successfully")) {
            alert('Registration successful! Please check your email for OTP.');
        } else if (data.includes("Email already exists")) {
            alert('Email already exists. Please select another account or login to continue.');
        } else {
            alert('Registration failed. Please try again later.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Registration failed due to a network error. Please try again.');
    });
}

// Function to show OTP verification form
function showOtpVerificationForm() {
    // Hide signup form
    document.getElementById('signupForm').style.display = 'none';
    // Show OTP verification form
    document.getElementById('otpVerificationForm').style.display = 'block';
}

// Attach form submit handler for OTP verification form
document.getElementById('otpVerificationForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);

    fetch('verify_otp.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log('Response from verify_otp.php:', data); // Log the response to console for debugging

        if (data === 'User registered successfully') {
            alert('Registration successful! You are now logged in.');
            isLoggedIn = true;
            window.location.href = 'search.html'; // Redirect to search.html
        } else {
            alert('OTP verification failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('OTP verification failed due to a network error. Please try again.');
    });
});

// Function to redirect to the search page
function redirectToSearchPage() {
    location.href = 'search.html';
}

// Event listener for "Find Hospital" button
document.getElementById('findHospitalsBtn').addEventListener('click', function() {
    if (isLoggedIn) {
        redirectToSearchPage(); // Proceed to the search page
    } else {
        openLoginPage(); // Open login modal if not logged in
    }
});

// Close modals when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target == document.getElementById('loginModal')) {
        closeLoginPage();
    }
}

// Attach form submit handlers
document.getElementById('loginForm').addEventListener('submit', handleLogin);
document.getElementById('signupForm').addEventListener('submit', handleSignup);
