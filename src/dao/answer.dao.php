<?php

namespace Dao;

class answer extends Mongo {

	// we want to track added & modified
	protected $_useAddedTimestamp = true;
	protected $_useModifiedTimestamp = true;

	protected function getStruct() {
		return array(
			'id' => array( 'type' => 'uuid' ),
			'name' => array(),
			'job' => array(),
			'loc' => array(),
			'by' => array(),
			'uid' => array(),
			'commit' => array(),			
			'text' => array(),
			'tags' => array(),
			'featured' => array( 'default' => 0 ),
			'll' => array()
		);		
	}

	public function get($by, $val) {

		// id
		if ( $by == 'id' ) { $by = '_id'; }		

		// lets get it 
		$q = array( $by => $val );
		
		// cid
		$cid = "answer.{$val}";
		
		// if by = id
		if ( $by != '_id' OR ($row = $this->cache->get($cid)) == false ) {

			// do ti 
			$row = $this->row('answers', $q);
			
			// save
			$this->cache->set($cid, $row);
		
		}
	

			// no row
			if ( !$row ) { return false; }

		// set it bitch
		$this->set($row);

	}

	public function set($row) {

		// set as a parent
		parent::set($row);
		
		$this->_adjunct['pic'] = "https://secure.gravatar.com/avatar/".md5($this->by->email)."?s=100&d=https://d3nwyuy0nl342s.cloudfront.net%2Fimages%2Fgravatars%2Fgravatar-140.png";

	}

	public function save() {
	
		// data
		$data = $this->normalize();
		
		// no
		$id = $data['_id']; unset($data['_id']);		
		
		// save it 
		try {
			$this->update('answers', array("_id" => $id), array('$set' => $data), array('upsert'=>true));			
		}
		catch (MongoCursorException $e) {
			return false;
		}
		
		// save id 
		$this->id = $id;
		
			// delete the cache
			$this->cache->delete("answer.{$id}");
	
		// return id
		return $id;
	
	}

}

?>