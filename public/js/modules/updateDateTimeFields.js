document.addEventListener('DOMContentLoaded', function () {
    const editableDateTimes = document.querySelectorAll('.editable-datetime');

    editableDateTimes.forEach(function (container) {
        container.addEventListener('click', function () {
            const id = container.dataset.id;
            const field = container.dataset.field;
            const model = container.dataset.model;

            // get current value from input[type="datetime-local"] if exists
            const currentInput = container.querySelector('input[type="datetime-local"]');
            const currentValue = currentInput ? currentInput.value : '';

            // create new datetime-local input
            const dtInput = document.createElement('input');
            dtInput.type = 'datetime-local';
            dtInput.className = 'form-control';
            dtInput.style.width = '80%';
            dtInput.value = currentValue;
            dtInput.dataset.editing = 'true';

            // replace container content with input
            container.innerHTML = '';
            container.appendChild(dtInput);
            dtInput.focus();

            // blur event
            dtInput.addEventListener('blur', function () {
                if (dtInput.dataset.editing === 'true') {
                    const newValue = dtInput.value || null;

                    // update container text
                    container.innerHTML = newValue;

                    // send AJAX PUT
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
                        // update original input if exists
                        const origInput = document.querySelector(`.editable-datetime[data-id="${id}"][data-field="${field}"] input[type="datetime-local"]`);
                        if (origInput) origInput.value = newValue;
                    })
                    .catch(() => alert('Error updating the data.'));

                    delete dtInput.dataset.editing;
                }
            });

            // keydown for Enter / Escape
            dtInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    dtInput.blur(); // confirm
                } else if (e.key === 'Escape') {
                    container.innerHTML = currentValue; // cancel
                }
            });
        });
    });
});