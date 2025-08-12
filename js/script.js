document.addEventListener("DOMContentLoaded", function () {
    document.forms["send-form"].addEventListener("submit", postData);
});

function postData(formsubmission) {

    const email = encodeURIComponent(document.getElementById("email").value);
    const phoneNumber = encodeURIComponent(document.getElementById("phone_number").value);
    const lastName = encodeURIComponent(document.getElementById("last_name").value);
    const firstName = encodeURIComponent(document.getElementById("first_name").value);
    const company = encodeURIComponent(document.getElementById("company").value);
    const message = encodeURIComponent(document.getElementById("message").value);
    const csrfToken = encodeURIComponent(document.getElementById("csrf_token").value);

    // Get the reCAPTCHA token
    const recaptchaResponse = encodeURIComponent(grecaptcha.getResponse());

    // Check if the user completed the CAPTCHA
    if (!recaptchaResponse) {
        Swal.fire({
            icon: "warning",
            text: "Bitte lösen Sie zuerst das CAPTCHA"
        });
        formsubmission.preventDefault();
        return;
    }

    const params = "email=" + email
        + "&phone_number=" + phoneNumber
        + "&last_name=" + lastName
        + "&first_name=" + firstName
        + "&company=" + company
        + "&message=" + message
        + "&csrf_token=" + csrfToken
        + "&g-recaptcha-response=" + recaptchaResponse;

    const http = new XMLHttpRequest();
    http.open("POST", "script.php", true);

    // Set headers
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    http.onreadystatechange = function () {

        if (http.readyState === 4 && http.status === 200) {

            const res = JSON.parse(http.responseText);

            if (res.status !== 200) {
                Swal.fire({
                    icon: "error",
                    text: res.message
                  });
            } else {
                Swal.fire({
                    icon: "success",
                    text: res.message
                  });
                // Clear the form fields
                document.getElementById("send-form").reset();
            }
            // Reset reCAPTCHA
            grecaptcha.reset();
        }
    }

    http.send(params);
    formsubmission.preventDefault();
}
