document.addEventListener("click", function (e) {
    const startBtn = e.target.closest(".edit-start");
    if (!startBtn) return;

    const wrapper = startBtn.closest(".editable-text-wrapper");
    if (!wrapper) return;

    const valueSpan = wrapper.querySelector(".editable-text-value");
    const confirmBtn = wrapper.querySelector(".edit-confirm");
    const cancelBtn = wrapper.querySelector(".edit-cancel");

    const originalValue = valueSpan.textContent.trim();
    const id = wrapper.dataset.id;
    const field = wrapper.dataset.field;
    const model = wrapper.dataset.model;

    startBtn.classList.add("d-none");
    confirmBtn.classList.remove("d-none");
    cancelBtn.classList.remove("d-none");

    const input = document.createElement("input");
    input.type = "text";
    input.className = "form-control form-control-sm";
    input.value = originalValue;

    valueSpan.replaceWith(input);
    input.focus();

    const finish = (save) => {
        if (!save) {
            input.replaceWith(valueSpan);
            valueSpan.textContent = originalValue;
            resetButtons();
            return;
        }

        const newValue = input.value.trim();
        if (newValue === "") {
            input.replaceWith(valueSpan);
            valueSpan.textContent = originalValue;
            resetButtons();
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
        .then(r => {
            if (!r.ok) throw new Error();
            valueSpan.textContent = newValue;
        })
        .catch(() => {
            alert("Greška prilikom spremanja.");
            valueSpan.textContent = originalValue;
        })
        .finally(() => {
            input.replaceWith(valueSpan);
            resetButtons();
        });
    };

    const resetButtons = () => {
        startBtn.classList.remove("d-none");
        confirmBtn.classList.add("d-none");
        cancelBtn.classList.add("d-none");
    };

    confirmBtn.onclick = () => finish(true);
    cancelBtn.onclick = () => finish(false);

    input.addEventListener("keydown", (ev) => {
        if (ev.key === "Enter") finish(true);
        if (ev.key === "Escape") finish(false);
    });
});