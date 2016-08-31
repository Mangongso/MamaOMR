<?php
/*
 * Created on 2006. 11. 22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once("Model/BBS/BBS.php");
require_once("Model/BBS/Comment.php");
require_once("Model/Member/Member.php");
require_once("Model/Core/DataManager/FileHandler.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Core/Util/Paging.php");

class Post extends BBS {
	var $CurrentPost = array();
	var $PrevPost = array();	
	var $NextPost = array();
	var $CurrentPostReply = array();
	var $CurrentPostComment = array();
	var $CurrentPostPaging = array();
	var $arrPostInsertId;
	var $table_name = "post";
	var $contents_table_name = "post_contents";
	var $comment_table_name = "post_comment";
	function Post($res_DB = null,$bbs_seq = null){
		if(!is_null($bbs_seq)){
			$table_name = "post_".$bbs_seq;
			$contents_table_name = "post_contents_".$bbs_seq;
			$comment_table_name = "post_comment_".$bbs_seq;
			if($this->checkTable($res_DB,$table_name)){
				$this->table_name = $this->checkTable($res_DB,$table_name);
			}
			if($this->checkTable($res_DB,$contents_table_name)){
				$this->contents_table_name = $this->checkTable($res_DB,$contents_table_name);
			}
			if($this->checkTable($res_DB,$comment_table_name)){
				$this->comment_table_name = $this->checkTable($res_DB,$comment_table_name);
			}
		}
	}
	function readCountIncrease($res_DB,$arr_input=null){
		if(!is_array($arr_input[seq])){$arr_input[seq]=array($arr_input[seq]);}
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
		}
		$return = $res_DB->DB_access($res_DB,$this->query["read_count_increase"]);	
		return($return);	
	}
	function getNotReadPostCount($res_DB,$intBBSSeq){
		$strQuery = sprintf("select count(*) as cnt from post where bbs_seq=%d and read_flg=0 and del_flg='0'",$intBBSSeq);
		$arrReturn = $res_DB->DB_access($res_DB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}
	function setReadFlg($res_DB,$intBBSSeq,$intPostSeq){
		echo $strQuery = sprintf("update post set read_flg=1 where bbs_seq=%d and seq=%d",$intBBSSeq,$intPostSeq);
		$boolReturn = $res_DB->DB_access($res_DB,$strQuery);
		return($boolReturn);		
	}
	function setPost($res_DB,$arr_input=null){
		if($arr_input[parent_seq]){
			$arr_input_for_parent_post = array(
 			'bbs_seq'=>$arr_input[bbs_seq],
 			'seq'=>array($arr_input[parent_seq]));
 			$this->getPost($res_DB,$arr_input_for_parent_post);
 			$selected_post_info = $this->CurrentPost;
 			$arr_input[sort] = $selected_post_info[0][sort];
 			$arr_input[depth] = $selected_post_info[0][depth]+1;
 			$arr_input[parent_seq] = $selected_post_info[0][parent_post_id];
		}else{
			$arr_input[sort] = 0;
			$arr_input[depth] = 0;
		}

		if(!empty($arr_input)){
			$this->getQuery(__function__."1",$arr_input);
			$this->result = $res_DB->DB_access($res_DB,$this->query["set_post"]);
			global $globalMysqliConn;
			if($globalMysqliConn){
				$arr_input[seq] = mysqli_insert_id($globalMysqliConn);
			}else{
				$arr_input[seq] = mysql_insert_id();
			}
			if(!$arr_input[seq]){
				$arrPostInsertId = $res_DB->DB_access($res_DB,$this->query["get_post_insert_id"]);
				$arr_input[seq] = $arrPostInsertId[0]['post_insert_id'];
			}
		}
		if($arr_input[sort]>0){
			$arr_input_for_sorting = array(
 			'bbs_seq'=>$arr_input[bbs_seq],
 			'parent_seq'=>$selected_post_info[0][parent_post_id], 			
 			'seq'=>$selected_post_info[0][seq],	
 			'sort'=>$selected_post_info[0][sort] 			
 			);
		}else{
			$arr_input_for_sorting = array(
 			'bbs_seq'=>$arr_input[bbs_seq],
 			'parent_seq'=>$arr_input[seq], 			
 			'seq'=>$arr_input[seq],
 			'depth'=>0,
 			'sort'=>0
 			);
		}
		$this->insert_post_seq = $arr_input[seq];
		$this->getQuery(__function__."2",$arr_input_for_sorting);
		$this->result = $res_DB->DB_access($res_DB,$this->query["update_post_sorting"]);
		$arr_content_info = array(bbs_seq=>$arr_input[bbs_seq],seq=>$arr_input[seq],contents=>$arr_input[contents]);
		$this->setContent($res_DB,$arr_content_info);
		$this->attachFile($res_DB,$arr_input);
		return($this->result);
	}
	function setBettingSeqToPost($res_DB,$intBBSSeq,$intPostSeq,$arrBettingSeq){
		$strQuery = sprintf("delete from bbs_betting where bbs_seq=%d and post_seq=%d",$intBBSSeq,$intPostSeq);
		$boolReturn = $res_DB->DB_access($res_DB,$strQuery);
		$arrInsertValue = array();
		foreach($arrBettingSeq as $intKey=>$intBettingSeq){
			array_push($arrInsertValue,sprintf("(%d,%d,%d)",$intBBSSeq,$intPostSeq,$intBettingSeq));
		}
		$strQuery = "insert into bbs_betting (bbs_seq,post_seq,betting_seq) values ".join(",",$arrInsertValue);
		$boolReturn = $res_DB->DB_access($res_DB,$strQuery);
		return($boolReturn);
	}
	function getBettingSeqFromPost($res_DB,$intBBSSeq,$intPostSeq){
		$strQuery = sprintf("select * from bbs_betting where bbs_seq=%d and post_seq=%d",$intBBSSeq,$intPostSeq);
		$arrReturn = $res_DB->DB_access($res_DB,$strQuery);
		return($arrReturn);		
	}
	function getPostCount($res_DB,$arr_input=null){
		if(!empty($arr_input)){
			if(!is_array($arr_input[seq]) && $arr_input[seq]){
				$arr_input[seq] = array($arr_input[seq]);
			}			
			$this->getQuery(__function__,$arr_input);
		}
		$this->result = $res_DB->DB_access($res_DB,$this->query["get_post"]);
		return($this->result[0][count]);
	}
	function getPost($res_DB,$arr_input=null){
		$arr_input[seq] = (!is_array($arr_input[seq]) && $arr_input[seq])?array($arr_input[seq]):$arr_input[seq];	
		$this->BBSInfo = $this->getBBS($res_DB,array('bbs_seq'=>$arr_input['bbs_seq']));
		$this->BBSid = $arr_input[bbs_seq];
		if(!empty($arr_input[paging])){
		$obj_paging = new Paging();
		$arr_input[paging] = $this->CurrentPostPaging = 
			$obj_paging->getPaging(
				$this->getPostCount($res_DB,$arr_input),
				$arr_input[paging][page]?$arr_input[paging][page]:1,
				$arr_input[paging][result_number],
				$arr_input[paging][block_number],
				$arr_input[paging][param]
			);	
		}
		$this->getQuery(__function__,$arr_input);
		$this->CurrentPost = $res_DB->DB_access($res_DB,$this->query["get_post"]);
		$int_result_no = $this->CurrentPostPaging[total_result_num] - $this->CurrentPostPaging[limit_start];
		foreach($this->CurrentPost as $key=>$value){
			if($arr_input[short_subject_limit]){
				if(strlen($value[subject])>$arr_input[short_subject_limit]){
					$this->CurrentPost[$key][subject_short] = iconv_substr($value[subject],0,$arr_input[short_subject_limit],"utf-8")."...";
				}else{
					$this->CurrentPost[$key][subject_short] = $this->CurrentPost[$key][subject];
				}		
			}	
			if(empty($arr_input[paging])){
				$obj_post_comment = new PostComment($this->comment_table_name);
				$this->CurrentPost[$key][contents] = $this->getContent($res_DB,array('bbs_seq'=>$value[bbs_seq],'seq'=>$value['seq']));
				$this->CurrentPost[$key][comments] = $obj_post_comment->getPostComment($res_DB,array('bbs_seq'=>$value[bbs_seq],'post_seq'=>$value['seq']));
				$this->CurrentPost[$key][files] = $this->getFile($res_DB,array('bbs_seq'=>$arr_input[bbs_seq],'seq'=>$value['seq']));
			}else{						
				$obj_post_comment = new PostComment($this->comment_table_name);
				$this->CurrentPost[$key][post_no] = $int_result_no;	
				$arr_input[param][bbs_seq]=$value[bbs_seq];
				$this->CurrentPost[$key][comments] = $obj_post_comment->getPostComment($res_DB,array('bbs_seq'=>$arr_input[bbs_seq],'post_seq'=>$value['seq']));
				$this->CurrentPost[$key][link_param] = $arr_input[param];	
				$this->CurrentPost[$key][link_param][seq] = $value[seq];
				$int_result_no--;
				if($arr_input[bbs_seq] == "products"){
					$arr_input_for_contents = array('bbs_seq'=>$arr_input[bbs_seq],seq=>array($value[seq]));
					$this->CurrentPost[$key][contents] = $this->getContent($res_DB,$arr_input_for_contents);
					$this->CurrentPost[$key][files] = $this->getFile($res_DB,$arr_input_for_contents);
				}
			}
			$this->CurrentPost[$key]['bbs_info'] = $this->getBBS($res_DB,array('bbs_seq'=>$value['bbs_seq']));
		}
	}
	function updatePost($res_DB,$arr_input=null){
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
		}
		$this->result = $res_DB->DB_access($res_DB,$this->query["update_post"]);
		$this->deleteContent($res_DB,$arr_input);
		$arr_content_info = array(bbs_seq=>$arr_input[bbs_seq],seq=>$arr_input[seq],contents=>$arr_input[contents]);
		$this->result = $this->setContent($res_DB,$arr_content_info);
		if($arr_input[delete_file]){		
		$this->result = $this->deleteFile($res_DB,$arr_input);
		}
		if($arr_input[files]){	
		$this->result = $this->attachFile($res_DB,$arr_input);
		}
		return($this->result);
	}
	function deletePost($res_DB,$arr_input=null,$delete_flg=false){
		$obj_member = new Member();
		$obj_DataHandler = new DataHandler();		
		if(!is_array($arr_input[seq])){
			$arr_input[seq] = array($arr_input[seq]);
		}
		if($obj_DataHandler->getUserSession("member_id")!="admin"){
			$arr_input[seq] = $this->authPost($res_DB,$arr_input);
		}
		if(count($arr_input[seq])>0){
			if(!empty($arr_input)){
				$this->getQuery(__function__,$arr_input);
			}
			if(count($this->query)>0){
				foreach($this->query as $key=>$value){
					if($value){
						$this->result=$res_DB->DB_access($res_DB,$value);
					}
				}
			}
		}else{
			$this->result = false;
		}
		return($this->result);	
	}
	function setContent($res_DB,$arr_input=null){
		foreach($arr_input[contents] as $key=>$value){
			if(trim($value)){
				$arr_input[content] = $value;
				$this->getQuery(__function__,$arr_input);
				$this->result = $res_DB->DB_access($res_DB,$this->query["set_content"]);
			}
		}
		return($this->result);
	}
	function getContent($res_DB,$arr_input=null){
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
		}
		$str_contents = $res_DB->DB_access($res_DB,$this->query["get_contents"]);
		if(!$arr_input[content_limit]){$arr_input[content_limit]=255;}
		foreach($str_contents as $key=>$value){
			$str_contents[$key][content_summary] = iconv_substr(strip_tags($value[content]),0,$arr_input[content_limit],'utf-8')."...";			
		}
		return($str_contents);
	}
	function updateContent($res_DB,$arr_input=null){
		foreach($arr_input as $key=>$value){
			if(!empty($arr_input)){
				$this->getQuery(__function__,$arr_input);
			}
			$this->result = $res_DB->DB_access($res_DB,$this->query["update_contents"]);
		}
		return($this->result);
	}
	function deleteContent($res_DB,$arr_input=null){
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
		}
		$this->result = $res_DB->DB_access($res_DB,$this->query["delete_content"]);
		return($this->result);
	}
	function getFile($res_DB,$arr_input=null){
		$this->getQuery(__function__,$arr_input);
		$arr_return = $res_DB->DB_access($res_DB,$this->query["getFile"]);
		return($arr_return);
	}
	function attachFile($res_DB,$arr_input=null){
		if(count($arr_input[files])>0){
			$obj_FileHandler = new FileHandler();
			$int_file_count = 0;
			foreach($arr_input[files] as $key=>$arr_file_info){
				if($arr_file_info[error]==0){
					$int_file_count++;
					$arr_file_info[save_name] = $arr_input[seq]."_".$int_file_count;
					if(eregi("^windows",getenv("OS"))){
						$arr_file_info[save_dir] = sprintf("%s\%d",POST_FILE_UPLOAD_DIR,$arr_input[bbs_seq]);
					}else{
						$arr_file_info[save_dir] =  sprintf("%s/%d",POST_FILE_UPLOAD_DIR,$arr_input[bbs_seq]);
					}
					$this->getQuery(__function__,$arr_input,$arr_file_info);
					if(!$this->result = $res_DB->DB_access($res_DB,$this->query["set_post_file"])){
						break;
					}
					$arr_input[files][$key] = $arr_file_info;
				}
			}
			if(count($arr_input[files])>0){
			$obj_FileHandler->FileUpload($arr_input[files]);
			}
		}
		return($this->result);
	}
	function deleteFile($res_DB,$arr_input=null){
		$int_file_count = 0;
		$arr_files = array();
		if(!is_array($arr_input[delete_file])){$arr_input[delete_file] = array($arr_input[delete_file]);}
		foreach($arr_input[delete_file] as $key=>$value){
			$int_file_count ++;
			if(eregi("^windows",getenv("OS"))){
				array_push($arr_files,sprintf("%s\%d\%s",POST_FILE_UPLOAD_DIR,$arr_input[bbs_seq],$arr_input[seq]."_".$int_file_count));
			}else{
				array_push($arr_files,sprintf("%s/%d/%s",POST_FILE_UPLOAD_DIR,$arr_input[bbs_seq],$arr_input[seq]."_".$int_file_count)); 
			}
		}
		$this->getQuery(__function__,$arr_input);
		$this->result = $res_DB->DB_access($res_DB,$this->query["delete_post_file"]);	
		$obj_FileHandler = new FileHandler();
		$this->result = $obj_FileHandler->FileDelete($arr_files);	
		return($this->result);	
	}
	function authPost($res_DB,$arr_input=null){
		$obj_member = new Member();
		$obj_DataHandler = new DataHandler();
		$arr_member_input = array(
		check_type=>1,
		member_id=>$obj_DataHandler->getUserCookie("member_id"),
		auth_key=>$obj_DataHandler->getUserCookie("auth_key")
		);
		$arr_member_info = $obj_member->memberLoginChk($res_DB,$arr_member_input);
		if($arr_member_info[status]){
			$str_member_id = $arr_member_info[member_id];
			$int_member_level = $arr_member_info[member_level];
		}
		$arr_input = array(
		seq=>$arr_input[seq],
		bbs_seq=>$arr_input[bbs_seq],
		member_id=>$str_member_id,
		member_level=>$int_member_level,
		password=>$arr_input[password]		
		);
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
		}
		$arr_result = $res_DB->DB_access($res_DB,$this->query["auth_post"]);
		$arr_return = array();
		foreach($arr_result as $value){
			array_push($arr_return,$value[seq]);
		}
		return($arr_return);
	}
	function getNextPost($res_DB,$arr_input){
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
			$arr_post = $res_DB->DB_access($res_DB,$this->query["get_next_post"]);
			$this->NextPost  = $arr_post[0];
//			if($arr_post[0][seq]){
//				$arr_input = array(bbs_seq=>$arr_input[bbs_seq],seq=>$arr_post[0][seq],parent_seq=>$arr_input[parent_seq],param=>$arr_input[param]);
//				return $this->NextPost = $this->getPost($res_DB,$arr_input);
//			}else{
//				return $this->NextPost = null;
//			}
		}		
	}
	function getPrevPost($res_DB,$arr_input){
		if(!empty($arr_input)){
			$this->getQuery(__function__,$arr_input);
			$arr_post = $res_DB->DB_access($res_DB,$this->query["get_prev_post"]);
			$this->PrevPost  = $arr_post[0];
				
//			if($arr_post[0][seq]){
//				$arr_input = array(bbs_seq=>$arr_input[bbs_seq],seq=>$arr_post[0][seq],parent_seq=>$arr_input[parent_seq],param=>$arr_input[param]);
//				return $this->PrevPost = $this->getPost($res_DB,$arr_input);
//			}else{
//				return $this->PrevPost = null;			
//			}
		}else{
			return $this->PrevPost = $arr_error = array(error_code=>"E201");	
		}
	}
	function getQuery($queryCode,$arr_input=null,$arr_file_info=null){
		switch($queryCode){
			case("readCountIncrease"):
				include("Model/BBS/SQL/MySQL/Post/readCountIncrease.php");
			break;
			case("setPost1"):
				include("Model/BBS/SQL/MySQL/Post/setPost.php");
			break;	
			case("setPost2"):
				include("Model/BBS/SQL/MySQL/Post/updatePostSorting.php");
			break;	
			case("getPostCount"):
				include("Model/BBS/SQL/MySQL/Post/getPostCount.php");
			break;		
			case("getPost"):
				include("Model/BBS/SQL/MySQL/Post/getPost.php");
			break;
			case("updatePost"):
				include("Model/BBS/SQL/MySQL/Post/updatePost.php");
			break;
			case("deletePost"):
				include("Model/BBS/SQL/MySQL/Post/deletePost.php");
			break;
			case("setContent"):
				include("Model/BBS/SQL/MySQL/Contents/setContents.php");
			break;
			case("getContent"):
				include("Model/BBS/SQL/MySQL/Contents/getContents.php");
			break;	
			case("updateContent"):
				include("Model/BBS/SQL/MySQL/Contents/updateContents.php");
			break;	
			case("deleteContent"):
				include("Model/BBS/SQL/MySQL/Contents/deleteContents.php");	
			break;	
			case("attachFile"):
				include("Model/BBS/SQL/MySQL/Files/setPostFile.php");
			break;
			case("deleteFile"):
				include("Model/BBS/SQL/MySQL/Files/deletePostFile.php");
			break;	
			case("authPost"):
				include("Model/BBS/SQL/MySQL/Post/authPost.php");
			break;	
			case("getNextPost"):
				include("Model/BBS/SQL/MySQL/Post/getNextPost.php");
			break;
			case("getPrevPost"):
				include("Model/BBS/SQL/MySQL/Post/getPrevPost.php");
			break;
			case("getFile"):
				include("Model/BBS/SQL/MySQL/Files/getFile.php");
			break;
		}
	}
}
?>