/*
|--------------------------------------------------------------------------------------------
| Ajax update for text fields with ENTER & ESC support
|--------------------------------------------------------------------------------------------
*/
document.addEventListener("dblclick", function (event) {
    const target = event.target.closest(".editable");
    if (!target || target.classList.contains("editing")) return;

    const id = target.dataset.id;
    const field = target.dataset.field;
    const model = target.dataset.model;
    const originalValue = target.textContent;

    const input = document.createElement("input");
    input.type = "text";
    input.className = "edit-input";
    input.value = originalValue;

    target.textContent = "";
    target.appendChild(input);
    input.focus();
    target.classList.add("editing");

    const finishEditing = (newValue, save = true) => {
        if (!save || newValue === "") {
            target.textContent = originalValue;
            target.classList.remove("editing");
            return;
        }

        fetch(`/${model}/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ field, newValue })
        })
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            target.textContent = newValue;
        })
        .catch(() => {
            alert("Error updating the data.");
            target.textContent = originalValue;
        })
        .finally(() => {
            target.classList.remove("editing");
        });
    };

    input.addEventListener("blur", () => finishEditing(input.value));
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            input.blur(); // okida fetch
        } else if (e.key === "Escape") {
            finishEditing(originalValue, false); // odustajanje
        }
    });
});

/*
|--------------------------------------------------------------------------------------------
| Ajax delete records (vanilla JS) with fade-out effect
|--------------------------------------------------------------------------------------------
*/
document.addEventListener("click", function(event) {
    const deleteBtn = event.target.closest(".delete-btn-x");
    if (!deleteBtn) return;

    const id = deleteBtn.dataset.id;
    const model = deleteBtn.dataset.model;

    const modalEl = document.getElementById("confirmationModal");
    const confirmBtn = modalEl.querySelector("#confirmDeleteBtn");

    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();

    const closeModal = () => bsModal.hide();

    const onConfirm = () => {
        fetch(`/${model}/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Delete failed");

            // Prvo pokušaj ajax-deletable, fallback na tr
            const element = deleteBtn.closest(".ajax-deletable") || deleteBtn.closest("tr");
            if (element) {
                element.style.transition = "opacity 0.4s ease";
                element.style.opacity = 0;
                setTimeout(() => element.remove(), 400);
            }
        })
        .catch(() => alert("Error deleting the record."))
        .finally(() => closeModal());
    };

    confirmBtn.onclick = onConfirm;
});

/*
|--------------------------------------------------------------------------------------------
| Table search on keyup
|--------------------------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------------------------
| Ajax update for select fields
|--------------------------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------------------------
| Ajax update for date fields
|--------------------------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.editable-date').forEach(container => {

        const textEl = container.querySelector('.date-text');
        const editBtn = container.querySelector('.edit-btn');

        editBtn.addEventListener('click', () => {
            startEdit(container, textEl, editBtn);
        });

    });

    function startEdit(container, textEl, editBtn) {
        const originalValue = container.dataset.value;

        const input = document.createElement('input');
        input.type = 'date';
        input.className = 'form-control';
        input.style.width = '150px';
        input.value = originalValue;

        const btnOk = document.createElement('button');
        btnOk.className = 'btn btn-success btn-sm p-1';
        btnOk.innerHTML = '<i class="bi bi-check-lg"></i>';

        const btnCancel = document.createElement('button');
        btnCancel.className = 'btn btn-danger btn-sm p-1';
        btnCancel.innerHTML = '<i class="bi bi-x-lg"></i>';

        textEl.style.display = 'none';
        editBtn.style.display = 'none';

        container.appendChild(input);
        container.appendChild(btnOk);
        container.appendChild(btnCancel);

        input.focus();

        btnOk.addEventListener('click', () => save(input.value));
        btnCancel.addEventListener('click', cancel);

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') save(input.value);
            if (e.key === 'Escape') cancel();
        });

        function save(newValue) {

            fetch(`/${container.dataset.model}/${container.dataset.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    field: container.dataset.field,
                    newValue: newValue
                })
            })
            .then(r => {
                if (!r.ok) throw new Error();
                container.dataset.value = newValue;
                textEl.textContent = newValue || 'Nema';
                finish();
            })
            .catch(() => alert("Greška pri spremanju."));
        }

        function cancel() {
            finish();
        }

        function finish() {
            input.remove();
            btnOk.remove();
            btnCancel.remove();

            textEl.style.display = '';
            editBtn.style.display = '';
        }
    }

});

/*
|--------------------------------------------------------------------------------------------
| Ajax update for datetime fields
|--------------------------------------------------------------------------------------------
*/
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
                    const newValue = dtInput.value;

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

/*
|--------------------------------------------------------------------------------------------
| Ajax update for checkbox state
|--------------------------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------------------------
| Ajax update checkbox state for delivery services
|--------------------------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.edit-checkbox-delivery-service');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            const serviceItem = checkbox.closest('.delivery-service-item');
            const id = serviceItem.dataset.id;
            const model = serviceItem.dataset.model;
            const inUse = checkbox.checked;

            fetch(`/promjena-statusa/${model}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ in_use: inUse })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error updating the data.');
            })
            .catch(() => alert('Error updating the data.'));
        });
    });
});

/*
|--------------------------------------------------------------------------------------------
| Scroll to top button
|--------------------------------------------------------------------------------------------
*/
let Pl = document.getElementById("btn-back-to-top");
window.onscroll = function () {
    WS();
};

function WS() {
    document.body.scrollTop > 20 || document.documentElement.scrollTop > 20 ? (Pl.style.display = "block") : (Pl.style.display = "none");
}

Pl.addEventListener("click", qS);
function qS() {
    (document.body.scrollTop = 0), (document.documentElement.scrollTop = 0);
}

/*
|--------------------------------------------------------------------------------------------
| Code to fetch and update the latest invoice number based on the selected year
|--------------------------------------------------------------------------------------------
*/
const refreshBtn = document.getElementById("refresh-number-btn");
if (refreshBtn) { // Check if element exists
    refreshBtn.addEventListener("click", function () {
        const numberInput = document.getElementById("number");
        const year = document.getElementById("year").value;
        const loader = document.getElementById("numberLoader");

        numberInput.disabled = true;
        numberInput.classList.add("opacity-50");
        loader.classList.remove("d-none");

        fetch(`/racuni/zadnji-broj/${year}`)
            .then(response => response.json())
            .then(data => {
                setTimeout(() => {
                    numberInput.value = data.latest;

                    numberInput.disabled = false;
                    numberInput.classList.remove("opacity-50");
                    loader.classList.add("d-none");
                }, 500);
            })
            .catch(() => {
                numberInput.disabled = false;
                numberInput.classList.remove("opacity-50");
                loader.classList.add("d-none");
            });
    });
}

/*
|--------------------------------------------------------------------------------------------
| Custom searchable dropdown for select inputs
|--------------------------------------------------------------------------------------------
|
| This script turns any input with the class "omniselect" into a custom searchable dropdown.
| It filters the list items in real time as the user types, allows selecting an item,
| and stores the selected value in a hidden input for form submission.
|
*/
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
