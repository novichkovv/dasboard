<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Facebook</title>

    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1">
<!--    <link rel="stylesheet" type="text/css" id="BPUjr" href="css/style.css">-->
    <link rel="stylesheet" type="text/css" href="owl.carousel.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="owl.carousel.min.js"></script>
</head>
<body>
<div id="carousel">
    <div class="owl-carousel">
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/1.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/2.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/3.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/4.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/5.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/6.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/7.jpg"></div>
        <div class="item"><img class="owl-carousel-item" src="images/keyboards/8.jpg"></div>
    </div>
</div>
<style>
    .owl-carousel-item {

    }
    #carousel {
        /*margin-top: 100px;*/
        /*position: absolute;*/
        /*bottom: 0;*/
    }
    .owl-controls {
        margin-top: 10px;
        text-align: center;
        -webkit-tap-highlight-color: transparent;
    }
    .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
        background: #869791;
    }
    .owl-dots .owl-dot {
        display: inline-block;
        zoom: 1;
    }
    .item {
        background-color: green;
    }
    .owl-item {
        background-color: green;
    }
    .owl-dots .owl-dot span {
        width: 10px;
        height: 10px;
        margin: 5px 7px;
        background: #d6d6d6;
        display: block;
        -webkit-backface-visibility: visible;
        -webkit-transition: opacity 200ms ease;
        -moz-transition: opacity 200ms ease;
        -ms-transition: opacity 200ms ease;
        -o-transition: opacity 200ms ease;
        transition: opacity 200ms ease;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
    }
    *, *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    /*div.owl-dot.active*/
    .owl-dots .owl-dot {
        display: inline-block;
        zoom: 1;
    }
    .owl-carousel .owl-controls .owl-dot, .owl-carousel .owl-controls .owl-nav .owl-next, .owl-carousel .owl-controls .owl-nav .owl-prev {
        cursor: pointer;
        cursor: hand;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    div.owl-controls
    .owl-theme .owl-controls {
        margin-top: 10px;
        text-align: center;
        -webkit-tap-highlight-color: transparent;
    }
    body {
        background: #fff;
        color: #222;
        padding: 0;
        margin: 0;
        font-family: "Lato","Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif;
        font-weight: normal;
        font-style: normal;
        line-height: 1;
        position: relative;
        cursor: default;
    }
    html, body {
        font-size: 100%;
    }
    html
    html, body {
        font-size: 100%;
    }
    html {
        font-family: sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }
    .owl-nav {
        display: none;
    }
</style>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        var screen_height = document.body.clientHeight;
        console.log(screen_height);
        console.log($(".owl-carousel-item").height());
        var keyboard_height = $(".owl-carousel-item").height();
        var width = screen.width;
        console.log(width);
        var height = 267/361 * width;
//        alert(keyboard_height);
        console.log(screen_height);
        var offset = screen_height - height;
        console.log(offset);
        $("#carousel").css("margin-top", offset);
//        setTimeout(function() {
//            $(".owl-controls").css('margin-top', - offset );
//        }, 1000)
        console.log($(".owl-controls").clone());
        $("#carousel").prepend($(".owl-controls").clone());
        var owl = $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        });
        $(".owl-controls").css('margin-top', - offset + 60 );
//        owl.on('initialized.owl.carousel', function() {
//            console.log(1);
//        })
    });
</script>
</body>
</html>