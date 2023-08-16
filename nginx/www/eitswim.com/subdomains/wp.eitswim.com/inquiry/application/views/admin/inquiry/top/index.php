<?php $this->renderPartial('/admin/inquiry/header');?>

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <!-- BEGIN: Subheader -->
            <div class="m-subheader ">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <h3 class="m-subheader__title "> Dashboard </h3>
                    </div>
                    <div> <span class="m-subheader__daterange" id="m_dashboard_daterangepicker"> <span class="m-subheader__daterange-label"> <span class="m-subheader__daterange-title"></span> <span class="m-subheader__daterange-date m--font-brand"></span> </span> <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill"> <i class="la la-angle-down"></i> </a> </span> </div>
                </div>
            </div>
            <!-- END: Subheader -->
            <div class="m-content">
                <!--begin:: Widgets/Stats-->
                <div class="m-portlet ">
                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <!--begin::Advertisement-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title"> Advertisement </h4>
                                        <br>
                                        <span class="m-widget24__desc"> Count </span> <span class="m-widget24__stats m--font-brand"> 100 </span>
                                        <div class="m--space-10"></div>
                                    </div>
                                </div>
                                <!--end::Advertisement-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <!--begin::Support-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title"> Support </h4>
                                        <br>
                                        <span class="m-widget24__desc"> Count </span> <span class="m-widget24__stats m--font-info"> 1349 </span>
                                        <div class="m--space-10"></div>
                                    </div>
                                </div>
                                <!--end::Support-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <!--begin::New Orders-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title"> Inquiry </h4>
                                        <br>
                                        <span class="m-widget24__desc"> Count </span> <span class="m-widget24__stats m--font-danger"> 567 </span>
                                        <div class="m--space-10"></div>
                                    </div>
                                </div>
                                <!--end::New Orders-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <!--begin::Others-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title"> Others </h4>
                                        <br>
                                        <span class="m-widget24__desc"> Count </span> <span class="m-widget24__stats m--font-success"> 276 </span>
                                        <div class="m--space-10"></div>
                                    </div>
                                </div>
                                <!--end::Others-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Stats-->
                <!--Begin::Section-->
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin:: Widgets/Support Cases-->
                        <div class="m-portlet  m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text"> Support Cases </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    <ul class="m-portlet__nav">
                                        <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true"> <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle"> <i class="la la-ellipsis-h m--font-brand"></i> </a>
                                            <div class="m-dropdown__wrapper"> <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
                                                            <ul class="m-nav">
                                                                <li class="m-nav__section m-nav__section--first"> <span class="m-nav__section-text"> Quick Actions </span> </li>
                                                                <li class="m-nav__item"> <a href="" class="m-nav__link"> <i class="m-nav__link-icon flaticon-share"></i> <span class="m-nav__link-text"> Activity </span> </a> </li>
                                                                <li class="m-nav__item"> <a href="" class="m-nav__link"> <i class="m-nav__link-icon flaticon-share"></i> <span class="m-nav__link-text"> Activity </span> </a> </li>
                                                                <li class="m-nav__item"> <a href="" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Messages </span> </a> </li>
                                                                <li class="m-nav__item"> <a href="" class="m-nav__link"> <i class="m-nav__link-icon flaticon-info"></i> <span class="m-nav__link-text"> FAQ </span> </a> </li>
                                                                <li class="m-nav__item"> <a href="" class="m-nav__link"> <i class="m-nav__link-icon flaticon-lifebuoy"></i> <span class="m-nav__link-text"> Support </span> </a> </li>
                                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                                <li class="m-nav__item"> <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm"> Cancel </a> </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget16">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="m-widget16__head">
                                                <div class="m-widget16__item"> <span class="m-widget16__sceduled"> Type </span> <span class="m-widget16__amount m--align-right"> Amount </span> </div>
                                            </div>
                                            <div class="m-widget16__body">
                                                <!--begin::widget item-->
                                                <div class="m-widget16__item"> <span class="m-widget16__date"> EPS </span> <span class="m-widget16__price m--align-right m--font-brand"> +78,05% </span> </div>
                                                <!--end::widget item-->
                                                <!--begin::widget item-->
                                                <div class="m-widget16__item"> <span class="m-widget16__date"> PDO </span> <span class="m-widget16__price m--align-right m--font-accent"> 21,700 </span> </div>
                                                <!--end::widget item-->
                                                <!--begin::widget item-->
                                                <div class="m-widget16__item"> <span class="m-widget16__date"> OPL Status </span> <span class="m-widget16__price m--align-right m--font-danger"> Negative </span> </div>
                                                <!--end::widget item-->
                                                <!--begin::widget item-->
                                                <div class="m-widget16__item"> <span class="m-widget16__date"> Priority </span> <span class="m-widget16__price m--align-right m--font-brand"> +500,200 </span> </div>
                                                <!--end::widget item-->
                                                <!--begin::widget item-->
                                                <div class="m-widget16__item"> <span class="m-widget16__date"> Net Prifit </span> <span class="m-widget16__price m--align-right m--font-brand"> $18,540,60 </span> </div>
                                                <!--end::widget item-->
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="m-widget16__stats">
                                                <div class="m-widget16__visual">
                                                    <div id="m_chart_support_tickets" style="height: 180px"></div>
                                                </div>
                                                <div class="m-widget16__legends">
                                                    <div class="m-widget16__legend"> <span class="m-widget16__legend-bullet m--bg-info"></span> <span class="m-widget16__legend-text"> 20% Margins </span> </div>
                                                    <div class="m-widget16__legend"> <span class="m-widget16__legend-bullet m--bg-accent"></span> <span class="m-widget16__legend-text"> 80% Profit </span> </div>
                                                    <div class="m-widget16__legend"> <span class="m-widget16__legend-bullet m--bg-danger"></span> <span class="m-widget16__legend-text"> 10% Lost </span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end:: Widgets/Support Stats-->
                    </div>
                </div>
                <!--End::Section-->
                <!--Begin::Section-->
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin:: Widgets/Application Sales-->
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text"> Application Sales </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                                        <li class="nav-item m-tabs__item"> <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget11_tab1_content" role="tab"> Last Month </a> </li>
                                        <li class="nav-item m-tabs__item"> <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget11_tab2_content" role="tab"> All Time </a> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="m_widget11_tab1_content">
                                        <!--begin::Widget 11-->
                                        <div class="m-widget11">
                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table">
                                                    <!--begin::Thead-->
                                                    <thead>
                                                    <tr>
                                                        <td class="m-widget11__label"> # </td>
                                                        <td class="m-widget11__app"> Application </td>
                                                        <td class="m-widget11__sales"> Sales </td>
                                                        <td class="m-widget11__change"> Change </td>
                                                        <td class="m-widget11__total m--align-right"> Total </td>
                                                    </tr>
                                                    </thead>
                                                    <!--end::Thead-->
                                                    <!--begin::Tbody-->
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                                                <input type="checkbox">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td><span class="m-widget11__title"> FREE </span></td>
                                                        <td> 19,200 </td>
                                                        <td>
                                                            <div class="m-widget11__chart" style="height:50px; width: 100px">
                                                                <iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <canvas id="m_chart_sales_by_apps_1_1" style="display: block; width: 100px; height: 50px;" width="100" height="50"></canvas>
                                                            </div>
                                                        </td>
                                                        <td class="m--align-right m--font-brand"> $0 </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                                                <input type="checkbox">
                                                                <span></span> </label></td>
                                                        <td><span class="m-widget11__title"> PRO </span></td>
                                                        <td> 24,310 </td>
                                                        <td><div class="m-widget11__chart" style="height:50px; width: 100px">
                                                                <iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <canvas id="m_chart_sales_by_apps_1_2" style="display: block; width: 100px; height: 50px;" width="100" height="50"></canvas>
                                                            </div></td>
                                                        <td class="m--align-right m--font-brand"> $16,010 </td>
                                                    </tr>
                                                    </tbody>
                                                    <!--end::Tbody-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                            <div class="m-widget11__action m--align-right">
                                                <button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--hover-brand"> Generate Report </button>
                                            </div>
                                        </div>
                                        <!--end::Widget 11-->
                                    </div>
                                    <div class="tab-pane" id="m_widget11_tab2_content">
                                        <!--begin::Widget 11-->
                                        <div class="m-widget11">
                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table">
                                                    <!--begin::Thead-->
                                                    <thead>
                                                    <tr>
                                                        <td class="m-widget11__label"> # </td>
                                                        <td class="m-widget11__app"> Application </td>
                                                        <td class="m-widget11__sales"> Sales </td>
                                                        <td class="m-widget11__change"> Change </td>
                                                        <td class="m-widget11__total m--align-right"> Total </td>
                                                    </tr>
                                                    </thead>
                                                    <!--end::Thead-->
                                                    <!--begin::Tbody-->
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                                                <input type="checkbox">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td><span class="m-widget11__title"> FREE</td>
                                                        <td> 19,200 </td>
                                                        <td>
                                                            <div class="m-widget11__chart" style="height:50px; width: 100px">
                                                                <iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <canvas id="m_chart_sales_by_apps_2_1" style="display: block; width: 0px; height: 0px;" height="0" width="0"></canvas>
                                                            </div>
                                                        </td>
                                                        <td class="m--align-right m--font-brand"> $0 </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                                                <input type="checkbox">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td><span class="m-widget11__title"> PRO </span></td>
                                                        <td> 24,310 </td>
                                                        <td>
                                                            <div class="m-widget11__chart" style="height:50px; width: 100px">
                                                                <iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <canvas id="m_chart_sales_by_apps_2_2" style="display: block; width: 0px; height: 0px;" height="0" width="0"></canvas>
                                                            </div>
                                                        </td>
                                                        <td class="m--align-right m--font-brand"> $46,010 </td>
                                                    </tr>
                                                    </tbody>
                                                    <!--end::Tbody-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                            <div class="m-widget11__action m--align-right">
                                                <button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--hover-brand"> Generate Report </button>
                                            </div>
                                        </div>
                                        <!--end::Widget 11-->
                                    </div>
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
    <?php $this->renderPartial('/admin/inquiry/footer');?>
