<?php
class Utility{
	private $stat = ["","Active","Inactive","Testing","Resolved","Closed"];
	private $allUser;

	function resolveStatus($sid){
		return $this->stat[$sid];
	}

	function setAllUser($db){
		$this->allUser = [""];
		$users = $db->getAllUser();
		while($row = $users->fetchArray(SQLITE3_ASSOC) ) array_push($this->allUser, $row['name']);
	}

	function resolveUser($uid){
		return $this->allUser[$uid];
	}

	function getStatusOptions(){
		$res = "";
		for($i=1;$i<count($this->stat);$i++){
			$res .= "<option value='".$i."'>".$this->stat[$i]."</option>";
		}
		return $res;
	}

	function getStatusList(){
		return $this->stat;
	}
}

?>
