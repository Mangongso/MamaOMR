<?
$query = sprintf("select member_seq,(select count(*)+1 from member_ranking where (win>R.win) and (win>=R.win and win/(win+lost)) and member_seq!=R.member_seq and member_seq > %d) as ranking, nickname, win, lost, draw, if(pov is null,0.0000,pov) as pov from (select B.member_seq,A.win/(A.win+A.lost) as pov,A.win,A.lost,A.draw,B.nickname from member_ranking as A left join member_basic_info as B on A.member_seq=B.member_seq) as R",$arr_input['robot_seq_range']);
$arr_where = array();
$query = $query." order by ranking, pov DESC";
$query = "select * from (".$query.") as result";
if($arr_input['member_seq']){
	$arr_where[] = sprintf("R.member_seq=%d",$arr_input['member_seq']);
}
if($arr_input['from_ranking']){
	$arr_where[] = sprintf("ranking>=%d",$arr_input['from_ranking']);
}
if($arr_input['to_ranking']){
	$arr_where[] = sprintf("ranking<=%d",$arr_input['to_ranking']);
}
$query = sprintf($query." where %s",join(" and ",$arr_where));
?>