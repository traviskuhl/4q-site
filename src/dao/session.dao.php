<?php

namespace Dao;

class session extends Mongo {

	protected $_useAddedTimestamp = true;
	protected $_useModifiedTimestamp = true;

	protected function getStruct() {
		return array(
			'id' => array('type'=>'uuid'),		
			'data' => array(),
			'token' => array(),
			'ip' => array('default' => IP),
			'expires' => array()
		);
	}

	public function get($by, $val) {
	
		if ( $by == 'id' ) { $by = '_id'; }
	
		// lets get it 
		$q = array( $by => $val );	
	
		// do ti 
		$row = $this->row('sessions', $q);
	
			// no row
			if ( !$row ) { return false; }
		
		$this->set($row);		
	
	}

	public function save() {
		
		// data
		$data = $this->normalize();

		// id
		$id = $data['_id'];
		
		// unset
		unset($data['_id']);		
		
		// save it 
		try {
			$r = $this->update('sessions', array('_id' => $id), array('$set' => $data), array('upsert'=>true, 'safe'=>true));		
		}
		catch (MongoCursorException $e) {
			return false;
		}
		
		// set some variables
		$vars = array(
			'id' => $id,
			'ip' => $data['ip'],
			'exp' => $data['expires'],
			'ts' => \b::utctime()
		);
		
		// set our cookie
		\b::setCookie('$site/cookieUserSession', $vars);
	
		// save id 
		$this->id = $id;	
	
	}

	public function delete() {	
		
		// set our cookie
		\b::deleteCookie('$site/cookieUserSession');	
	
		// remove me
		parent::delete("sessions", array('_id'=>$this->id));
		
	}

}


?>