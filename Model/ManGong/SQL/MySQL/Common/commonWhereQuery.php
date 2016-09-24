<?
$arrWhereQuery = array();
if(count($arrSearch)){
	foreach($arrSearch as $key=>$mixValue){
		switch($key){
			case("search_type"):
				if($arrSearch['search_keyword']!=''){
					array_push($arrWhereQuery, sprintf(" %s like '%%%s%%' ",$arrSearch['search_type'],quote_smart($arrSearch['search_keyword'])));
				}
			break;
			case("search_keyword"):
			case("start_date"):
			case("end_date"):
				continue;
			break;
			case("date_type"):
				if($mixValue=="participation_period" && $arrSearch['start_date']!='' && $arrSearch['end_date']!=''){
					array_push($arrWhereQuery, sprintf(" start_date between '%s' and '%s' ",$arrSearch['start_date'],$arrSearch['end_date']));
				}else if($arrSearch['start_date']!='' && $arrSearch['end_date']!=''){
					array_push($arrWhereQuery, sprintf(" %s between '%s' and '%s' ",$mixValue,$arrSearch['start_date'],$arrSearch['end_date']));
				}
			break;
			case("del_flg"):
				array_push($arrWhereQuery, sprintf(" %s='%s' ",$key,$mixValue));
			break;
			default;
				if(is_numeric($mixValue) && $mixValue!=''){
					array_push($arrWhereQuery, sprintf(" %s=%d ",$key,$mixValue));
				}else if(is_string($mixValue) && $mixValue!=''){
					array_push($arrWhereQuery, sprintf(" %s='%s' ",$key,quote_smart($mixValue)));
				}			
			break;
		}
	}
}
?>