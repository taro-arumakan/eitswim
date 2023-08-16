<?php
$this->title = 'フォーム';
$mode = '';
if($cmd==='conf') {
	$this->title .= '編集';
	$mode = 'update';
}
$param = '';
if(!empty($id)) {
	$param = '&id='.$id;
}
?>
<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action));?>
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
					<div class="portlet-body form">
						<form role="form" action="?cmd=conf<?php echo $param;?>" method="post">
							<div class="form-body">
								<div class="table-responsive">
									<table class="table">
										<tbody>
										<?php if(!empty($option['Inquiry']['inquiry_id'])):?>
											<input type="hidden"
														 name="Inquiry[inquiry_id]"
														 value="<?php echo $option['Inquiry']['inquiry_id'];?>">
										<?php else:?>
											<input type="hidden"
														 name="Inquiry[inquiry_id]"
														 value="">
										<?php endif;?>
										<tr>
											<td>表示順</td>
											<?php if(!empty($option['Inquiry']['inquiry_rank'])):?>
												<td><?php echo $option['Inquiry']['inquiry_rank'];?></td>
												<input type="hidden"
															 name="Inquiry[inquiry_rank]"
															 value="<?php echo $option['Inquiry']['inquiry_rank'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[inquiry_rank]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>フォームURL</td>
											<?php if(!empty($option['Inquiry']['inquiry_code'])):?>
												<td><?php echo $option['Inquiry']['inquiry_code'];?></td>
												<input type="hidden"
															 name="Inquiry[inquiry_code]"
															 value="<?php echo $option['Inquiry']['inquiry_code'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[inquiry_code]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>フォーム名</td>
											<?php if(!empty($option['Inquiry']['inquiry_title'])):?>
												<td><?php echo $option['Inquiry']['inquiry_title'];?></td>
												<input type="hidden"
															 name="Inquiry[inquiry_title]"
															 value="<?php echo $option['Inquiry']['inquiry_title'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[inquiry_title]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>SEO_TITLE</td>
											<?php if(!empty($option['Inquiry']['seo_title'])):?>
												<td><?php echo $option['Inquiry']['seo_title'];?></td>
												<input type="hidden"
															 name="Inquiry[seo_title]"
															 value="<?php echo $option['Inquiry']['seo_title'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[seo_title]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>SEO_DESCRIPTION</td>
											<?php if(!empty($option['Inquiry']['seo_description'])):?>
												<td><?php echo $option['Inquiry']['seo_description'];?></td>
												<input type="hidden"
															 name="Inquiry[seo_description]"
															 value="<?php echo $option['Inquiry']['seo_description'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[seo_description]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>SEO_KEYWORDS</td>
											<?php if(!empty($option['Inquiry']['seo_keywords'])):?>
												<td><?php echo $option['Inquiry']['seo_keywords'];?></td>
												<input type="hidden"
															 name="Inquiry[seo_keywords]"
															 value="<?php echo $option['Inquiry']['seo_keywords'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[seo_keywords]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>返信宛タイトル</td>
											<?php if(!empty($option['InquiryMail']['mail_reply_subject'])):?>
												<td><?php echo $option['InquiryMail']['mail_reply_subject'];?></td>
												<input type="hidden"
															 name="InquiryMail[mail_reply_subject]"
															 value="<?php echo $option['InquiryMail']['mail_reply_subject'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMail[mail_reply_subject]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>管理者宛タイトル</td>
											<?php if(!empty($option['InquiryMail']['mail_admin_subject'])):?>
												<td><?php echo $option['InquiryMail']['mail_admin_subject'];?></td>
												<input type="hidden"
															 name="InquiryMail[mail_admin_subject]"
															 value="<?php echo $option['InquiryMail']['mail_admin_subject'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMail[mail_admin_subject]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>FROM</td>
											<?php
											$name = '';
											$address = '';
											$from = '';
											if(!empty($option['InquiryMail']['mail_from_name'])) {
												$name = $option['InquiryMail']['mail_from_name'];
												$from = $option['InquiryMail']['mail_from_name'];
											}
											if(!empty($option['InquiryMail']['mail_from_address'])) {
												$address = $option['InquiryMail']['mail_from_address'];
												if(empty($from)) {
													$from = $option['InquiryMail']['mail_from_address'];
												} else {
													$from .= ' [ '.$option['InquiryMail']['mail_from_address'].' ]';
												}
											}
											?>
											<?php if(!empty($from)):?>
												<td><?php echo $from;?></td>
												<input type="hidden"
															 name="InquiryMail[mail_from_name]"
															 value="<?php echo $name;?>">
												<input type="hidden"
															 name="InquiryMail[mail_from_address]"
															 value="<?php echo $address;?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMail[mail_from_name]"
															 value="">
												<input type="hidden"
															 name="InquiryMail[mail_from_address]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>TO</td>
											<td>
												<?php if(!empty($option['InquiryTo'])):?>
													<?php foreach($option['InquiryTo'] as $index => $to):?>
														<?php
														$to_name = '';
														if(!empty($to['to_name'])) {
															$to_name = $to['to_name'];
														}
														if(!empty($to['to_address'])) {
															if(empty($to_name)) {
																$to_name = $to['to_address'].'<br>';
															} else {
																$to_name .= ' [ '.$to['to_address'].' ]<br>';
															}
														}
														?>
														<?php echo $to_name;?>
														<input type="hidden"
																	 name="InquiryTo[<?php echo $index;?>][to_name]"
																	 value="<?php echo $to['to_name'];?>">
														<input type="hidden"
																	 name="InquiryTo[<?php echo $index;?>][to_address]"
																	 value="<?php echo $to['to_address'];?>">
														<input type="hidden"
																	 name="InquiryTo[<?php echo $index;?>][to_question_no]"
																	 value="<?php echo $to['to_question_no'];?>">
														<input type="hidden"
																	 name="InquiryTo[<?php echo $index;?>][to_answer_no]"
																	 value="<?php echo $to['to_answer_no'];?>">
														<input type="hidden"
																	 name="InquiryTo[<?php echo $index;?>][to_comment]"
																	 value="<?php echo $to['to_comment'];?>">
													<?php endforeach;?>
												<?php else:?>
													&nbsp;
												<?php endif;?>
											</td>
										</tr>
										<tr>
											<td>BCC</td>
											<td>
												<?php if(!empty($option['InquiryBcc'])):?>
													<?php foreach($option['InquiryBcc'] as $index => $bcc):?>
														<?php
														$bcc_name = '';
														if(!empty($bcc['bcc_name'])) {
															$bcc_name = $bcc['bcc_name'];
														}
														if(!empty($bcc['bcc_address'])) {
															if(empty($bcc_name)) {
																$bcc_name = $bcc['bcc_address'].'<br>';
															} else {
																$bcc_name .= ' [ '.$bcc['bcc_address'].' ]<br>';
															}
														}
														?>
														<?php echo $bcc_name;?>
														<input type="hidden"
																	 name="InquiryBcc[<?php echo $index;?>][bcc_name]"
																	 value="<?php echo $bcc['bcc_name'];?>">
														<input type="hidden"
																	 name="InquiryBcc[<?php echo $index;?>][bcc_address]"
																	 value="<?php echo $bcc['bcc_address'];?>">
													<?php endforeach;?>
												<?php else:?>
													&nbsp;
												<?php endif;?>
											</td>
										</tr>
										<tr>
											<td>返信メールヘッダー</td>
											<?php if(!empty($option['InquiryMail']['mail_header'])):?>
												<td><?php echo nl2br($option['InquiryMail']['mail_header']);?></td>
												<input type="hidden"
															 name="InquiryMail[mail_header]"
															 value="<?php echo $option['InquiryMail']['mail_header'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMail[mail_header]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>返信メールフッター</td>
											<?php if(!empty($option['InquiryMail']['mail_footer'])):?>
												<td><?php echo nl2br($option['InquiryMail']['mail_footer']);?></td>
												<input type="hidden"
															 name="InquiryMail[mail_footer]"
															 value="<?php echo $option['InquiryMail']['mail_footer'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMail[mail_footer]"
															 value="">
											<?php endif;?>
										</tr>
