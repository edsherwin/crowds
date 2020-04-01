const iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
if (iOS) {
    $("#_is_ios").val("yes");
}

(function(d, s, id) {
    var js,
        fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
})(document, "script", "facebook-jssdk");

window.fbAsyncInit = function() {
    FB.init({
        appId: process.env.MIX_FACEBOOK_APP_ID,
        cookie: true,
        xfbml: true,
        version: "v6.0",
    });

    FB.AppEvents.logPageView();
};

function checkLoginState() {
    FB.getLoginStatus(function(response) {
        if (response.status === "connected") {
            $("#_fb_access_token").val(response.authResponse.accessToken);
            $("#step-two-form").trigger("submit");
        }
    });
}

window.checkLoginState = checkLoginState;

const firebaseConfig = {
    apiKey: process.env.MIX_FIREBASE_API_KEY,
    projectId: process.env.MIX_FIREBASE_PROJECT_ID,
    messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID,
    appId: process.env.MIX_FIREBASE_APP_ID,
};

if ($("#setup_step").val() == "3") {
    try {
        $(".button-loader").hide();

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        $("#enable-notifications").click(function() {
            $(".button-loader").show();
            $(".button-text").hide();

            messaging
                .getToken()
                .then(function(token) {
                    $(".button-loader").hide();
                    $(".button-text").show();
                    $("#_fcm_token").val(token);
                    $("#notifications-form").trigger("submit");
                })
                .catch(function() {
                    const retry = confirm(
                        "There was a problem enabling notifications on your device. Would you like to retry?"
                    );
                    if (retry) {
                        window.location.reload();
                    } else {
                        $("#notifications-form").trigger("submit");
                    }
                });
        });
    } catch (err) {
        $("#enable-notifications").hide();
        $(".alert")
            .text(
                "Sorry. But your device doesn't support notifications. Click the finish setup button to finish account setup."
            )
            .removeClass("alert-info")
            .addClass("alert-warning");
        alert(
            "Sorry. But your device doesn't support notifications. Click the finish setup button to finish account setup."
        );
    }
}

const setup_modal = parseInt($("#setup_step").val());
$(`#user-setup-modal-${setup_modal}`).modal();

$("#province").change(function() {
    const id = $(this).val();
    $.get(`/province/${id}`, (cities) => {
        let html = '<option value="">Select city</option>';
        cities.forEach((row) => {
            html += `<option value="${row.id}">${row.name}</option>`;
        });

        $("#city").html(html);
        $("#barangay").html("");
    });
});

$("#city").change(function() {
    const id = $(this).val();
    $.get(`/city/${id}`, (barangays) => {
        let html = '<option value="">Select barangay</option>';
        barangays.forEach((row) => {
            html += `<option value="${row.id}">${row.name}</option>`;
        });

        $("#barangay").html(html);
    });
});
