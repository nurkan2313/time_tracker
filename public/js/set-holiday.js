$("#yearChooseForChange").change(function(){
    var year = $(this).val();
    $.ajax({
        type: "POST",
        url: "/admin/holiday",
        data: { year: year, month: '', work: 'true' },
        success: function(result) {
            if(result.length > 0) {
                var elem = document.getElementById("month");
                if(elem !== null) {
                    while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                    }
                }
                var list = document.getElementById("month");
                var option;
                for (var i = 0; i < result.length; i++ ) {
                    option = document.createElement("option");
                    option.appendChild(document.createTextNode(result[i]));
                    list.appendChild(option);
                }
            }
        }
    });
});

$("#month").change(function(){

    var month = $(this).val();
    var year = $('#yearChooseForChange').val();

    $.ajax({
        type: "POST",
        url: "/admin/holiday",
        data: {month: month, year: year, work: 'true'},
        success: function(result) {
            if(result.length > 0) {
                var elem = document.getElementById("setDayoff");

                if(elem !== null) {
                    while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                    }
                }

                var list = document.getElementById("setDayoff");
                var li;
                for (var i = 0; i < result.length; i++ ) {
                    li = document.createElement("li");
                    li.setAttribute("class", "list-group-item");
                    li.setAttribute('onclick', "makeSelectable(this);");
                    li.setAttribute('value', result[i]);
                    li.appendChild(document.createTextNode(result[i]));
                    list.appendChild(li);
                }

            }
        }
    });
});
