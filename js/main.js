// prevent FOUC
jQuery('html').addClass('hidden');

jQuery(document).ready(function($) {
    $('html').removeClass('hidden'); // prevent FOUC

    //When clicking menu name that has a submenu, don't redirect unless the menu is folded
    $('.wp-has-submenu > a').click(function(e) {
        if (!$("body").hasClass("folded")) {
            e.preventDefault();
            e.stopPropagation();

            // add and remove classes responsible for open states
            if ($(this).parents('li').children('.wp-submenu.wp-submenu-wrap').hasClass("openmenudesktop")) {
                $(this).parents('li').children('.wp-submenu.wp-submenu-wrap').removeClass('openmenudesktop');
            } else {
                $(this).parents('li').children('.wp-submenu.wp-submenu-wrap').addClass('openmenudesktop');
            }
        }

    });

    //if menu has wp class open, then it should be appended the open class from ubi
    $(".wp-menu-open").parents("li").children(".wp-submenu.wp-submenu-wrap").addClass("openmenudesktop");

    //set all other menus to a closed state
    $("#adminmenu > li").removeClass("current wp-menu-open wp-has-current-submenu");
    $("#adminmenu > li").addClass("wp-not-current-submenu");

    //open menu needs to have other relevant WP classes that signify it is open
    $(".wp-menu-open").parents("li").addClass("current wp-menu-open wp-has-current-submenu");
    $(".wp-menu-open").parents("li").removeClass("wp-not-current-submenu")

    //toggle the menu open and closed
    $(".ubi-admin-menu-toggle").click(function(e) {
        if ($(this).hasClass("open-admin")) {
            $(this).removeClass("open-admin");
            $("#wpwrap").removeClass("wp-responsive-open");

        } else {
            $(this).addClass("open-admin")
            $("#wpwrap").addClass("wp-responsive-open");

        }
    })
});

window.wpNavMenuUrlUpdate = function(e) {
    return;
}