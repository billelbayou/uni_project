function toggleForms() {
  const registerForm = document.querySelector(".container");
  const loginForm = document.getElementById("loginForm");
  registerForm.style.display = registerForm.style.display === "none" ? "block" : "none";
  loginForm.style.display = loginForm.style.display === "none" ? "block" : "none";
}

document.querySelector("#loginForm form").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch("Login.php", {
      method: "POST",
      body: formData,
  })
      .then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              window.location.href = data.role === "admin" ? "admin_dashboard.php" : "student_dashboard.php";
          } else {
              alert(data.message);
          }
      });
});

document.querySelector(".container form").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch("Register.php", {
      method: "POST",
      body: formData,
  })
      .then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              alert(data.message);
              toggleForms();
          } else {
              alert(data.message);
          }
      });
});
