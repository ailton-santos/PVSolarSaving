(function($) {
    "use strict"; // Start of use strict

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $(document).on('click', 'a.page-scroll', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 100
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Offset for Main Navigation
    $('#mainNav').affix({
        offset: {
            top: 50
        }
    })

    // Chart
    if ($('.ct-chart').length > 0) {
        
        var solr = parseInt($('#dom-target').text());

        var data = {
            labels: ['Electric', 'Solar'],
            series: [100 - solr, solr]
        };

        var options = {
            labelInterpolationFnc: function(value) {
                return value[0]
            }
        };

        var responsiveOptions = [
            ['screen and (min-width: 640px)', {
                chartPadding: 30,
                labelOffset: 100,
                labelDirection: 'explode',
                labelInterpolationFnc: function(value) {
                return value;
                }
            }],
            ['screen and (min-width: 1024px)', {
                labelOffset: 80,
                chartPadding: 20
            }]
        ];

        new Chartist.Pie('.ct-chart', data, options, responsiveOptions);
    }

    //Validate form
    var constraints = {
        'utility-bill': {
            presence: true,
            format: {
                pattern: "[0-9\.]+",
                message: "Can only contain numbers and decimal point"
            }
        },
        'estimated-cost': {
            presence: true,
            format: {
                pattern: "[0-9\.]+",
                message: "Can only contain numbers and decimal point"
            }
        },
        'zip-code': {
            presence: true,
            format: {
                pattern: "\\d{5}"
            }
        }
    }

    if ($('form#solar-calc').length > 0) {
        // Hook up the form so we can prevent it from being posted
        var form = document.querySelector("form#solar-calc");
        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            handleFormSubmit(form);
        });

        // Hook up the inputs to validate on the fly
        var inputs = document.querySelectorAll("input, textarea, select")
        for (var i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(ev) {
                var errors = validate(form, constraints) || {};
                showErrorsForInput(this, errors[this.name])
            });
        }

        // Shows the errors for a specific input
        function showErrorsForInput(input, errors) {
            var error_element = $(input).next('.error');
            error_element.text('');
            if (errors) {
                var txt = '';
                $.each(errors, function(key, value) {
                    txt += value + ' ';
                });
                error_element.text(txt);
            }
        }

        function handleFormSubmit(form, input) {
            // validate the form aainst the constraints
            var errors = validate(form, constraints);
            console.log(errors);
            // then we update the form to reflect the results
            showErrors(form, errors || {});
            if (!errors) {
                form.submit();
            }
        }

        // Updates the inputs with the validation errors
        function showErrors(form, errors) {
            // We loop through all the inputs and show the errors for that input
            $.each(form.querySelectorAll("input[name], select[name]"), function(input) {
                // Since the errors can be null if no errors were found we need to handle
                // that
                showErrorsForInput(input, errors && errors[input.name]);
            });
        }
    } // end form if 



})(jQuery); // End of use strict
