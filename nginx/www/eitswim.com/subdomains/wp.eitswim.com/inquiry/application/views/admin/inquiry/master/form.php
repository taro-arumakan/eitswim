<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action));?>
<?php
$this->title = 'お問い合わせフォーム一覧';
//URLに付加する
$ses = '?'.date("YmdHis");

?>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
<?php $this->renderPartial('/admin/inquiry/sidebar', array('action' => $action, 'template' => $template));?>
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title">お問い合わせフォーム一覧</h3>
		<!-- END PAGE HEADER-->

		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject font-green-sharp sbold">フォーム一覧</span>
						</div>
					</div>
					<div class="portlet-body form search-page search-content-4">
						<div class="search-table table-responsive">
							<table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
								<tbody>
								<?php foreach($data as $item):?>
									<?php
									$mail = InquiryMail::model()->findByAttributes(array('mail_inquiry_id' => $item->inquiry_id,'delete_flag'=>0));
									if(empty($mail)) {
										$mail = new InquiryMail;
									}
									$name = '';
									if(!empty($mail->mail_from_name)) {
										$name = $mail->mail_from_name.'<br>';
									}
									$mail_to = InquiryTo::model()->findAllByAttributes(array('to_mail_id' => $mail->mail_id,'delete_flag'=>0));
									?>
									<tr class="odd gradeX">
										<td><?php echo $item->inquiry_rank;?></td>
										<td><?php echo $item->inquiry_title;?></td>
										<td><?php echo $item->inquiry_code;?></td>
										<td><?php echo $name;?>[<?php echo $mail->mail_from_address;?>]</mail></td>
										<td>
											<?php foreach($mail_to as $to):?>
												<?php echo $to->to_address;?><br>
											<?php endforeach;?>
										</td>
										<td>
											<a class="btn btn-default btn-block" href="/inquiry/admin/master/form/?cmd=edit&id=<?php echo $item->inquiry_id;?>" role="button">編集</a>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
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
			var action = 'history';
			$('form').attr('action',action);
			$('form').submit();
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
