class TableHandler {
    constructor(options) {
        this.tableId = options.tableId;
        this.apiUrl = options.apiUrl;
        this.columns = options.columns;
        this.searchable = options.searchable || false;
        this.sortable = options.sortable || false;
        
        this.initializeTable();
        if (this.searchable) this.initializeSearch();
        if (this.sortable) this.initializeSort();
    }

    initializeTable() {
        this.loadData();
        this.initializePagination();
    }

    loadData(page = 1) {
        const params = new URLSearchParams(window.location.search);
        params.set('page', page);

        $.ajax({
            url: `${this.apiUrl}?${params.toString()}`,
            type: 'GET',
            success: (response) => {
                this.renderTable(response.data);
                this.renderPagination(response);
            },
            error: (xhr) => {
                Utilities.handleError(xhr);
            }
        });
    }

    renderTable(data) {
        const tbody = data.map(item => {
            return `
                <tr>
                    ${this.columns.map(column => `<td>${this.getCellContent(item, column)}</td>`).join('')}
                </tr>
            `;
        }).join('');

        $(`#${this.tableId} tbody`).html(tbody);
    }

    getCellContent(item, column) {
        if (typeof column.render === 'function') {
            return column.render(item);
        }
        return item[column.data];
    }

    initializeSearch() {
        const searchInput = $('#searchInput');
        searchInput.on('input', Utilities.debounce(() => {
            this.loadData(1);
        }, 500));
    }

    initializeSort() {
        $(`#${this.tableId} th[data-sortable]`).click(function() {
            const column = $(this).data('column');
            const currentDirection = $(this).data('direction') || 'asc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            
            $(this).data('direction', newDirection);
            Utilities.updateUrlParams({
                sort_by: column,
                sort_direction: newDirection
            });
            this.loadData(1);
        });
    }
}

export default TableHandler; 