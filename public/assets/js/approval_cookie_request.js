$(document).ready(function () {
    /*const urlParams = new URLSearchParams(window.location.search);
    var id = urlParams.get('id').trim();

    if (!(id == "null")) {
        sendRequest(id);
    }*/
    sendRequest(user_id);

});


function sendRequest(id) {

    $.ajax({
        type: "GET",
        url: '/sendRequest/' + getBrowser() + '/' + id,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}


function getBrowser() {
    const userAgent = navigator.userAgent;

    if (userAgent.includes("Chrome")) {
        return "Chrome";
    } else if (userAgent.includes("Firefox")) {
        return "Firefox";
    } else if (userAgent.includes("Safari") && !userAgent.includes("Chrome")) {
        return "Safari";
    } else if (userAgent.includes("Edg")) {
        return "Edg";
    } else if (userAgent.includes("Trident") || userAgent.includes("MSIE")) {
        return "InternetExplorer";
    } else {
        return "Unknown";
    }
}