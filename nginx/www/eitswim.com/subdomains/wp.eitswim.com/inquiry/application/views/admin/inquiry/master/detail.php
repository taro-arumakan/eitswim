<?php
$this->title = 'お問い合わせ履歴・詳細';
$mode = 'validate';
$param = '';
if(!empty($id)) {
	$param = '?id='.$id;
}
?>
<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action, 'cmd' => $cmd));?>
<?php
//URLに付加する
$ses = '?'.date("YmdHis");
?>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
<?php $this->renderPartial('/admin/inquiry/sidebar', array('action' => $action, 'template' => $template, 'cmd' => $cmd));?>
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title"><?php echo $this->title;?></h3>
		<!-- END PAGE HEADER-->

		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<?php if($cmd==='edit'):?>
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-green-sharp sbold"><?php echo $model->history_code;?></span>
							</div>
						</div>
					<?php endif;?>
					<div class="portlet-body form">
						<form role="form" action="spot_conf<?php echo $param;?>" method="post">
							<span class="caption-subject font-red-mint sbold"><?php echo CHtml::errorSummary($model); ?></span>

							<input type="hidden" name="spot_id" value="<?php echo $model->history_id;?>">
							<div class="form-body">
								<div class="form-group">
									<label>お問い合わせ種別</label>
									<input type="text" class="form-control" placeholder=""
												 name="spot_area_id"
												 value="<?php echo $model->history_title;?>"
									>
								</div>
								<div class="form-group">
									<label>日時</label>
									<input type="text" class="form-control" placeholder=""
												 name="spot_area_id"
												 value="<?php echo $model->create_date;?>"
									>
								</div>
								<div class="form-group">
									<label>お名前</label>
									<input type="text" class="form-control" placeholder=""
									       name="spot_area_id"
									       value="<?php echo $model->history_name;?>"
									>
								</div>
								<div class="form-group">
									<label>メールアドレス</label>
									<input type="text" class="form-control" placeholder=""
									       name="spot_section_name"
									       value="<?php echo $model->history_mail;?>"
									>
								</div>
								<div class="form-group">
									<label>お問い合わせ内容</label>
									<?php
									$line = explode("\n", $model->history_answer);
									?>
									<textarea class="form-control" rows="<?php echo count($line);?>" ><?php echo $model->history_answer;?></textarea>
								</div>
							<div class="form-actions right">
								<button type="submit" class="btn blue">戻る</button>
							</div>
							<input type="hidden" name="cmd" value="<?php echo $cmd;?>">
							<input type="hidden" name="mode" value="<?php echo $mode;?>">
						</form>
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

<script type="text/javascript">
	jQuery(function($){
		$(function(){
			$('.category').on('click', function() {
				if ($(this).prop('checked')){
					// 一旦全てをクリアして再チェックする
					$('.category').prop('checked', false);
					$(this).prop('checked', true);
				}
			});
		});
	});
</script>

</body>
</html>
