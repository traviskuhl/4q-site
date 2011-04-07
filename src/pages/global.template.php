<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>fourqu.es/tions</title>
		<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?3.2.0/build/cssfonts/fonts-min.css&3.2.0/build/cssreset/reset-min.css&3.2.0/build/cssgrids/grids-min.css">				
		{$cssEmbeds}			
		<script src="<?php echo URI; ?>assets/bolt/js/global.js"></script>    					
	</head>
	<body class="{$bodyClass}">
		<div id="doc">
			<div id="hd">
				<div class="cnt">
					<?php 
						$t = '<a href="{$url.home}" class="logo"><em>4</em>Q<span>uestions</span></a>';
					
						if ( isset($h1) ) {
							echo "{$t} {$h1}";
						}
						else {
							echo "<h1>{$t}</h1>";	
						}
					?>
				</div>
			</div>
			<div id="bd">
				{$_body}
			</div>
			<div id="ft">
				&copy; Copyright 2011 <a href="http://the.kuhl.co">the.kuhl.co</a> - 
				developed by <a href='http://twitter.com/traviskuhl'>@traviskuhl</a> w/ <a href="http://twitter.com/rochers">@rochers</a> -
				<a href="https://github.com/traviskuhl/4q-site">the code</a>
			
			</div>
		</div>	
		<script type="text/javascript">		
			BLT.init({$jsEmbeds}, {"Urls": { "base": "<?php echo URI; ?>", 'self': "<?php echo SELF ?>", 'login': "<?php echo Config::url('login'); ?>","logout":"<?php echo Config::url('logout'); ?>","ajax":"<?php echo URI; ?>ajax" }, "fb": false, "fbApiKey": "<?php echo Config::get('site/fb-key'); ?>","session": { "logged": <?php echo (Session::getLogged()?'true':'false'); ?>}});     
		</script>
							
		<div id="fb-root"></div>
		<div id="payload"></div>	
	</body>
</html>