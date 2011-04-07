<?php

class browse extends FrontEnd {

	public function render($args) {
	
		// body
		$args['bodyClass'] = 'browse';	
		
		// tag
		$args['onTag'] = $tag = pp(0); if ( $tag ) { $tag = " / ".$tag; }
		
		$args['h1'] = '<h1><a href="'.b::url('browse').'">Browse</a></h1><b>'.$tag.'</b>';

		$q = array();

		// get them all
		$args['answers'] = new \dao\answers('get',array($q));
		
		// get all tags
		$args['tags'] = array();
		
		// loop and find
		foreach ( $args['answers'] as $item ) {
			if ($item->tags_display) {
				foreach ( $item->tags->display as $t ) {
					
					// there
					if ( !array_key_exists($t, $args['tags']) ) { $args['tags'][$t] = 0; }
				
					// add one
					$args['tags'][$t]++;
					
				}
			}
		}
		
		// render page
		return Controller::renderPage(
			'browse/browse',
			$args
		);
	
	}
	
}
	
?>