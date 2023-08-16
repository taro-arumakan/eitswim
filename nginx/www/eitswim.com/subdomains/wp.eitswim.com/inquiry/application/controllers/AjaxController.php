<?php

class AjaxController extends Controller
{
	public $layout='column1';

        /**
         * @return array action filters
         */
        public function filters() {
            return array(
                'accessControl', // perform access control for CRUD operations
                'postOnly + delete', // we only allow deletion via POST request
            );
        }
        
        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        public function accessRules() {
            return array(
                array('allow', // allow all users to perform 'index' and 'view' actions
                    'users' => array('*'),
                ),
            );
        }
        
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/*
	 * 市区町村取得
	 * $_POST['prefecture_id']
	 */
	public function actionCity() {
		//Serviceが増えたら個々に追加していく
		if(isset($_SERVER['SCRIPT_NAME'])) {
			if($_SERVER['SCRIPT_NAME'] == '/shop/index.php') {
				$this->actionCityShop();
			} else if($_SERVER['SCRIPT_NAME'] == '/unext_wifi/index.php') {
				$this->actionCityWifi();
			} else if($_SERVER['SCRIPT_NAME'] == '/inquiry/index.php') {
				$this->actionCityInquiry();
			}
		}
	}

	/*
	 * 市区町村取得
	 * $_POST['prefecture_id']
	 */
	public function actionCityWifi()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['prefecture_id'])) {
			$prefecture_id = $_POST['prefecture_id'];
		} else {
			$prefecture_id = 0;
		}
		$data = makeOptionHtml($prefecture_id);
		echo $data;
	}

	/*
	 * 市区町村取得
	 * $_POST['prefecture_id']
	 */
	public function actionCityShop()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['prefecture_id'])) {
			$prefecture_id = $_POST['prefecture_id'];
		} else {
			$prefecture_id = 0;
		}
		$data = makeOptionShopHtml($prefecture_id);
		echo $data;
	}

	/*
	 * 市区町村取得
	 * $_POST['prefecture_id']
	 */
	public function actionCityInquiry()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['prefecture_id'])) {
			$prefecture_id = $_POST['prefecture_id'];
		} else {
			$prefecture_id = 0;
		}
		$data = makeOptionHtml($prefecture_id);
		echo $data;
	}

	//strAddressToLatLng
	//住所から緯度経度
	public function actionLanLng() {
		//Serviceが増えたら個々に追加していく
		if(isset($_SERVER['SCRIPT_NAME'])) {
			if($_SERVER['SCRIPT_NAME'] == '/shop/index.php') {
				$this->actionLanLngShop();
			} else if($_SERVER['SCRIPT_NAME'] == '/unext_wifi/index.php') {
				$this->actionLanLngWifi();
			} else if($_SERVER['SCRIPT_NAME'] == '/inquiry/index.php') {
				$this->actionLanLngInquiry();
			}
		}
	}

	//strAddressToLatLng
	//住所から緯度経度
	public function actionLanLngShop()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['address'])) {
			$address = $_POST['address'];
		} else {
			$address = 0;
		}
		$point = getLatLng($address);


	}

	//strAddressToLatLng
	//住所から緯度経度
	public function actionLanLngWifi()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['address'])) {
			$address = $_POST['address'];
		} else {
			$address = 0;
		}
		$point = getLatLng($address);


	}

	//strAddressToLatLng
	//住所から緯度経度
	public function actionLanLngInquiry()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['address'])) {
			$address = $_POST['address'];
		} else {
			$address = 0;
		}
		$point = getLatLng($address);


	}

	//緯度経度からスポット検索
	public function actionLocation() {
		//Serviceが増えたら個々に追加していく
		if(isset($_SERVER['SCRIPT_NAME'])) {
			if($_SERVER['SCRIPT_NAME'] == '/shop/index.php') {
				$this->actionLocationShop();
			} else if($_SERVER['SCRIPT_NAME'] == '/unext_wifi/index.php') {
				$this->actionLocationWifi();
			} else if($_SERVER['SCRIPT_NAME'] == '/inquiry/index.php') {
				$this->actionLocationInquiry();
			}
		}
	}

	//緯度経度からスポット検索
	public function actionLocationShop()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['lat'])) {
			$lat = $_POST['lat'];
		}
		if(isset($_POST['lng'])) {
			$lng = $_POST['lng'];
		}
		$width = 0;
		if(isset($_POST['width'])) {
			$width = $_POST['width'];
		}
		$distance = 0;
		if(isset($_POST['zoom'])) {
			$zoom     = $_POST['zoom'];
			if($width < param('sp_width')) {
				//SP
				$z = param('array_zoom_sp');
			} else {
				//Not SP
				$z = param('array_zoom');
			}
			if($zoom > 20) {
				$distance = 1000;
			} else {
				$distance = $z[$zoom];
			}
		}
		$model = new Shop;
		if(!empty($_POST['category'])) {
			$cat = explode(',', $_POST['category']);
			if($cat) $model->category = $cat;
		}
		if(!empty($_POST['service'])) {
			$service = explode(',', $_POST['service']);
			if($service) $model->service = $service;
		}
		if(!empty($_POST['sim'])) {
			$model->sim = $_POST['sim'];
		}
		if(!empty($lat) && !empty($lng)) {
			$data = Shop::model()->searchByLocation($model, $lat, $lng, $distance);
			if(empty($data)) return '';
			$marker = array();
			foreach($data as $item) {
				$line = array();
				$line['name'] = $item->shop_name;
				$line['id'] = $item->shop_id;
				$line['path'] = $item->prefecture_path;
				$line['address'] = $item->shop_address;
				$line['lat'] = $item->latitude;
				$line['lng'] = $item->longitude;
				$marker[] = $line;
			}
			if(!empty($marker)) {
				$ret = json_encode($marker);
				echo $ret;
			} else {
				echo '';
			}
		}

	}

	//緯度経度からスポット検索
	public function actionLocationWifi()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['lat'])) {
			$lat = $_POST['lat'];
		}
		if(isset($_POST['lng'])) {
			$lng = $_POST['lng'];
		}
		$width = 0;
		if(isset($_POST['width'])) {
			$width = $_POST['width'];
		}
		$distance = 0;
		if(isset($_POST['zoom'])) {
			$zoom     = $_POST['zoom'];
			if($width < param('sp_width')) {
				//SP
				$z = param('array_zoom_sp');
			} else {
				//Not SP
				$z = param('array_zoom');
			}
			if($zoom > 20) {
				$distance = 1000;
			} else {
				$distance = $z[$zoom];
			}
		}
		$model = new Spot;
		if(!empty($_POST['category'])) {
			$cat = explode(',', $_POST['category']);
			if($cat) $model->category = $cat;
		}
		if(!empty($lat) && !empty($lng)) {
			$data = Spot::model()->searchByLocation($model, $lat, $lng, $distance);
			if(empty($data)) return '';
			$marker = array();
			foreach($data as $item) {
				$line = array();
				$line['name'] = $item->spot_name;
				$line['id'] = $item->spot_id;
				$line['path'] = $item->prefecture_path;
				$line['address'] = $item->spot_address;
				$line['lat'] = $item->latitude;
				$line['lng'] = $item->longitude;
				$line['dospot'] = $item->spot_dospot;
				$marker[] = $line;
			}
			if(!empty($marker)) {
				$ret = json_encode($marker);
				echo $ret;
			} else {
				echo '';
			}
		}

	}

	//緯度経度からスポット検索
	public function actionLocationInquiry()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['lat'])) {
			$lat = $_POST['lat'];
		}
		if(isset($_POST['lng'])) {
			$lng = $_POST['lng'];
		}
		$width = 0;
		if(isset($_POST['width'])) {
			$width = $_POST['width'];
		}
		$distance = 0;
		if(isset($_POST['zoom'])) {
			$zoom     = $_POST['zoom'];
			if($width < param('sp_width')) {
				//SP
				$z = param('array_zoom_sp');
			} else {
				//Not SP
				$z = param('array_zoom');
			}
			if($zoom > 20) {
				$distance = 1000;
			} else {
				$distance = $z[$zoom];
			}
		}
		$model = new Spot;
		if(!empty($_POST['category'])) {
			$cat = explode(',', $_POST['category']);
			if($cat) $model->category = $cat;
		}
		if(!empty($lat) && !empty($lng)) {
			$data = Spot::model()->searchByLocation($model, $lat, $lng, $distance);
			if(empty($data)) return '';
			$marker = array();
			foreach($data as $item) {
				$line = array();
				$line['name'] = $item->spot_name;
				$line['id'] = $item->spot_id;
				$line['path'] = $item->prefecture_path;
				$line['address'] = $item->spot_address;
				$line['lat'] = $item->latitude;
				$line['lng'] = $item->longitude;
				$line['dospot'] = $item->spot_dospot;
				$marker[] = $line;
			}
			if(!empty($marker)) {
				$ret = json_encode($marker);
				echo $ret;
			} else {
				echo '';
			}
		}

	}

	/*
	 * エリアから店舗リストを取得
	 * $_POST['area']
	 */
	public function actionList()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['area'])) {
			$area = $_POST['area'];
		} else {
			$area = '';
		}
		if(!empty($_POST['select'])) {
			$select = $_POST['select'];
		} else {
			$select = '';
		}
		$data = makeAreaHtml($area, $select);
		echo $data;
	}


	function actionInquiry() {
		$models = Inquiry::model()->findAll(array("condition" => "delete_flag = 0",
																							"order" => "inquiry_rank"));
		$html = '';
		$html .= '<div class="sidebarmenu">'."\n";
		$html .= '	<ul>'."\n";
		foreach($models as $model) {
			$html .= '		<li>'."\n";
			$html .= '			<a href="/inquiry/'.$model->inquiry_code.'">'.$model->inquiry_title."</a></li>\n";
			$html .= '		</li>'."\n";
		}
		$html .= '	</ul>'."\n";
		$html .= '</div>'."\n";
		echo $html;
	}

	/*
	 * キャプチャを作成
	 * $_POST['url']
	 */
	public function actionImage()
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		date_default_timezone_set('Asia/Tokyo');

		if(isset($_POST['src'])) {
			$url = $_POST['src'];
		} else {
			$url = '';
		}
		if(isset($_GET['src'])) {
			$url = $_GET['src'];
		}
		$data = get_image($url);
		echo $data;
	}

}
