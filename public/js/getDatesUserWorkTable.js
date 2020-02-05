$("#yearUserTable").change(function(){
    let year = $(this).val();

    $.ajax({
        type: "POST",
        url: "/user/worktable",
        data: {yearUserTbale: year},
        success: function(result) {
            if(result.length > 0) {

                let elem = document.getElementById("monthUserTable");
                if(elem !== null) {
                    while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                    }
                }

                let list = document.getElementById("monthUserTable");
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