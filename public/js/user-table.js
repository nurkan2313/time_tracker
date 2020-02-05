function changeUsersTime(el) {

    let val = el.parentNode
        .parentNode
        .querySelector('.form-control')
        .value;
    let id = el.getAttribute('data-id');
    let day = el.getAttribute('data-day');
    let month = el.getAttribute('data-month');
    let year = el.getAttribute('data-year');

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

    let val = el.parentNode
        .parentNode
        .querySelector('.form-control')
        .value;
    let id = el.getAttribute('data-id');
    let day = el.getAttribute('data-day');
    let month = el.getAttribute('data-month');
    let year = el.getAttribute('data-year');

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