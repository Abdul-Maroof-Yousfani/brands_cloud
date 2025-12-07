jQuery(document).ready(function ($) {
    var docBody = $(document.body);
    var shiftPressed = false;
    var clickedOutside = false;

    function initializeSelect2(container) {
        // Initialize Select2 within the specified container
        container.find('select.select2').each(function () {
            if (!$(this).data('select2')) {
                $(this).select2({
                    width: '100%' // Adjust options as needed
                });
            }
        });
    }

    // Keydown and Keyup for shift detection
    docBody.on('keydown', function (e) {
        if (e.keyCode === 16) {
            shiftPressed = true;
        }
    }).on('keyup', function (e) {
        if (e.keyCode === 16) {
            shiftPressed = false;
        }
    });

    // Mousedown detection
    docBody.on('mousedown', function (e) {
        clickedOutside = !$(e.target).closest('[class*="select2"]').length;
    });

    // Select2 open and close events
    docBody.on('select2:opening', function (e) {
        clickedOutside = false;
        $(e.target).attr('data-s2open', 1);
    }).on('select2:closing', function (e) {
        $(e.target).removeAttr('data-s2open');
    }).on('select2:close', function (e) {
        var elSelect = $(e.target);
        elSelect.removeAttr('data-s2open');
        var currentForm = elSelect.closest('form');

        if (!currentForm.find('[data-s2open]').length && !clickedOutside) {
            var inputs = currentForm.find(':input:enabled:not([readonly], input:hidden, button:hidden, textarea:hidden)')
                .not(function () {
                    return $(this).parent().is(':hidden');
                });
            var elFocus = null;
            inputs.each(function (index) {
                if ($(this).attr('id') === elSelect.attr('id')) {
                    elFocus = shiftPressed ? inputs.eq(index - 1) : inputs.eq(index + 1);
                    return false;
                }
            });

            if (elFocus) {
                if (elFocus.siblings('.select2').length) {
                    elFocus.select2('open');
                } else {
                    elFocus.focus();
                }
            }
        }
    });

    // Focus on Select2
    docBody.on('focus', '.select2', function () {
        var elSelect = $(this).siblings('select');
        if (!elSelect.prop('disabled') && !elSelect.data('select2-open')) {
            elSelect.attr('data-s2open', 1).select2('open');
        }
    });

    // Reinitialize Select2 after AJAX content load
    $(document).on('ajaxComplete', function (event, xhr, settings) {
        initializeSelect2(docBody);
    });

    // Initial Select2 initialization
    initializeSelect2(docBody);
});


// async function fetchData(params) {
//     const response = await fetch('https://api.example.com/data');
//     const data = await response.json();
//     return data;
//   }
  
//   $('#mySelect').select2({
//     ajax: {
//       transport: function (params, success, failure) {
//         fetchData(params)
//           .then(data => success(data))
//           .catch(error => failure(error));
//       },
//       delay: 0,
//       processResults: function (data) {
//         return {
//           results: data.items.map(item => ({
//             id: item.id,
//             text: item.name
//           }))
//         };
//       }
//     }
//   });
  