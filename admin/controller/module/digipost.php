<?php
define('EXTENSION_NAME', "api.Digi-Post.ru - плагин для комфортной работы с Почтой России");
define('EXTENSION_VERSION', "1.0.0");
define('EXTENSION_TYPE', "vQmod");
define('EXTENSION_COMPATIBILITY', "OpenCart 1.5.5.X");
define('EXTENSION_URL', "http://api.digi-post.ru/site/page?view=opencart");
define('EXTENSION_SUPPORT', "d.post@dtgp.ru");
define('EXTENSION_SUPPORT_FORUM', "");

class ControllerModuleDigiPost extends Controller {
	private $error = array();

	private $defaults = array(
		'digipost_installed'              => 1,
		'digipost_status'   => 1,
		'digipost_login'          => '',
		'digipost_api_key'          => '',
		'digipost_from_name'          => '',
		'digipost_from_address_1'            => '',
		'digipost_from_address_2'        => '',
		'digipost_postindex'               => '',
		'digipost_doc_type'    =>'',
		'digipost_doc_serial'    =>'',
		'digipost_doc_num'    =>'',
		'digipost_doc_date'    =>'',
		'digipost_doc_received_by'    =>'',
		'digipost_from_name_legal'          => '',
		'digipost_inn'    =>'',
		'digipost_bik'    =>'',
		'digipost_correspondent_account'    =>'',
		'digipost_current_account'    =>'',
		'digipost_bank_name'    =>'',
		'enable_digi_tracking' =>'0',
		'enable_digi_calc' =>'0',
		'digipost_postcode' => '',
		'digipost_delivery_type_parcel'=>'0',
		'digipost_delivery_type_bookpost_1class'=>'0',
		'digipost_delivery_type_valued_bookpost'=>'0',
		'digipost_delivery_type_bookpost'=>'0',
		'digipost_delivery_type_ems'=>'0',
		'digipost_addcost'=> '0',

	);


	public function index() {
		$this->data = array_merge($this->data, $this->language->load('module/digipost'));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			# Loop through all settings for the post/config values
			foreach (array_keys($this->defaults) as $setting) {
				if (!isset($this->request->post[$setting])) {
					$this->request->post[$setting] = 0;
				}
			}

			$this->model_setting_setting->editSetting('digipost', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (!class_exists('VQMod')) {
			$this->data['error_warning'] = $this->language->get('error_vqmod');
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/digipost', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/digipost', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['ext_name'] = EXTENSION_NAME;
		$this->data['ext_version'] = EXTENSION_VERSION;
		$this->data['ext_type'] = EXTENSION_TYPE;
		$this->data['ext_compatibility'] = EXTENSION_COMPATIBILITY;
		$this->data['ext_url'] = EXTENSION_URL;
		$this->data['ext_support'] = EXTENSION_SUPPORT;
		$this->data['ext_support_forum'] = EXTENSION_SUPPORT_FORUM;
		$this->data['ext_subject'] = sprintf($this->language->get('text_ext_subject'), EXTENSION_NAME);

		# Loop through all settings for the post/config values
		foreach (array_keys($this->defaults) as $setting) {
			if (isset($this->request->post[$setting])) {
				$this->data[$setting] = $this->request->post[$setting];
			} else {
				$this->data[$setting] = $this->config->get($setting);
				if ($this->data[$setting] === null) {
					$this->data['error_warning'] = $this->language->get('error_unsaved_settings');
					if (isset($this->defaults[$setting])) {
						$this->data[$setting] = $this->defaults[$setting];
					}
				}
			}
		}

		$this->template = 'module/digipost.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('digipost', $this->defaults);
		
		$result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'postcode' ");
		
		if($result->num_rows > 0) {
			$exists = TRUE;
		} else {
			$exists = FALSE;
		}
		if (!$exists) {
			$tmp = $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `postcode` VARCHAR( 20 ) NOT NULL , ADD INDEX (  `postcode` ) ");
		}

	}

	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('digipost');
		//$this->db->query("ALTER TABLE `" . DB_PREFIX . "order`  DROP `postcode` ");
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/digipost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>