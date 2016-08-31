<?php
/*
 * Created on 2006. 12. 6
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $str_where = "";
 if($arr_input[seq]){$str_where = " where bbs_seq='".$arr_input[bbs_seq]."' and post_seq in (".$arr_input[seq].")";}
 $this->query["get_contents"] = "select * from ".$this->contents_table_name." ".$str_where;
?>