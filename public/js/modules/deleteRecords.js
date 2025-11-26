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