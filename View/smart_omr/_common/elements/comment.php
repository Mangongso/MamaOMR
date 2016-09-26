<?$viewID='GET_SOMR_COMMENT';?>
<?include ($_SERVER['DOCUMENT_ROOT']."/_connector/yellow.501.php");?>
<!--##################################################################-->
<!--######################### Users Comments #########################-->
<!--##################################################################-->
<div class="tab-content">
	<div id="tab_div_comment_1" class="tab-pane active">
		<form name="frmComment" id="frmComment">
			<input type="hidden" name="post_seq" id="post_seq"
				value="<?=$arr_output['comment']['post_seq'];?>" /> <input
				type="hidden" name="bbs_seq" id="bbs_seq"
				value="<?=$arr_output['comment']['bbs_seq'];?>" />
			<div class="input-group" style="width: 100%; padding: 10px;">
			<label class="sr-only" for="comment">댓글을 작성하세요</label>
				<input type="text" name="comment" id="comment"
					class="form-control input-sm" placeholder="댓글을 작성하세요"> <span
					class="input-group-btn">
					<button class="btn btn-default input-sm" type="button"
						id="btn_comment" onclick="objCommon.saveComment();"
						style="padding-top: 4px;">등록</button>
				</span>
			</div>
		</form>
		<div class="sub_contents_body_box comment_list">
			<?if(count($arr_output['comment']['comment'])>0){?>
		<? foreach($arr_output['comment']['comment'] as $intKey=>$arrResutl){ ?>
			<!--  ######### -->
			<ul>
				<li class="col-lg-2"><b><?=$arrResutl['cmt_name'];?></b></li>
				<li class="col-lg-8"><?=$arrResutl['comment'];?></li>
				<li class="col-lg-2"><small><?=date('Y-m-d',$arrResutl['reg_date']);?> <? if(md5($arrResutl['reg_id'])==$arr_output['comment']['member_key']){ ?>
						<a
						onclick="objCommon.deleteComment(<?=$arr_output['comment']['post_seq']?>,<?=$arr_output['comment']['bbs_seq']?>,<?=$arrResutl['cmt_id']?>);"
						href="#" title="삭제"> <i class="fa fa-times"
							aria-hidden="true"></i><span class="sr-only">삭제<</span>
					</a>
					<? } ?></small></li>
			</ul>
			<!--  ######### -->
		<? } ?>	
		<? }else{ ?>
			<div class="h_dot_box info-box-ul info-comment"
				style="padding: 20px 10px 20px 10px;margin-top: -25px;">
				<i class="fa fa-info-circle fa-5x" aria-hidden="true"></i><br />
				<p align="cneter">새 댓글이 없습니다</p>
			</div>
		<? }?>
		</div>
	</div>
</div>