// modals.js
class ModalManager {
    constructor() {
        this.modalContainer = document.getElementById('modalContainer');
        this.init();
    }

    init() {
        // Initialize modal triggers
        document.addEventListener('click', (e) => {
            const modalTrigger = e.target.closest('[data-modal]');
            if (modalTrigger) {
                e.preventDefault();
                const modalId = modalTrigger.dataset.modal;
                this.showModal(modalId);
            }

            // Close modal
            if (e.target.classList.contains('modal-close') ||
                e.target.closest('.modal-close') ||
                (e.target.classList.contains('modal') && e.target.id !== 'modalContainer')) {
                this.hideModal();
            }
        });
    }

    showModal(modalId) {
        // Remove existing modal
        this.hideModal();

        // Create modal element
        const modal = document.createElement('div');
        modal.className = 'modal show';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-header">
                    <h3 class="modal-title">Modal Title</h3>
                    <button class="modal-close">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
            </div>
        `;

        this.modalContainer.appendChild(modal);

        // Load modal content via AJAX
        this.loadModalContent(modalId, modal);
    }

    loadModalContent(modalId, modalElement) {
        const url = this.getModalUrl(modalId);

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const modalBody = modalElement.querySelector('.modal-body');
                modalBody.innerHTML = html;

                // Initialize form validation if exists
                const form = modalBody.querySelector('form');
                if (form) {
                    this.initializeFormValidation(form);
                }
            })
            .catch(error => {
                console.error('Error loading modal:', error);
                modalElement.querySelector('.modal-body').innerHTML =
                    '<div class="alert alert-danger">Error loading content</div>';
            });
    }

    getModalUrl(modalId) {
        // Map modal IDs to URLs
        const modalUrls = {
            'create-jamaah': '/admin/kelompok/data-jamaah/create',
            'edit-jamaah': '/admin/kelompok/data-jamaah/{id}/edit',
            'create-transaksi': '/admin/kelompok/transaksi/create',
            // Add more modal URLs as needed
        };

        return modalUrls[modalId] || `/${modalId}`;
    }

    initializeFormValidation(form) {
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
            }
        });
    }

    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                this.showError(input, 'This field is required');
            } else {
                this.hideError(input);
            }
        });

        return isValid;
    }

    showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error text-danger';
        errorDiv.textContent = message;
        input.parentNode.appendChild(errorDiv);
        input.classList.add('is-invalid');
    }

    hideError(input) {
        const errorDiv = input.parentNode.querySelector('.form-error');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('is-invalid');
    }

    hideModal() {
        const modal = this.modalContainer.querySelector('.modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
    }
}

// Initialize modal manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.modalManager = new ModalManager();
});
