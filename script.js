console.log("Script loaded");

function toggleForms() {
  const registerForm = document.querySelector(".container");
  const loginForm = document.getElementById("loginForm");
  registerForm.style.display =
    registerForm.style.display === "none" ? "block" : "none";
  loginForm.style.display =
    loginForm.style.display === "none" ? "block" : "none";
}

document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault(); // Prevent the default form submission

  // Create a FormData object to get all form data
  const formData = new FormData(this);

  // Send the form data using AJAX
  fetch("register.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      // Check if registration was successful
      if (data.status === "success") {
        // Display the success message
        document.getElementById("successMessage").textContent = data.message;
        document.getElementById("successMessage").style.display = "block";
      } else {
        // Display the error message
        document.getElementById("errorMessage").textContent = data.message;
        document.getElementById("errorMessage").style.display = "block";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

document.addEventListener("DOMContentLoaded", () => {
  // Get the login form
  const loginForm = document.querySelector("#loginForm form");
  const errorMessage = document.createElement("p");
  errorMessage.id = "loginErrorMessage";
  errorMessage.style.color = "red";
  loginForm.appendChild(errorMessage);

  // Add event listener to handle form submission
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData(this); // Collect form data

    // Send data to the server using fetch
    fetch("login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json()) // Parse JSON response
      .then((data) => {
        if (data.status === "success") {
          // Redirect user based on their role
          if (data.role === "student") {
            window.location.href = "student_dashboard.php";
          } else if (data.role === "admin") {
            window.location.href = "admin_dashboard.php";
          }
        } else {
          // Display error message if login fails
          errorMessage.textContent = data.message;
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        errorMessage.textContent =
          "An unexpected error occurred. Please try again.";
      });
  });
});
