<?php
class ControllerShippingSdekUpdater extends Controller {
	public $ECHO = true;
	public $LOG_LEVEL = 3;
	public $CONFIG = array(
		'status'=>true,
		'Account' => '',
		'Secure_password' => '',
		'Timezone_diff'=>-4,
		'shipping_code'=>''
	);
	private $last_hours = 12;

	public $STATUS_MAPPING = array(
	 1 => array('name'=>'Создан',
		'notify'=>'true', 'enabled'=>true, 'status'=>17, 'text'=>'Заказ зарегистрирован в базе данных СДЭК, вы можете отслеживать заказ на сайте: http://www.edostavka.ru/nakladnoy/?RegistrNum={track_no}',
		'smsnotify'=>true, 'sms'=>'{firstname}, ваш заказ #{order_id} отправлен. Вы можете отслеживать заказ на сайте www.edostavka.ru, код отслеживания: {track_no}'),
	 2 => array('name'=>'Удален',
		'notify'=>'true', 'enabled'=>true, 'status'=>9, 'text'=>'Заказ отменен ИМ после регистрации в системе до прихода груза на склад СДЭК в городе-отправителе'),
	 3 => array('name'=>'Принят на склад отправителя',
		'notify'=>'false', 'enabled'=>true, 'status'=>17, 'text'=>'Оформлен приход на склад СДЭК в городе-отправителе'),
	 6 => array('name'=>'Выдан на отправку в г.-отправителе',
		'notify'=>'false', 'enabled'=>true, 'status'=>17, 'text'=>'Оформлен расход со склада СДЭК в городе-отправителе. Груз подготовлен к отправке (консолидирован с другими посылками)'),
	16 => array('name'=>'Возвращен на склад отправителя',
		'notify'=>'false', 'enabled'=>true, 'status'=>17, 'text'=>'Повторно оформлен приход в городе-отправителе (не удалось передать перевозчику по какой-либо причине)'),
	 7 => array('name'=>'Сдан перевозчику в г.-отправителе',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована отправка в городе-отправителе. Консолидированный груз передан на доставку (в аэропорт/загружен машину)'),
	21 => array('name'=>'Отправлен в г.-транзит',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована отправка в город-транзит. Проставлены дата и время отправления у перевозчика'),
	22 => array('name'=>'Встречен в г.-транзите',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована встреча в городе-транзите'),
	13 => array('name'=>'Принят на склад транзита',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Оформлен приход в городе-транзите'),
	17 => array('name'=>'Возвращен на склад транзита',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Повторно оформлен приход в городе-транзите (груз возвращен на склад)'),
	19 => array('name'=>'Выдан на отправку в г.-транзите',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Повторно оформлен приход в городе-транзите (груз возвращен на склад)'),
	20 => array('name'=>'Сдан перевозчику в г.-транзите',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована отправка у перевозчика в городе-транзите'),
	 8 => array('name'=>'Отправлен в г.-получатель',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована отправка в город-получатель, груз в пути'),
	 9 => array('name'=>'Встречен в г.-получателе',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Зарегистрирована встреча груза в городе-получателе'),
	10 => array('name'=>'Принят на склад доставки',
		'notify'=>'false', 'enabled'=>true, 'status'=>18, 'text'=>'Оформлен приход на склад города-получателя., ожидает доставки до двери'),
	12 => array('name'=>'Принят на склад до востребования',
		'notify'=>'true', 'enabled'=>true, 'status'=>22, 'text'=>'Оформлен приход на склад города-получателя. Доставка до склада, посылка ожидает забора клиентом - покупателем ИМ',
		'smsnotify'=>true, 'sms'=>'{firstname}, ваш заказ #{order_id} доставлен. Вы можете забрать его по адресу: {address}'),
	11 => array('name'=>'Выдан на доставку',
		'notify'=>'true', 'enabled'=>true, 'status'=>18, 'text'=>'Добавлен в курьерскую карту, выдан курьеру на доставку'),
	18 => array('name'=>'Возвращен на склад доставки',
		'notify'=>'true', 'enabled'=>true, 'status'=>18, 'text'=>'Оформлен повторный приход на склад в городе-получателе. Доставка не удалась по какой-либо причине, ожидается очередная попытка доставки'),
	4 => array('name'=>'Вручен',
		'notify'=>'false', 'enabled'=>true, 'status'=>5, 'text'=>'Успешно доставлен и вручен адресату'),
	5 => array('name'=>'Не вручен, возврат',
		'notify'=>'true', 'enabled'=>true, 'status'=>7, 'text'=>'Покупатель отказался от покупки, возврат в ИМ')
	);

	private function setConfig() {
		if (!$this->config->get('sdekupd_set')) {
			foreach($this->STATUS_MAPPING as $code=>$vals) {
				$this->STATUS_MAPPING[$code]['text'] = "{DATE} {CITY}\n".$vals['text'];
			}
			return;
		}
	
		foreach($this->CONFIG as $key=>$conf) {
			$this->CONFIG[$key] = $this->config->get('sdekupd_'.$key);
		}
		$this->CONFIG['status']	= $this->config->get('sdek_updater_status');
		foreach($this->STATUS_MAPPING as $code=>$vals) {
			$this->STATUS_MAPPING[$code]['notify'] = $this->config->get('sdekupd_mapping'.$code.'_notify');
			$this->STATUS_MAPPING[$code]['enabled'] = $this->config->get('sdekupd_mapping'.$code.'_enabled');
			$this->STATUS_MAPPING[$code]['status'] = $this->config->get('sdekupd_mapping'.$code.'_status');
			$this->STATUS_MAPPING[$code]['text'] = $this->config->get('sdekupd_mapping'.$code.'_text');
			if (isset($this->STATUS_MAPPING[$code]['sms'])) {
				$this->STATUS_MAPPING[$code]['sms'] = $this->config->get('sdekupd_mapping'.$code.'_sms');
			}
			if (isset($this->STATUS_MAPPING[$code]['smsnotify'])) {
				$this->STATUS_MAPPING[$code]['smsnotify'] = $this->config->get('sdekupd_mapping'.$code.'_smsnotify');
			}
		}
	}

	public function install() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT 1");
		if (!isset($query->row['track_no'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `track_no` VARCHAR(32) NOT NULL AFTER `order_id`");
		}
	}

	public function uninstall() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group`='sdekupd'");
	}
	
	public function index() {
		$this->load->language('shipping/sdek_updater');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sdekupd', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}
		
		$this->setConfig();
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/sdek_updater', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/sdek_updater', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 		
		
		
		$this->load->model('localisation/order_status');
    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		foreach($this->CONFIG as $key=>$conf) {
			if (isset($this->request->post['sdekupd_'.$key])) {
				$this->data['sdekupd_'.$key] = $this->request->post['sdekupd_'.$key];
			} else {
				$this->data['sdekupd_'.$key] = $conf;
			}
		}
		
		$this->data['status_mapping'] = $this->STATUS_MAPPING;

		if (isset($this->request->post['sdek_updater_status'])) {
			$this->data['sdek_updater_status'] = $this->request->post['sdek_updater_status'];
		} else {
			$this->data['sdek_updater_status'] = $this->CONFIG['status'];
		}
		
		foreach($this->STATUS_MAPPING as $code=>$val) {
			if (isset($this->request->post['sdekupd_mapping'.$code.'_notify'])) {
				$this->data['sdekupd_mapping'.$code.'_notify'] = $this->request->post['sdekupd_mapping'.$code.'_notify'];
			} else {
				$this->data['sdekupd_mapping'.$code.'_notify'] = $val['notify'];
			}
			if (isset($this->request->post['sdekupd_mapping'.$code.'_smsnotify'])) {
				$this->data['sdekupd_mapping'.$code.'_smsnotify'] = $this->request->post['sdekupd_mapping'.$code.'_smsnotify'];
			} elseif (isset($val['smsnotify'])) {
				$this->data['sdekupd_mapping'.$code.'_smsnotify'] = $val['smsnotify'];
			}
			
			if (isset($this->request->post['sdekupd_mapping'.$code.'_enabled'])) {
				$this->data['sdekupd_mapping'.$code.'_enabled'] = $this->request->post['sdekupd_mapping'.$code.'_enabled'];
			} else {
				$this->data['sdekupd_mapping'.$code.'_enabled'] = $val['enabled'];
			}
			
			if (isset($this->request->post['sdekupd_mapping'.$code.'_status'])) {
				$this->data['sdekupd_mapping'.$code.'_status'] = $this->request->post['sdekupd_mapping'.$code.'_status'];
			} else {
				$this->data['sdekupd_mapping'.$code.'_status'] = $val['status'];
			}
			
			if (isset($this->request->post['sdekupd_mapping'.$code.'_text'])) {
				$this->data['sdekupd_mapping'.$code.'_text'] = $this->request->post['sdekupd_mapping'.$code.'_text'];
			} else {
				$this->data['sdekupd_mapping'.$code.'_text'] = $val['text'];
			}

			if (isset($this->request->post['sdekupd_mapping'.$code.'_sms'])) {
				$this->data['sdekupd_mapping'.$code.'_sms'] = $this->request->post['sdekupd_mapping'.$code.'_sms'];
			} elseif (isset($val['sms'])) {
				$this->data['sdekupd_mapping'.$code.'_sms'] = $val['sms'];
			}
		}
		
		$this->template = 'shipping/sdek_updater.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/sdek_updater')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function update() {
		$this->setConfig();
	
		if (!$this->CONFIG['status']) return false;
		$from_date = date('Y-m-d\TH:i:s', time() + $this->CONFIG['Timezone_diff']*3600 - $this->last_hours*3600);
		$tuData = $this->getStatuses($from_date);
		if ($tuData !== false) {
			$res = $this->processResponse($tuData);
			if ($res != false && is_array($res)) {
				foreach ($res as $track_no=>$state) {
					$this->handleStatus($track_no, $state);
				}
			}
		}
	}
	
	private function getStatuses($from_date) {
		$date = date('Y-m-d\TH:i:s', time() + $this->CONFIG['Timezone_diff']*3600);
		$secure = md5($date.'&'.$this->CONFIG['Secure_password']);
		$request = '<?xml version="1.0" encoding="UTF-8" ?>
				<StatusReport Date="'.$date.'" Account="'.$this->CONFIG['Account'].'" Secure="'.$secure.'" ShowHistory="1">
				<ChangePeriod DateFirst="'.$from_date.'" DateLast="'.$date.'"/>
			</StatusReport>';
			
		return $this->postRequest('http://gw.edostavka.ru/status_report_h.php', $request);
	}
	
	/**
	* Отправка POST-запроса серверу СДЭК
	**/
	private function postRequest($url, $request) {
		$this->log('Sending request to '.$url.":\n" . $request, 5);
		
		$tuCurl = curl_init();		
		curl_setopt($tuCurl, CURLOPT_URL, $url);
		curl_setopt($tuCurl, CURLOPT_PORT , 11443);
		curl_setopt($tuCurl, CURLOPT_HEADER, 0);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($tuCurl, CURLOPT_POST, 1);
		curl_setopt($tuCurl, CURLOPT_POSTFIELDS, array('xml_request'=>$request));

		$tuData = curl_exec($tuCurl);
		if(curl_errno($tuCurl)){
			$info = curl_getinfo($tuCurl);
			$this->log('Curl Error: '.curl_error($ch).'. Took: ' . $info['total_time'] . 'sec. URL: ' . $info['url'], 1);
			return false;
		} else {
			return $tuData;
		}
	}
	
	/**
	* Обработка ответа со статусами заказов от СДЭК
	**/
	private function processResponse($tuData) {
		try {
			$xml = simplexml_load_string($tuData);
			$attr = $xml->Attributes();
			if (isset($attr['ErrorCode'])) {
				$this->log('Edostavka returns error: "'.$attr['Msg'].".", 1);
			}
			elseif (!isset($xml->Order) || !count($xml->Order)) {
				$this->log('Edostavka returns empty order history.', 4);
			}
			else {
				$ret = array();
				foreach($xml->Order as $order) {
					$oattr = $order->Attributes();
					$ret[strval($oattr['DispatchNumber'])] = array();

					$sattr = $order->Status->Attributes();
					$ret[strval($oattr['DispatchNumber'])] = array(
						'Date' => strval($sattr['Date']),
						'Code' => strval($sattr['Code']),
						'Description' => strval($sattr['Description']),
						'CityName' => strval($sattr['CityName']),					
					);
					/*
					foreach($order->Status->State as $state) {
						$sattr = $state->Attributes();
						$ret[strval($oattr['DispatchNumber'])][] = array(
							'Date' => strval($sattr['Date']),
							'Code' => strval($sattr['Code']),
							'Description' => strval($sattr['Description']),
							'CityName' => strval($sattr['CityName']),					
						);
					}
					*/
				}
				$this->log('Edostavka returns order states: '.json_encode($ret), 4);
				return($ret);
			}
		} catch (Exception $e) {
			$this->log("Got invalid XML ('$e->getMessage().'): \n".$tuData, 1);
		}
		return false;	
	}

	private function handleStatus($track_no, $state) {
		if (!isset($this->STATUS_MAPPING[$state['Code']]) || !$this->STATUS_MAPPING[$state['Code']]['enabled']) {
			$this->log('Order #'.$track_no.', status: '.$state['Code'].'. Do nothing.', 3);
			return false;
		}

		$order_id = $this->findOrder($track_no);
		if (!$order_id) {
			$this->log('Order #'.$track_no.', status: '.$state['Code'].'. Order not found.', 1);
			return false;
		}
		$status = $this->STATUS_MAPPING[$state['Code']]['status'];
		$this->load->model('sale/order');
		$order_info = $this->model_sale_order->getOrder($order_id);

		$comment = $this->getComment($state, $order_info);
		$q1 = $this->db->query("SELECT * FROM `".DB_PREFIX."order_history` WHERE order_id='".(int)$order_id."' ORDER BY date_added DESC LIMIT 1");
		if ($q1->row && $q1->row['order_status_id'] == $status && strpos($q1->row['comment'], $comment) !== false) {
			$this->log('Order #'.$track_no.' (ID:'.$order_id.'), status: '.$state['Code'].' ('.$status.'). Status already changed.', 3);
			return false;
		}
		
		try {
			$this->load->model('sale/order');
			$data = array(
				'order_status_id' => $this->STATUS_MAPPING[$state['Code']]['status'],
				'notify' => $this->STATUS_MAPPING[$state['Code']]['notify'],
				'comment' => $comment
			);
			$this->model_sale_order->addOrderHistory($order_id, $data);
			$this->log('Order #'.$track_no.' (ID:'.$order_id.'), status: '.$state['Code'].' ('.$status.'). Added order history.', 3);
			if (isset($this->STATUS_MAPPING[$state['Code']]['smsnotify']) && $this->STATUS_MAPPING[$state['Code']]['smsnotify']) {
				$this->smsNotify($order_info, $this->getComment($state, $order_info, 'sms'));
			}
			return true;
		} catch (Exception $e) {
			$this->log('Order #'.$track_no.' (ID:'.$order_id.'), status: '.$state['Code'].' ('.$status.'). Unable to add order history ('.$e->getMessage().').', 1);
			return false;
		}	
	}

	/**
	* Получить ID заказа по номеру треккинга
	*/
	private function findOrder($track_no) {
		/*
		if ($this->CONFIG['shipping_code'] == '') {
			$sql = "SELECT order_id FROM `".DB_PREFIX."order_history` WHERE comment LIKE '%".$this->db->escape($track_no)."%' LIMIT 1";
		}
		else {
			$sql = "SELECT o.order_id FROM `".DB_PREFIX."order_history` h LEFT JOIN `".DB_PREFIX."order` o ON o.order_id=h.order_id
				WHERE o.shipping_code='".$this->db->escape($this->CONFIG['shipping_code'])."' AND h.comment LIKE '%".$this->db->escape($track_no)."%' LIMIT 1";
		}
		*/
		if ($this->CONFIG['shipping_code'] == '') {
			$sql = "SELECT order_id FROM `".DB_PREFIX."order` WHERE track_no='".$this->db->escape(trim($track_no))."' LIMIT 1";
		}
		else {
			$sql = "SELECT order_id FROM `".DB_PREFIX."order` WHERE track_no='".$this->db->escape(trim($track_no))."' 
				AND shipping_code='".$this->db->escape($this->CONFIG['shipping_code'])."' LIMIT 1";
		}
		
		$q = $this->db->query($sql);
		if (!$q->row) {
			return false;
		}
		else {
			return $q->row['order_id'];
		}
	}

	private function getComment($state, $order_info, $key='text') {
		$date = date('d.m.Y H:i:s', strtotime($state['Date']));
		$text = str_replace('{DATE}', $date, $this->STATUS_MAPPING[$state['Code']][$key]);
		$text = str_replace('{CITY}', $state['CityName'], $text);
		$text = str_replace('{firstname}', $order_info['firstname'], $text);
		$text = str_replace('{lastname}', $order_info['lastname'], $text);
		$text = str_replace('{order_id}', $order_info['order_id'], $text);
		$text = str_replace('{track_no}', $order_info['track_no'], $text);
		$address_arr = array($order_info['shipping_zone'], $order_info['shipping_city'], $order_info['shipping_address_1'], $order_info['shipping_address_2']);
		$address_arr = array_diff($address_arr, array(''));
		$text = str_replace('{address}', implode(', ', $address_arr), $text);
		return $text;
	}

	protected function smsNotify($order, $message) {
		if ($this->config->get('config_sms_alert')) {
			$options = array(
				'to'       => $order['telephone'],
				'copy'     => '',
				'from'     => $this->config->get('config_sms_from'),
				'username' => $this->config->get('config_sms_gate_username'),
				'password' => $this->config->get('config_sms_gate_password'),
				'message'  => $message,
				'ext'      => null
			);
			
			$this->load->library('sms');

			$sms = new Sms($this->config->get('config_sms_gatename'), $options);
			$sms->send();
			$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'). Send SMS to ' . $order['telephone'] . ' (' . $message . ').', 3);
		}
	}
	
	/**
	* Писать в журнал ошибки и сообщения
	* @param str $msg запись
	* @param int $level приоритет ошибки/сообщения. Если приоритет больше $this->LOG_LEVEL, то он записан не будет
	**/
	private function log($msg, $level = 0) {
		if ($level > $this->LOG_LEVEL) return;
		$fp = fopen(DIR_LOGS.'sdek_updater.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		if ($this->ECHO) echo nl2br(htmlspecialchars($msg))."<br/>\n";
		fclose($fp);
	}
}
