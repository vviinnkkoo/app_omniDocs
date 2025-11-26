document.addEventListener('DOMContentLoaded', function () {
    const editableSelects = document.querySelectorAll('.editable-select');

    editableSelects.forEach(function (container) {
        const select = container.querySelector('select');
        const span = container.querySelector('span');
        const id = container.dataset.id;
        const field = container.dataset.field;
        const model = container.dataset.model;

        let initialValue;

        // Hide select initially
        select.style.display = 'none';

        // Show select on span click
        span.addEventListener('click', function () {
            initialValue = select.value;
            span.style.display = 'none';
            select.style.display = '';
            select.focus();
        });

        // Function to confirm change
        function confirmChange() {
            const newValue = select.value;
            if (newValue !== initialValue) {
                span.textContent = select.options[select.selectedIndex].text;
                span.style.display = '';
                select.style.display = 'none';

                // Send AJAX PUT
                fetch(`/${model}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ field: field, newValue: newValue })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error updating the data.');
                })
                .catch(() => alert('Error updating the data.'));
            } else {
                cancelChange();
            }
        }

        // Function to cancel change
        function cancelChange() {
            select.value = initialValue;
            span.style.display = '';
            select.style.display = 'none';
        }

        // Blur event
        select.addEventListener('blur', confirmChange);

        // Keydown event for Enter / Escape
        select.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                confirmChange();
            } else if (e.key === 'Escape') {
                cancelChange();
            }
        });
    });
});