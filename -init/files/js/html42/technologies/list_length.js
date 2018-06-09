/**
 * Requires jQuery
 */
function list_length() {
    $('ul').change(function() {
        $(this).attr('data-length', $(this).children('li').length);
    }).trigger('change');
};