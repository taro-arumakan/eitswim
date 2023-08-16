<?php
$this->title = 'お問い合わせ履歴・詳細';
$mode = 'validate';
$param = '';
if(!empty($id)) {
	$param = '?id='.$id;
}

$this->title = $model->subject;

if(!isset($model->category)) {
	$action = 'dashboard';
} else if($model->category === 'Advertisement') {
	$action = 'advertisement';
} else if($model->category === 'Support') {
	$action = 'support';
} else if($model->category === 'Inquiry') {
	$action = 'inquiry';
} else if($model->category === 'Other') {
	$action = 'others';
} else {
	$action = 'dashboard';
}

?>
<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action, 'cmd' => $cmd));?>
<?php
//URLに付加する
$ses = '?'.date("YmdHis");
?>

<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
  <div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">
      <!--Begin::Section-->
      <div class="row">
        <div class="col-xl-12">
          <!--begin:: Widgets/All Mails-->
          <div class="m-portlet m-portlet--full-height ">
            <div class="m-portlet__head">
              <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                  <h3 class="m-portlet__head-text"> <?php echo $this->title;?> </h3>
                </div>
              </div>
            </div>
            <div class="m-portlet__body">
              <div class="tab-pane" id="m_widget11_tab2_content">
                <!--begin::Widget 11-->
                <div class="m-widget11">
                  <form method="post" action="">
                    <input type="hidden" id="mode" name="mode" value="save">
                    <div class="table-responsive">
                      <!--begin::Table-->
                      <table class="table">
                        <colgroup>
                          <col width="20%">
                          <col width="auto">
                        </colgroup>
                        <!--begin::Tbody-->
                        <tbody>
                        <tr>
                          <td>管理番号</td>
                          <input type="hidden"
                                 name="history_id"
                                 value="<?php echo $model->history_id;?>">
                          <td><?php echo $model->history_id;?></td>
                        </tr>
                        <tr>
                          <td>受信日時</td>
                          <td><?php echo $model->create_date;?></td>
                        </tr>
                        <tr>
                          <td>お名前</td>
                          <td><?php echo $model->history_name;?></td>
                        </tr>
                        <tr>
                          <td>メールアドレス</td>
                          <td><?php echo $model->history_mail;?></td>
                        </tr>
                        <tr>
                          <td>カテゴリー</td>
                          <td><?php echo $model->category;?></td>
                        </tr>
                        <tr>
                          <td>内容</td>
                          <td><?php echo nl2br($model->message);?></td>
                        </tr>
                        <tr>
													<?php
													$sel0 = 'selected';
													$sel1 = '';
													$sel2 = '';
													if($model->history_response) {
														if($model->history_response==1) {
															$sel0 = '';
															$sel1 = 'selected';
															$sel2 = '';
														} else if($model->history_response==2) {
															$sel0 = '';
															$sel1 = '';
															$sel2 = 'selected';
														}
													}
													?>
                          <td>対応状況</td>
                          <td>
                            <select class="form-control m-input"
                                    name="history_response"
                                    id="exampleSelect1">
                              <option <?php echo $sel0;?> value="0">未対応</option>
                              <option <?php echo $sel1;?> value="1">対応済</option>
                              <option <?php echo $sel2;?> value="2">保留</option>
                            </select>
                          </td>
                        </tr>
                        </tbody>
                        <!--end::Tbody-->
                      </table>
                      <!--end::Table-->
                    </div>
                    <div class="row align-items-center mb-3">
                      <div class="col-lg-6 m--valign-middle">
                        <button type="submit" id="update" class="btn btn-success m-btn--wide">更新</button>
                      </div>
                      <div class="col-lg-6 m--align-right">
                        <button id="delete" class="btn m-btn--gradient-from-warning m-btn--gradient-to-danger m-btn--wide">削除</button>
                      </div>
                    </div>
                  </form>
                </div>
                <!--end::Widget 11-->
              </div>
            </div>
          </div>
          <!--end:: Widgets/Application Sales-->
        </div>
      </div>
      <!--End::Section-->
    </div>
  </div>
  <!--
		</div>
		-->
</div>
<!-- end::Body -->


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

      $('button#update').on('click',function(){
        $('input#mode').val('update');
      })

      $('button#delete').click(function(e) {
        swal({
          title: '削除しますか？',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'OK',
          cancelButtonText: 'CANCEL',
          reverseButtons: true
        }).then(function(result){
          if (result.value) {
            $('input#mode').val('delete');
            $('form').submit();
          } else if (result.dismiss === 'cancel') {
            return false;
          }
        });
        return false;

      });



    });
  });
</script>

</body>
</html>
