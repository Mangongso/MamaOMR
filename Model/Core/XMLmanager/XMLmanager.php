<?php
/**
 * XMLmanager
 *
 * @property		string $dom_support : 다큐먼트 서포트
 * @property		resource $res_XML : xml 객체
 * @property		array $query : 쿼리
 * @category     	XMLmanager
 */
class XMLmanager{
	var $dom_support;
	var $res_XML;
	var $query = array();	
	/**
	 * 생성자
	 *
	 * @param 	string 		$str_XML_file xml파일명
	 * @return 	null
	 */
	function __construct($str_XML_file){
		$this->dom_support = version_compare(phpversion(),"5.0","<");
		$this->res_XML = $this->XML_conn($str_XML_file);
	}
	
	/**
	 * xML 연결
	 *
	 * @param 	string 		$str_XML_file xml파일명
	 * @return resource	$obj_xsdpath	  : DOMXPath 객체를 반환
	 */
	function XML_conn($str_XML_file){
		if(file_exists(ini_get('include_path')."/".$str_XML_file)){
			$str_XML_file = ini_get('include_path')."/".$str_XML_file;
		}
		if($this->dom_support){	// under 5.0
			$obj_xml = domxml_open_file($str_XML_file);
			$obj_xml->preserveWhiteSpace=false;
			$obj_xsdpath = $obj_xml->xpath_new_context();
		}else{
			$obj_xml= new DOMDocument();
			$obj_xml->preserveWhiteSpace=false;
			@$obj_xml->load($str_XML_file);
			$obj_xsdpath = new DOMXPath($obj_xml); 
		}
		return($obj_xsdpath);	
	}
	
	/**
	 * xML 접근
	 *
	 * @param resource	 $obj_XML 	:	xml 객체
	 * @param string		$expresion 	:	표현
	 * @return array	$result 	:  xml 객체를 fetch 한 결과 array 를 반환
	 */
	function XML_access($obj_XML,$expresion){
		if($this->dom_support){	// under 5.0
			$result = xpath_eval($obj_XML->res_XML,$expresion);
		}else{
			$result = $obj_XML->res_XML->query($expresion);
		}
		$result = $this->ResultFetchArray($result);
		return($result);
	}
	
	/**
	 * ResultFetchArray 
	 *
	 * @param resource $res_XML 	
	 * @return array	$arr_result	: xml fetch결과를 반환
	 */
	function ResultFetchArray($res_XML){
		$arr_result = array();
		if($this->dom_support){
			$int_result_num = count($res_XML->nodeset);
			
		}else{
			$int_result_num = $res_XML->length;
		}
		for($i=0;$i<$int_result_num;$i++){
			$obj_XML = $this->dom_support?$res_XML->nodeset[$i]:$res_XML->item($i);	
			$arr_result[$this->dom_support?$obj_XML->tagname:$obj_XML->nodeName][] = $this->XML2array($obj_XML);
		}
		return($arr_result);
	}
	
	/**
	 * XML2array
	 *
	 * @param resource $res_XML 	
	 * @return array	$arr_result	: xml fetch결과를 반환
	 */
	function XML2array($obj_XML){
		$return = array();
		if($this->dom_support){
			$arr_child = $obj_XML->child_nodes();
			
			if(count($arr_child)>1){
				foreach ($arr_child as $value) {
				  if(trim($value->tagname)){
				  	if(count($value->child_nodes())>1){
						$return[$value->tagname][] = $this->xml2array($value);
					}else{
						$return[$value->tagname] = $this->xml2array($value);
					}				  						  
				  }
				}
			}else{
				$return = trim($obj_XML->get_content());			   
			}			
		}else{
			$arr_child = $obj_XML->childNodes;
			if($obj_XML->childNodes->length>1){
				foreach($arr_child as $nc){
					if($nc->childNodes->length>1){
						$return[$nc->nodeName][] = $this->xml2array($nc);
					}else{
						$return[$nc->nodeName] = $this->xml2array($nc);
					}
				}
			}else{
				$return=$obj_XML->nodeValue;
			}
		}
		return $return;		
	}
}
?>