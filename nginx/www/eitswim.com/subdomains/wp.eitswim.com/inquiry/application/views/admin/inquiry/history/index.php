<?php $this->renderPartial('/admin/inquiry/header', array('action' => $action));?>
<?php
//URLに付加する
$ses = '?'.date("YmdHis");

if(!empty($_GET['function'])) {
	$function = $_GET['function'];
	$title = ucfirst($function);
}

?>
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
  <div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
      <div class="d-flex align-items-center">
        <div class="mr-auto">
          <h3 class="m-subheader__title "> <?php echo $title;?> </h3>
        </div>
        <div> <span class="m-subheader__daterange" id="m_dashboard_daterangepicker"> <span class="m-subheader__daterange-label"> <span class="m-subheader__daterange-title"></span> <span class="m-subheader__daterange-date m--font-brand"></span> </span> <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill"> <i class="la la-angle-down"></i> </a> </span> </div>
      </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
      <!--Begin::Section-->
      <div class="row">
        <div class="col-xl-12">
          <!--begin:: Widgets/All Mails-->
          <div class="m-portlet m-portlet--full-height ">
            <div class="m-portlet__head">
              <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                  <h3 class="m-portlet__head-text"> <?php echo $title;?> Mails </h3>
                </div>
              </div>
            </div>
            <div class="m-portlet__body">
              <form class="m-form" method="post" action="">
                <input type="hidden" name="mode" value="delete">
                <div class="tab-pane" id="m_widget11_tab2_content">
                  <!--begin::Widget 11-->
                  <div class="m-widget11">
                    <div class="table-responsive">
                      <div class="m-form__group form-group">
                        <!--begin::Table-->
                        <table class="table">
                          <colgroup>
                            <col width="2%">
                            <col width="12%">
                            <col width="15%">
                            <col width="auto">
                            <col width="10%">
                            <col width="10%">
                          </colgroup>
                          <!--begin::Thead-->
                          <thead>
                          <tr>
                            <td> <div class="m-checkbox-list"><label class="m-checkbox">
                                  <input id="all"
                                         type="checkbox"><span></span></label></div> </td>
                            <td> No </td>
                            <td> 受信日時 </td>
                            <td> Subject </td>
                            <td> Category </td>
                            <td> 対応状況 </td>
                          </tr>
                          </thead>
                          <!--end::Thead-->
                          <!--begin::Tbody-->
                          <tbody>
                          <!--end::Thead-->
                          <!--begin::Thead-->
                          <thead>
													<?php foreach($data as $item):?>
                            <tr>
                              <td><div class="m-checkbox-list"><label class="m-checkbox">
                                    <input
                                        name="history_ids[<?php echo $item->history_id;?>]"
                                        id="chk" type="checkbox"><span id="<?php echo $item->history_id;?>"></span></label></div></td>
                              <td><?php echo $item->history_id;?></td>
                              <td> <?php echo $item->create_date;?> </td>
                              <td><a href="<?php echo RAH();?>detail?id=<?php echo $item->history_id;?>"><?php echo empty($item->subject)?'No Subject':$item->subject;?></a></td>

                              <td> <?php echo $item->category;?> </td>
                              <td> <?php echo InquiryHistory::model()->getResponseName($item->history_response);?></td>
                            </tr>
													<?php endforeach;?>
                          </thead>
                          <!--end::Thead-->
                          </tbody>
                          <!--end::Tbody-->
                        </table>
                        <!--end::Table-->
                      </div>
                    </div>
                  </div>
                  <!--end::Widget 11-->
                </div>
                <div class="row align-items-center mb-3">
                  <div class="col-lg-6 m--valign-middle">
                    <button type="button" class="btn btn-outline-metal">Prev</button>
                  </div>
                  <div class="col-lg-6 m--align-right">
                    <button type="button" class="btn btn-outline-metal">Next</button>
                  </div>
                </div>
                <div class="row align-items-center">
                  <div class="col-lg-12 m--align-center">
                    <button id="delete" class="btn m-btn m-btn--gradient-from-warning m-btn--gradient-to-danger">Delete</button>
                  </div>
                </div>
              </form>
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
    });
  });
</script>

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

    /*
		 * 選択ALL,解除
		 */
    $("input#all").click(function(){
      if($("input#all:checked").val()) {
        //チェックALL
        //$('span#1').click();
        //$('input#chk').click();
        checkAll(true);
      } else {
        //$('span#1').click();
        //$('input#chk').click();
        //チェック解除
        checkAll(false);
      }
    });

    /*
		 * チェック操作
		 * checked:true つける
		 * checked:false 外す
		 */
    function checkAll(checked) {
      $("input[type=checkbox]").prop('checked', checked);
      if(checked) {
        //$("input[type=checkbox]").parent().attr('class','checked');
      } else {
        //$("input[type=checkbox]").parent().attr('class','');
      }
    }

    $('button#delete').click(function(e) {
      var checked = false;
      $("input[id=chk]").each(function(i) {
        var chk = $(this).prop('checked');
        if(chk) {
          checked = true;
        }
      });
      if(checked) {
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
      } else {
        swal("警告", "項目を選択してください。", "warning");
      }
      return false;
    });

  });
</script>

</body>
</html>
