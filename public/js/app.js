// app.js
document.addEventListener('DOMContentLoaded', function () {
    // Initialize components
    initializeComponents();

    // Handle form submissions
    handleFormSubmissions();

    // Initialize table functionality
    initializeTables();

    // Setup event listeners
    setupEventListeners();
});

function initializeComponents() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function () {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.title;
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left = `${rect.left + rect.width / 2}px`;
            tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;

            this._tooltip = tooltip;
        });

        element.addEventListener('mouseleave', function () {
            if (this._tooltip) {
                this._tooltip.remove();
                delete this._tooltip;
            }
        });
    });
}

function handleFormSubmissions() {
    // AJAX form submission
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            }

            fetch(this.action, {
                    method: this.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.message) {
                            window.toastManager.success(data.message);
                            this.reset();
                        }
                    } else {
                        window.toastManager.error(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.toastManager.error('Network error occurred');
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = submitBtn.dataset.originalText || 'Submit';
                    }
                });
        });
    });
}

function initializeTables() {
    // Data table functionality
    document.querySelectorAll('.data-table').forEach(table => {
        // Sortable headers
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function () {
                const column = this.dataset.column;
                const direction = this.dataset.direction === 'asc' ? 'desc' : 'asc';
                this.dataset.direction = direction;

                sortTable(table, column, direction);
                updateSortIcons(table, column, direction);
            });
        });

        // Row selection
        const selectAll = table.querySelector('thead [type="checkbox"]');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const checkboxes = table.querySelectorAll('tbody [type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    });
}

function sortTable(table, column, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const aValue = a.querySelector(`td:nth-child(${getColumnIndex(table, column)})`).textContent;
        const bValue = b.querySelector(`td:nth-child(${getColumnIndex(table, column)})`).textContent;

        if (direction === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });

    // Reorder rows
    rows.forEach(row => tbody.appendChild(row));
}

function getColumnIndex(table, columnName) {
    const headers = table.querySelectorAll('th');
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].dataset.column === columnName) {
            return i + 1;
        }
    }
    return 1;
}

function updateSortIcons(table, sortedColumn, direction) {
    const headers = table.querySelectorAll('th');
    headers.forEach(header => {
        const icon = header.querySelector('.sort-icon');
        if (icon) icon.remove();

        if (header.dataset.column === sortedColumn) {
            const sortIcon = document.createElement('i');
            sortIcon.className = `sort-icon bi bi-chevron-${direction === 'asc' ? 'up' : 'down'}`;
            sortIcon.style.marginLeft = '5px';
            header.appendChild(sortIcon);
        }
    });
}

function setupEventListeners() {
    // Print functionality
    document.querySelectorAll('.btn-print').forEach(btn => {
        btn.addEventListener('click', function () {
            window.print();
        });
    });

    // Export functionality
    document.querySelectorAll('.btn-export').forEach(btn => {
        btn.addEventListener('click', function () {
            const format = this.dataset.format || 'excel';
            const url = this.dataset.url || window.location.href;
            exportData(format, url);
        });
    });

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
}

// Global utility functions
function exportData(format, url) {
    const exportUrl = `${url}?export=${format}&${new URLSearchParams(window.location.search).toString()}`;
    window.open(exportUrl, '_blank');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function () {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}
