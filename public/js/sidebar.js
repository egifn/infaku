// sidebar.js
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSidebar');
    const navbartoggleBtn = document.getElementById('toggleNavbar');
    const sidebar = document.getElementById('sidebar');
    const sidebarHeader = document.querySelector('.sidebar-header');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const navbarToggle = document.querySelector('.navbar-toggle-btn');
    const mainContent = document.getElementById('mainContent');
    const navbar = document.getElementById('navbar');

    // Toggle sidebar function
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        sidebarHeader.classList.toggle('collapsed');
        sidebarToggle.classList.toggle('collapsed');
        navbarToggle.classList.toggle('collapsed');
        navbarToggle.classList.toggle('expanded');
        mainContent.classList.toggle('expanded');
        navbar.classList.toggle('expanded');

        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);

        // Update menu header text
        updateMenuHeaderText();
    }

    // Attach event listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }

    if (navbartoggleBtn) {
        navbartoggleBtn.addEventListener('click', toggleSidebar);
    }

    // Submenu functionality
    document.querySelectorAll('.has-submenu').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.submenu;
            if (!id) return;

            // If sidebar is collapsed, don't toggle submenu
            if (sidebar.classList.contains('collapsed')) {
                closeAllSubmenus();
                return;
            }

            const submenu = document.getElementById(id);
            const isOpen = submenu.classList.contains('open');

            closeAllSubmenus();

            if (!isOpen) {
                submenu.classList.add('open');
                this.classList.add('open');
            }
        });
    });

    // Close all submenus
    function closeAllSubmenus() {
        document.querySelectorAll('.submenu').forEach(s => s.classList.remove('open'));
        document.querySelectorAll('.has-submenu').forEach(b => b.classList.remove('open'));
    }

    // Close submenus when sidebar is collapsed
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === 'class' &&
                sidebar.classList.contains('collapsed')) {
                closeAllSubmenus();
            }
        });
    });

    observer.observe(sidebar, {
        attributes: true
    });

    // Update menu header text function
    window.updateMenuHeaderText = function () {
        const menuHeaders = document.querySelectorAll('.menu-header');
        menuHeaders.forEach(header => {
            const fullText = header.querySelector('.full-text');
            const shortText = header.querySelector('.short-text');

            if (sidebar.classList.contains('collapsed')) {
                if (fullText) fullText.style.display = 'none';
                if (shortText) shortText.style.display = 'inline';
            } else {
                if (fullText) fullText.style.display = 'inline';
                if (shortText) shortText.style.display = 'none';
            }
        });
    };

    // Initialize menu header text
    updateMenuHeaderText();

    // Dropdown functionality
    document.querySelectorAll('.dropbtn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const dropdownContent = this.nextElementSibling;
            if (dropdownContent) {
                dropdownContent.classList.toggle('show');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
});
