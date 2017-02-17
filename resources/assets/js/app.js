/*global $, document */
/*jslint
    this
*/
"use strict";

/**
 *
 * Set default variables.
 *
 */

var attachmentCounter = 1;
var validateInit = [];


/**
 *
 * Detect touch devices.
 *
 */

function is_touch_device() {
  return 'ontouchstart' in window        // works on most browsers 
      || navigator.maxTouchPoints;       // works on IE10/11 and Surface
};


/**
 *
 * Adapt menu for mobiles.
 *
 */

function adaptMenu() {
    if ($(window).width() < 720) {
        $('.main-nav').removeClass('btn-group-justified');
        $('.main-nav').addClass('btn-group-vertical');
        $('.main-nav').addClass('btn-block');
    } else {
        $('.main-nav').addClass('btn-group-justified');
        $('.main-nav').removeClass('btn-group-vertical');
        $('.main-nav').removeClass('btn-block');
    }
}


/**
 *
 * Start and stop page progress.
 *
 */

function startProgress() {
    // Start nprogress
    NProgress.start();

    // Add the loading class
    $('body').addClass('loading');
}

function stopProgress() {
    // Remove nprogress
    NProgress.done();

    // Remove the loading class
    $('body').removeClass('loading');
}


/**
 *
 * Toggle Page Function.
 *
 */

function togglePage($element, $ajaxUri, $slug) {
    if ($element.is(":visible") == true) {
        // Hide all open windows
        $('.ajaxable').slideUp();

        // Change history
        window.history.pushState("", "", "/");
    } else {
        startProgress();

        // Change the history
        window.history.pushState("", "", "/" + $slug);

        // Hide all open windows
        $('.ajaxable').slideUp();

        $.ajax({
            url: $ajaxUri,
            cache: false
        })
        .done(function(html) {
            $element.html(html).slideDown();
            setUpValidation();
            stopProgress();
        })
        .error(function(html) {
            $.growl.error({ title: "Error", message: 'You have been logged out, please reload the page to login again'});
            stopProgress();
        });
    }
}


/**
 *
 * Set up validation function.
 *
 */
function setUpValidation() {
    var index;
    for (index = 0; index < validateInit.length; ++index) {
        validateInit[index]();
    }
}


/**
 *
 * Ticket Creation Toggle Function.
 *
 */
function toggleFormFields(typeValue){

    $('.icon-container label').removeClass('selected');
    $selectedTab = $('input[name=type]:checked', '.object-editor').val();
    $labelFor = $('input[name=type][value=' + $selectedTab + ']').attr('id');
    $('label[for="' + $labelFor + '"]').addClass('selected');

    $('.dateInput').mask("99/99/9999");

    switch(typeValue) {
        case '1':
            $('#publishedAtDiv').addClass('hidden');
            $('#authorDiv').addClass('hidden');
            $('#categoriesDiv').addClass('hidden');
            $('#artitcleTitleDiv').addClass('hidden');
            $('#scheduleDiv').addClass('hidden');
            $('#content').attr('placeholder', 'Amend Description e.g. Please put the new attached logo within my homepage associates section...');
            $('.form-title-input').attr('placeholder', 'Title of amend required e.g. Update homepage image');
            break;
        case '2':
            $('#publishedAtDiv').removeClass('hidden');
            $('#authorDiv').removeClass('hidden');
            $('#categoriesDiv').removeClass('hidden');
            $('#artitcleTitleDiv').removeClass('hidden');
            $('#scheduleDiv').addClass('hidden');
            $('#content').attr('placeholder', 'Notes (For content ideally please submit a word or text doc. below)');
            $('.form-title-input').attr('placeholder', 'Title of content e.g. My blog post');
            break;
        case '3':
            $('#publishedAtDiv').addClass('hidden');
            $('#authorDiv').addClass('hidden');
            $('#categoriesDiv').addClass('hidden');
            $('#artitcleTitleDiv').addClass('hidden');
            $('#scheduleDiv').removeClass('hidden');
            $('#content').attr('placeholder', 'Your Text');
            $('.form-title-input').attr('placeholder', 'Title');
            break;
        case '4':
            $('#publishedAtDiv').addClass('hidden');
            $('#authorDiv').addClass('hidden');
            $('#categoriesDiv').addClass('hidden');
            $('#artitcleTitleDiv').addClass('hidden');
            $('#scheduleDiv').addClass('hidden');
            $('#content').attr('placeholder', 'How can we help...');
            $('.form-title-input').attr('placeholder', 'Title');
            break;
        default:
            break;
    }
}


