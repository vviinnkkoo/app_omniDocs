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