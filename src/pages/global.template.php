<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>fourqu.es/tions {$metaTitle}</title>
		<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?3.2.0/build/cssfonts/fonts-min.css&3.2.0/build/cssreset/reset-min.css&3.2.0/build/cssgrids/grids-min.css">				
		{$cssEmbeds}			
		<script src="<?php echo b::_('bolt-global'); ?>"></script>    					
		{$extraHead}
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
				developed by <a href='https://github.com/traviskuhl'>@traviskuhl</a> &amp; <a href="https://github.com/traviskuhl/4q-site/blob/master/CONTRIB.md">friends</a> -
				<a href="https://github.com/traviskuhl/4q-site">the code</a>
			
			</div>
		</div>	
		<script type="text/javascript">		
			BLT.init({$jsEmbeds}, {"Urls": { "base": "<?php echo URI; ?>", 'self': "<?php echo SELF ?>", 'login': "<?php echo Config::url('login'); ?>","logout":"<?php echo Config::url('logout'); ?>","ajax":"<?php echo URI; ?>ajax" }, "fb": false, "fbApiKey": "<?php echo Config::get('site/fb-key'); ?>","session": { "logged": <?php echo (Session::getLogged()?'true':'false'); ?>}});     
		</script>
							
		<div id="fb-root"></div>
		<div id="payload"></div>	
		

		<script src="http://static.getclicky.com/js" type="text/javascript"></script>
		<script type="text/javascript">try{ clicky.init(66422072); }catch(e){}</script>
		<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66422072ns.gif" /></p></noscript>		
	</body>
</html>