$(document).ready(function () {

  // If already logged in, redirect
  if (localStorage.getItem("token")) {
    window.location.href = "dashboard.html";
  }

  $("#loginBtn").on("click", function () {
    const email = $("#email").val().trim();
    const password = $("#password").val();

    $("#message").removeClass("text-danger text-success").text("");

    if (!email || !password) {
      $("#message").addClass("text-danger").text("Email and password are required");
      return;
    }

    $.ajax({
      url: "/api/login.php", // backend later
      type: "POST",
      dataType: "json",
      data: {
        email,
        password
      },
      success: function (response) {
        /**
         * Expected backend response:
         * {
         *   success: true,
         *   token: "abc123"
         * }
         */

        if (response.success) {
          localStorage.setItem("token", response.token);
          window.location.href = "dashboard.html";
        } else {
          $("#message")
            .addClass("text-danger")
            .text(response.message || "Invalid credentials");
        }
      },
      error: function () {
        $("#message")
          .addClass("text-danger")
          .text("Server error. Please try again.");
      }
    });
  });
});
