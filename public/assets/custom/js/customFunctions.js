$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var baseUrl = $('#baseUrl').val();

function showSuccessToast(message) {
    var toastButton = document.getElementById('toastButton');
    if (toastButton) {
        toastButton.click();

        var elements = document.getElementsByClassName('text-2');
        for (var i = 0; i < elements.length; i++) {
            elements[i].innerHTML = message;
        }
    }
}

function getAjaxData() {

    var flag = $('#flag').val();
    var actionUrl;
    var modalId;
    var dataa;
    var form;
    if(flag == 1) {
        form = $('#submit');
        actionUrl = $('#ajaxLoadUrl').val();
        modalId = $('#modalId').val();
        dataa = form.serialize();
    } else {
        form = $('#list_data');
        dataa = form.serialize();
        actionUrl = form.attr('action');
    }

    $('#response').html('<div class="loading"></div>');
    $.ajax({
        type: "get",
        url: actionUrl,
        data: dataa,
        async: true,
        cache: false,
        success: function (data) {
            $('#' + modalId).modal('hide');
            $('#response').html(data);
        }
    });
}

$('#submit').submit(function(e) {
    e.preventDefault();
    var url = $('#submit').attr('action');
    //this is condition is for filter pages
    if(url) {
        $.ajax({
            url: $('#submit').attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                getAjaxData();
                if (response.success) {
                    showSuccessToast(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    } else {
        getAjaxData();
    }
});

$('#submit2').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: $('#submit2').attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#showMasterEditModal').modal('hide');
            getAjaxData();
            if (response.success) {
                showSuccessToast(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
});

function deleteTableRecord(id, table_name) {
    $.ajax({
        url: baseUrl + '/deleteTableRecord',
        method: 'POST',
        data: {
            id: id,
            table_name: table_name,
        },
        success: function(response) {
            getAjaxData();
            if (response.success) {
                showSuccessToast(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}