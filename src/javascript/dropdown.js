document.addEventListener('DOMContentLoaded', function() {
    window.toggleDropdown = function(dropdownId) {
        var dropdownContent = document.getElementById(dropdownId);
        
        if (dropdownContent.tagName === 'SELECT') {
            return; 
        }

        if (!dropdownContent) {
            console.error("Dropdown not found: " + dropdownId);
            return;
        }

        dropdownContent.classList.toggle("show");
        var button = document.querySelector(`button[onclick="toggleDropdown('${dropdownId}')"]`);
        var icon = button.querySelector('.fa.fa-arrow-down');
        if (icon) {
            var isDown = icon.style.transform === 'rotate(180deg)';
            icon.style.transform = isDown ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    };

    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-button') && !event.target.matches('.fa.fa-arrow-down')) {
            var dropdowns = document.querySelectorAll(".dropdown-content:not(select)");
            dropdowns.forEach(function(dropdown) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    var button = dropdown.previousElementSibling;
                    var icon = button.querySelector('.fa.fa-arrow-down');
                    if (icon) {
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        }
    };
});
