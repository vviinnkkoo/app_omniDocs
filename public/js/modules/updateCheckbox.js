document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.edit-checkbox');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            const orderItem = checkbox.closest('.order-item');
            const id = orderItem.dataset.id;
            const model = orderItem.dataset.model;
            const isDone = checkbox.checked;

            fetch(`/promjena-statusa/${model}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_done: isDone })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error updating the data.');
            })
            .catch(() => alert('Error updating the data.'));
        });
    });
});