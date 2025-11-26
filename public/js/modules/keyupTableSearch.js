document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');

    if (searchInput) { // provjera da li element postoji
        searchInput.addEventListener('keyup', function() {
            const searchTerms = searchInput.value.toLowerCase().split(" ");
            document.querySelectorAll('table tbody tr').forEach(function(row) {
                const text = row.textContent.toLowerCase();
                let match = true;
                searchTerms.forEach(function(term) {
                    if (!text.includes(term)) match = false;
                });
                row.style.display = match ? '' : 'none';
            });
        });
    }
});