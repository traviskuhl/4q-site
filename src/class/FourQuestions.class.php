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