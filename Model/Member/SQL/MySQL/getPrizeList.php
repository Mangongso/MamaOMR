<?php
$query = sprintf("select * from prize_info where match_seq in (%d) and ranking = '%s' " ,$arr_input['match_seq'],$arr_input['ranking']);
?>