document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".editable-date").forEach(container => {
        const editBtn = container.querySelector(".edit-btn");
        const span = container.querySelector(".date-text");

        if (!editBtn) return;

        editBtn.addEventListener("click", () => {
            const id = container.dataset.id;
            const field = container.dataset.field;
            const model = container.dataset.model;
            const inputValue = container.dataset.inputdate || "";

            const input = document.createElement("input");
            input.type = "date";
            input.className = "form-control form-control-sm d-inline-block me-2";
            input.style.width = "160px";
            input.value = inputValue;

            const btnWrapper = document.createElement("div");
            btnWrapper.className = "d-inline-flex gap-1";

            const confirmBtn = document.createElement("button");
            confirmBtn.className = "btn btn-sm btn-success";
            confirmBtn.innerHTML = `<i class="bi bi-check-lg"></i>`;

            const cancelBtn = document.createElement("button");
            cancelBtn.className = "btn btn-sm btn-secondary";
            cancelBtn.innerHTML = `<i class="bi bi-x-lg"></i>`;

            btnWrapper.appendChild(confirmBtn);
            btnWrapper.appendChild(cancelBtn);

            container.innerHTML = "";
            container.appendChild(input);
            container.appendChild(btnWrapper);
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
                    body: JSON.stringify({ field: field, newValue: newValue || '' })
                })
                .then(async res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(data => {
                    const formatted = data.formatted || "Nema";
                    container.dataset.inputdate = data.input_formatted || "";

                    const newSpan = document.createElement("span");
                    newSpan.className = "date-text me-2";
                    newSpan.textContent = formatted;

                    container.innerHTML = "";
                    container.appendChild(newSpan);
                    container.appendChild(editBtn);
                })
                .catch(() => {
                    alert("Greška kod spremanja datuma.");
                    container.innerHTML = "";
                    container.appendChild(span);
                    container.appendChild(editBtn);
                });
            };

            confirmBtn.addEventListener("click", () => finishEdit(input.value));
            input.addEventListener("blur", () => finishEdit(input.value));

            input.addEventListener("keydown", e => {
                if (e.key === "Enter") finishEdit(input.value);
                if (e.key === "Escape") {
                    container.innerHTML = "";
                    container.appendChild(span);
                    container.appendChild(editBtn);
                }
            });

            cancelBtn.addEventListener("click", () => {
                container.innerHTML = "";
                container.appendChild(span);
                container.appendChild(editBtn);
            });
        });
    });
});