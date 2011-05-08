<?php

class index extends FrontEnd {

	public function render($args) {
	
		// body
		$args['bodyClass'] = 'index';	
		
		// path
		switch( pp(1) ) {
		
			// git hub
			case 'github':
				return $this->github($args);
				
			// ajax
			case 'ajax':
				return $this->ajax($args);
				
			// login
			case 'login':
				return $this->login($args);
		
			// default
			default:
				return $this->home($args);		
		
		}

	
	}
	
	public function home($args) {
		
		// user
		$user = pp(1);
	
		// query
		if ( $user ) {
			$q = array(
				'by.login' => $user
			);		
		}
		else { 
			$q = array(
				'featured' => 1
			);
		}
		
		$a = array(
			'sort' => array('modified' => -1),
			'per' => 1
		);
	
		// st
		$sth = new \dao\answers('get',array($q, $a));
		
			// nope
			if ( $user AND $sth->_total == 0 ) {
				b::show_404();
			}
	
		// ansers
		$args['answer'] = $a = $sth->item(0);
	
		// h1
		$args['h1'] = "<b>for</b> <h1><a href='".b::url('profile',array('name'=>$args['answer']->by->login))."'>{$args['answer']->by->name}</a></h1>";
	
	
		// near
		$args['near'] = new \dao\answers('get',
			array(
				array( 'll' => array('$near'=> b::_('ll')) ), 
				array('per'=>5)
			)
		);
	
		// add a title
		$args['metaTitle'] = "for " . trim($a->by->name);
		
		// url
		$url = b::url('profile',array('name'=>$args['answer']->by->login));
		
		// extra head
		$args['extraHead'] = '
			<link rel="canonical" href="'.$url.'" /> 
		    <meta property="og:title" content="FourQuestions for '.trim($a->by->name).'"/>
		    <meta property="og:type" content="website"/>
		    <meta property="og:url" content="'.$url.'"/>
		    <meta property="og:image" content="'.$a->pic.'"/>
		    <meta property="og:site_name" content="FourQuestions"/>
		    <meta property="fb:admins" content="503173345"/>
		    <meta property="og:description" content="Four simple questions to learn more about developers and the things they make. Powered by GitHub.com"/>
		';
	
		// render
		return Controller::renderPage(
			"index/index",
			$args
		);		
	
	}

	public function github($args) {
		
		// markdown
		include("/home/bolt/share/htdocs/drib/it/markdown.php");		
	
		// id
		$id = p('id');
	
		// get some info about the commit
		$info = json_decode(`curl -s http://github.com/api/v2/json/commits/show/traviskuhl/4q/$id`, true);

		// files
		$files = array();

		// info
		$c = $info['commit'];
		
		// modified
		if ( isset($c['modified']) ) {
			foreach ($c['modified'] as $m) {
				$files[] = $m['filename'];
			}
		}
		if ( isset($c['added']) ) {
			$files = array_merge($files, $c['added']);
		}		
		
		// if author
		if ( isset($c['author']) ) {
			$c['committer'] = $c['author'];
		}
		
		// what was added
		foreach ( $files as $added ) {
		
			if ( !$added OR $added == 'questions.md' OR $added == 'README.md' ) { continue; }
			
			// lets get what they added
			$cmd = "curl -Ls https://github.com/traviskuhl/4q/raw/master/{$added}";
		
			// run it
			$answers = `$cmd`;
			
			// split into lines
			$lines = explode("\n", $answers);
			
			// name
			$c['committer']['name'] = trim($lines[0],'#');			
			
			// tags
			$tags = array();
			$t = 0;
			
				// loop and find tags
				foreach ( $lines as $i => $line ) {
					if ( trim(strtolower($line)) == '## tags' ) { unset($lines[$i]); $t = true; }
					else if ( $t ) { 					
						$tg = trim(str_replace('*','',$line));
						if ( $tg )	 {
							$tags[] = $tg;
							unset($lines[$i]);
						}
					}
					else if ( trim(strtolower($line)) == '## about you' ) { $lines[$i] = "## About {$c['committer']['name']}"; }
					else if ( substr(trim(strtolower($line)),0,8) == '## bonus' ) {
						$lines[$i] = str_replace("Bonus: Answer your own question:", "Bonus - asked by {$c['committer']['name']}: ", $line);
					}
				}
		
			// print it
			$html = Markdown(trim(implode("\n", array_slice($lines,2))));
			
			// job
			list($job, $ht) = explode("-", $lines[1]);
		
			// now create their answers
			$a = new \dao\answer('get',array('by.login', $c['committer']['login']));
		
			// set some stuff
			$a->text = array(
				'raw' => $answers,
				'html' => $html
			); 
			$a->by = $c['committer'];
			$a->uid = md5($c['committer']['email']);
			$a->commit = array(
				'id' => $c['id'],
				'url' => $c['url'],
				'date' => strtotime($c['committed_date']),
				'author' => strtotime($c['authored_date']),
				'file' => $added
			);
			$a->job = trim($job);
			$a->loc = trim($ht);
			$a->tags = array(
				'display' => $tags,
				'search' => array_map(function($i){ return strtolower(b::makeSlug($i)); }, $tags)
			);
			
			// simple geo
			require_once 'Services/SimpleGeo.php';

			// client
			$client = new Services_SimpleGeo('jpjT4K5fABgmPY3YN8kpp8HyZ2t2VwwE','YufFWeKLHYc2gjz7yFG6j7ywC9ZcQaqz');
			
			// do it 
			$resp = $client->getContextFromAddress($ht);
			
			// save them
			$a->ll = array($resp->query->latitude, $resp->query->longitude);
			
			// save
			$a->save();
			
			// tell them
			echo b::url("profile",array('name'=>$a->by->login)) . "<br>";
		
		}
		
		// done
		exit();
	
	}

