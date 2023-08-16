<?php
/* @var $this SiteController */

$this->title = 'お問い合わせフォーム-'.$model->inquiry_title;

if(empty($isSecure)) {
	$isSecure = false;
}

?>
<?php $this->renderPartial('//site/contact/head', array('model'=>$model, 'isSecure' => $isSecure)); ?>

<?php $this->renderPartial('//site/contact/header', array('model'=>$model, 'isSecure' => $isSecure)); ?>

<?php if(!empty($option['status'])):?>
    <!-- Page Content -->
    <section class="p-t-120 p-b-20">
        <div class="container">
            <div class="col-md-8 m-b-100 center">
                <div class="heading section-title">
                    <h2 class="text-medium-light t200">Thanks for Send Message</h2>
                    <p class="m-t-50 m-b-50 t400">Message sent successfully.</p>
                    <a class="btn btn-light btn-rounded" href="/">Back to Home Screen</a>
                </div>
            </div>
        </div>
    </section><!-- end: Page Content -->
<?php else:?>

  <!-- Page Content -->
  <section id="page-content" class="p-b-0">
    <div class="container">

      <!-- content -->
      <div class="content col-md-8 center">
        <div class="col-md-12">
          <p>お問い合わせはこちらのフォームより必要事項を記入の上、「Send message」ボタンを押してください。<br>
            3営業日以内に返信させていただきますが、弊社より返信がない場合は <a class="border-bottom" href=" ">メールアドレス</a> までお手数ですがご連絡願います。</p>
        </div>
        <div class="col-md-12">
          <form class="widget-contact-form" action="include/contact-form.php" role="form" method="post">
            <div class="row">
              <?php foreach($option['question'] as $question):?>
              <?php
              $require = '';
              $class = '';
              if($question->question_require==1) {
                $require = '<span class="text-red">*</span>';
                $class = 'required';
              }
              if(!empty($model->error[$question->question_rank])) {
                $class .= ' error';
              }
              ?>
              <?php if($question->question_type=='Text'):?>
                <?php
                $value = '';
                if(!empty($model->answer[$question->question_rank])) {
                  $value = $model->answer[$question->question_rank];
                }
                ?>
                <div class="form-group col-sm-12">
                  <label class="t400" for="name"><?php echo $question->question_name;?>&nbsp;&nbsp;&nbsp;<?php echo $require;?></label>
                  <input type="text" id="element-<?php echo $question->question_rank;?>" name="answer[<?php echo $question->question_rank;?>]" value="<?php echo $value;?>" class="<?php echo $class;?> form-control"
                         placeholder="<?php echo $question->question_placeholder;?>">
                </div>
              <?php elseif($question->question_type=='Select'):?>
                <?php
                $answers = InquiryAnswer::model()->findAll(
                  array('condition'=>"answer_question_id = {$question->question_id} AND delete_flag = 0",
                    'order'=>'answer_rank'));
                ?>
                <?php
                $value = '';
                if(!empty($model->answer[$question->question_rank])) {
                  $value = $model->answer[$question->question_rank];
                }
                ?>
                <div class="form-group col-sm-12">
                  <label class="t400" for="phone"><?php echo $question->question_name;?>&nbsp;&nbsp;&nbsp;<?php echo $require;?></label>
                  <select id="template-contactform-service" name="answer[<?php echo $question->question_rank;?>]" class="sm-form-control <?php echo $class;?>">
                    <?php foreach($answers as $answer):?>
                      <?php
                      $selected = '';
                      if(!empty($value)) {
                        if($answer->answer_value == $value) {
                          $selected = ' selected';
                        }
                      }
                      ?>
                      <option <?php echo $selected;?> value="<?php echo $answer->answer_value;?>"><?php echo $answer->answer_name;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              <?php elseif($question->question_type=='Radio'):?>
                <?php
                $value = '';
                if(!empty($model->answer[$question->question_rank])) {
                  $value = $model->answer[$question->question_rank];
                }
                ?>
                <?php
                $answers = InquiryAnswer::model()->findAll(
                  array('condition'=>"answer_question_id = {$question->question_id} AND delete_flag = 0",
                    'order'=>'answer_rank'));
                ?>
                <tr id="Q<?php echo $question->question_rank;?>" class="formerror">
                  <th><?php echo $question->question_name;?>&nbsp;&nbsp;&nbsp;<?php echo $require;?></th>
                  <td>
                    <?php if(!empty($model->error[$question->question_rank])):?>
                      <p class="errorelement"><?php echo $model->error[$question->question_rank];?></p>
                    <?php endif;?>
                    <?php foreach($answers as $answer):?>
                      <?php
                      $checked = '';
                      if($value==$answer->answer_value) {
                        $checked = ' checked';
                      }
                      ?>
                      <div class="radio">
                        <label>
                          <input type="radio" name="answer[<?php echo $question->question_rank;?>]" value="<?php echo $answer->answer_value;?>" <?php echo $checked;?>>
                          <?php echo $answer->answer_name;?>
                        </label>
                      </div>
                    <?php endforeach;?>
                  </td>
                </tr>
              <?php elseif($question->question_type=='Checkbox'):?>
                <?php
                $answers = InquiryAnswer::model()->findAll(
                  array('condition'=>"answer_question_id = {$question->question_id} AND delete_flag = 0",
                    'order'=>'answer_rank'));
                ?>
                <?php
                $value = '';
                if(!empty($model->answer[$question->question_rank])) {
                  $value = $model->answer[$question->question_rank];
                }
                ?>
                <tr id="Q<?php echo $question->question_rank;?>" class="formerror">
                  <th><?php echo $question->question_name;?>&nbsp;&nbsp;&nbsp;<?php echo $require;?></th>
                  <td>
                    <?php if(!empty($model->error[$question->question_rank])):?>
                      <p class="errorelement"><?php echo $model->error[$question->question_rank];?></p>
                    <?php endif;?>
                    <?php foreach($answers as $answer):?>
                      <?php
                      $checked = '';
                      if(!empty($value[$answer->answer_rank])) {
                        $checked = ' checked';
                      }
                      ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="answer[<?php echo $question->question_rank;?>][<?php echo $answer->answer_rank;?>]" value="<?php echo $answer->answer_value;?>" <?php echo $checked;?>>
                          <?php echo $answer->answer_name;?>
                        </label>
                      </div>
                    <?php endforeach;?>
                  </td>
                </tr>
              <?php elseif($question->question_type=='TextArea'):?>
              <?php
              $value = '';
              if(!empty($model->answer[$question->question_rank])) {
                $value = $model->answer[$question->question_rank];
              }
              ?>
            </div>
            <div class="form-group">
              <label class="t400" for="message"><?php echo $question->question_name;?>&nbsp;&nbsp;&nbsp;<?php echo $require;?></label>
              <textarea class="<?php echo $class;?> form-control" id="element-<?php echo $question->question_rank;?>" name="answer[<?php echo $question->question_rank;?>]" rows="9"
                        placeholder="<?php echo $question->question_placeholder;?>"><?php echo $value;?></textarea>
            </div>
            <?php endif;?>
            <?php endforeach;?>
            <div class="form-group text-center p-t-40 m-b-0">
              <button class="btn btn-rounded" type="submit" id="form-submit">Send message</button>
            </div>
          </form>
        </div>
      </div><!-- end: content -->

    </div>
  </section><!-- end: Page Content -->

<?php endif?>

<?php $this->renderPartial('//site/contact/footer', array('isSecure' => $isSecure)); ?>

<?php if(!empty($model->inquiry_script)):?>
	<script>
		<?php echo $model->inquiry_script;?>
	</script>
<?php endif;?>
