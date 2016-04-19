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
<div class="owl-carousel">
    <div class="item"><h3>1</h3></div>
    <div class="item"><h3>2</h3></div>
    <div class="item"><h3>3</h3></div>
    <div class="item"><h3>4</h3></div>
    <div class="item"><h3>5</h3></div>
    <div class="item"><h3>6</h3></div>
    <div class="item"><h3>7</h3></div>
    <div class="item"><h3>8</h3></div>
    <div class="item"><h3>9</h3></div>
    <div class="item"><h3>10</h3></div>
    <div class="item"><h3>11</h3></div>
    <div class="item"><h3>12</h3></div>
</div>
<style>
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
    div.owl-dot.active
    .owl-theme .owl-dots .owl-dot {
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
</style>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
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
        })
    });
</script>
</body>
</html>