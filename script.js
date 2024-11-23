// Function to toggle between login and register forms
function toggleForms() {
  const registerForm = document.getElementById("registerForm");
  const loginForm = document.getElementById("loginForm");
  registerForm.style.display =
    registerForm.style.display === "none" ? "block" : "none";
  loginForm.style.display =
    loginForm.style.display === "none" ? "block" : "none";
}

// Event listener for login form submission
document
  .querySelector("#loginForm form")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission behavior
    const formData = new FormData(this);
    const messageDiv = document.getElementById("loginMessage");

    fetch("login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        messageDiv.className = "message"; // Clear existing classes
        if (data.status === "success") {
          messageDiv.classList.add("success");
          window.location.href =
            data.role === "admin"
              ? "admin_dashboard.php"
              : "student_dashboard.php";
        } else {
          messageDiv.classList.add("error");
          messageDiv.textContent = data.message;
        }
      })
      .catch(() => {
        messageDiv.className = "message error";
        messageDiv.textContent = "An error occurred. Please try again.";
      });
  });

// Event listener for register form submission
document
  .querySelector("#registerForm form")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission behavior
    const formData = new FormData(this);
    const messageDiv = document.getElementById("registerMessage");

    fetch("register.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        messageDiv.className = "message"; // Clear existing classes
        if (data.status === "success") {
          messageDiv.classList.add("success");
          messageDiv.textContent = data.message;
          setTimeout(() => {
            toggleForms(); // Switch to login form after a delay
            messageDiv.textContent = ""; // Clear message after toggling
          }, 1500);
        } else {
          messageDiv.classList.add("error");
          messageDiv.textContent = data.message;
        }
      })
      .catch(() => {
        messageDiv.className = "message error";
        messageDiv.textContent = "An error occurred. Please try again.";
      });
  });
