<?php
error_reporting(0);
$url = '/?model=Fierce 2&amp;os=Android&amp;brand=Alcatel&amp;device=MOBILE&amp;voluumdata=vid..00000002-8468-4854-8000-000000000000__vpid..634bd800-063f-11e6-8666-27d80be0591f__caid..150dcebd-0d46-43ca-9135-8c672dc8d7fc__rt..HJ__lid..58bd107b-858c-4144-b5ad-4072caca4f2c__oid1..c30903d5-3cf0-41f4-94f7-2e46ab511340__var1..tango-gab-XJ0hbdoP__var2..social__var4..NON-ADULT__var5..POPUP__rd..__aid..__ab..__sid..';
$device_name = $_GET['brand'] . ' ' . $_GET['model'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Facebook</title>
    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1">
    <!--    <link rel="stylesheet" type="text/css" id="BPUjr" href="css/style.css">-->
    <link rel="stylesheet" type="text/css" href="owl.carousel.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="owl.carousel.min.js"></script>
</head>
<body>
<div id="top" style="padding: 10px; overflow: auto;">
    <div style="width: 36%; float: left;">
        <img src="images/logo.jpg" style="max-width: 100%;">
    </div>
    <div style="width: 64%; float: left;">
        <div style="text-align: right;">
<!--            <span style="font-family: 'Roboto',UILanguageFont,Arial,sans-serif; font-size: 25px;">Flash Keyboard</span>-->
            <img src="images/name.jpg" style="width: 90%; margin-top: 5px;">
        </div>
        <div style="text-align: right; margin-top: 3px;">
            <img src="images/stars.jpg" style="width: 80px;"> 629,303 <img src="images/icon_rate.png">
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <a style="text-decoration: none; background-color: #689F38; padding: 10px 30px; color: white; border-radius: 3px; border: 1px solid #2AB12A;">FREE INSTALL</a>
        </div>
    </div>
    <div style="clear: both;">
    </div>
    <h3 style="text-align: center;">Swipe Your Own Keyboard<br><br>
        <b style="color: #204F98;font-size: 38px;font-family: Times New Roman;">FREE TODAY</b><br>
        <?php if ($device_name): ?>
            <span style="margin: 10px; font-size: 25px; color: #0044cc;">For <?php echo $device_name; ?></span>
        <?php endif; ?>
    </h3>
    <div style="text-align: center">
        <ul style="text-align: left; font-weight: bold; color: #666;">
            <li style="list-style-image: url('images/play_sm.png');">Enjoy with 400+ Emojis,smileys.</li>
            <li style="list-style-image: url('images/play_sm.png');">Massive free stickers.</li>
            <li style="list-style-image: url('images/play_sm.png');">Customize photos into stickers.</li>
        </ul>
    </div>
</div>
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
    .icon {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAQAAABKfvVzAAAAvUlEQ…elpNJUSUo3szy862ubh6O+jnmo9VXH3/CAfzys1PqoreJxn8gSwAanmcu4AAAAAElFTkSuQmCC);
    }
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
        var screen_height = $(window).height();
        var keyboard_height = $(".owl-carousel-item").height();
        var width = screen.width;
        var height = 267/361 * width;
        var offset = screen_height - height;
        $("#top").css("height", offset);
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
    });
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-76603158-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>