document.addEventListener('DOMContentLoaded', function() {
    window.toggleFilterSection = function() {
        var filterSection = document.getElementById('filter-section');
        filterSection.style.display = (filterSection.style.display === 'none' ? 'block' : 'none');
    };

    window.toggleSelection = function(element) {
        element.classList.toggle('selected');
    };

    window.updatePriceValue = function(value) {
        document.getElementById('price-value').innerText = '0 - ' + value;
    };

    window.applyFilters = function() {
        var selectedCategories = Array.from(document.querySelectorAll('.category-btn.selected')).map(btn => btn.innerText);
        var selectedConditions = Array.from(document.querySelectorAll('.condition-btn.selected')).map(btn => btn.innerText);
        var price = document.getElementById('price').value;
        var order = document.getElementById('sort').value;

        console.log('Selected categories:', selectedCategories);  // DEBUG
        console.log('Selected conditions:', selectedConditions);  // DEBUG
        console.log('Price:', price);  // DEBUG
        console.log('Order:', order);  // DEBUG

        const request = new XMLHttpRequest(); 
        request.open('GET', '../actions/action_get_posts.php?categories=' + encodeURIComponent(selectedCategories.join(',')) + 
                     '&conditions=' + encodeURIComponent(selectedConditions.join(',')) + 
                     '&price=' + encodeURIComponent(price) + 
                     '&order=' + encodeURIComponent(order), true); 
        
        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                document.getElementById('results').innerHTML = request.responseText;
            } else {
                console.log("Error retrieving posts: " + request.responseText);
            }
        };
    
        request.onerror = function() {
            console.log("Connection error");
        };
    
        request.send();
    };

    window.resetFilters = function() {
        document.querySelectorAll('.selected').forEach(btn => btn.classList.remove('selected'));
        document.getElementById('price').value = '0';
        updatePriceValue('0');
        document.getElementById('results').innerHTML = '';
    };

    document.getElementById('apply-filters').addEventListener('click', window.applyFilters);
    document.getElementById('clear-filters').addEventListener('click', window.resetFilters);
});
