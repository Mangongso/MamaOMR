<?$viewID='GET_SOMR_COMMENT';?>
<?include ("../../_connector/yellow.501.php");?>
<div class="comment_title mb10 clearfix" style="padding-left: 0px;"><span class="glyphicon glyphicon-comment font_size18"></span> 댓글</div>

<!-- <ul class="nav nav-tabs cleardfix">
  <li><a href="#tab_div_comment_2" data-toggle="tab" style="padding-top: 5px; padding-right: 15px; padding-bottom: 5px; padding-left: 15px;">댓글2</a></li>
  <li><a href="#tab_div_comment_3" data-toggle="tab" style="padding-top: 5px; padding-right: 15px; padding-bottom: 5px; padding-left: 15px;">댓글3</a></li>
</ul> -->
<div class="tab-content">
	<div id="tab_div_comment_1" class="tab-pane active">
		<form name="frmComment" id="frmComment">
			<input type="hidden" name="post_seq" id="post_seq" value="<?=$arr_output['comment']['post_seq'];?>"/>
			<input type="hidden" name="bbs_seq" id="bbs_seq" value="<?=$arr_output['comment']['bbs_seq'];?>"/>
			<div class="input-group" style="width:100%;padding: 10px;">
				<input type="text" name="comment" id="comment" class="form-control input-sm" placeholder="댓글을 작성하세요"> 
				<span class="input-group-btn">
					<button class="btn btn-default input-sm" type="button" id="btn_comment" onclick="objCommon.saveComment();" style="padding-top: 4px;">등록</button>
				</span>
			</div>
			<!-- /input-group -->
		</form>	
		
		
		
		<table class="table mg_table comment">
			<?if(count($arr_output['comment']['comment'])>0){?>
		<? foreach($arr_output['comment']['comment'] as $intKey=>$arrResutl){ ?>
		<tr>
			<!--
			<td rowspan="2" class="col-xs-1 col-sm-1"><img src="/_images/student_profile_img.jpg"></td>
			-->
			<td>
				<div class="fl col-sm-12 cfs">
					<div class="col-sm-2 cfs mb10 table_subtitle">
						<strong><?=$arrResutl['cmt_name'];?></strong>
					</div> 
					<div class="col-sm-10 text-justify comment_linehight">
						<?=$arrResutl['comment'];?>
					</div>
				</div>
				<div class="col-offset-sm-12" style="float:right;">
					<? if(md5($arrResutl['reg_id'])==$arr_output['comment']['member_key']){ ?>
						<a onclick="objCommon.deleteComment(<?=$arr_output['comment']['post_seq']?>,<?=$arr_output['comment']['bbs_seq']?>,<?=$arrResutl['cmt_id']?>);" href="javascript:void(0);">
							<span class="glyphicon glyphicon-remove" ></span>
						</a>
					<? } ?>
					&nbsp;&nbsp;&nbsp;
					<span class="hidden-xs"><?=date('Y-m-d',$arrResutl['reg_date']);?></span>
				</div>
			</td>
		</tr>
		
		<? } ?>	
		<? }else{ ?>
					<tr>
						<td align="cneter">새 댓글이 없습니다</td>
					</tr>
					<? }?>
		</table>
		<!--
		<div class="fr hidden-xs">
			<ul class="pagination pagination-sm clearfix" style="overflow: hidden;">
				<li>
					<a href="#">«</a>
				</li>
				<li>
					<a href="#">1</a>
				</li>
				<li>
					<a href="#">2</a>
				</li>
				<li>
					<a href="#">3</a>
				</li>
				<li>
					<a href="#">4</a>
				</li>
				<li>
					<a href="#">5</a>
				</li>
				<li>
					<a href="#">»</a>
				</li>
			</ul>
		</div>
		-->
	</div>
	<div id="tab_div_comment_2" class="tab-pane">댓글 2</div>
	<div id="tab_div_comment_3" class="tab-pane">댓글 3</div>
</div>
<br /><br /><br /><br />