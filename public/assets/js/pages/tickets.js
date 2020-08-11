var tableTickets;

const dataLoader = {
    totalTickets: 0,
    totalTicketsMonth: 0,
    myTickets: 0,
    myOpenTickets: 0
}

function populateCardsDash(data) {
    $("#totalTickets").text(data.totalTickets);
    $("#totalTicketsMonth").text(data.totalTicketsMonth);
    $("#myTickets").text(data.myTickets);
    $("#openTickets").text(data.myOpenTickets);
}

function loadCardTickets(monthYear) {
    $.ajax({
        url: '/getCardTicket',
        method: "GET",
        data: {
            monthYear: monthYear,
        },
        dataType: 'JSON',
        success: (d) => {
            if (d.status = 1) populateCardsDash(d.data)
        },
        beforeSend: (b) => {
            populateCardsDash(dataLoader)
            $("#searchTicket").attr('disabled', true).find("i").removeClass("fa-search").addClass("fa-spinner fa-spin")
        },
        complete: (c) => {
            $('#searchTicket').removeAttr('disabled').find('i').removeClass('fa-spinner fa-spin').addClass('fa-search')
        },
        error: (e) => {
            $('#searchTicket').removeAttr('disabled').find('i').removeClass('fa-spinner fa-spin').addClass('fa-search')
        }
    });
}

function loadChartTickets(monthYear) {
    $.ajax({
        url:  '/getChartTickets',
        method: "GET",
        data: {
            monthYear: monthYear
        },
        dataType: 'JSON',
        success: (d) => {
            if (d.status = 1) geraChartLine(d.data.categories, d.data.data, `Total Tickets Opened per Day`);
        },
        beforeSend: (b) => {},
        complete: (c) => {},
        error: (e) => {
            console.log(e)
        }
    });
}

function loadTableTickets() {
    tableTickets = $(`#tableTickets`).DataTable({
        sPaginationType: "full_numbers",
        destroy: true,
        searching: true,
        responsive: false,
        language: translateTable(),
        order: [[0, "DESC"]],
        ajax: {
            url: `/getTickets`,
            dataSrc: (data) => {
                return data.data || []
            },
            dataType: 'JSON',
            cache: true,
            error: (e) => {
                $("#btnSync").removeAttr("disabled").find("i").removeClass("fa-spinner fa-spin").addClass("fa-sync")
            },
            beforeSend: () => {
                $("#btnSync").attr("disabled", true).find("i").removeClass("fa-sync").addClass("fa-spinner fa-spin")
            },
            complete: () => {
                $("#btnSync").removeAttr("disabled").find("i").removeClass("fa-spinner fa-spin").addClass("fa-sync")
            }
        },
        lengthChange: false,
        pageLength: 50,
        columns: [
            {
                data: "cod",
            },
            {
                data: "inicio"
            },
            {
                data: "nome_cliente"
            },
            {
                data: "descricao"
            },
            {
                data: "andamento",
                class: "text-center",
            },
            {
                data: "prioridade",
                class: "text-center"
            },
            /*{
                data: "nome_tipo"
            },*/
            {
                data: "nome_modulo"
            },
            {
                data: "nome_atendente",
                class: "text-right",
                render: (data, type, row, meta) => {
                    return (data)
                }
            }
        ],
        dom: "Bfrtip",
        createdRow: function (row, data) {

            if (data.prioridade < 3)
                $('td', row).eq(5).css('background', '#79a7d0').css('color', '#fff');
            else
                $('td', row).eq(5).css('background', '#f64f5c').css('color', '#fff');

            switch (parseInt(data.andamento)) {
                case 0: $('td', row).eq(4).css('background', '#dc8512').css('color', '#fff'); break;
                case 1: $('td', row).eq(4).css('background', '#64738F').css('color', '#fff'); break;
                case 2: $('td', row).eq(4).css('background', '#514cf7').css('color', '#fff'); break;
                case 3: $('td', row).eq(4).css('background', '#f6c763').css('color', '#fff'); break;
                case 4: $('td', row).eq(4).css('background', '#2ecc71').css('color', '#fff'); break;
            }
        },
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, row, meta) {
                    return data.substring(0, 60)
                }
            },
            {
                targets: 4,
                render: function (data, type, row, meta) {
                    switch (parseInt(data)) {
                        case 0: return "Aguardando Atribuição";
                        case 1: return "Atribuído";
                        case 2: return "Em Execução";
                        case 3: return "Parado";
                        case 4: return "Encerrado";
                    }
                }
            },
            {
                targets: 6,
                render: function (data, type, row, meta) {
                    return data.substring(0, 15)
                }
            }
        ]
    });

    $('#tableTickets .filtros th').each(function () {
        $(this).html('<input type="text" style="width: 100% !important;height: 24px !important; margin-bottom: 4px !important; border-radius: 3px !important" class="form-control hidden-xs" placeholder=""/>');
    });

    tableTickets.columns().eq(0).each(function (indice) {
        $('input', $('.filtros th')[indice]).on('keyup change', function () {
            tableTickets.column(indice).search(this.value).draw();
        });
    });

    $('.dataTables_filter').css('display', 'none');

    $(`#tableTickets tbody`).on('click', 'td', function() {

        let tr  = $(this).closest('tr');
        let row = dtTable.row(tr);
    });
}
