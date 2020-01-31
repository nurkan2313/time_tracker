(function($){"use strict";})(jQuery);

function checkInputTime() {
    var time = new Date();
    var value = $('input#start').val();

    if(value === '') {
        return;
    }

    if(value === 'старт') {

        var n = time.getDate();
        var h = time.getHours();
        var m = time.getMinutes();
        var total = h + '.' + m;

        $.ajax({
            url:"/user/worktable",
            method:"POST",
            data:{ key : total, day: n, start: 'старт', stop: '' },
            success : function() {
                $('div#startTime').text(total);
                $('input#start').val('стоп');
            },
            error: function (request, status, error) {
                console.log(error);
            }
        });
    }

    if (value === 'стоп') {
        var date = time.getDate();
        var hour = time.getHours();
        var minute = time.getMinutes();
        var stopTime = hour + '.' + minute;

        $.ajax({
            url:"/user/worktable",
            method:"POST",
            data:{ key : stopTime, day: date, start: '', stop: 'стоп' },
            success : function() {
                $('div#endTime').text(stopTime);
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
