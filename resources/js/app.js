/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');


// AJAX UPDATE
// When a user double clicks on an editable field
$('.editable').on('dblclick', function () {
    const id = $(this).data('id');
    const field = $(this).data('field');
    const model = $(this).data('model');

    // Store the reference to $(this)
    const $editableElement = $(this);

    // Check if the content is already an input field
    if (!$editableElement.hasClass('editing')) {
        // Store the original text
        const originalText = $editableElement.text();

        // Create an input field with the original text as the initial value
        const inputField = $('<input>', {
            type: 'text',
            class: 'edit-input',
            value: originalText,
        });

        $editableElement.html(inputField); // Replace the content with the input field
        inputField.focus(); // Set focus to the input field
        $editableElement.addClass('editing'); // Add a class to indicate that editing is in progress

        inputField.blur(function () {
            const newValue = inputField.val();
            if (newValue === '') {
                // If the new value is empty, revert to the original value
                $editableElement.html(originalText);
            } else {
                // Send the new value to the server via an AJAX request and handle the update

                // Send an AJAX request to update the data
                $.ajax({
                    type: 'PUT',
                    url: `/update-${model}/${id}`,
                    data: {
                        field: field,
                        newValue: newValue,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function () {
                        // Update the displayed value
                        $editableElement.removeClass('editing'); // Remove the editing class
                        $editableElement.text(newValue);
                    },
                    error: function () {
                        alert('Error updating the data.');
                        // If there's an error, you can choose to keep editing or close the edit box
                        // $editableElement.removeClass('editing');
                    },
                });
            }
        });
    }
});






// AJAX DEL function
// when user clicks on the X field in the table
$(function () {
    // When the delete button is clicked
    $('.delete-btn-x').on('click', function () {
        const id = $(this).data('id');
        const model = $(this).data('model');

        // Show the confirmation diTo alog
        $('.confirmation-dialog').show();

        // Handle the confirm delete action
        $('.confirm-delete').on('click', function () {
            // Send an AJAX request to delete the record
            $.ajax({
                type: 'DELETE',
                url: `/${model}/${id}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    // Handle success, e.g., remove the record from the UI
                    $(`[data-id="${id}"]`).closest('tr').remove();
                    $('.confirmation-dialog').hide();
                },
                error: function () {
                    // Handle error
                    alert('Error deleting the record.');
                    $('.confirmation-dialog').hide();
                }
            });
        });

        // Handle the cancel delete action
        $('.cancel-delete').on('click', function () {
            // Hide the confirmation dialog
            $('.confirmation-dialog').hide();
        });
    });
});

// TABLE SEARCH
// that filters on key up
$(function () {
    $('#search').on('keyup', function () {
        const query = $(this).val().toLowerCase().split(' '); // Split the input into keywords
        $('table tbody tr').each(function () {
            const row = $(this).text().toLowerCase();
            let showRow = true;
            query.forEach(function (keyword) {
                if (row.indexOf(keyword) === -1) {
                    showRow = false; // If any keyword is not found, don't show the row
                }
            });
            if (showRow) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});




// SELECT BOX EDIT
// on click
$('.editable-select').each(function() {
    const selectElement = $(this).find('select');
    const spanElement = $(this).find('span');
    const id = $(this).data('id');
    const field = $(this).data('field');
    const model = $(this).data('model');
    let originalValue;

    // Hide the select element initially
    selectElement.hide();

    // When the span element is clicked
    spanElement.click(function () {
        originalValue = selectElement.val(); // Store the original value
        spanElement.hide();
        selectElement.show();
        selectElement.focus();
    });

    // When the select element loses focus
    selectElement.blur(function () {
        const newValue = selectElement.val();

        if (newValue !== originalValue) {
            // Value has changed; update the UI and send the AJAX request
            spanElement.text(selectElement.find(':selected').text()).show();
            selectElement.hide();

            // Send an AJAX request to update the data
            $.ajax({
                type: 'PUT',
                url: `/update-${model}/${id}`,
                data: { field: field, newValue: newValue },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    // Handle success, if needed
                },
                error: function () {
                    // Handle error, if needed
                    alert('Error updating the data.');
                }
            });
        } else {
            // Value hasn't changed; reset the UI to the original value
            selectElement.val(originalValue);
            spanElement.show();
            selectElement.hide();
        }
    });
});




// DATE EDIT
// on click
$('.editable-date').on('click', function () {
    const id = $(this).data('id');
    const field = $(this).data('field');
    const model = $(this).data('model');
    const originalDate = $(this).find('input[type="date"]').val(); // Get the original date

    // Create an input field with the original date as the initial value
    const inputField = $('<input>', {
        type: 'date',
        class: 'form-control',
        style: 'width:80%',
        value: originalDate,
    });

    // Prevent the input field from being reinitialized when the date picker icon is clicked
    inputField.on('focus', function () {
        $(this).attr('data-editing', 'true');
    });

    inputField.blur(function () {
        // Check if the input field is still in editing mode
        if ($(this).attr('data-editing') === 'true') {
            const newDate = inputField.val();
            $(this).html(newDate); // Replace the input field with the new date

            // Send the new date to the server via an AJAX request and handle the update

            // Send an AJAX request to update the data
            $.ajax({
                type: 'PUT',
                url: `/update-${model}/${id}`,
                data: {
                    field: field,
                    newValue: newDate,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function () {
                    // Update the displayed value
                    $(`[data-id="${id}"][data-field="${field}"]`).find('input[type="date"]').val(newDate);
                },
                error: function () {
                    alert('Error updating the data.');
                },
            });

            // Remove the editing attribute to allow the date picker to work
            $(this).removeAttr('data-editing');
        }
    });

    $(this).html(inputField); // Replace the content with the input field
    inputField.focus(); // Set focus to the input field
});




// DATETIME EDIT
// on click
$('.editable-datetime').on('click', function () {
    const id = $(this).data('id');
    const field = $(this).data('field');
    const model = $(this).data('model');
    const originalDate = $(this).find('input[type="datetime-local"]').val(); // Get the original date

    // Create an input field with the original date as the initial value
    const inputField = $('<input>', {
        type: 'datetime-local',
        class: 'form-control',
        style: 'width:80%',
        value: originalDate,
    });

    // Prevent the input field from being reinitialized when the date picker icon is clicked
    inputField.on('focus', function () {
        $(this).attr('data-editing', 'true');
    });

    inputField.blur(function () {
        // Check if the input field is still in editing mode
        if ($(this).attr('data-editing') === 'true') {
            const newDate = inputField.val();
            $(this).html(newDate); // Replace the input field with the new date

            // Send the new date to the server via an AJAX request and handle the update

            // Send an AJAX request to update the data
            $.ajax({
                type: 'PUT',
                url: `/update-${model}/${id}`,
                data: {
                    field: field,
                    newValue: newDate,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function () {
                    // Update the displayed value
                    $(`[data-id="${id}"][data-field="${field}"]`).find('input[type="date"]').val(newDate);
                },
                error: function () {
                    alert('Error updating the data.');
                },
            });

            // Remove the editing attribute to allow the date picker to work
            $(this).removeAttr('data-editing');
        }
    });

    $(this).html(inputField); // Replace the content with the input field
    inputField.focus(); // Set focus to the input field
});




// Editable datetime for invoice
$('.editable-date-invoice').on('click', function () {
    // Find the relevant elements
    const id = $(this).data('id');
    const field = $(this).data('field');
    const model = $(this).data('model');

    // Find the relevant elements within the clicked element
    const dateDisplayElement = $(this).find('.date-display');

    // Retrieve the raw date value from the data attribute
    const originalDate = $(this).data('raw-date');

    // Create an input field with the original date value as the initial value
    const inputField = $('<input>', {
        type: 'datetime-local',
        class: 'form-control',
        style: 'width:80%',
        value: originalDate,
    });

    // Replace the content with the input field
    dateDisplayElement.html(inputField);
    inputField.focus();

    // When the input field loses focus, save the value and update the UI
    inputField.blur(function () {
        const newDate = inputField.val();
        const formattedDate = formatDateForDisplay(newDate);
        dateDisplayElement.text(formattedDate);
        inputField.remove();

        // Send an AJAX request to update the data
        $.ajax({
            type: 'PUT',
            url: `/update-${model}/${id}`,
            data: {
                field: field,
                newValue: newDate,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                // Handle success, if needed
            },
            error: function () {
                // Handle error, if needed
                alert('Error updating the data.');
            }
        });
    });

    function formatDateForDisplay(isoDate) {
        const date = new Date(isoDate);
        const day = date.getDate();
        const month = date.getMonth() + 1; // Month is 0-based
        const year = date.getFullYear();
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();

        return `${day}.${month}.${year} - ${hours}:${minutes}:${seconds}`;
    }
});

// IS DONE CHECKBOX
// on click
$(document).ready(function () {
    $('.edit-checkbox').on('click', function () {
        const orderItemId = $(this).closest('.order-item').data('id');
        const isDone = $(this).is(':checked');
        const model = $(this).closest('.order-item').data('model');

        // Send an AJAX request to update the data
        $.ajax({
            type: 'PUT',
            url: `/${model}-isdone-status/${orderItemId}`,
            data: { is_done: isDone },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                // Handle success, if needed
            },
            error: function () {
                // Handle error, if needed
                alert('Error updating the data.');
            }
        });
    });
});

// IN USE CHECKBOX
// on click
$(document).ready(function () {
    $('.edit-checkbox-delivery-service').on('click', function () {
        const deliveryServiceId = $(this).closest('.delivery-service-item').data('id');
        const inUse = $(this).is(':checked');
        const model = $(this).closest('.delivery-service-item').data('model');

        // Send an AJAX request to update the data
        $.ajax({
            type: 'PUT',
            url: `/${model}/use-status/${deliveryServiceId}`,
            data: { in_use: inUse },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                // Handle success, if needed
            },
            error: function () {
                // Handle error, if needed
                alert('Error updating the data.');
            }
        });
    });
});






// Select2 setups
// Default search select setup
$(function () {
    $('.searchable-select').select2();
});

// Search select setup in modal due to visual bug in bootstrap
$(function () {
    $('.searchable-select-modal').select2({
        dropdownParent: $('#exampleModal')
    });
});

// Search select setup for the second modal
$(function () {
    $('.searchable-select-modal2').select2({
        dropdownParent: $('#expensesModal')
    });
});

// Search select setup for the second modal
$(function () {
    $('.searchable-customer-modal').select2({
        dropdownParent: $('#customerModal')
    });
});






//Get the button
let mybutton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (
    document.body.scrollTop > 20 ||
    document.documentElement.scrollTop > 20
  ) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}