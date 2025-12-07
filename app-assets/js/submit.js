$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

// generalize submit
$(document).on("submit", "#subm", function (e) {
  e.preventDefault(); // avoid to execute the actual submit of the form.

  $(".print-error-msg").find("ul").html("");
  $(".alert-success").find("ul").html("");

  var form = $(this);
  var actionUrl = form.attr("action");
  var formData = new FormData(form[0]);

  $(".loader-container").show();
  $.ajax({
    type: "POST",
    url: actionUrl,
    data: formData, // serializes the form's elements.
    processData: false, // Important: Prevent jQuery from processing the data.
    contentType: false,

    success: function (data) {
      $(".loader-container").hide();

      if (data.catchError) {
        $(".alert-danger").removeClass("hide");
        $(".print-error-msg").find("ul").html("");
        $(".print-error-msg").css("display", "block");
        $(".print-error-msg")
          .find("ul")
          .append("<li>" + data.catchError + "</li>");
      }
      if ($.isEmptyObject(data.error)) {
        var successMessage = form.find(".alert-success");
        successMessage.removeClass("hide");
        successMessage.html(data.success);
        viewRangeWiseDataFilter();

        $("#subm").trigger("reset");
      } else {
        printErrorMsg(data.error);
      }
    },
    error: function (xhr, status, error) {
      // Handle errors here
      $(".loader-container").hide();
      console.log(error); // Log the error message for debugging

      // You can also display an error message to the user or take other actions as needed.
    },
  });
});

$(document).on("submit", "#submitadv", function (e) {
  var formhunyr = $(this);
  e.preventDefault(); // Avoid executing the actual submit of the form.

  // Clear previous errors and success messages
  $(".print-error-msg").find("ul").html("");
  $(".alert-success").find("ul").html("");

  var form = $(this);
  var actionUrl = form.attr("action");
  $();
  // if (uploadedFileIds && uploadedFileIds !== "undefined" && uploadedFileIds.length) {
  //     document.getElementById("uploaded_file_ids").value = uploadedFileIds
  //         .flat()
  //         .join(",");
  // }
  var formData = new FormData(form[0]);

  // Process 'notes' field if value is '1'
  var notesValue = formData.get("notes");
  if (notesValue == 1) {
    var editorElement = document.querySelector(".ql-editor");
    var textContent = editorElement.innerHTML;
    formData.append("notes", textContent);
  }

  // Remove any previous error styling and messages
  $(".error-message").remove();
  $(".is-invalid").removeClass("is-invalid");

  // Display SweetAlert loader
  Swal.fire({
    title: "Processing",
    text: "Please wait...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  // AJAX request
  $.ajax({
    type: "POST",
    url: actionUrl,
    data: formData,
    processData: false,
    contentType: false,

    success: function (data) {
      Swal.close(); // Close loader

      if (data.catchError) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.catchError,
          confirmButtonColor: "#D95000",
        });
      } else if ($.isEmptyObject(data.error)) {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: data.success,
          confirmButtonColor: "#3085d6",
        }).then((result) => {
          if (result.isConfirmed || result.isDismissed) {
            // Further actions after the user clicks "Okay"
            if (form.data("reset") === true) {
              form[0].reset(); // Reset the form without trigger
            }
            // Handle redirection or list refresh
            var url = form.find("#url").val();
            var listRefresh = form.find("#listRefresh").val();
            var ajaxLoadFlag = form.find("#ajaxLoadFlag").val();
            $(formhunyr).parents(".model").slideUp();
            console.log(listRefresh);
            console.log({ url });

            if (url) {
              window.location.href = url;
            }
            if (listRefresh) {
              console.log({ listRefresh });
              filterationCommon(listRefresh);
              $(formhunyr).parents(".model").slideUp();
            }
            if (ajaxLoadFlag == 1) {
              getAjaxDataOnEditColumns();
            }
            $(formhunyr).closest(".modal").modal("toggle");
          }
        });
      } else {
        printErrorMsg(data.error);
      }
    },

    error: function (xhr, status, error) {
      Swal.close(); // Close loader

      // Clear previous error messages and invalid classes
      $(".error-message").remove();
      $(".is-invalid").removeClass("is-invalid");

      if (xhr.responseJSON && xhr.responseJSON.errors) {
        var validationErrors = xhr.responseJSON.errors;

        $.each(validationErrors, function (key, errors) {
          // Check if the key is for an array field like fieldname.0, fieldname.1, etc.
          if (key.includes(".")) {
            // Split the key to get the field name and index (e.g., "fieldname.0" -> "fieldname" and "0")
            var parts = key.split(".");
            var fieldName = parts[0] + "[]"; // Convert to array format (e.g., "brands[]")
            var index = parts[1]; // Get the index

            // Target the specific array input by name and index
            var field = $('[name="' + fieldName + '"]').eq(index);
            field.addClass("is-invalid");

            // Display the error message appropriately for Select2 or regular fields
            if (field.hasClass("select2-hidden-accessible")) {
              // For Select2, place the error after the Select2 container
              field
                .parent()
                .find(".select2-container")
                .after(
                  '<div class="error-message text-danger">' +
                    errors[0] +
                    "</div>"
                );
            } else {
              // For regular fields, place the error right after the field
              field.after(
                '<div class="error-message text-danger">' + errors[0] + "</div>"
              );
            }
          } else {
            // Handle non-array fields normally
            var field = $('[name="' + key + '"]');
            field.addClass("is-invalid");

            // For Select2, place the error after the Select2 container
            if (field.hasClass("select2-hidden-accessible")) {
              field
                .parent()
                .find(".select2-container")
                .after(
                  '<div class="error-message text-danger">' +
                    errors[0] +
                    "</div>"
                );
            } else {
              field.after(
                '<div class="error-message text-danger">' + errors[0] + "</div>"
              );
            }
          }
        });

        Swal.fire({
          title: "Validation Errors",
          text: "Some fields are mandatory. Please check and correct the errors.",
          icon: "error",
          confirmButtonColor: "#D95000",
        });
      } else if (xhr.responseJSON && xhr.responseJSON.message) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: xhr.responseJSON.message,
          confirmButtonColor: "#D95000",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: xhr.responseText,
          confirmButtonColor: "#D95000",
        });
      }
    },
  });
});

function deletemodal(url, redirecturl, reloadtype = null) {
  // Use SweetAlert for confirmation
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    preConfirm: () => {
      // Show preloader using SweetAlert
      Swal.fire({
        title: "Processing...",
        text: "Please wait while we process your request.",
        icon: "question",

        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading(); // Show loading spinner
        },
      });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      // Perform the delete action using jQuery AJAX
      $.ajax({
        url: url,
        method: "POST",
        data: {
          _method: "DELETE", // Laravel's way of spoofing DELETE request
          _token: $('meta[name="csrf-token"]').attr("content"), // Assuming you have a CSRF token meta tag
        },
        success: function (response) {
          Swal.fire(
            "Deleted!",
            "Your record has been deleted.",
            "success"
          ).then(() => {
            if (reloadtype == "tab") {
              openLeadsChunks(redirecturl);
            } else {
              filterationCommon(redirecturl);
            }
          });
        },
        error: function (xhr, status, error) {
          // Handle errors if necessary
          console.error("Error deleting content:", error);
          Swal.fire({
            icon: "error",
            title: "Error " + status,
            html: `<p>${xhr.responseJSON.error}</p><small>${xhr.responseJSON.details}</small>`,
          });
        },
      });
    }
  });
}
