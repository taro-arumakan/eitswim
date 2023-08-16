<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action));?>
<?php
$this->title = 'お問い合わせ履歴一覧';
//URLに付加する
$ses = '?'.date("YmdHis");

?>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
<?php $this->renderPartial('/admin/inquiry/sidebar', array('action' => $action, 'template' => $template));?>
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title">お問い合わせ履歴一覧</h3>
		<!-- END PAGE HEADER-->

		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject font-green-sharp sbold">履歴を絞り込む</span>
						</div>
					</div>
					<div class="portlet-body form">
						<form role="form" action="/inquiry/admin/master/history" method="post">
							<div class="form-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>お名前</label>
											<input type="text" class="form-control" placeholder=""
														 name="history_name"
														 value="<?php echo empty($model->history_name)?'':$model->history_name;?>"
											>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>メールアドレス</label>
											<input type="text" class="form-control" placeholder=""
														 name="history_mail"
														 value="<?php echo empty($model->history_mail)?'':$model->history_mail;?>"
											>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>期間</label>
											<div>
												<input type="text"
															 name="history_from"
															 value="<?php echo $model->history_from;?>"
															 class="form-control input-small datepicker"
															 style="display: inline-block">
												〜
												<input type="text"
															 name="history_to"
															 value="<?php echo $model->history_to;?>"
															 class="form-control input-small datepicker"
															 style="display: inline-block">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>お問い合わせ種別</label>
											<div class="mt-checkbox-inline">
												<?php foreach($option['kind'] as $item):?>
													<?php
													$checked = '';
													if(!empty($model->kind)) {
														if(is_array($model->kind)) {
															foreach($model->kind as $sub) {
																if($item->inquiry_code == $sub) {
																	$checked = 'checked';
																}
															}
														}
													}
													?>

													<label class="mt-checkbox mt-checkbox-outline">
														<input type="checkbox" name="kind[]" value="<?php echo $item->inquiry_code;?>" <?php echo $checked;?>> <?php echo $item->inquiry_title;?>
														<span></span>
													</label>
												<?php endforeach;?>

											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="mode" id="cmd" value="search">
							<div class="form-actions right">
								<button type="button" id="search" class="btn blue">検索</button>
							</div>
							<input type="hidden" id="csv" name="csv" value="0">
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject font-green-sharp sbold">履歴一覧</span>
						</div>
						<div class="actions">
							<?php if(!empty($option['total_count'])):?>
								<?php
								$page_from = $option['page'] * param('max_count_one') + 1;
								$page_to = $page_from + param('max_count_one') - 1;
								if($page_to > $option['total_count']) {
									$page_to = $option['total_count'];
								}
								?>
								<?php echo $option['total_count'];?>件中 <?php echo $page_from;?>〜<?php echo $page_to;?>件表示
							<?php else:?>
								0件
							<?php endif;?>
						</div>
					</div>
					<div class="portlet-body form search-page search-content-4">
						<?php if(!empty($option['total_count'])):?>
							<div class="form-actions right" style="border-top: none;">
								<button type="button" id="csv" class="btn btn btn-default">CSV出力</button>
							</div>
						<?php endif;?>
						<div class="search-table table-responsive">
							<table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
								<tbody>
								<?php foreach($data as $item):?>
									<tr class="odd gradeX">
										<!--
										<td style="display: none;">
											<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
												<input name="csv" type="checkbox" class="checkboxes" value="<?php echo $item->history_id;?>">
												<span></span>
											</label>
										</td>
										-->
										<td><?php echo $item->history_id;?></td>
										<td><?php echo $item->history_title;?></td>
										<td><?php echo $item->history_name;?></td>
										<td><?php echo $item->history_mail;?></td>
										<td><?php echo $item->create_date;?></td>
										<td>
											<a class="btn btn-default btn-block" href="<?php echo RM();?>detail?id=<?php echo $item->history_id;?>" role="button">詳細</a>
										</td>
									</tr>
								<?php endforeach;?>

								</tbody>
							</table>
							<?php $this->renderPartial('/admin/inquiry/pagination'
								,array('page' => $option['page'],'total_count' => $option['total_count']));?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<?php $this->renderPartial('/admin/inquiry/footer', array('action' => $action));?>

<script>

	jQuery(document).ready(function() {

		//検索
		$('button#search').on('click',function(){
			var action = '/inquiry/admin/master/history';
			$('input#csv').val('');
			$('form').attr('action',action);
			$('form').submit();
		});
		//CSV
		$('button#csv').on('click',function(){
			if(!confirm('CSVファイルをダウンロードしますか？')){
				/* キャンセルの時の処理 */
				return false;
			}else{
				/* OKの時の処理 */
				var action = '/inquiry/admin/master/history';
				$('input#csv').val('1');
				$('form').attr('action',action);
				$('form').submit();
			}

		});

		//リンク
		$('a#link').on('click',function(){
			var action = '?page=' + $(this).attr('page');
			$('form').attr('action',action);
			$('form').submit();
		});


		$('input.datepicker').datepicker({
			autoclose: true,
			format: "yyyy/mm/dd",
			todayBtn: "linked",
			clearBtn: true,
			language: "ja"
		});
	});
</script>

</body>
</html>
