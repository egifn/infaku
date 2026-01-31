// toasts.js
class ToastManager {
    constructor() {
        this.container = document.querySelector('.toast-container') || this.createContainer();
        this.init();
    }

    createContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }

    init() {
        // Auto-remove toasts after 5 seconds
        setInterval(() => {
            this.removeExpiredToasts();
        }, 10000);
    }

    show(message, type = 'info', title = null) {
        const toast = document.createElement('div');
        toast.className = `toast ${type} show`;
        toast.dataset.created = Date.now();
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bi ${this.getIcon(type)}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title || this.getTitle(type)}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">
                <i class="bi bi-x"></i>
            </button>
        `;

        this.container.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            this.removeToast(toast);
        }, 10000);

        // Add close event
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.removeToast(toast));

        return toast;
    }

    success(message, title = 'Sukses') {
        return this.show(message, 'success', title);
    }

    error(message, title = 'Error') {
        return this.show(message, 'error', title);
    }

    warning(message, title = 'Peringatan') {
        return this.show(message, 'warning', title);
    }

    info(message, title = 'Informasi') {
        return this.show(message, 'info', title);
    }

    getIcon(type) {
        const icons = {
            'success': 'bi-check-circle',
            'error': 'bi-exclamation-circle',
            'warning': 'bi-exclamation-triangle',
            'info': 'bi-info-circle'
        };
        return icons[type] || 'bi-info-circle';
    }

    getTitle(type) {
        const titles = {
            'success': 'Sukses',
            'error': 'Error',
            'warning': 'Peringatan',
            'info': 'Informasi'
        };
        return titles[type] || 'Informasi';
    }

    removeToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    removeExpiredToasts() {
        const toasts = this.container.querySelectorAll('.toast');
        toasts.forEach(toast => {
            const created = parseInt(toast.dataset.created) || 0;
            if (Date.now() - created > 10000) {
                this.removeToast(toast);
            }
        });
    }

    clearAll() {
        const toasts = this.container.querySelectorAll('.toast');
        toasts.forEach(toast => this.removeToast(toast));
    }
}

// Initialize toast manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.toastManager = new ToastManager();

    // Global toast functions
    window.showToast = function (message, type = 'info') {
        return window.toastManager.show(message, type);
    };

    window.showSuccess = function (message) {
        return window.toastManager.success(message);
    };

    window.showError = function (message) {
        return window.toastManager.error(message);
    };
});
