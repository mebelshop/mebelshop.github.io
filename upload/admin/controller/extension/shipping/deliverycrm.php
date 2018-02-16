<?php
/**
* Order Track Number for OpenCart (ocStore) 2.3 and DeliveryCRM
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2014- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
class ControllerExtensionShippingDeliverycrm extends Controller {
	protected $error = array();
	
	public $CONFIG = array(
		'ignore_security'=>0,
		
		'change_status'=>1,
		'order_status'=>2,
		//'order_history'=>'Заказ отправлен, трек-номер: {track_no}.',
		
		'email_notify'=>1,
		'email_text'=>'Уважаемый {shipping_firstname} {shipping_lastname}, ваш заказ №{order_id} передан службе доставки, трек-номер: {track_no}.',

		'sms_notify'=>0,
		'sms_gatename'=>'unisender',
		'sms_gate_username'=>'',
		'sms_gate_password'=>'',
		'sms_gate_from'=>'',
		'sms_text'=>'Ваш заказ №{order_id} передан службе доставки, трек-номер: {track_no}.',
		
		'export'=>0,
		'api_id'=>'',
		'user'=>'',
		'test_mode'=>1,
		//'carriers'=>array(0,7,8,10),
	);

	public $CARRIERS = array(
		0 => "Почта России",
		1 => "TopDelivery",
		2 => "СПСР",
		3 => "Boxberry",
		4 => "PickPoint",
		5 => "Shop-Logistics",
		6 => "Деловые линии",
		7 => "СДЭК",
		8 => "DPD",
		9 => "Экспресс.Ру",
		10=> "ПЭК",
		11=> "КСЭ",
		12=> "Dimex",
		13=> "УкрПочта",
		14=> "Почта Казахстана",
		15=> "Нова Пошта"
	);

	private function setConfig() {
		if ($this->config->get('deliverycrm_set')) {
			foreach($this->CONFIG as $key=>$conf) {
				/*
				if ($key == 'carriers') {
					$this->CONFIG['carriers'] = array();
					$carr = $this->config->get('deliverycrm_carriers');
					if ($carr) {
						$this->CONFIG['carriers'] = @array_keys(json_decode($carr, true));
					}
				}
				else {
				*/
					$this->CONFIG[$key] = $this->config->get('deliverycrm_'.$key);
				//}
			}
		}
	}
	
	public function install() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT 1");
		if (!isset($query->row['track_no'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `track_no` VARCHAR(32) NOT NULL AFTER `order_id`");
		}
		if (!isset($query->row['track_carrier'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `track_carrier` VARCHAR(4) NOT NULL AFTER `track_no`");
		}
	}

	public function uninstall() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code`='deliverycrm'");
	}
	
	public function index() {
		$this->load->language('extension/shipping/deliverycrm');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$carriers = isset($this->request->post['deliverycrm_carriers']) ? $this->request->post['deliverycrm_carriers'] : array();
			$this->request->post['deliverycrm_carriers'] = array();
			foreach($carriers as $id) {
				$this->request->post['deliverycrm_carriers'][$id] = $this->CARRIERS[$id];
			}
			$this->request->post['deliverycrm_carriers'] = json_encode($this->request->post['deliverycrm_carriers']);
			
			$this->model_setting_setting->editSetting('deliverycrm', $this->request->post);	
			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', 'SSL'));
			return;
		}
		$data = array();
		
		$this->setConfig();
		if (isset($this->request->post['deliverycrm_carriers'])) {
			$data['deliverycrm_carriers'] = $this->request->post['deliverycrm_carriers'];
		} else {
			$carr = $this->config->get('deliverycrm_carriers');
			$data['deliverycrm_carriers'] = array();
			if ($carr) {
				$data['deliverycrm_carriers'] = json_decode($carr, true);
			}
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/shipping/deliverycrm', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/shipping/deliverycrm', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', 'SSL'); 		

		$this->load->model('localisation/order_status');
    	$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['sms_gatenames'] = array();
		$files = glob(DIR_SYSTEM . 'smsgate/*.php');
		foreach ($files as $file) {
			$data['sms_gatenames'][] =  basename($file, '.php');
		}
		
		if (isset($this->request->post['deliverycrm_status'])) {
			$data['deliverycrm_status'] = $this->request->post['deliverycrm_status'];
		} else {
			$data['deliverycrm_status'] = $this->config->get('deliverycrm_status');
		}
		
		foreach($this->CONFIG as $key=>$conf) {
			if (isset($this->request->post['deliverycrm_'.$key])) {
				$data['deliverycrm_'.$key] = $this->request->post['deliverycrm_'.$key];
			} else {
				$data['deliverycrm_'.$key] = $conf;
			}
		}

		$data['all_carriers'] = $this->CARRIERS;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['store'] = HTTPS_CATALOG;
		$data['token'] = $this->session->data['token'];

		// API login

		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		
		$this->response->setOutput($this->load->view('extension/shipping/deliverycrm.tpl', $data));		
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/deliverycrm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		else if (isset($this->request->post['deliverycrm_export']) && $this->request->post['deliverycrm_export'] && !$this->request->post['deliverycrm_api_id']) {
			$this->error['warning'] = 'Вы выбрали экспортировать заказы в DeliveryCRM, но не указали ключ API.';
		}
		else if (isset($this->request->post['deliverycrm_export']) && $this->request->post['deliverycrm_export'] && !$this->request->post['deliverycrm_user']) {
			$this->error['warning'] = 'Вы выбрали экспортировать заказы в DeliveryCRM, но не указали ID пользователя API.';
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
