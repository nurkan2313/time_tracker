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
            data:{ key : total, day: n, checkButtonInput: 'старт' },
            success : function(data) {
                $('div#startTime').text(data);
                $('input#start').val('стоп');
                return data;
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
            data:{ key : stopTime, day: date, checkButtonInput: 'стоп' },
            success : function(data) {
                console.log(data);
                $('div#endTime').text(data);
                $('input#start').val('старт');
                return data;
            },
            error: function (request, status, error) {
                console.log(error);
            }
        });
    }
}
