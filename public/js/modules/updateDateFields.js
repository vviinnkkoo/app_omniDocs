document.addEventListener("DOMContentLoaded", () => {

    // funkcija za formatiranje datuma iz YYYY-MM-DD u DD.MM.YYYY
    const formatDateToDMY = (dateStr) => {
        if (!dateStr) return "Nema";
        const [year, month, day] = dateStr.split("-");
        return `${day}.${month}.${year}`;
    };

    document.querySelectorAll(".editable-date").forEach(container => {
        const startBtn = container.querySelector(".edit-start");
        const confirmBtn = container.querySelector(".edit-confirm");
        const cancelBtn = container.querySelector(".edit-cancel");
        const span = container.querySelector(".date-text");

        if (!startBtn) return;

        startBtn.addEventListener("click", () => {
            const id = container.dataset.id;
            const field = container.dataset.field;
            const model = container.dataset.model;
            const originalValue = span.textContent;
            const inputValue = container.dataset.inputdate || "";

            const input = document.createElement("input");
            input.type = "date";
            input.className = "form-control form-control-sm";
            input.value = inputValue;

            span.classList.add("d-none");
            startBtn.classList.add("d-none");
            confirmBtn.classList.remove("d-none");
            cancelBtn.classList.remove("d-none");

            container.insertBefore(input, confirmBtn);
            input.focus();

            const finishEdit = (rawValue) => {
                const newValue = rawValue === "" ? null : rawValue;

                fetch(`/${model}/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ field, newValue: newValue || '' })
                })
                .then(res => {
                    if (!res.ok) throw new Error();
                    // Ako je response ok, odmah postavi input value u span, formatirano
                    span.textContent = formatDateToDMY(rawValue);
                    container.dataset.inputdate = rawValue || "";
                })
                .catch(() => {
                    alert("Greška kod spremanja datuma.");
                    span.textContent = originalValue;
                })
                .finally(() => {
                    input.remove();
                    span.classList.remove("d-none");
                    startBtn.classList.remove("d-none");
                    confirmBtn.classList.add("d-none");
                    cancelBtn.classList.add("d-none");
                });
            };

            confirmBtn.addEventListener("click", () => finishEdit(input.value), { once: true });

            cancelBtn.addEventListener("click", () => {
                input.remove();
                span.classList.remove("d-none");
                startBtn.classList.remove("d-none");
                confirmBtn.classList.add("d-none");
                cancelBtn.classList.add("d-none");
                span.textContent = originalValue;
            }, { once: true });

            input.addEventListener("keydown", e => {
                if (e.key === "Enter") finishEdit(input.value);
                if (e.key === "Escape") cancelBtn.click();
            });
        });
    });
});