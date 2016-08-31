<?php
/*
 * Created on 2006. 11. 22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class PostComment{
	var $comment_table_name = "post_comment";
	function __construct($table_name=null){
		if(trim($table_name)){
			$this->comment_table_name = $table_name;
		}
	}
	function __destruct(){}
 	function setPostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			include("Model/BBS/SQL/MySQL/Comment/setPostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["set_post_comment"]);
 		return($this->result); 		  		 		 		
 	}
  	function updatePostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			include("Model/BBS/SQL/MySQL/Comment/updatePostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["set_post_comment"]);
 		return($this->result); 		  		 		 		
 	} 	
 	function getPostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			if(!is_array($arr_input[post_seq])){
 				$arr_input[post_seq] = array($arr_input[post_seq]);
 			}
 			include("Model/BBS/SQL/MySQL/Comment/getPostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["get_post_comment"]);
 		return($this->result);  		
 	}
 	function deletePostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 		 	if(!is_array($arr_input['post_seq'])){
 				$arr_input['post_seq'] = array($arr_input['post_seq']);
 			} 			
 			include("Model/BBS/SQL/MySQL/Comment/deletePostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["delete_post_comment"]);
 		return($this->result);  		
 	}
 }
?>
