<?php

namespace Dao;

class comments extends Mongo {

	protected function getStruct() {
		return array(
			'id' => array( 'type' => 'uuid'),
			'asset_type' => array(),
			'asset_id' => array(),
			'user' => array( ),
			'text' => array(),
			'ts' => array( 'type' => 'timestamp'),
			'asset' => array()
		);	
	}

	public function get($query=array(), $args=array()) {
		
		// sort
		if ( !p('sort', false, $args) ) {
			$args['sort'] = array('ts' => 1);
		}
	
		// query
		$sth = $this->query('comments', $query, $args);
			
		// give it back
		foreach ( $sth as $e ) {
			$this->_items[$e['_id']] = new commentitem('set', $e);
		}
	
		$this->setPager(count($this->_items), 1, 1);
	
	}
	
	public function save() {
	
		$new = !$this->id;
	
		// data
		$data = $this->normalize();
		
		// no
		$id = $data['_id']; unset($data['_id'], $data['asset']);
		
		// save it 
		try {
			$this->update('comments', array("_id" => $id), array('$set' => $data), array('upsert'=>true));			
		}
		catch (MongoCursorException $e) {
			return false;
		}
		
		$this->id = $id;				
				
		// fire 
		if ( $new ) {
			// fire off a req event
			$this->event->fire('notify',array(
				'type' => 'comment',
				'obj' => $this,
				'id' => $id
			));		
		}		
		
		return true;
	
	}	

}

class commentitem extends comments {

	public function get() {}

}

?>