<!--##########################################################-->
<!--######################### Search #########################-->
<!--##########################################################-->
<!-- Search START-->
<div class="">
	<div class="top_search text-center">
				
				<? if($viewID=="SOMR_INDEX"){ ?>
			  	<form action="/smart_omr/exercise_book/list.php" method="post"
			onsubmit="return $('#search_key').val()!=''?true:false;">
			<div class="pure-g">
				<div class="pure-u-4-5">
					<label class="sr-only" for="search_key">Search</label> <input
						type="text" class="form-control input-lg search-input"
						name="search_key" id="search_key" placeholder="검색어를 입력하세요"
						value="<?=$_POST['search_key']?>">
				</div>
				<div class="pure-u-1-5">
					<button type="submit" class="btn btn-primary btn-lg search-bt"
						style="">
						<i class="fa fa-search" aria-hidden="true"></i>
					</button>
				</div>

			</div>
		</form>
				<? }else{ ?>
				<form id="frmSearch" onsubmit="return false;">
			<input type="hidden" id="before_search_key" name="before_search_key"
				value="<?=$_POST['search_key']?>"> <input type="hidden"
				id="category_seq" name="category_seq">
			<div class="pure-g">
				<div class="pure-u-4-5">
					<label class="sr-only" for="search_key">Search</label> <input
						type="text" class="form-control input-lg search-input"
						name="search_key" id="search_key" placeholder="검색어를 입력하세요"
						value="<?=$_POST['search_key']?>">
				</div>
				<div class="pure-u-1-5">
					<button type="button" class="btn btn-primary btn-lg search-bt"
						style="">
						<i class="fa fa-search" aria-hidden="true"></i>
					</button>
				</div>

			</div>
		</form>
				<? } ?>
				</div>
</div>
<!-- Search END-->