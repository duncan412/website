/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';
import Typed from 'typed.js';
import $ from 'jquery';

$(document).ready(function () {
    let fullHeight = function () {

        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function () {
            $('.js-fullheight').css('height', $(window).height());
        });

    };
    fullHeight();

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
    });

    var typed = new Typed('#typed', {
        stringsElement: '#typed-strings',
        loop: true,
        smartBackspace: true,
        loopCount: Infinity,
        backDelay: 1200,
        backSpeed: 50,
        typeSpeed: 50,
    });

    $('.switch-language').each(function (index, element) {
        $(element).on('click', function (event) {
            let url = window.location.href;

            if (url.indexOf('?') > -1) {
                url = url.split('?')[0];
            }
            url += '?locale=' + $(this).data('language');
            window.location.href = url;
        });
    });

});
