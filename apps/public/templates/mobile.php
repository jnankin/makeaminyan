<!doctype html> 
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include_title() ?>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <? if (has_slot('head'))
            include_slot('head'); ?>
        <script src="/js/jquery-1.4.2.min.js" type="text/javascript"></script>
        <script src="/js/masks.js" type="text/javascript"></script>
        <link href="/css/mobile.css" media="screen" type="text/css" rel="stylesheet"/>
        <link href="/css/formTablelessMobile.css" media="screen" type="text/css" rel="stylesheet"/>
        <link href="/favicon.png" type="image/png" rel="apple-touch-icon" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=yes;" />
        <meta names="apple-mobile-web-app-status-bar-style" content="black" />

    </head>
    <body onload="setTimeout(function() { window.scrollTo(0, 1); }, 100)">
        <div id="header"><img src="/images/makeminyanmobile.png" /></div>
        <div id="wrapper"><?php echo $sf_content ?></div>
    </body>
</html>