<?php //ここからMAX用 ?>
										<?php
										$data = array();
										if(!empty($option['InquiryMailSub'])) {
											$data = $option['InquiryMailSub'];
										}
										?>
										<?php foreach($data as $sub):?>
										<tr>
											<td><?php echo $sub['sub_title'];?>用 質問No.</td>
											<td><?php echo $sub['sub_question_no'];?></td>
										</tr>
										<tr>
											<td><?php echo $sub['sub_title'];?>用 回答No.</td>
											<td><?php echo $sub['sub_answer_no'];?></td>
										</tr>

											<input type="hidden"
														 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_id]"
														 value="<?php echo $sub['sub_id'];?>">
											<input type="hidden"
														 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_rank]"
														 value="<?php echo $sub['sub_rank'];?>">
											<input type="hidden"
														 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_title]"
														 value="<?php echo $sub['sub_title'];?>">
											<input type="hidden"
														 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_question_no]"
														 value="<?php echo $sub['sub_question_no'];?>">
											<input type="hidden"
														 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_answer_no]"
														 value="<?php echo $sub['sub_answer_no'];?>">
										<tr>
											<td>FROM (<?php echo $sub['sub_title'];?>用)</td>
											<?php
											$name = '';
											$address = '';
											$from = '';
											if(!empty($sub['sub_from_name'])) {
												$name = $sub['sub_from_name'];
												$from = $sub['sub_from_name'];
											}
											if(!empty($sub['sub_from_address'])) {
												$address = $sub['sub_from_address'];
												if(empty($from)) {
													$from = $sub['sub_from_address'];
												} else {
													$from .= ' [ '.$sub['sub_from_address'].' ]';
												}
											}
											?>
											<?php if(!empty($from)):?>
												<td><?php echo $from;?></td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_from_name]"
															 value="<?php echo $name;?>">
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_from_address]"
															 value="<?php echo $address;?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_from_name]"
															 value="">
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_from_address]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>返信メールヘッダー (<?php echo $sub['sub_title'];?>用) </td>
											<?php if(!empty($sub['sub_header'])):?>
												<td><?php echo nl2br($sub['sub_header']);?></td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_header]"
															 value="<?php echo $sub['sub_header'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_header]"
															 value="">
											<?php endif;?>
										</tr>
										<tr>
											<td>返信メールフッター (<?php echo $sub['sub_title'];?>用) </td>
											<?php if(!empty($sub['sub_footer'])):?>
												<td><?php echo nl2br($sub['sub_footer']);?></td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_footer]"
															 value="<?php echo $sub['sub_footer'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="InquiryMailSub[<?php echo $sub['sub_id'];?>][sub_footer]"
															 value="">
											<?php endif;?>
										</tr>
										<?php endforeach;?>
										<tr>
											<td>注意事項</td>
											<?php if(!empty($option['Inquiry']['inquiry_notes'])):?>
												<td><?php echo htmlspecialchars($option['Inquiry']['inquiry_notes']);?></td>
												<?php
												$option['Inquiry']['inquiry_notes'] = str_replace('"', '&quot;', $option['Inquiry']['inquiry_notes']);
												?>
												<input type="hidden"
															 name="Inquiry[inquiry_notes]"
															 value="<?php echo $option['Inquiry']['inquiry_notes'];?>">
											<?php else:?>
												<td>&nbsp;</td>
												<input type="hidden"
															 name="Inquiry[inquiry_notes]"
															 value="">
											<?php endif;?>
										</tr>
										<?php
										$questions = array();
										if(!empty($option['InquiryQuestion'])) {
											$questions = $option['InquiryQuestion'];
										}
										?>
										<?php foreach($questions as $index => $question):?>
											<?php if(!empty($question['question_name'])):?>
												<tr>
													<td>質問<?php echo $question['question_rank'];?></td>
													<?php $opt = '';?>
													<?php $sep = '';?>
													<?php if(!empty($question['question_require'])):?>
														<?php $opt = '必須';?>
														<?php $sep = '、';?>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][question_require]"
																	 value="1">
													<?php endif;?>
													<?php if(!empty($question['is_name'])):?>
														<?php $opt .= $sep.'お名前欄';?>
														<?php $sep = '、';?>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][is_name]"
																	 value="1">
													<?php endif;?>
													<?php if(!empty($question['is_mail'])):?>
														<?php $opt .= $sep.'メールアドレス欄';?>
														<?php $sep = '、';?>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][is_mail]"
																	 value="1">
													<?php endif;?>
													<?php if(!empty($question['question_type'])):?>
														<?php $opt .= $sep.$question['question_type'];?>
														<?php $type = $question['question_type'];?>
														<?php $sep = '、';?>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][question_rank]"
																	 value="<?php echo $question['question_rank'];?>">
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][question_type]"
																	 value="<?php echo $question['question_type'];?>">
													<?php else:?>
														<?php $type = '';?>
													<?php endif;?>
													<?php if(!empty($question['question_validation'])):?>
														<?php $opt .= $sep.$question['question_validation'];?>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][question_validation]"
																	 value="<?php echo $question['question_validation'];?>">
													<?php else:?>
													<?php endif;?>
													<td>
														<?php echo $question['question_name'];?><br>
														<input type="hidden"
																	 name="InquiryQuestion[<?php echo $index;?>][question_name]"
																	 value="<?php echo $question['question_name'];?>">
														<?php echo $opt;?><br>
														<?php if($type=='Checkbox' || $type=='Radio'):?>
															<?php if(!empty($option['InquiryAnswer'][$index])):?>
																<?php
																$answers = $option['InquiryAnswer'][$index];
																?>
																<?php foreach($answers as $answer):?>
																	<?php if(!empty($answer['answer_name'])):?>
																		回答<?php echo $answer['answer_rank'];?>:<?php echo $answer['answer_name'];?><br>
																		<input type="hidden"
																					 name="InquiryAnswer[<?php echo $index;?>][<?php echo $answer['answer_rank'];?>][answer_rank]"
																					 value="<?php echo $answer['answer_rank'];?>">
																		<input type="hidden"
																					 name="InquiryAnswer[<?php echo $index;?>][<?php echo $answer['answer_rank'];?>][answer_name]"
																					 value="<?php echo $answer['answer_name'];?>">
																	<?php endif;?>
																<?php endforeach;?>
															<?php endif;?>
														<?php endif;?>
													</td>
												</tr>
											<?php endif;?>
										<?php endforeach;?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="form-actions right">
								<button type="submit" class="btn blue">変更する</button>
							</div>
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

</body>
</html>
