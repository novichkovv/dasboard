/**
 * Created by asus1 on 30.05.2015.
 */
$ = jQuery.noConflict();
$(document).ready(function() {
    $(".sidebar-menu .expand").click(function()
    {
        $(this).parent().children('.sub-menu').slideToggle(100);
    });
});