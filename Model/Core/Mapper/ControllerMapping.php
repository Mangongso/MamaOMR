<?php
/**
 * 컨트롤러 매핑
 *
 * @subpackage 	Core/XMLmanager/XMLmanager
 * @property		private resource 	$obj_XML : xml 객체
 * @property		private array 		$arr_mapping_info : 매핑 정보
 * @category     	MailHandler
 *
 */
require_once("Model/Core/XMLmanager/XMLmanager.php");
class ControllerMapping{
	var $obj_XML;
	var $arr_mapping_info;
	/**
	 * 생성자
	 *
	 * @param string $str_mapping_file : 매핑 파일명
	 * @return null
	 */
	function __construct($str_mapping_file = "controller_mapping.xml"){
		$this->obj_XML = new XMLmanager(CONTROLLER_NAME."/_Lib/".$str_mapping_file); 	
	}
	
	/**
	 * 매핑을 가져온다.
	 *
	 * @param string $str_view_id : viewID
	 *
	 * @return array	 $result  : 메핑정보를 반환한다.
	 */
	function getMapping($str_view_id){
		$query = "/web-app/controller-mapping/controller[@viewID='".$str_view_id."']";
		$result = $this->arr_mapping_info = $this->obj_XML->XML_access($this->obj_XML,$query);
		return($result);
	}
	
	/**
	 * 컨트롤러 연결 
	 *
	 * @param string 	$str_view_id : viewID
	 * @return array 	$arr_output : 가져온 컨트롤러 정보를 반환한다. 
	 */
	function connect($str_view_id){
		$arr_output = array();
		$arr_controller_mapping = $this->getMapping($str_view_id);		
		$str_script_name = substr(trim(getenv("SCRIPT_NAME")),1);
		$str_presentation_name = trim($arr_controller_mapping['controller'][0]['presentation-name']);
		if(count($arr_controller_mapping['controller'])>0){
			if(trim($arr_controller_mapping['controller'][0]['controller-name'])){
				$arr_controller_mapping['controller'][0]['parameter'];
				$arr_controller_mapping['controller'][0]['parameter'] = str_replace("|","&",$arr_controller_mapping['controller'][0]['parameter']);
				parse_str(trim($arr_controller_mapping['controller'][0]['parameter']));				
				require_once(CONTROLLER_NAME."/".trim($arr_controller_mapping['controller'][0]['controller-name']));
			}			
			$arr_output['controller_info'] = $arr_controller_mapping['controller'][0];
			$arr_output['controller_info']['viewID'] = $str_view_id;
		}
		return($arr_output);
	}
}
?>