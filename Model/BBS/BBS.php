<?php
/*
 * Created on 2006. 11. 22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class BBS{
 	var $BBSId;
 	var $TotalPostNumber;
 	var $BBSInfo;
 	function setBBS($res_DB,$arr_input=null){
  		if(!empty($arr_input)){
			include("Dao/MySQL/BBS/setBBSInfo.php");
			$this->result=$res_DB->DB_access($res_DB,$this->query["setFormInfo"]);
		}
		return($this->result);	 		
 	}
 	function getBBS($res_DB,$arr_input=null){
 		if(!empty($arr_input)){
 			if(!is_array($arr_input[bbs_seq])){
 				$arr_input[bbs_seq] = array($arr_input[bbs_seq]);
 			}
			include("Model/BBS/SQL/MySQL/BBS/getBBSInfo.php");
			$this->result=$res_DB->DB_access($res_DB,$this->query["getBBSInfo"]);
		}
		return($this->result); 		
 	}
 	function updateBBS($res_DB,$arr_input=null){
  		if(!empty($arr_input)){
			include("Dao/MySQL/BBS/updateBBSInfo.php");
			$this->result=$res_DB->DB_access($res_DB,$this->query["updateBBSInfo"]);
		}
		return($this->result);	  		
 	}
 	function deleteBBS($res_DB,$arr_input=null){
   		if(!empty($arr_input)){
   		 	if(!is_array($arr_input[bbs_seq])){
 				$arr_input[bbs_seq] = array($arr_input[bbs_seq]);
 			}   			
			include("Dao/MySQL/BBS/deleteBBSInfo.php");
			$this->result=$res_DB->DB_access($res_DB,$this->query["deleteBBSInfo"]);
		}
		return($this->result);	 		
 	}
 	function checkTable($res_DB,$table_name){
		if(trim($table_name)){
			include("Model/BBS/SQL/MySQL/BBS/checkTable.php");
			$arr_table = $res_DB->DB_access($res_DB,$this->query["checkTable"]);
			if(count($arr_table)>0){
				return($arr_table[0][Name]);
			}else{
				return(false);
			}	
 		}
 	}
 }
?>
