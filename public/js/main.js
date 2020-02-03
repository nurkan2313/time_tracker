(function($){"use strict";})(jQuery);

function checkInputTime() {
    var time = new Date();
    var year = time.getFullYear();
    var month = time.getMonth();
    var date = time.getDate();
    var hour = time.getHours();
    var minute = time.getMinutes();
    var total = hour + '.' + minute;

    var value = $('input#start').val();

    if(value === '') {
        return;
    }

    if(value === 'старт') {
        var m = time.getMonth();
        $.ajax({
            url:"/user/worktable",
            method:"POST",
            data:{ key : total, day: date, start: 'старт', stop: '', 'month': time.getMonth() + 1, year: year },
            success : function(res) {
                $('div#startTime').text(total);
                $('input#start').val('стоп');
            },
            error: function (request, status, error) {
                console.log(error);
            }
        });
    }

    if (value === 'стоп') {
        $.ajax({
            url:"/user/worktable",
            method:"POST",
            data:{ key : total, day: date, start: '', stop: 'стоп', month: month + 1, year: year },
            success : function() {
                $('div#endTime').text(total);
                $('input#start').val('старт');
            },
            error: function (request, status, error) {
                console.log(error);
            }
        });
    }
}

$('#myAlert').on('closed.bs.alert', function () {
    $('.alert').alert('close');
});

function changeUsersTime(el) {

    var val = el.parentNode
      .parentNode
      .querySelector('.form-control')
      .value;
    var id = el.getAttribute('data-id');
    var day = el.getAttribute('data-day');
    var month = el.getAttribute('data-month');
    var year = el.getAttribute('data-year');

    if(val === '') {
        return;
    }

    $.ajax({
        url:"/admin/manageUsers",
        method:"POST",
        data:{ key : val, id: id, day: day, start: 'старт', stop: '', 'month': month, year: year },
        success : function() {
            el.parentNode
                .parentNode
                .querySelector('.form-control')
                .value = val
        },
        error: function (request, status, error) {
            console.log(error);
        }
    });
}

function changeUsersTimeStop(el) {

    var val = el.parentNode
        .parentNode
        .querySelector('.form-control')
        .value;
    var id = el.getAttribute('data-id');
    var day = el.getAttribute('data-day');
    var month = el.getAttribute('data-month');
    var year = el.getAttribute('data-year');

    if(val === '') {
        return;
    }

    $.ajax({
        url:"/admin/manageUsers",
        method:"POST",
        data:{ key : val, id: id, day: day, start: '', stop: 'стоп', 'month': month, year: year },
        success : function() {
            el.parentNode
                .parentNode
                .querySelector('.form-control')
                .value = val
        },
        error: function (request, status, error) {
            console.log(error);
        }
    });
}