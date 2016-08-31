<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$this->query["set_content"] = "update ".$this->contents_table_name." set ".
"post_seq=".$value[seq].", ".
"bbs_seq='".$value[bbs_seq]."', ".
"contents='".quote_smart($value[content])."'".
" where bbs_seq='".$arr_input[bbs_seq]."' and post_seq=".$arr_input[seq];
?>
