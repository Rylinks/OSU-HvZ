<?php

class Player {
	public $data=array();
	public $result;
	protected $id;
	
	function __construct($id){
		$this->id = $id;
		$ret = mysql_query("SELECT * FROM ".$GLOBALS['table_u']." WHERE id='$id';");
		
		if ($ret == FALSE){
			throw new Exception('SQL problem: '.mysql_error());
		} else if (mysql_num_rows($ret)==0) {
			throw new Exception('ID not found.');
		} else if (mysql_num_rows($ret)>1) {
			throw new Exception('Duplicate ID (if you see this, fix the database now)');
		}
		$this->result = $ret;
		$this->data = mysql_fetch_array($ret);
	}
	
	function change($field, $value, $conditions = FALSE){ 
		if (!$conditions){
			print "SET $field = $value WHERE id='$this->id';";
			$ret = mysql_query("UPDATE ".$GLOBALS['table_u']." SET $field = $value WHERE id='$this->id';");
		} else {
                        print "SET $field = $value WHERE id='$this->id' AND $conditions";
			$ret = mysql_query("UPDATE ".$GLOBALS['table_u']." SET $field = $value WHERE id='$this->id' AND $conditions;");
		}
		
		if ($ret == FALSE){
			throw new Exception('SQL problem: '.mysql_error());
		}
		return $ret;
	}
	
	function update(){	 //changes $data to match the db
		if (!$ret = mysql_query("SELECT * FROM ".$GLOBALS['table_u']." WHERE id='$this->id';")){
			throw new Exception('SQL problem: '.mysql_error());
		}
		$this->result = $ret;
		$this->data = mysql_fetch_array($ret);
	}
		
	
	function kill($killer, $time){
		if ($killer instanceof Player){ $killer = $killer->data['id'];}
		$this->change('state','-1');
		$this->setFeedTime($time, $inconly=FALSE);
		$this->change('killed_by', "'$killer'");
		$this->change('killed', "TIMESTAMP '$time'");
		$this->update();
	}
	
	function starve(){
		$this->change('state', 0);
		$this->change('starved', 'feed + INTERVAL 2 day');
		$this->update();
	}
	
	function setFeedTime($time, $inconly = TRUE){
		if ($inconly){
			$condition = "timediff(TIMESTAMP '$time' + INTERVAL 2 day,now()) > timediff(feed + INTERVAL 2 day,now())";
		} else {
			$condition = FALSE;
		}
		$this->change("feed", "TIMESTAMP '$time'", $condition);
		$this->update();
	}
	
	function addFeedTime($hours, $max = "now()"){
		$this->change("feed", "feed + INTERVAL $hours hour");
		if (max == "now()"){
			$this->change("feed", "now()", "now() < feed");
		} else {
			$this->change("feed", "TIMESTAMP '$max'", "TIMESTAMP '$max' < feed");
		}
		$this->update();
	}
	
	function nom($kill_time, $is_share) {
		if ($is_share){
			$this->addFeedTime(24, $kill_time);
		} else {
			$this->setFeedTime($kill_time); //this will be updated to change a seperate starve field
			$k=$this->data['kills'];
			$k=$k+1;
			$this->change('kills', $k);
			$this->update();
		}
	}

	function receiveShare($kill_time){ //this exists for future email or starve field support
		$this->addFeedTime(24, $kill_time);
	}
	
	function __destruct(){
		mysql_free_result($this->result);
	}
}
?>
