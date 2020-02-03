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