/**
 *
 * Adapt the menu so mobile devices.
 *
 */

$(window).resize(function () {
    adaptMenu();
});
adaptMenu();

/**
 *
 * Document Ready Functions.
 *
 */

$(document).ready(function () {

    /**
     *
     * Set up the csrf token for all ajax requests.
     *
     */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    /**
     *
     * Set up validation for any existing content.
     *
     */

    setUpValidation();

    // Set touch 
    if(is_touch_device()) {
        $('.click-only').addClass('hidden');
        $('.touch-only').removeClass('hidden');

        $('.tappable-row').on('click', function() {
            window.location.href = $(this).data('url');
        });
    } else {
        $('.click-only').removeClass('hidden');
        $('.touch-only').addClass('hidden');
    }

    if($('body').hasClass('admin')) {
        // Maintenance Button
        $('.btn-maintenance-support').on('click', function (e) {
            togglePage($("#maintenance-support-div"), '/dashboard/maintenance', 'maintenance');
            e.preventDefault();
        });

        // Clients Button
        $('.btn-clients').on('click', function (e) {
            togglePage($("#clients-div"), '/clients', 'clients');
            e.preventDefault();
        });

        // Banners
        $('.btn-banners').on('click', function (e) {
            togglePage($('#banners-div'), '/adverts', 'adverts');
            e.preventDefault();
        });

        // Services
        $('.btn-services').on('click', function (e) {
            togglePage($("#services-div"), '/services', 'services');
            e.preventDefault();
        });

        // Seo
        $('.btn-seo-reports-admin').on('click', function (e) {
            togglePage($("#seo-div"), '/documents/seo', 'documents/seo');
            e.preventDefault();
        });

        // Info
        $('.btn-information-documents-admin').on('click', function (e) {
            togglePage($("#info-div"), '/documents/info', 'documents/info');
            e.preventDefault();
        });

        //Tickcet Update Log
        $('.btn-report-ticket').on('click', function (e) {
            togglePage($("#report-ticket"), '/ticket_logs', 'ticket_logs');
            e.preventDefault();
        });

        // When clicking create or a client, load the data and scroll to the view
        $('body').on('click', '.clientFormToggler', function (e) {
            if ($(this).attr('clientId') == 0) {
                var loadURI = '/clients/create';
            } else {
                var loadURI = '/clients/' + $(this).attr('clientId') + '/edit';
            }

            window.history.pushState("", "", loadURI);

            startProgress();
            $.ajax({
                type: 'GET',
                url: loadURI,
                success: function (response) {
                    // Load the response into the div
                    $("#clientFormDiv").html(response);
                    setUpValidation();

                    // Stop nprogress
                    stopProgress();

                    // Scroll to the form
                    $.smoothScroll({
                        offset: -100,
                        scrollTarget: '#clientFormDiv'
                    });
                },
                error: function(html) {
                    $.growl.error({ title: "Error", message: 'You have been logged out, please reload the page to login again'});
                    stopProgress();
                }
            });

            e.preventDefault();
        });

        // All Form Toggle Buttons
        $('body').on('click', '.form-toggler', function (e) {
            var $uri = $(this).data('uri');
            var $target = $(this).data('target');
            var $selecttarget = $(this).data('selecttarget');

            startProgress();

            window.history.pushState("", "", $uri);

            $.ajax({
                type: 'GET',
                url: $uri,
                success: function (response) {
                    $($target).html(response);
                    $($target).slideDown();

                    $('.clientValue').val($($selecttarget).val());
                    setUpValidation();
                    stopProgress();

                    // Scroll to the form
                    $.smoothScroll({
                        offset: -100,
                        scrollTarget: $target
                    });
                },
                error: function(html) {
                    $.growl.error({ title: "Error", message: 'You have been logged out, please reload the page to login again'});
                    stopProgress();
                }
            });
            e.preventDefault();
        });

        // All delete buttons
        $('body').on('click', '.ajax-delete', function (e) {
            var $type = $(this).data('type');
            var $contentid = $(this).data('contentid');
            var $uri = $(this).data('uri') + $contentid;
            var $delrow = $(this).data('delrow');

            bootbox.confirm('Are you sure you want to delete this ' + $type + ' permanently?', function(result) {
                if(result == true) {
                    $.ajax({
                        type: 'DELETE',
                        url: $uri,
                        success: function (response) {
                            var res = $.parseJSON(response);
                            $($delrow).remove();
                            $.growl.notice({ title: "Success", message: res.success});
                        },
                        error: function(html) {
                            $.growl.error({ title: "Error", message: 'You have been logged out, please reload the page to login again'});
                            stopProgress();
                        }
                    });
                }
            }); 

            e.preventDefault();
            e.stopPropagation();
        });

        // All Client Dropdowns
        $('body').on('change', '.ajax-dropdown', function (e) {
            var $clientid = $(this).val();
            var $target = $(this).data('target');
            var $uri = $(this).data('uri') + $clientid;

            // Set global client ID
            window.history.pushState("", "", $uri);

            if($clientid != '') {
                startProgress();
                $('.clientValue').val($clientid);
                $.ajax({
                    type: 'GET',
                    url: $uri,
                    success: function (response) {
                        $($target).html(response);
                        $($target).slideDown();
                        stopProgress();
                    },
                    error: function(html) {
                        $.growl.error({ title: "Error", message: 'You have been logged out, please reload the page to login again'});
                        stopProgress();
                    }
                });
            } else {
                $($target).html('');
                $($target).slideUp();
            }

            e.preventDefault();
            e.stopPropagation();
        });

        $('.dateInput').mask("99/99/9999");
    }


    /**
     *
     * Ticket Creation Toggle.
     *
     */

    $('.type').on('change', function(){
        toggleFormFields($('.type:checked').val());

        // Scroll down the the form
        $.smoothScroll({
            offset: -100,
            scrollTarget: '.ticket-form'
        });
    });

    $('.type').on('click', function(){
        // Scroll down the the form
        $.smoothScroll({
            offset: -100,
            scrollTarget: '.ticket-form'
        });
    });

    toggleFormFields($('.type:checked').val());
    $('.dateInput').mask("99/99/9999");


    /**
     *
     * Sortable tables.
     *
     */

    $('.sorted_table').sortable({
        containerSelector: 'table',
        handle: 'i.icon-move',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        onDrop: function ($item, container, _super, event) {

            var new_order = [];
            $("#ticket_table tbody").find("tr").each(function () {
                new_order.push(this.id);
            });

            $.ajax({
                type: "POST",
                url: '/api/ticketsort',
                data: {
                    'user_id': $client_id,
                    'archived': $archived,
                    'new_order': new_order
                },
                success: function (response) {

                }
            });

            $item.removeClass(container.group.options.draggedClass).removeAttr("style")
            $("body").removeClass(container.group.options.bodyClass)
        }
    });


    /**
     *
     * File Inputs.
     *
     */
    var fileInputCounter = 1;
    function addInput() {
        if($('.file-view-template').length) {
            // Remove blanks
            $("input[type='file']").filter(function (){
                // Check if its the template
                if($(this).closest('.file-view-template').length > 0) {
                    return false;
                } else {
                    return !this.value;
                }
            }).parent('.form-group').remove();

            // Add a new one
            var html = $('.file-view-template').html().replace(/XX/g, fileInputCounter);
            $('.file-input-container').append(html);

            // Add to the counter to prevent collisions
            fileInputCounter++;
        }
    }
    addInput();

    var originalPlaceholder = '';
    var dropMessage = 'Drop file to upload...';

    $('body').on('dragenter', '.file-input', function(e) {
        e.preventDefault();
        e.stopPropagation();

        originalPlaceholder = $(this).prev('.label-file').children('.file-source').text();
        $(this).prev('.label-file').children('.file-source').text(dropMessage);
        $(this).prev('.label-file').addClass('hover mouse-over');
    });

    $('body').on('dragleave', '.file-input', function(e) {
        $(this).prev('.label-file').children('.file-source').text(originalPlaceholder);
        $(this).prev('.label-file').removeClass('hover mouse-over');
        originalPlaceholder = '';
    });

    $('body').on('drop', '.file-input', function(e) {
        $(this).prev('.label-file').removeClass('hover mouse-over');
        $(this).prev('.label-file').children('.file-source').text(originalPlaceholder);
        originalPlaceholder = '';
    });

    $('body').on('mouseenter', '.file-input', function(e) {
        $(this).prev('.label-file').addClass('hover');
    });

    $('body').on('mouseleave', '.file-input', function(e) {
        $(this).prev('.label-file').removeClass('hover');
    });

    $('body').on('change', '.file-input', function() {
        if($(this).val().length) {
            $($(this).data('file-text')).html($(this).val());
            addInput();
        } else {
            $(this).val('Attachments, click to add file');
        }
    });

    $('body').on('click', '.clickable-row', function() {
        window.location.href = $(this).data('href');
    });

    $('body').on('focus', '.input-daterange', function(){
        $(this).datepicker();
    });
});



