<?php
$this->title = 'フォーム';
$mode = 'validate';
if($cmd==='add') {
	$this->title .= '新規登録';
}
if($cmd==='edit') {
	$this->title .= '編集';
}
$param = '';
if(!empty($id)) {
	$param = '&id='.$id;
}

if(!empty($model)) {
	if(!empty($option['mail'])) {
		$mail = $option['mail'];
		if(!empty($option['mail_to'])) {
			$mail_to = $option['mail_to'];
		} else {
			$mail_to = InquiryTo::model()->findAllByAttributes(array('to_mail_id' => $mail->mail_id,'delete_flag'=>0));
		}
		if(!empty($option['mail_bcc'])) {
			$mail_bcc = $option['mail_bcc'];
		} else {
			$mail_bcc = InquiryBcc::model()->findAllByAttributes(array('bcc_mail_id' => $mail->mail_id,'delete_flag'=>0));
		}
	} else {
		$mail = new InquiryMail;
		$mail_to = array();
		$mail_bcc = array();
	}
	if(!empty($option['question'])) {
		$question = $option['question'];
	} else {
		$question = array();
	}
	if(!empty($option['answer'])) {
		$question_answer = $option['answer'];
	} else {
		$question_answer = array();
	}
} else {
	$model = new Inquiry;
	$mail = new InquiryMail;
	$mail_to = array();
	$mail_bcc = array();
	$question = array();
	$question_answer = array();
}

$to_count = 0;
$bcc_count = 0;
$question_count = 0;

