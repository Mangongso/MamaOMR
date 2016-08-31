<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$this->query["set_content"] = "insert into ".$this->contents_table_name." set ".
"post_seq=".$arr_input[seq].", ".
"bbs_seq='".$arr_input[bbs_seq]."', ".
"content='".quote_smart($arr_input['content'])."'";
?>
