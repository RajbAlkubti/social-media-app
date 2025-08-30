
function toggleDropdown(button) {
        const menu = button.nextElementSibling;
        const allMenus = document.querySelectorAll('.more-menu');
        allMenus.forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.more-button') && !e.target.closest('.more-menu')) {
            document.querySelectorAll('.more-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });