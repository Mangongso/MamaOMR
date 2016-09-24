<?php
/**
 * ImageHelper
 * @category     	Paging
 */
class Paging{
	/**
	 * 페이징 가져오기
	 *
	 * @param	integer 		$int_total_result_number : 결과 번호
	 * @param 	integer 		$int_page_number : 	페이지 번호
	 * @param 	integer 		$int_result_number : 결과 번호
	 * @param 	integer 		$int_block_number : 블럭 번호
	 * @param 	array 			$arr_param : 파라미터
	 * @return array	$arr_result 	페이징 결과를 반환
	 */
	function getPaging($int_total_result_number=null,$int_page_number=1,$int_result_number=20,$int_block_number=10,$arr_param){
		$arr_result = array();
		$arr_result[block_num] = $int_block_number;
		$arr_result[reault_per_page] = $int_result_number;
		$arr_result[total_result_num] = $int_total_result_number;
		$arr_result[total_page_num] = ceil($int_total_result_number/$int_result_number);
		$arr_result[limit_start] = ($int_page_number*$int_result_number)-$int_result_number;
		$arr_result[limit_start_desc] = $arr_result[total_result_num] - $arr_result[limit_start];
		$arr_result[limit_offset] = ($int_result_number);
		
		$arr_result[prev_page] = $int_page_number - 1;
		$arr_result[next_page] = $int_page_number + 1;
		if($arr_result[prev_page]<1){
			$arr_result[prev_page] = 1;
		}
		if($arr_result[next_page]>$arr_result[total_page_num]){
			$arr_result[next_page] = $arr_result[total_page_num];
		}
		$arr_result[prev_block] = floor(($int_page_number-1)/$int_block_number)*$int_block_number;
		$arr_result[next_block] = floor(($int_page_number-1)/$int_block_number)*$int_block_number+$int_block_number+1;		
		if($arr_result[prev_block]<1){
			$arr_result[prev_block] = 1;
		}
		if($arr_result[next_block]>$arr_result[total_page_num]){
			$arr_result[next_block] = $arr_result[total_page_num];
		}
		$arr_paging = array();
		$arr_param[page] = 1;
		$arr_result[start] = array(number=>1,link_param=>$arr_result[prev_block]>1?$arr_param:false);
		$arr_param[page] = $arr_result[total_page_num];
		$arr_paging[first] = array(number=>1,link_param=>$arr_result[prev_block]>1?$arr_param:false);
		$arr_paging[end] = array(number=>$arr_result[total_page_num],link_param=>$arr_result[prev_block]<$arr_result[total_page_num]?$arr_param:false);		
		$arr_param[page] = $arr_result[prev_block];
		$arr_paging[prev] = array(number=>$arr_result[prev_block],link_param=>$arr_param);
		$arr_param[page] = $arr_result[next_block];
		$arr_paging[next] = array(number=>$arr_result[next_block],link_param=>$arr_param);
		for($i=(($arr_result[prev_block]==1)?1:$arr_result[prev_block]+1);$i<=(($arr_result[next_block]==$arr_result[total_page_num])?$arr_result[total_page_num]:$arr_result[next_block]-1);$i++){
			$arr_param[page] = $i;
			$arr_paging[page][] = array(number=>$i,link_param=>$arr_param);
		}
		if(count($arr_paging[page])==0){
			$arr_param[page]=1;
			$arr_paging[page][] = array(number=>1,link_param=>$arr_param);
		}
		$arr_result[start_page] = 1;
		$arr_result[end_page] = $arr_result[total_page_num];
		$arr_result[page] = $int_page_number;
		$arr_result[paging] = $arr_paging;
		return($arr_result);
	}
}
?>