if(!empty($option['error'])) {
	$error = $option['error'];
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
					<div class="portlet-body form">
						<form role="form" action="?cmd=conf<?php echo $param;?>" method="post">
							<div class="form-body">
								<div class="form-group">
									<label>表示順 (*)</label>
									<?php $e = '';
									if(!empty($error['Inquiry']['inquiry_rank'])) {
										$e='ui-state-error';
									} ?>
									<input type="text" class="form-control input-mini <?php echo $e;?>" placeholder=""
												 name="Inquiry[inquiry_rank]"
												 value="<?php echo $model->inquiry_rank;?>"
									>
									<input type="hidden"
												 name="Inquiry[inquiry_id]"
												 value="<?php echo $model->inquiry_id;?>"
								</div>
									<label>フォームURL (*)</label>
								<?php $e = '';
								if(!empty($error['Inquiry']['inquiry_code'])) {
									$e='ui-state-error';
								} ?>
									<input type="text" class="form-control input-small <?php echo $e;?>" placeholder="sim"
												 name="Inquiry[inquiry_code]"
												 value="<?php echo $model->inquiry_code;?>"
									>
								</div>
								<div class="form-group">
									<label>フォーム名 (*)</label>
									<?php $e = '';
									if(!empty($error['Inquiry']['inquiry_title'])) {
										$e='ui-state-error';
									} ?>
									<input type="text" class="form-control <?php echo $e;?>" placeholder="xxxに関するお問い合わせ"
												 name="Inquiry[inquiry_title]"
												 value="<?php echo $model->inquiry_title;?>"
									>
								</div>

							<div class="form-group">
								<label>SEO_TITLE</label>
								<?php $e = '';
								if(!empty($error['Inquiry']['inquiry_title'])) {
									$e='ui-state-error';
								} ?>
								<input type="text" class="form-control <?php echo $e;?>" placeholder=""
											 name="Inquiry[seo_title]"
											 value="<?php echo $model->seo_title;?>"
								>
							</div>
							<div class="form-group">
								<label>SEO_DESCRIPTION</label>
								<?php $e = '';
								if(!empty($error['Inquiry']['seo_description'])) {
									$e='ui-state-error';
								} ?>
								<input type="text" class="form-control <?php echo $e;?>" placeholder=""
											 name="Inquiry[seo_description]"
											 value="<?php echo $model->seo_description;?>"
								>
							</div>

							<div class="form-group">
								<label>SEO_KEYWORDS</label>
								<?php $e = '';
								if(!empty($error['Inquiry']['seo_keywords'])) {
									$e='ui-state-error';
								} ?>
								<input type="text" class="form-control <?php echo $e;?>" placeholder=""
											 name="Inquiry[seo_keywords]"
											 value="<?php echo $model->seo_keywords;?>"
								>
							</div>

								<div class="form-group">
									<label>自動返信宛タイトル (*)</label>
									<?php $e = '';
									if(!empty($error['InquiryMail']['mail_reply_subject'])) {
										$e='ui-state-error';
									} ?>
									<input type="text" class="form-control <?php echo $e;?>" placeholder=""
												 name="InquiryMail[mail_reply_subject]"
												 value="<?php echo $mail->mail_reply_subject;?>"
									>
								</div>
								<div class="form-group">
									<label>管理者宛タイトル (*)</label>
									<?php $e = '';
									if(!empty($error['InquiryMail']['mail_admin_subject'])) {
										$e='ui-state-error';
									} ?>
									<input type="text" class="form-control <?php echo $e;?>" placeholder=""
												 name="InquiryMail[mail_admin_subject]"
												 value="<?php echo $mail->mail_admin_subject;?>"
									>
								</div>
								<div class="form-group">
									<?php $e = '';$msg = '';
									if(!empty($error['InquiryMail']['mail_from_address'])) {
										$e='ui-state-error';
										$msg = $error['InquiryMail']['mail_from_address'];
									} ?>
									<label>FROM (*)</label>
									<div>
										<span style="white-space: nowrap;">名称
											<input type="text" class="form-control input-medium"
														 placeholder="差出人"
														 value="<?php echo $mail->mail_from_name;?>"
														 name="InquiryMail[mail_from_name]" style="display: inline-block"> </span>
										<span style="white-space: nowrap;">アドレス
											<input type="text" class="form-control input-medium <?php echo $e;?>"
														 placeholder="xxxx@unext.jp"
														 value="<?php echo $mail->mail_from_address;?>"
														 name="InquiryMail[mail_from_address]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;"> <?php echo $msg;?>
									</div>
								</div>
								<div class="form-group">
									<label>TO [管理者メールアドレス] (*)
									質問No:回答No:が一致したメールアドレスに送信します。</label>
									<?php foreach($mail_to as $idx => $to):?>
										<?php $to_count++;?>
										<div>
											<?php $e = '';$msg = '';
											if(!empty($error['InquiryTo']['to_address'][$to_count])) {
												$e='ui-state-error';
												$msg = $error['InquiryTo']['to_address'][$to_count];
											} ?>
											<span style="white-space: nowrap;">名称
												<input type="text" class="form-control input-medium"
															 placeholder=""
															 value="<?php echo $to->to_name;?>"
															 name="InquiryTo[<?php echo $idx;?>][to_name]" style="display: inline-block"> </span>
											<span style="white-space: nowrap;">アドレス 
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $to->to_address;?>"
															 name="InquiryTo[<?php echo $idx;?>][to_address]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">質問No:
												<input type="text" class="form-control input-mini <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $to->to_question_no;?>"
															 name="InquiryTo[<?php echo $idx;?>][to_question_no]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">回答No:
												<input type="text" class="form-control input-mini <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $to->to_answer_no;?>"
															 name="InquiryTo[<?php echo $idx;?>][to_answer_no]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">備考:
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $to->to_comment;?>"
															 name="InquiryTo[<?php echo $idx;?>][to_comment]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;"> <?php echo $msg;?>
										</div>
									<?php endforeach;?>
									<?php for($i=$to_count;$i<5;$i++):?>
										<div>
											<?php $e = '';$msg = '';
											if(!empty($error['InquiryTo']['to_address'][$i])) {
												$e='ui-state-error';
												$msg = $error['InquiryTo']['to_address'][$i];
											} ?>
											<span style="white-space: nowrap;">名称
												<input type="text" class="form-control input-medium" 
															 placeholder=""
															 value=""
															 name="InquiryTo[<?php echo $i+1;?>][to_name]" style="display: inline-block"> </span>
											<span style="white-space: nowrap;">アドレス 
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryTo[<?php echo $i+1;?>][to_address]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">質問No:
												<input type="text" class="form-control input-mini <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryTo[<?php echo $i+1;?>][to_question_no]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">回答No:
												<input type="text" class="form-control input-mini <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryTo[<?php echo $i+1;?>][to_answer_no]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">備考:
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryTo[<?php echo $i+1;?>][to_comment]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;"> <?php echo $msg;?>
										</div>
									<?php endfor;?>
								</div>
								<div class="form-group">
									<label>BCC [自動返信メール、管理者メール]</label>
									<?php foreach($mail_bcc as $idx => $bcc):?>
										<?php $bcc_count++;?>
										<div>
											<?php $e = '';$msg = '';
											if(!empty($error['InquiryBcc']['bcc_address'][$bcc_count])) {
												$e='ui-state-error';
												$msg = $error['InquiryBcc']['bcc_address'][$bcc_count];
											} ?>
											<span style="white-space: nowrap;">名称
												<input type="text" class="form-control input-medium"
															 placeholder=""
															 value="<?php echo $bcc->bcc_name;?>"
															 name="InquiryBcc[<?php echo $idx;?>][bcc_name]" style="display: inline-block"> </span>
											<span style="white-space: nowrap;">アドレス 
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $bcc->bcc_address;?>"
															 name="InquiryBcc[<?php echo $idx;?>][bcc_address]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;"> <?php echo $msg;?>
										</div>
									<?php endforeach;?>
									<?php for($i=$bcc_count;$i<5;$i++):?>
										<div>
											<?php $e = '';$msg = '';
											if(!empty($error['InquiryBcc']['bcc_address'][$i])) {
												$e='ui-state-error';
												$msg = $error['InquiryBcc']['bcc_address'][$i];
											} ?>
											<span style="white-space: nowrap;">名称
												<input type="text" class="form-control input-medium"
															 placeholder=""
															 value=""
															 name="InquiryBcc[<?php echo $i+1;?>][bcc_name]" style="display: inline-block"> </span>
											<span style="white-space: nowrap;">アドレス 
												<input type="text" class="form-control input-medium <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryBcc[<?php echo $i+1;?>][bcc_address]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;"> <?php echo $msg;?>
										</div>
									<?php endfor;?>
								</div>
								<div class="form-group">
									<label>返信用メールヘッダー [自動返信メールにのみ適用]</label>
									<textarea class="form-control" 
														name="InquiryMail[mail_header]" rows="12"><?php echo $mail->mail_header;?></textarea>
								</div>
								<div class="form-group">
									<label>返信用メールフッター [自動返信メールにのみ適用]</label>
									<textarea class="form-control" 
														name="InquiryMail[mail_footer]" rows="12"><?php echo $mail->mail_footer;?></textarea>
								</div>
							<?php
							$data = InquiryMailSub::model()->findAll(
								array("condition" => "delete_flag = 0 AND sub_inquiry_id = {$model->inquiry_id}",
											"order" => "sub_rank"
								));
							?>
							<?php foreach ($data as $sub):?>
								<input type="hidden"
											 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_id]"
											 value="<?php echo $sub->sub_id;?>">
								<input type="hidden"
											 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_title]"
											 value="<?php echo $sub->sub_title;?>">
								<input type="hidden"
											 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_rank]"
											 value="<?php echo $sub->sub_rank;?>">
							<div class="form-group">
								<span><?php echo $sub->sub_title;?>用 質問No:
												<input type="text" class="form-control input-mini"
															 placeholder=""
															 value="<?php echo $sub->sub_question_no;?>"
															 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_question_no]" style="display: inline-block"></span>
											<span style="white-space: nowrap;">回答No:
												<input type="text" class="form-control input-mini"
															 placeholder=""
															 value="<?php echo $sub->sub_answer_no;?>"
															 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_answer_no]" style="display: inline-block"></span>
							</div>
							<div class="form-group">
								<?php $e = '';$msg = '';
								if(!empty($error['InquiryMailSub']['sub_from_address'])) {
									$e='ui-state-error';
									$msg = $error['InquiryMailSub']['sub_from_address'];
								} ?>
								<label>FROM (<?php echo $sub->sub_title;?>用)</label>
								<div>
										<span style="white-space: nowrap;">名称
											<input type="text" class="form-control input-medium"
														 placeholder="差出人"
														 value="<?php echo $sub->sub_from_name;?>"
														 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_from_name]" style="display: inline-block"> </span>
										<span style="white-space: nowrap;">アドレス
											<input type="text" class="form-control input-medium"
														 placeholder="xxxx@unext.jp"
														 value="<?php echo $sub->sub_from_address;?>"
														 name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_from_address]" style="display: inline-block"></span>
										<span class="ui-state-error-text" style="white-space: nowrap;">

								</div>
							</div>
							<div class="form-group">
								<label>返信用メールヘッダー (<?php echo $sub->sub_title;?>用) [自動返信メールにのみ適用]</label>
									<textarea class="form-control"
														name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_header]" rows="12"><?php echo $sub->sub_header;?></textarea>
							</div>
							<div class="form-group">
								<label>返信用メールフッター (<?php echo $sub->sub_title;?>用) [自動返信メールにのみ適用]</label>
									<textarea class="form-control"
														name="InquiryMailSub[<?php echo $sub->sub_id;?>][sub_footer]" rows="12"><?php echo $sub->sub_footer;?></textarea>
							</div>
							<?php endforeach;?>

							<div class="form-group">
								<label>注意事項</label>
									<textarea class="form-control"
														name="Inquiry[inquiry_notes]" rows="12"><?php echo $model->inquiry_notes;?></textarea>
							</div>

								<div class="form-group">
									質問 (*)
								</div>
								<?php foreach($question as $q):?>
								<?php $question_count++;?>
									<?php $e = '';
									if(!empty($error['InquiryQuestion'][$question_count]['question_name'])) {
										$e='ui-state-error';
									} ?>
									<input type="hidden"
												 name="InquiryQuestion[<?php echo $question_count;?>][question_id]"
												 value="<?php echo $q->question_id;?>">
									<div class="form-group Q<?php echo $question_count;?>">
												<span style="white-space: nowrap;">質問
													<input type="text" class="form-control input-mini"
																 value="<?php echo $question_count;?>"
																 name="InquiryQuestion[<?php echo $question_count;?>][question_rank]" style="display: inline-block"></span>　
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
														 <?php echo empty($q->question_require)?'':'checked';?>
														 name="InquiryQuestion[<?php echo $question_count;?>][question_require]"
														 value="1" style="display: inline-block">必須　<span></span>
										</label>
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
												<?php echo ($model->inquiry_name_rank==$q->question_rank)?'checked':'';?>
														 name="InquiryQuestion[<?php echo $question_count;?>][is_name]"
														 value="1" style="display: inline-block">お名前欄　<span></span>
										</label>
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
												<?php echo ($model->inquiry_mail_rank==$q->question_rank)?'checked':'';?>
														 name="InquiryQuestion[<?php echo $question_count;?>][is_mail]"
														 value="1" style="display: inline-block">メールアドレス欄　<span></span>
										</label>
									</div>
									<div class="form-group Q1">
												<span style="white-space: nowrap;">
													質問内容：<input type="text" class="form-control input-xlarge <?php echo $e;?>"
																			placeholder=""
																			value="<?php echo $q->question_name;?>"
																			name="InquiryQuestion[<?php echo $question_count;?>][question_name]" style="display: inline-block"></span>
									</div>
									<div class="form-group">
										<?php
										$text = '';
										$radio = '';
										$checkbox = '';
										$textarea = '';
										$style = '';
										if($q->question_type=='Text') {
											$text = ' selected';
											$style = ' style="display:none"';
										} else if($q->question_type=='Radio') {
											$radio = ' selected';
										} else if($q->question_type=='Checkbox') {
											$checkbox = ' selected';
										} else if($q->question_type=='TextArea') {
											$textarea = ' selected';
											$style = ' style="display:none"';
										}
										?>
												<span style="white-space: nowrap;">
													種別：
												<select
													id="answer[<?php echo $question_count;?>]"
													name="InquiryQuestion[<?php echo $question_count;?>][question_type]" class="form-control input-small" style="display: inline-block">
													<option value="Text" <?php echo $text;?>>Text</option>
													<option value="Radio" <?php echo $radio;?>>Radio</option>
													<option value="Checkbox" <?php echo $checkbox;?>>Checkbox</option>
													<option value="TextArea" <?php echo $textarea;?>>TextArea</option>
												</select></span>
									</div>
									<div class="form-group">
										<?php
										$item1 = '';
										$item2 = '';
										$item3 = '';
										$item4 = '';
										$item5 = '';
										if(empty($q->question_validation)) {
											$item1 = ' selected';
										} else if($q->question_validation=='電話') {
											$item2 = ' selected';
										} else if($q->question_validation=='メール') {
											$item3 = ' selected';
										} else if($q->question_validation=='カタカナ') {
											$item4 = ' selected';
										} else if($q->question_validation=='確認') {
											$item5 = ' selected';
										}
										?>
										<span style="white-space: nowrap;">
													チェック内容：
												<select
													name="InquiryQuestion[<?php echo $question_count;?>][question_validation]" class="form-control input-small" style="display: inline-block">
													<option value="" <?php echo $item1;?>></option>
													<option value="電話" <?php echo $item2;?>>電話</option>
													<option value="メール" <?php echo $item3;?>>メール</option>
													<option value="確認" <?php echo $item5;?>>確認</option>
													<option value="カタカナ" <?php echo $item4;?>>カタカナ</option>
												</select></span>
									</div>

									<div name="answer[<?php echo $question_count;?>]" <?php echo $style;?>>
										<label>回答：</label>
										<div class="form-group">
											<?php
											if(!empty($question_answer[$question_count])) {
												$answers = $question_answer[$question_count];
											} else {
												if(!empty($q->question_id)) {
													$answers = InquiryAnswer::model()->findAll(
														array(
															'condition' => "answer_question_id = {$q->question_id} AND delete_flag = 0",
															'order'     => 'answer_rank'
														) );
												} else {
													$answers = array();
												}
											}
											$answer_count = 0;
											?>
											<?php foreach($answers as $answer):?>
												<?php $answer_count ++;?>
												<?php $e = '';
												if(!empty($error['InquiryAnswer'][$question_count][$answer_count]['answer_name'])) {
													$e='ui-state-error';
												} ?>
												<input type="hidden"
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $answer_count;?>][answer_id]"
															 value="<?php echo $answer->answer_id;?>">
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-mini"
															 value="<?php echo $answer->answer_rank;?>"
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $answer_count;?>][answer_rank]" style="display: inline-block"> </span>
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-xlarge <?php echo $e;?>"
															 placeholder=""
															 value="<?php echo $answer->answer_name;?>"
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $answer_count;?>][answer_name]" style="display: inline-block"></span><br>
											<?php endforeach;?>

											<?php for($j=$answer_count;$j<10;$j++):?>
												<?php $e = '';
												if(!empty($error['InquiryAnswer'][$question_count][$j]['answer_name'])) {
													$e='ui-state-error';
												} ?>
												<input type="hidden"
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $j+1;?>][answer_id]"
															 value="">
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-mini"
															 value="<?php echo $j+1;?>"
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $j+1;?>][answer_rank]" style="display: inline-block"> </span>
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-xlarge <?php echo $e;?>"
															 placeholder=""
															 value=""
															 name="InquiryAnswer[<?php echo $question_count;?>][<?php echo $j+1;?>][answer_name]" style="display: inline-block"></span><br>
											<?php endfor;?>
										</div>
									</div>
								<?php endforeach;?>
								
								<?php for($i=$question_count;$i<15;$i++):?>
									<div class="form-group Q<?php echo $i+1;?>">
												<span style="white-space: nowrap;">質問
													<input type="text" class="form-control input-mini"
																 value="<?php echo $i+1;?>"
																 name="InquiryQuestion[<?php echo $i+1;?>][question_rank]" style="display: inline-block"></span>　
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
														 name="InquiryQuestion[<?php echo $i+1;?>][question_require]"
														 value="1" style="display: inline-block">必須　<span></span>
										</label>
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
														 name="InquiryQuestion[<?php echo $i+1;?>][is_name]"
														 value="-1" style="display: inline-block">お名前欄　<span></span>
										</label>
										<label class="mt-checkbox mt-checkbox-outline">
											<input type="checkbox" class="form-control"
														 name="InquiryQuestion[<?php echo $i+1;?>][is_mail]"
														 value="-1" style="display: inline-block">メールアドレス欄　<span></span>
										</label>
									</div>
									<div class="form-group Q1">
												<span style="white-space: nowrap;">
													質問内容：<input type="text" class="form-control input-xlarge"
																			placeholder=""
																			value=""
																			name="InquiryQuestion[<?php echo $i+1;?>][question_name]" style="display: inline-block"></span>
									</div>
									<div class="form-group Q1">
												<span style="white-space: nowrap;">
													種別：
												<select
													id="answer[<?php echo $i+1;?>]"
													name="InquiryQuestion[<?php echo $i+1;?>][question_type]" class="form-control input-small" style="display: inline-block">
													<option value="Text">Text</option>
													<option value="Radio">Radio</option>
													<option value="Checkbox">Checkbox</option>
													<option value="TextArea">TextArea</option>
												</select></span>
									</div>
									<div class="form-group">
										<span style="white-space: nowrap;">
													チェック内容：
												<select
													name="InquiryQuestion[<?php echo $i+1;?>][question_validation]" class="form-control input-small" style="display: inline-block">
													<option value=""></option>
													<option value="電話">電話</option>
													<option value="メール">メール</option>
													<option value="確認">確認</option>
													<option value="カタカナ">カタカナ</option>
												</select></span>
									</div>
									<div name="answer[<?php echo $i+1;?>]" style="display: none">
										<label>回答：</label>
										<div class="form-group">
											<?php for($j=0;$j<10;$j++):?>
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-mini"
															 value="<?php echo $j+1;?>"
															 name="InquiryAnswer[<?php echo $i+1;?>][<?php echo $j+1;?>][answer_rank]" style="display: inline-block"> </span>
												<span style="white-space: nowrap;">
												<input type="text" class="form-control input-xlarge"
															 placeholder=""
															 value=""
															 name="InquiryAnswer[<?php echo $i+1;?>][<?php echo $j+1;?>][answer_name]" style="display: inline-block"></span><br>
											<?php endfor;?>
										</div>
									</div>
								<?php endfor;?>

							</div>
							<div class="form-actions right">
								<button type="submit" class="btn blue">確認する</button>
							</div>
							<input type="hidden" name="mode" value="<?php echo $mode;?>">
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php if(false):?>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-body form">
							<form role="form" action="?cmd=conf<?php echo $param;?>" method="post">
								<span class="caption-subject font-red-mint sbold"><?php echo CHtml::errorSummary($model); ?></span>

								<input type="hidden" name="inquiry_id" value="<?php echo $model->inquiry_id;?>">
								<div class="form-body">
									<div class="form-group">
										<label>店舗名</label>
										<input type="text" class="form-control" placeholder=""
													 name="shop_name"
													 value="<?php echo $model->shop_name;?>"
										>
									</div>

									<div class="form-group">
										<label>電話番号</label>
										<input type="text" class="form-control" placeholder=""
													 name="shop_phone"
													 value="<?php echo $model->shop_phone;?>"
										>
									</div>

									<div class="form-group">
										<label>営業時間</label>
										<input type="text" class="form-control" placeholder=""
													 name="shop_hours"
													 value="<?php echo $model->shop_hours;?>"
										>
									</div>

									<div class="form-group">
										<label>都道府県</label>
										<div>
											<select name="shop_prefecture" class="form-control input-small prefecture" style="display: inline-block">
												<option value="">都道府県</option>
												<?php foreach($option['prefecture'] as $item):?>
													<?php
													$selected = '';
													if(!empty($model->shop_prefecture)) {
														if($item->prefecture_name == $model->shop_prefecture) {
															$selected = 'selected';
														}
													}
													?>
													<option value="<?php echo $item->prefecture_name;?>" <?php echo $selected;?>><?php echo $item->prefecture_name;?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label>住所</label>
										<input type="text" class="form-control" placeholder=""
													 name="shop_address"
													 value="<?php echo $model->shop_address;?>"
										>
									</div>
									<div class="form-group">
										<label>緯度経度（緯度経度を入力した場合はこちらが優先されます）</label>
										<div>
										<span style="white-space: nowrap;">緯度
											<input type="text" class="form-control input-small"
														 placeholder=""
														 name="shop_lat"
														 value="<?php echo $model->shop_lat;?>"
														 style="display: inline-block"> </span>
										<span style="white-space: nowrap;">経度
											<input type="text" class="form-control input-small"
														 placeholder=""
														 name="shop_lng"
														 value="<?php echo $model->shop_lng;?>"
														 style="display: inline-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label>カテゴリー</label>
										<div class="mt-checkbox-inline">
											<?php
											$categories = ShopCategory::model()->getCategoryMasterIds($model->shop_id);
											?>
											<?php foreach($option['category'] as $item):?>
												<?php
												$checked = '';
												if(!empty($categories)) {
													if(in_array($item->category_id, $categories)) {
														$checked = 'checked';
													}
												}
												?>

												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" class="category" name="categories[]" value="<?php echo $item->category_id;?>" <?php echo $checked;?>> <?php echo $item->category_name;?>
													<span></span>
												</label>
											<?php endforeach;?>
										</div>
									</div>
									<div class="form-group">
										<label>サービス</label>
										<div class="mt-checkbox-inline">
											<?php
											$services = ShopService::model()->getServiceMasterIds($model->shop_id);
											?>
											<?php foreach($option['service'] as $item):?>
												<?php
												$checked = '';
												if(!empty($services)) {
													if(in_array($item->service_id, $services)) {
														$checked = 'checked';
													}
												}
												?>

												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" class="category" name="services[]" value="<?php echo $item->service_id;?>" <?php echo $checked;?>> <?php echo $item->service_name;?>
													<span></span>
												</label>
											<?php endforeach;?>
										</div>
									</div>
								</div>
								<div class="form-actions right">
									<button type="submit" class="btn blue">確認する</button>
								</div>
								<input type="hidden" name="cmd" value="<?php echo $cmd;?>">
								<input type="hidden" name="mode" value="<?php echo $mode;?>">
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php endif;?>

	</div>
	<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<?php $this->renderPartial('/admin/inquiry/footer', array('action' => $action));?>

</body>
</html>

<script>
	$(function(){
		$('select').on('change',function(){
			var id = $(this).attr('id');
			var type = $(this).children("option:selected").text();
			if(type=='Text' || type=='TextArea') {
				$('div[name="'+id+'"]').css('display','none');
			} else {
				$('div[name="'+id+'"]').css('display','block');
			}
		});
	});

</script>