//= ../../node_modules/datatables.net/js/jquery.dataTables.js
//= ../../node_modules/datatables.net-src/js/integration/dataTables.bootstrap4.js

window.dataTablesConfigs = [
    {
        "pagingType": "numbers",
        "pageLength": 100,
        "lengthMenu": [
            [1, 2, 3, 4, 5, 10, 25, 50, 100, 125, 150, -1],
            [1, 2, 3, 4, 5, 10, 25, 50, 100, 125, 150, "все"]
        ],
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "language": {
            "decimal": "",
            "emptyTable": "Данные отсутствуют в таблице",
            "info": "Показано _START_ - _END_ из _TOTAL_ записей",
            "infoEmpty": "Показано 0 - 0 из 0 записей",
            "infoFiltered": "(отфильтровано из _MAX_ записей)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Показать _MENU_ записей",
            "loadingRecords": "<i class=\"fa fa-circle-o-notch fa-spin\"></i>",
            "processing": "",
            "search": "Поиск:",
            "zeroRecords": "Не найдено совпадений",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            },
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        },
        "scrollY": "78vh",
        "scrollX": true,
        "scrollCollapse": true,
    }
];

$(function () {
    $('#admin-navbar .nav-link.active').length && $('#main-link').html($('#admin-navbar .nav-link.active').text());
});

function afterRemoved(response) {
    if (response.error) {
        errorToast(response.error);
        return;
    }
    $('.dataTable').DataTable().ajax.reload(null, false);
    dangerToast(response.message);
}

function callbackAction(response) {
    if (response.error) {
        errorToast(response.error);
        return;
    }
    $('.dataTable').DataTable().ajax.reload(null, false);
    successToast(response.message);
}

function tableCss() {
    try {
        window.table_data = JSON.parse(window.localStorage.table_data) || {};
    } catch (error) {
        window.table_data = {};
    }
    let table = location.pathname.split('/').pop();
    if (!table_data[table]) {
        let hide;
        $('.dataTable th').each((i, el) => {
            if ($(el).is('[data-hide]')) {
                table_data[table] = table_data[table] || {};
                table_data[table][i] = false;
                hide = true;
                window.localStorage.table_data = JSON.stringify(table_data);
            }
        });
        if (!hide) return;
    };
    let ruleCss = '';
    for (let key in table_data[table]) {
        if ($('#table-' + table).length) {
            ruleCss += '.dataTables_scroll thead th:nth-child(' + (+key + 1) + '){display:' + (table_data[table][key] ? 'table-cell' : 'none') + '}';
            ruleCss += '.dataTables_scroll tbody tr td:nth-child(' + (+key + 1) + '){display:' + (table_data[table][key] ? 'table-cell' : 'none') + '}';
        }
    }
    $('#table-css').html(ruleCss);
    let title = $('[data-target="#hide-table-cols"]').attr('title') || $('[data-target="#hide-table-cols"]').attr('data-original-title');
}

$(function () {
    tableCss();
});

$(window).on("interaction", function () {
    if (!$('[data-target="#hide-table-cols"]').length) return;
    let title = $('[data-target="#hide-table-cols"]').attr('data-original-title');
    if (!title) return;
    title = title.replace(/<br><small>.*<\/small>/, '');
    $('[data-target="#hide-table-cols"]').attr('data-original-title', title);
    try {
        window.table_data = JSON.parse(window.localStorage.table_data) || {};
    } catch (error) {
        window.table_data = {};
    }
    let table = location.pathname.split('/').pop();
    if (!table_data[table]) return;
    let cols = [];
    for (let key in table_data[table]) {
        if (table_data[table][key] === false) {
            cols.push($('.dataTable th').eq(key).attr('data-name') || $('.dataTable th').eq(key).text());
        }
    }
    if (!cols.length) return;
    title += '<br><small>Скрытые колонки: ' + cols.map(col => {
        return '<b>' + col + '</b>'
    }).join(', ') + '</small>';
    $('[data-target="#hide-table-cols"]').attr('data-original-title', title);
});

$(document).on('preXhr.dt', '.dataTable', function () {
    loading();
});

$(document).on('draw.dt', '.dataTable', function (res) {
    loading('hide');
    tableCss();
});

function afterResetFilter(response) {
    if (response.error) {
        errorToast(response.error);
        return;
    }
    $('.dataTable').DataTable().ajax.reload(null, false);
    $('#reset-filter').hide();
    successToast(response.message);
}

function toggled(response) {
    if (response.error) {
        errorToast(response.error);
        return;
    }
    $('#tgl-' + response.id).prop({
        'checked': response.act
    });
    $('.toast').toast('hide');
    if (!response.act) {
        dangerToast(response.message);
    } else {
        successToast(response.message);
    }
    setTimeout(() => {
        $('.dataTable').DataTable().ajax.reload(null, false);
    }, 350);
}

$(document).on('click', '[href="#donate"]', function (e) {
    e.preventDefault();
    _modal('#donates', null, 'noremove');
});


$(document).on('xhr.dt', '.dataTable', function (e, settings, json) {
    json.uptime && $('#uptime').length && $('#uptime').html(json.uptime);
});