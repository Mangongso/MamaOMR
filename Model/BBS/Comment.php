<?php
/**
 * 코멘트 등록, 수정, 삭제, 조회 
 *
 * @package      	BBS/PostComment
 * @property		var string $comment_table_name : 코멘트 테이블명
 * @category     	Comment
 */
 class PostComment{
	var $comment_table_name = "post_comment";
	
	/**
	 * 생성자
	 *
	 * @param string $table_name 테이블명
	 * @return null
	 */
	function __construct($table_name=null){
		if(trim($table_name)){
			$this->comment_table_name = $table_name;
		}
	}
	function __destruct(){}
	
	/**
	 * 코멘트 저장
	 *
	 * @param resource $res_DB 리소스 형태의 DB커넥션
	 * @param array $arr_input 배열 형태의 코멘트 저장 데이터
	 *
	 * @return boolean 코멘트 저장 성공 여부. (false 또는 true)
	 */
 	function setPostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			include("Model/BBS/SQL/MySQL/Comment/setPostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["set_post_comment"]);
 		return($this->result); 		  		 		 		
 	}
 	
 	/**
 	 * 코멘트 수정
 	 *
 	 * @param resource $res_DB 리소스 형태의 DB커넥션
 	 * @param array $arr_input 배열 형태의 코멘트 저장 데이터
 	 *
 	 * @return boolean 코멘트 수정 성공 여부. (false 또는 true)
 	 */
  	function updatePostComment($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			include("Model/BBS/SQL/MySQL/Comment/updatePostComment.php");
 		}
 		$this->result = $res_DB->DB_access($res_DB,$this->query["set_post_comment"]);
 		return($this->result); 		  		 		 		
 	} 	
 	
 	/**
 	 * 코멘트 저장
 	 *
 	 * @param resource $res_DB 리소스 형태의 DB커넥션
 	 * @param array $arr_input 배열 형태의 검색 데이터
 	 *
 	 * @return array  post_comment table 참조
 	 */
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
 	
 	/**
 	 * 코멘트 삭제
 	 *
 	 * @param resource $res_DB 리소스 형태의 DB커넥션
 	 * @param array $arr_input 배열 형태의 코멘트 시컨즈
 	 *
 	 * @return boolean 코멘트 삭제 성공 여부. (false 또는 true)
 	 */
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
