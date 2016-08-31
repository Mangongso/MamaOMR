<?
if(trim($table_name)){
	$this->query['checkTable'] = sprintf("SHOW TABLE STATUS LIKE '%s'",$table_name);
}
?>