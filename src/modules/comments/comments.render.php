<?php

namespace modules;

class comments extends \FrontEnd {

	function render($args) {
		
		// reset
		$args['asset'] = $this->arg('asset');
		$args['id'] = $this->arg('id');
	
		$q = array(
			'asset_type' => $this->arg('asset'),
			'asset_id' => $this->arg('id'),
		);
		
		$a = array(
			'sort' => array( 'ts' => 1 ),
			'per' => $this->arg('per', 30)
		);
	
		// get comments
		$args['comments'] = new \dao\comments('get',array($q, $a));
		
		// reutrn
		return \Controller::renderModule(
			'comments/comments',
			$args
		);
	
	}

	function ajax() {
	
		// login
		if ( !\b::_('_logged') ) {
			return array('login' => 'false');
		}		
		
		// user
		$u = \b::_('_u');		
		
		$a = p('asset');
		$id = p('id');


		// do it 
		$c = new \dao\comments();
		
		$c->asset_type = p('asset');
		$c->asset_id = $id;
		$c->user = array(
			'login' => $u->login,
			'gravatar_id' => $u->gravatar_id,
			'email' => $u->email,
			'name' => $u->name
		);
		$c->text = strip_tags(p('text'));
		$c->ts = \b::utctime();
		
		// save
		$c->save();
		
		$url = "https://github.com/{$u->login}";
		
		// html
		$html = "
			<li>
				<div class='avatar'>
					<a href='".$url."'><img width='40' height='40' src='https://secure.gravatar.com/avatar/{$u->gravatar_id}?s=100&d=https://d3nwyuy0nl342s.cloudfront.net%2Fimages%2Fgravatars%2Fgravatar-140.png'></a>				
				</div>
				<div class='content'>
					<a href='".$url."' class='b'>{$u->name}</a> ".nl2br($c->text)."
				</div>
			</li>
		";
		
		return array('html'=>$html);	
	
	}

}

?>