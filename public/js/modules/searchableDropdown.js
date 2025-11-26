document.querySelectorAll('.omniselect').forEach(input => {
    const container = input.closest('.omniselect-dropdown');
    if (!container) return;

    const dropdown = container.querySelector('ul');
    if (!dropdown) return;

    const hiddenInput = container.querySelector('.omniselect-hidden');

    function filterOptions() {
        const val = input.value.toLowerCase();
        const groups = {};
        let currentGroup = null;

        dropdown.querySelectorAll('li').forEach(li => {
            if (li.classList.contains('dropdown-group')) {
                currentGroup = li.textContent.trim();
                groups[currentGroup] = false; // inicijalno nema vidljivih
                return;
            }

            const text = li.textContent.toLowerCase();
            const visible = text.includes(val);
            li.style.display = visible ? '' : 'none';

            if (visible && currentGroup) {
                groups[currentGroup] = true;
            }
        });

        dropdown.querySelectorAll('li.dropdown-group').forEach(groupLi => {
            const groupName = groupLi.textContent.trim();
            groupLi.style.display = groups[groupName] ? '' : 'none';
        });
    }

    input.addEventListener('input', () => {
        filterOptions();
        dropdown.classList.add('show');
    });

    input.addEventListener('focus', () => {
        filterOptions();
        dropdown.classList.add('show');
    });

    dropdown.querySelectorAll('li a').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const cleanText = a.textContent.replace(/\s+/g, ' ').trim();
            input.value = cleanText;

            if (hiddenInput) {
                hiddenInput.value = a.dataset.value;
            }

            dropdown.classList.remove('show');
        });
    });

    document.addEventListener('click', e => {
        if (!container.contains(e.target)) dropdown.classList.remove('show');
    });
});