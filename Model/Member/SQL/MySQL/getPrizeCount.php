<?php
$query = "select count(*) as count from prize_info ";


if($match_seq){
	$query = sprintf($query."where match_seq = %d and ranking = %d",$match_seq,$ranking);
}

?>