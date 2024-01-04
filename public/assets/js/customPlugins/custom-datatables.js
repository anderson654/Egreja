function CustomDataTables({
    elemetTotalPerPage,
    elementTable,
    elementSearch,
    elementNext,
    elementPrevious,
    elementFistPage,
    elementLastPage
}) {

    this.elementoTable = elementTable;
    this.elementSearch = $(elementSearch);
    this.elementNext = $(elementNext);
    this.elementPrevious = $(elementPrevious);
    this.elementFistPage = $(elementFistPage);
    this.elementLastPage = $(elementLastPage);

    const table = new DataTable(elementTable, {
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas"
            // Outras configurações de idioma...
        },
        lengthChange: false,
        dom: '',
        // searching: false,
        pageLength: 10,
        "order": [
            [3, 'desc']
        ]
    });

    $(document).ready(function() {
        $(elemetTotalPerPage).on("change", function() {
            var novoTamanho = $(this).val();
            table.page.len(novoTamanho).draw();
            definePages((table.page.info().page + 1));
        });
    });

    this.elementSearch.on("keyup", function() {
        table.search($(this).val()).draw();
        definePages(table.page() + 1);
    });

    this.elementNext.on('click', function() {
        table.page('next').draw('page');
        definePages(table.page() + 1);
    });

    this.elementPrevious.on('click', function() {
        table.page('previous').draw('page');
        definePages(table.page() + 1);
    });

    this.elementFistPage.on('click', function() {
        table.page(0).draw('page');
        definePages(table.page() + 1);
    });

    this.elementLastPage.on('click', function() {
        table.page('last').draw('page');
        definePages(table.page() + 1);
    });

    function setPages(array, selectPage = 1) {
        const elements = [];
        const totalPages = table.page.info().pages;

        $("#paginate").empty();
        const pages = array.map((value) => {
            const element = $(`<li class="page-item ${selectPage == value?'active':''}">
                        <a class="page-link" href="#">${value}</a>
                    </li>`);
            element.on('click', function() {
                definePages(value);
            })
            $("#paginate").append(element);
        });
    }


    function definePages(selectPage) {
        table.page((selectPage - 1)).draw('page');
        // console.log(selectPage);
        const maxPages = 5;
        const totalPages = table.page.info().pages;

        if (maxPages >= totalPages) {
            const finalArray = Array(totalPages).fill().map((_, index) => index + 1);
            setPages(finalArray, selectPage);
            return;
        } else {
            const verifyExistPagesIndex = (maxPages - 1) / 2;
            const finalArray = [];
            finalArray.push(selectPage);
            const totalArrayPages = Array(totalPages).fill().map((_, index) => index + 1);

            let count = 1;
            while (finalArray.length != maxPages && count != maxPages) {
                const nextElement = totalArrayPages[(selectPage - 1) + count];
                nextElement && finalArray.push(nextElement);

                const prevElement = totalArrayPages[(selectPage - 1) - count];
                prevElement && finalArray.unshift(prevElement);
                count++;
            }

            setPages(finalArray, selectPage);
            return;
        }
    }
    definePages(1);
}