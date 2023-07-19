<?php
class DB extends SQLite3 {
	// reff: https://www.tutorialspoint.com/sqlite/sqlite_php.htm
	function __construct() {
		$this->open('./assets/db/db.db');
	}

	function getUserByUsername($un){
		$sql = "SELECT * FROM users WHERE username='".$un."' LIMIT 1;";
		$ret = $this->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
		  return $row;
		}
	}

	function getProjectById($pid){
		$sql = "SELECT * FROM prj_details WHERE id=".$pid." LIMIT 1;";
		$ret = $this->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
		  return $row;
		}
	}

	function getTaskListById($tlid){
		$sql = "SELECT * FROM prj_task_list WHERE id=".$tlid." LIMIT 1;";
		$ret = $this->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
		  return $row;
		}
	}

	function getTaskList($prj_id,$status,$page,$limit){
		$offset = ($limit * $page) - $limit;
		$sql = "SELECT * FROM prj_task_list WHERE prj_id=".$prj_id." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset.";";
		return $this->query($sql);
	}

	function getAllUser(){
		$sql = "SELECT id,name FROM users;";
		return $this->query($sql);
	}

	function addNewTaskList($prj_id,$user_id,$name,$date,$status){
		$sql = "INSERT INTO prj_task_list (id, prj_id, user_id, name, date, status) VALUES (NULL, ".$prj_id.", ".$user_id.", '".$name."', '".$date."', ".$status.");";
		$ret = $this->exec($sql);
	}

	function getAllTaskWithEvents($tl_id){
		$sql = 'SELECT * FROM prj_task
				INNER JOIN prj_task_list_event ON prj_task_list_event.task_id=prj_task.id
				WHERE prj_task.tl_id='.$tl_id.'
				ORDER BY prj_task.id DESC,prj_task_list_event.id DESC;
				';
		return $this->query($sql);
	}

	function updateTaskListStatus($tl_id,$status){
		$sql = "UPDATE prj_task_list set status = ".$status." where id=".$tl_id.";";
		$ret = $this->exec($sql);
		if(!$ret) {
			// echo $db->lastErrorMsg();
			return false;
		} else {
			// echo $db->changes(), " Record updated successfully\n";
			return true;
		}
	}

	function updateTaskStatus($tid,$status,$uname,$stat_def){
		// Update status
		$sql = "UPDATE prj_task set status = ".$status." where id=".$tid.";";
		$ret = $this->exec($sql);
		// Add event
		$sql = "INSERT INTO prj_task_list_event (id, task_id, event_type, comment, date) VALUES (NULL, ".$tid.", 2, '".$uname." Changed status to ".$stat_def[$status].".', '".date("Y-m-d")."');";
		$ret = $this->exec($sql);
	}

	function addTaskInTaskList($tl_id,$uid,$task_name,$status,$uname){
		// Add task
		$sql = "INSERT INTO prj_task (id, tl_id, user_id, name, status) VALUES (NULL, ".$tl_id.", ".$uid.", '".$task_name."', '".$status."');";
		$ret = $this->exec($sql);
		// Get the task
		$sql = "SELECT * FROM prj_task WHERE tl_id=".$tl_id." ORDER BY id DESC LIMIT 1;";
		$ret = $this->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
		  break;
		}
		// Add event
		$sql = "INSERT INTO prj_task_list_event (id, task_id, event_type, comment, date) VALUES (NULL, ".$row['id'].", 2, '".$uname." Created Task this task.', '".date("Y-m-d")."');";
		$ret = $this->exec($sql);
	}

	function addCommentInTask($task_id,$comment,$uname){
		// Add event
		$sql = "INSERT INTO prj_task_list_event (id, task_id, event_type, comment, date) VALUES (NULL, ".$task_id.", 1, '".$uname.": ".$comment."', '".date("Y-m-d")."');";
		$ret = $this->exec($sql);
	}
}

?>