	function ajax() {
	
		// get an id
		$id = p('id');
		
		// a
		$a = new \dao\answer('get',array('id', $id));
		
		// find out if we have this user in cache
		$cid = "gh.user.{$id}";
		
		if ( ($html = $this->cache->get($cid)) == false ) {
			
			// get me some xhr
			$ws = new Webservice(array(
				'host' => 'github.com'
			));
			
			// call it 
			$r = $ws->sendRequest("api/v2/json/user/show/".$a->by->login);
			
			// user
			$u = $r['user'];
			
			// url
			$url = "https://github.com/{$u['login']}";
			
			// html
			$html = "
				<b></b>
				<a class='btn' href='https://github.com/users/follow?target={$u['login']}'>Follow</a> 
				<a class='btn' href='https://github.com/inbox/new/{$u['login']}'>Message</a>			
				<a href='$url'><img src='{$a->pic}'></a>
				<ul>
					<li><a href='$url/repositories'><em>".number_format($u['public_repo_count'])."</em> Repos</a></li>
					<li><a href='$url/following'><em>".number_format($u['following_count'])."</em> Following</a></li>
					<li><a href='$url/followers'><em>".number_format($u['followers_count'])."</em> Followers</a></li>				
				</ul>
			";
			
			// tags
			$tags = $a->tags->display->asArray();
			
			// tags
			if ( count($tags) > 0 ) {
				$html .= "<div>Tags: ".implode(", ", array_map(function($i){ return "<a href='".b::url('browse',array('tag'=>$i))."'>$i</a>"; }, $tags));
			}
		
			// save
			$this->cache->set($cid, $html, 60*30);
			
		}
		
		// print the response
		$this->printJsonResponse(array(
			'html' => $html
		));

	}


	function login($args) {
	
		// id
		$id = b::_('site/oauth-key');
		$secret = b::_('site/oauth-secret');
	
		// auth
		$auth = "https://github.com/login/oauth/authorize";
		$acc = "https://github.com/login/oauth/access_token";

		// oauth_token
		if ( p('code') ) {
		
			$redi = b::url('login', false, array('r'=>p('r')));
		
			// code
			$code = p('code');
			
			// cmd
			$cmd = "curl -X POST \"$acc?client_id=$id&client_secret=$secret&code=$code&redirect_uri=".urlencode($redi).'"';
		
			// now ask for a token
			$resp = `$cmd`;
			
			// get it 
			list($result, $token) = explode("=", trim($resp));
		
			// what up
			if ( $result == 'error' ) { 
				die('something bad happend');
			}
			
			// now ask for the user
			$user = json_decode(`curl https://github.com/api/v2/json/user/show?access_token=$token`, true);
		
			// save me a token
			$s = new \dao\session();
			
			// set it 
			$s->ip = IP;
			$s->data = $user['user'];
			$s->token = $token;
			
			// save it 
			$s->save();
		
			// take them bac
			b::location( base64_decode(p('r')) );
		
		}
		else {
		
			// return
			$r = base64_encode(p('r', b::url('home')));
		
			// params
			$params = array(
				'client_id' => $id,
				'redirect_uri' => b::url('login', false, array('r'=>$r))
			);
		
			// send them
			b::location($auth, $params);
		
		}
		
	}

}


?>