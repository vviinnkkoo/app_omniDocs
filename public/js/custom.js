// Ajax update for text fields with ENTER & ESC support
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

// Ajax delete records (vanilla JS) s fade-out efektom
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

// Table search on keyup
$(function () {
    $("#search").on("keyup", function () {
        const e = $(this).val().toLowerCase().split(" ");
        $("table tbody tr").each(function () {
            const t = $(this).text().toLowerCase();
            let n = !0;
            e.forEach(function (s) {
                t.indexOf(s) === -1 && (n = !1);
            }),
                n ? $(this).show() : $(this).hide();
        });
    });
});

// Ajax update for select fields
$(".editable-select").each(function () {
    const e = $(this).find("select"),
        t = $(this).find("span"),
        n = $(this).data("id"),
        s = $(this).data("field"),
        r = $(this).data("model");
    let i;
    e.hide(),
        t.click(function () {
            (i = e.val()), t.hide(), e.show(), e.focus();
        }),
        e.blur(function () {
            const o = e.val();
            o !== i
                ? (t.text(e.find(":selected").text()).show(),
                  e.hide(),
                  $.ajax({
                      type: "PUT",
                      url: `/${r}/${n}`,
                      data: { field: s, newValue: o },
                      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                      success: function () {},
                      error: function () {
                          alert("Error updating the data.");
                      },
                  }))
                : (e.val(i), t.show(), e.hide());
        });
});

// Ajax update for date fields
$(".editable-date").on("click", function () {
    const e = $(this).data("id"),
        t = $(this).data("field"),
        n = $(this).data("model"),
        s = $(this).find('input[type="date"]').val(),
        r = $("<input>", { type: "date", class: "form-control", style: "width:80%", value: s });
    r.on("focus", function () {
        $(this).attr("data-editing", "true");
    }),
        r.blur(function () {
            if ($(this).attr("data-editing") === "true") {
                const i = r.val();
                $(this).html(i),
                    $.ajax({
                        type: "PUT",
                        url: `/${n}/${e}`,
                        data: { field: t, newValue: i },
                        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                        success: function () {
                            $(`[data-id="${e}"][data-field="${t}"]`).find('input[type="date"]').val(i);
                        },
                        error: function () {
                            alert("Error updating the data.");
                        },
                    }),
                    $(this).removeAttr("data-editing");
            }
        }),
        $(this).html(r),
        r.focus();
});

// Ajax update for datetime fields
$(".editable-datetime").on("click", function () {
    const e = $(this).data("id"),
        t = $(this).data("field"),
        n = $(this).data("model"),
        s = $(this).find('input[type="datetime-local"]').val(),
        r = $("<input>", { type: "datetime-local", class: "form-control", style: "width:80%", value: s });
    r.on("focus", function () {
        $(this).attr("data-editing", "true");
    }),
        r.blur(function () {
            if ($(this).attr("data-editing") === "true") {
                const i = r.val();
                $(this).html(i),
                    $.ajax({
                        type: "PUT",
                        url: `/${n}/${e}`,
                        data: { field: t, newValue: i },
                        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                        success: function () {
                            $(`[data-id="${e}"][data-field="${t}"]`).find('input[type="date"]').val(i);
                        },
                        error: function () {
                            alert("Error updating the data.");
                        },
                    }),
                    $(this).removeAttr("data-editing");
            }
        }),
        $(this).html(r),
        r.focus();
});

// Ajax update for datetime fields on invoices
$(".editable-date-invoice").on("click", function () {
    const e = $(this).data("id"),
        t = $(this).data("field"),
        n = $(this).data("model"),
        s = $(this).find(".date-display"),
        r = $(this).data("raw-date"),
        i = $("<input>", { type: "datetime-local", class: "form-control", style: "width:80%", value: r });
    s.html(i),
        i.focus(),
        i.blur(function () {
            const l = i.val(),
                a = o(l);
            s.text(a),
                i.remove(),
                $.ajax({
                    type: "PUT",
                    url: `/${n}/${e}`,
                    data: { field: t, newValue: l },
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: function () {},
                    error: function () {
                        alert("Error updating the data.");
                    },
                });
        });
    function o(l) {
        const a = new Date(l),
            c = a.getDate(),
            u = a.getMonth() + 1,
            f = a.getFullYear(),
            d = a.getHours(),
            h = a.getMinutes(),
            p = a.getSeconds();
        return `${c}.${u}.${f} - ${d}:${h}:${p}`;
    }
});

// Ajax update checkbox state
$(document).ready(function () {
    $(".edit-checkbox").on("click", function () {
        const e = $(this).closest(".order-item").data("id"),
            t = $(this).is(":checked"),
            n = $(this).closest(".order-item").data("model");
        $.ajax({
            type: "PUT",
            url: `/${n}/status/${e}`,
            data: { is_done: t },
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function () {},
            error: function () {
                alert("Error updating the data.");
            },
        });
    });
});

// Ajax update checkbox state for delivery services
$(document).ready(function () {
    $(".edit-checkbox-delivery-service").on("click", function () {
        const e = $(this).closest(".delivery-service-item").data("id"),
            t = $(this).is(":checked"),
            n = $(this).closest(".delivery-service-item").data("model");
        $.ajax({
            type: "PUT",
            url: `/${n}/status/${e}`,
            data: { in_use: t },
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function () {},
            error: function () {
                alert("Error updating the data.");
            },
        });
    });
});

$(function () {
    $(".searchable-select").select2();
});

$(function () {
    $(".searchable-select-modal").select2({ dropdownParent: $("#exampleModal") });
});

$(function () {
    $(".searchable-select-modal2").select2({ dropdownParent: $("#expensesModal") });
});

$(function () {
    $(".searchable-customer-modal").select2({ dropdownParent: $("#customerModal") });
});

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
|
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
    const dropdown = container.querySelector('ul');
    const hiddenInput = container.querySelector('.omniselect-hidden');

    function filterOptions() {
        const val = input.value.toLowerCase();
        dropdown.querySelectorAll('li').forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(val) ? '' : 'none';
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
            // Remove leading/trailing whitespace and normalize multiple spaces
            const cleanText = a.textContent.replace(/\s+/g, ' ').trim();
            input.value = cleanText;
            hiddenInput.value = a.dataset.value;
            dropdown.classList.remove('show');
        });
    });

    document.addEventListener('click', e => {
        if (!container.contains(e.target)) dropdown.classList.remove('show');
    });
});