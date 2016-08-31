<?php
$query = sprintf("update prize_info set state = %d where match_seq = %d and ranking = %d ",$state,$match_seq,$ranking);
?>