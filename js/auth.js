$(document).ready(function () {

    $("#signupBtn").click(function () {
        const name = $("#signupName").val();
        const email = $("#signupEmail").val();
        const password = $("#signupPassword").val();

        console.log("Signup:", { name, email, password });
    });

    $("#signinBtn").click(function () {
        const email = $("#signinEmail").val();
        const password = $("#signinPassword").val();

        console.log("Signin:", { email, password });
    });

});
