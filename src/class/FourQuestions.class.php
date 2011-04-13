<?php

class FourQuestions extends Bolt {

	public static function start() {
		
		// see if they have a session
		$cs = b::getCookie('$site/cookieUserSession');
		
		// set hte user
		b::__("_logged", false, true);		
		
		// what up
		if ( is_array($cs) AND $cs['ip'] == IP ) {
			
			// try to get it 
			$s = new \dao\session('get',array('id', $cs['id']));
			
			// if yes
			if ( $s->id !== false ) {
				
				// set hte user
				b::__("_logged", true, true);
				
				// user
				b::__("_u", $s->data, true);
				
			}
			
		}
		
		// gookie
		$ll = b::getCookie('ll');
	
		// no ll
		if ( !$ll ) {
		
			// simple geo
			require_once 'Services/SimpleGeo.php';

			// client
			$client = new Services_SimpleGeo('jpjT4K5fABgmPY3YN8kpp8HyZ2t2VwwE','YufFWeKLHYc2gjz7yFG6j7ywC9ZcQaqz');
			
			// do it 
			$resp = $client->getContextFromIpAddress(IP);
			
			// save them
			$ll = array($resp->query->latitude, $resp->query->longitude);
		
			// save them
			b::setCookie('ll', $ll, b::SecondsInYear);
		
		}
	
		// globalize
		b::__('ll', $ll, true);
	
	}

	public static function preRoute(&$page) {
			
		// no pages
		if ( in_array($page, array('ajax','xhr')) ) { return; }
		
		// uri
		$uri = trim(b::_('_bUri'), '/');
		
		// if first part of uri is not tions
		// we need to redirect with that
		if ( substr($uri, 0, 5) != 'tions' ) {
			header("Location:".URI."tions/{$uri}");
		}
	
	}


}


?>