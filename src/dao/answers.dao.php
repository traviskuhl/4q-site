<?php

namespace Dao;

class answers extends Mongo {
	
	public function get($q, $a=array()) {

		// fields
		$a['fields'] = array('_id' => 1);

		// do ti 
		$sth = $this->query('answers', $q, $a);

		// items
		foreach ($sth as $row) {
			$this->_items[] = new \dao\answer('get',array('id',$row['_id']));
		}

		$this->setPager(count($this->_items), 1, 1);

	}

}

?>