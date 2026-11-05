// Mobile navigation toggle
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });

    // Close menu when clicking a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('open');
        });
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            const navbarHeight = document.getElementById('navbar').offsetHeight;
            const top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    });
});

// Highlight active nav section on scroll
const sections = document.querySelectorAll('section[id]');
const navItems = document.querySelectorAll('.nav-links a');

function onScroll() {
    const scrollPos = window.pageYOffset + document.getElementById('navbar').offsetHeight + 100;
    sections.forEach(section => {
        const top = section.offsetTop;
        const bottom = top + section.offsetHeight;
        const id = section.getAttribute('id');
        if (scrollPos >= top && scrollPos < bottom) {
            navItems.forEach(a => {
                a.classList.remove('active');
                if (a.getAttribute('href') === '#' + id) a.classList.add('active');
            });
        }
    });
}

window.addEventListener('scroll', onScroll);

// Active nav style
const style = document.createElement('style');
style.textContent = `
  .nav-links a.active { color: var(--text-main); }
  .nav-links a.active::after { width: 100%; }
`;
document.head.appendChild(style);

// Toast notification
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${escapeHtml(message)}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function createToastContainer() {
    const c = document.createElement('div');
    c.id = 'toastContainer';
    c.className = 'toast-container';
    document.body.appendChild(c);
    return c;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Confirmation before delete
function confirmDelete(message = 'Are you sure you want to delete this record?') {
    return confirm(message);
}

// Image preview before upload
function setupImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => (preview.src = e.target.result);
            reader.readAsDataURL(file);
        }
    });
}
