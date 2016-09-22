<?
if(phpversion()<7){
	require_once('Model/Core/DBmanager/MySQLmanager.php');
	class DB_manager extends DB_MySQL_manager{
	}
}else{
	function mysql_insert_id($resDB){
		return($resDB->lastInsertId());
	}	
	require_once('Model/Core/DBmanager/PDOmanager.php');
	class DB_manager extends PDOmanager{
	}	
}
?>
