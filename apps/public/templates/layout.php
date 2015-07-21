<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>

    <link rel="shortcut icon" href="/favicon.ico" />
    <link href="/css/main.css" media="screen" type="text/css" rel="stylesheet"/>
    <link href="/css/formTableless.css" media="screen" type="text/css" rel="stylesheet"/>
    <script src="/js/jquery-1.4.2.min.js" type="text/javascript"></script>
    <script src="/js/masks.js" type="text/javascript"></script>
    <script src="/js/mam_ui.js" type="text/javascript"></script>
    <script src="/js/general.js" type="text/javascript"></script>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="logo">
                    <a href="/"><img src="/images/logo.png" border="0"></a>
		</div>
	</div>
	<!-- end #header -->
        <? MenuBar::writeMenu(($sf_response->menu ? $sf_response->menu : MenuBar::$EXTERNAL), $sf_response); ?>
	<!-- end #menu -->
	<div id="page">
		  <?php echo $sf_content ?>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) 2011 Makeaminyan.com. All rights reserved. A <a href="http://www.kishkee.com">Kishkee</a> Project</p>
</div>
<!-- end #footer -->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7349037-12']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>
