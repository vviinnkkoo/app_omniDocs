// Ajax update for text fields
$(".editable").on("dblclick", function () {
    const e = $(this).data("id"),
        t = $(this).data("field"),
        n = $(this).data("model"),
        s = $(this);
    if (!s.hasClass("editing")) {
        const r = s.text(),
            i = $("<input>", { type: "text", class: "edit-input", value: r });
        s.html(i),
            i.focus(),
            s.addClass("editing"),
            i.blur(function () {
                const o = i.val();
                o === ""
                    ? s.html(r)
                    : $.ajax({
                          type: "PUT",
                          url: `/${n}/${e}`,
                          data: { field: t, newValue: o },
                          headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                          success: function () {
                              s.removeClass("editing"), s.text(o);
                          },
                          error: function () {
                              alert("Error updating the data.");
                          },
                      });
            });
    }
});

// Ajax delete records
$(function () {
    $(".delete-btn-x").on("click", function () {
        const e = $(this).data("id"),
            t = $(this).data("model");
        $(".confirmation-dialog").show(),
            $(".confirm-delete").on("click", function () {
                $.ajax({
                    type: "DELETE",
                    url: `/${t}/${e}`,
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: function () {
                        $(`[data-id="${e}"]`).closest("tr").remove(), $(".confirmation-dialog").hide();
                    },
                    error: function () {
                        alert("Error deleting the record."), $(".confirmation-dialog").hide();
                    },
                });
            }),
            $(".cancel-delete").on("click", function () {
                $(".confirmation-dialog").hide();
            });
    });
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

document.getElementById("refresh-number-btn").addEventListener("click", function () {
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
                numberInput.value = data.latest_number;

                numberInput.disabled = false;
                numberInput.classList.remove("opacity-50");
                loader.classList.add("d-none");
            }, 500); // pola sekunde pauze
        });
});