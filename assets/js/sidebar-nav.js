/**
 * Sidebar Navigation Helper Script
 * 
 * This script helps to automatically expand and highlight
 * the correct menu item in the sidebar based on the current URL
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get current URL path
    const currentPath = window.location.pathname;
    
    // Auto-expand and highlight menus based on current URL
    function highlightCurrentPage() {
        // Handle different URL formats (/books/add and /books/add.php should both work)
        const normalizedPath = currentPath.endsWith('.php') 
            ? currentPath 
            : currentPath + (currentPath.endsWith('/') ? '' : '.php');
        
        // Find all links in the sidebar
        const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
        
        // Books Management section
        if (currentPath.includes('/books')) {
            // Expand Books Management section
            const booksCollapse = document.getElementById('booksManagement');
            if (booksCollapse) {
                booksCollapse.classList.add('show');
                const parentToggle = document.querySelector('a[href="#booksManagement"]');
                if (parentToggle) {
                    parentToggle.setAttribute('aria-expanded', 'true');
                    parentToggle.classList.add('active');
                }
            }
            
            // Highlight specific links within Books section
            if (currentPath.includes('/books/add')) {
                highlightLink('books/add');
            } else if (currentPath.includes('/books/edit')) {
                highlightLink('books/edit');
            } else if (currentPath === '/books' || currentPath === '/books/' || currentPath === '/books/index.php') {
                highlightLink('books/');
            }
        }
        
        // Students Management section
        if (currentPath.includes('/students')) {
            // Expand Students Management section
            const studentsCollapse = document.getElementById('studentsManagement');
            if (studentsCollapse) {
                studentsCollapse.classList.add('show');
                const parentToggle = document.querySelector('a[href="#studentsManagement"]');
                if (parentToggle) {
                    parentToggle.setAttribute('aria-expanded', 'true');
                    parentToggle.classList.add('active');
                }
            }
            
            // Highlight specific links within Students section
            if (currentPath.includes('/students/add')) {
                highlightLink('students/add');
            } else {
                highlightLink('students');
            }
        }
        
        // Loans/Book Issue section
        if (currentPath.includes('/loans')) {
            // Expand Loans Management section
            const loansCollapse = document.getElementById('loanManagement');
            if (loansCollapse) {
                loansCollapse.classList.add('show');
                const parentToggle = document.querySelector('a[href="#loanManagement"]');
                if (parentToggle) {
                    parentToggle.setAttribute('aria-expanded', 'true');
                    parentToggle.classList.add('active');
                }
            }
            
            // Highlight specific links within Loans section
            if (currentPath.includes('/loans/add')) {
                highlightLink('loans/add');
            } else if (currentPath.includes('/loans/verify')) {
                highlightLink('loans/verify');
            } else {
                highlightLink('loans');
            }
        }
        
        // Dashboard
        if (currentPath === '/dashboard' || currentPath === '/dashboard.php') {
            highlightLink('dashboard');
        }
    }
    
    // Helper function to highlight a specific link
    function highlightLink(partialPath) {
        const links = document.querySelectorAll('.sidebar-nav a.nav-link');
        
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes(partialPath)) {
                link.classList.add('active');
                link.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                link.style.borderRadius = '5px';
            }
        });
    }
    
    // Run the highlighting function
    highlightCurrentPage();
}); 