function containsValue(table, column, value) {
    var contains = false;
    $.ajax({
        type: "GET",
        url: '../contains_value/' + table + '/' + column + '/' + value,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            contains = (response == 1);
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }

    });
    return contains;
}