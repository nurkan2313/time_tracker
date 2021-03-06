(function($){"use strict";})(jQuery);

function checkInputTime() {
    let time = new Date();
    let year = time.getFullYear();
    let month = time.getMonth();
    let date = time.getDate();
    let hour = time.getHours();
    let minute = time.getMinutes();
    let total = hour + '.' + minute;

    let value = $('input#start').val();

    if(value === '') {
        return;
    }

    if(value === 'старт') {
        let m = time.getMonth();
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

$("#exampleFormControlSelect1").change(function(){
    let year = $(this).val();

    $.ajax({
        type: "POST",
        url: "/admin/holiday",
        data: {year: year, month: ''},
        success: function(result) {
            if(result.length > 0) {

                let elem = document.getElementById("showMonth");
                if(elem !== null) {
                    while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                    }
                }

                let list = document.getElementById("showMonth");
                let option;
                for (let i = 0; i < result.length; i++ ) {
                    option = document.createElement("option");
                    option.appendChild(document.createTextNode(result[i]));
                    list.appendChild(option);
                }

            }

        }
    });
});

$("#showMonth").change(function(){

    let month = $(this).val();
    let year = $('#exampleFormControlSelect1').val();
    $.ajax({
        type: "POST",
        url: "/admin/holiday",
        data: {month: month, year: year, work: false},
        success: function(result) {
            if(result.length > 0) {
                let elem = document.getElementById("days");
                if (elem !== null) {
                    while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                    }
                }

                let list = document.getElementById("days");
                let li;
                for (let i = 0; i < result.length; i++) {
                    li = document.createElement("li");
                    li.setAttribute("class", "list-group-item");
                    li.appendChild(document.createTextNode(result[i]));
                    list.appendChild(li);
                }

            }
        }

    });
});

function makeSelectable(el) {
    if(el.classList.contains('selected')) {
        console.log(el.classList);
        el.setAttribute('class', 'list-group-item')
    } else {
        el.setAttribute('class', 'list-group-item selected')
    }
}

function getAllSelected() {
    let elem = document.getElementById("setDayoff");
    let li = elem.getElementsByClassName("selected");
    let updateDay = [];
    let holiday_month = $("#month").val();

    for (let i = 0; i < li.length; i++) {
        updateDay.push(li[i].value);
    }

    $.ajax({
        type: "POST",
        url: "/admin/holiday",
        data: {year : '', month : '', work : 'false', updateDay: updateDay, holiday_month: holiday_month},
        dataType: 'json',
        success: function(result) {
            console.log(result);
        }
    });

}

function hide() {

    $('#mytab1 tr')
        .show()
        .filter(function () {
            let match = true;

            $(this).find('#start').each(function () {
                console.log($(this));
                match = false;
            });

            return match;
        })
        .hide();
}

function show() {
    $('#mytab1 tr')
        .show()
        .filter(function () {
            let match = false;

            $(this).find('#start').each(function () {
                console.log($(this));
                match = true;
            });

            return match;
        })
        .show();
}