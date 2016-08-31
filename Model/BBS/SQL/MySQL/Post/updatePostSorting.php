<?php
/*
 * Created on 2007. 01. 21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
if($arr_input[sort]==0){
	$this->query["update_post_sorting"] = "update ".$this->table_name." set".
	" sort=1,parent_post_id=$arr_input[parent_seq]".
	" where bbs_seq='".$arr_input[bbs_seq]."' and seq=".$arr_input[seq];	
}else{
	$this->query["update_post_sorting"] = "update ".$this->table_name." set".
	" sort=sort+1".
	" where bbs_seq=$arr_input[bbs_seq] and parent_post_id=$arr_input[parent_seq] and seq!=$arr_input[seq] and sort>=$arr_input[sort]";
}	
// echo $this->query["update_post_sorting"] ;
?>
