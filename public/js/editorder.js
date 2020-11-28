"use strict";

let dialog,
    awb = $("#awb_no"),
    allFields = $([]).add(name);

dialog = $("#dialog-form").dialog({
    autoOpen: false,
    height: 150,
    width: 350,
    modal: true,
    buttons: {
        "Save": function () {
            dialog.dialog("close");
            const options = dialog.dialog("option");
            makeRequest(options.order_id, options.status);
        },
        Cancel: function () {
            /*
            Below code doesnt seems to be working
            hence refreshing the page

            const order_id = dialog.dialog("option", "order_id");
            const current_status = $('#singleorderstatus' + order_id + ' > span').html().toLowerCase();
            $('select#status' + order_id).selectmenu();
            $('select#status' + order_id + ' option:selected').attr("selected", null);
            $('select#status' + order_id + ' option[value=' + current_status + ']').attr("selected", "selected");
            $('select#status' + order_id).selectmenu('refresh', true);*/

            dialog.dialog("close");
            location.reload();
        }
    },
    close: function () {
        allFields.removeClass("ui-state-error");
    }
});

function changeStatus(id) {
    let status = $('#status' + id).val();
    if (status === 'shipped') {
        dialog.dialog("option", {'order_id': id, 'status': status});
        dialog.dialog("open");
    } else {
        makeRequest(id, status);
    }
}

function makeRequest(id, status) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        url: url + '/' + id,
        data: {
            status: status,
            awb: awb.val()
        },
        beforeSend: function () {
            $('.preL').fadeIn();
            $('.preloader3').fadeIn();
        },
        success: function (data) {
            $('#singleorderstatus' + data.id).html('');
            if (data.status == 'pending') {
                $('#singleorderstatus' + data.id).append('<span class="label label-default">' + data.status.charAt(0).toUpperCase() + data.status.slice(1) + '</span>');
            }
            if (data.status == 'processed') {
                $('#singleorderstatus' + data.id).append('<span class="label label-info">' + data.status.charAt(0).toUpperCase() + data.status.slice(1) + '</span>');
            }
            if (data.status == 'shipped') {
                $('#singleorderstatus' + data.id).append('<span class="label label-primary">' + data.status.charAt(0).toUpperCase() + data.status.slice(1) + '</span>');
            }
            if (data.status == 'delivered') {
                $('#singleorderstatus' + data.id).append('<span class="label label-success">' + data.status.charAt(0).toUpperCase() + data.status.slice(1) + '</span>');
                $('#canbtn' + data.id).remove();
                $('#fullcanbtn').remove();
            }
            if (data.status == 'returned') {
                $('#singleorderstatus' + data.id).append('<span class="label label-danger">' + data.status.charAt(0).toUpperCase() + data.status.slice(1) + '</span>');
            }
            if (data.status == 'return_request') {
                $('#singleorderstatus' + data.id).append('<span class="label label-warning">Return Request</span>');
            }
            if (data.status == 'cancel_request') {
                $('#singleorderstatus' + data.id).append('<span class="label label-warning">Cancelation Request</span>');
            }
            $('#ifnologs').html('');
            if (userrole == 'a') {
                $('#logs').prepend('<p><small>' + data.lastlogdate + ' • For Order <b>' + data.proname + '( ' + data.variant + ' ) [' + data.invno + ']</b>: <span class="required"><b>' + username + '</b> (Admin)</span> changed status to <b>' + data.dstatus + '</b> </small></p>');
            } else {
                $('#logs').prepend('<p><small>' + data.lastlogdate + ' • For Order ' + data.proname + '(' + data.variant + ')[' + data.invno + ']: <span class="text-blue"><b>' + username + ' (Vendor)</b></span> changed status to <b>' + data.dstatus + ' </b></small></p>');
            }
            var animateIn = "lightSpeedIn";
            var animateOut = "lightSpeedOut";
            PNotify.success({
                title: data.dstatus,
                text: 'For ' + data.invno + ' status changed to ' + data.dstatus,
                icon: 'fa fa-check-circle',
                modules: {
                    Animate: {
                        animate: true,
                        inClass: animateIn,
                        outClass: animateOut
                    }
                }
            });
            $('.preL').fadeOut();
            $('.preloader3').fadeOut();
        }
    });
}
