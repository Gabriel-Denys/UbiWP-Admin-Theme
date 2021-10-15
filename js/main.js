jQuery('html').addClass('hidden');

jQuery(document).ready(function($) {
    $('html').removeClass('hidden');

    $('.wp-not-current-submenu > a').click(function(e) {
        $(this).unbind('click');
        e.preventDefault();
        e.stopPropagation();

        $(this).parents('li').children('.wp-submenu.wp-submenu-wrap').addClass('openmenudesktop');



    });

    var url = window.location.href;
    var current_page = $(location).attr("href").split('/').pop();

    //console.log($('a[href="' + current_page + '"]').parents("li.menu-top"));
    console.log($("#adminmenu > li"));
    $("#adminmenu > li").removeClass("current wp-menu-open wp-has-current-submenu");
    $("#adminmenu > li").addClass("wp-not-current-submenu");
    $("#adminmenu > li > a").removeAttr("href");

    $('a[href="' + current_page + '"]').parents("li.menu-top").addClass("current wp-menu-open wp-has-current-submenu");
    $('a[href="' + current_page + '"]').parents("li.menu-top").removeClass("wp-not-current-submenu")
        //$('a[href="' + current_page + '"]').parents("ul.wp-submenu").addClass();

    $('a[href="' + current_page + '"]').click(function() {
        $("#adminmenu > li").removeClass("current wp-menu-open wp-has-current-submenu");
        $("#adminmenu > li").addClass("wp-not-current-submenu");
        $("#adminmenu > li > a").removeAttr("href");

        $('a[href="' + current_page + '"]').parents("li.menu-top").addClass("current wp-menu-open wp-has-current-submenu");
        $('a[href="' + current_page + '"]').parents("li.menu-top").removeClass("wp-not-current-submenu")
    })

});

window.wpNavMenuUrlUpdate = function(e) {
    return;
}