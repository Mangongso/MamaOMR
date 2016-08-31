<?php
/* 
 * DB Connection Information 
 * SERVICE_MODE in the View/_connector/yellow.501.php
 * SERVICE_MODE : STAGE or PRODUCTION or Other(DEV)
 */
$DB_info = array();
switch(SERVICE_MODE){
	case("STAGE"):
		$DB_info['MAIN_SERVER'] = array('host'=>'localhost','user'=>'root','pass'=>'nothing','database'=>'mamaomr_stage');
	break;
	case("PRODUCTION"):
		$DB_info['MAIN_SERVER'] = array('host'=>'localhost','user'=>'root','pass'=>'fkaofhrmdls','database'=>'mamaomr');
	break;
	default:
		$DB_info['MAIN_SERVER'] = array('host'=>'localhost','user'=>'root','pass'=>'nothing','database'=>'mamaomr_dev');
	break;
}

/*
 * Set Social key
 */
$SNS_key = array();
switch(SERVICE_MODE){
	case("STAGE"):
		$SNS_key['facebook'] = array('host'=>'localhost','user'=>'root','pass'=>'nothing','database'=>'mamaomr_stage');
		break;
	case("PRODUCTION"):
		break;
	default:
		break;
}	
?>