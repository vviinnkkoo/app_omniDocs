document.addEventListener("click", function (e) {
    const nullBtn = e.target.closest(".edit-null");
    if (!nullBtn) return;

    const wrapper = nullBtn.closest(".editable-text-wrapper");
    if (!wrapper) return;

    const id = wrapper.dataset.id;
    const field = wrapper.dataset.field;
    const model = wrapper.dataset.model;

    const valueSpan = wrapper.querySelector(".editable-text-value");
    const originalValue = valueSpan.textContent.trim();

    const modalEl = document.getElementById("confirmationModal");
    const confirmBtn = modalEl.querySelector("#confirmDeleteBtn");

    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();

    confirmBtn.onclick = () => {
        fetch(`/${model}/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                field: field,
                newValue: null
            })
        })
        .then(r => {
            if (!r.ok) throw new Error();
            valueSpan.textContent = "- - -";
        })
        .catch(() => {
            valueSpan.textContent = originalValue;
            alert("Greška prilikom brisanja vrijednosti.");
        })
        .finally(() => bsModal.hide());
    };
});
