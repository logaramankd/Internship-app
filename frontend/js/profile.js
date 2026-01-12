$(document).ready(function () {

    const sessionKey = localStorage.getItem("session_key");
    const userName = localStorage.getItem("user_name");
    const userEmail = localStorage.getItem("user_email");

    if (!sessionKey) {
        window.location.href = "auth.html";
        return;
    }

    $("#profileName").text(userName);
    $("#profileEmail").text(userEmail);

    // LOAD PROFILE
    $.ajax({
        url: "https://internship-app-lz1o.onrender.com/api/profile.php",
        method: "GET",
        data: { session_key: sessionKey },
        success: function (res) {
            if (res.status === "success" && res.data) {
                $("#age").val(res.data.age ?? "");
                $("#dob").val(res.data.dob ?? "");
                $("#contact").val(res.data.contact ?? "");
                $("#address").val(res.data.address ?? "");
                $("#gender").val(res.data.gender ?? "");
                $("#designation").val(res.data.designation ?? "");
                $("#company").val(res.data.company ?? "");
            }
        },
        error: function () {
            alert("Failed to load profile data");
        }
    });
    const firstLetter = userName ? userName.charAt(0).toUpperCase() : "U";
    $("#avatarLetter").text(firstLetter);

    // SAVE PROFILE
    $("#saveProfileBtn").click(function () {

        const age = $("#age").val().trim();
        const dob = $("#dob").val().trim();
        const contact = $("#contact").val().trim();
        const address = $("#address").val().trim();
        const designation = $("#designation").val().trim();
        const company = $("#company").val().trim();

        if (!age || !dob || !contact || !address) {
            alert("All fields are required");
            return;
        }

        $.ajax({
            url: "https://internship-app-lz1o.onrender.com/api/profile.php",
            method: "POST",
            data: {
                session_key: sessionKey,
                age,
                dob,
                contact,
                address,
                designation,
                company,
            },
            success: function (res) {
                if (res.status === "success") {
                    alert("Profile saved successfully");
                } else {
                    alert(res.message || "Failed to save profile");
                }
            },
            error: function () {
                alert("Server error while saving profile");
            }
        });

    });

    // LOGOUT
    $("#logoutBtn").click(function () {
        $.post("/internship-app/backend/api/logout.php", { session_key: sessionKey }, function () {
            localStorage.clear();
            window.location.href = "/internship-app/frontend/pages/SignUp.html";
        });
    });

});
