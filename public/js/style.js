// components.js
// sidebar
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var content = document.getElementById('content');
    sidebar.classList.toggle('sidebar-collapsed');
    content.classList.toggle('sidebar-collapsed');
}

function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var content = document.getElementById('content');
    sidebar.classList.toggle('sidebar-collapsed');
    content.classList.toggle('sidebar-collapsed');
}


// Dropdown
document.addEventListener('DOMContentLoaded', () => {
    // Click dropdown
    const clickDropdowns = document.querySelectorAll('.click-dropdown .dropbtn');
    clickDropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', () => {
            const dropdownContent = dropdown.nextElementSibling;
            dropdownContent.classList.toggle('show');
        });
    });

    // Animated dropdown
    const animatedDropdowns = document.querySelectorAll('.animated-dropdown .dropbtn');
    animatedDropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', () => {
            const dropdownContent = dropdown.nextElementSibling;
            dropdownContent.classList.toggle('show');
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(dropdown => {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            });
        }
    };
});


// Toast
function showToast(toastId) {
    const toast = document.getElementById(toastId);
    if (!toast) return;
    toast.classList.add('show');
}

function hideToast(toastId) {
    const toast = document.getElementById(toastId);
    if (!toast) return;
    toast.classList.remove('show');
}

// Event listeners for toast close
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast-close]').forEach(button => {
        button.addEventListener('click', () => {
            const toastId = button.getAttribute('data-toast-close');
            hideToast(toastId);
        });
    });
});


// Carousel
let slideIndex = 0;

function showSlides() {
    const slides = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.carousel-indicators li');
    
    slides.forEach((slide, index) => {
        slide.style.transform = `translateX(${-slideIndex * 100}%)`;
        indicators[index].classList.remove('active');
    });
    
    indicators[slideIndex].classList.add('active');
}

function moveSlide(n) {
    const slides = document.querySelectorAll('.carousel-item');
    slideIndex = (slideIndex + n + slides.length) % slides.length;
    showSlides();
}

function currentSlide(n) {
    slideIndex = n;
    showSlides();
}

document.addEventListener('DOMContentLoaded', () => {
    showSlides();

    // Auto move to next slide every 3 seconds
    setInterval(() => {
        moveSlide(1);
    }, 7000);
});
