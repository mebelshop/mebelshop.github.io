<?php
class ControllerShippingBoxberryUpdater extends Controller {
	public $ECHO = true;
	public $LOG_LEVEL = 4;
	public $CONFIG = array(
		'status'=>true,
		'token' => '',
		'shipping_code'=>''
	);
	private $last_error = '';

	public $STATUS_MAPPING = array(
	 1 => array('name'=>'Принято к доставке',
		'notify'=>true, 'enabled'=>true, 'status'=>2, 'text'=>'{firstname}, ваш заказ #{order_id} зарегистрирован в базе данных Boxberry, вы можете отслеживать заказ на сайте: http://www.boxberry.ru/ru/tracking/',
		'smsnotify'=>true, 'sms'=>'{firstname}, ваш заказ #{order_id} отправлен. Вы можете отслеживать заказ на сайте www.boxberry.ru, код отслеживания: {track_no}'),
	 2 => array('name'=>'Передано на сортировку',
		'notify'=>false, 'enabled'=>true, 'status'=>2, 'text'=>'Заказ передан на пункт сортировки Boxberry.'),
	 3 => array('name'=>'Отправлено в город назначения',
		'notify'=>false, 'enabled'=>true, 'status'=>2, 'text'=>'Заказ отправлен в город назначения.'),
	 4 => array('name'=>'Поступило в пункт выдачи',
		'notify'=>true, 'enabled'=>true, 'status'=>3, 'text'=>'{firstname}, ваш заказ #{order_id} доставлен. Вы можете забрать его в пункте выдачи Boxberry. {COMMENT}',
		'smsnotify'=>true, 'sms'=>'{firstname}, ваш заказ #{order_id} доставлен. Вы можете забрать его в пункте выдачи Boxberry.  {COMMENT}'),
	 5 => array('name'=>'Передано на курьерскую доставку',
		'notify'=>true, 'enabled'=>true, 'status'=>2, 'text'=>'{firstname}, ваш заказ #{order_id} передан курьеру на доставку. {COMMENT}',
		'smsnotify'=>true, 'sms'=>'{firstname}, ваш заказ #{order_id} передан курьеру на доставку. {COMMENT}'),
	 6 => array('name'=>'Выдано',
		'notify'=>false, 'enabled'=>true, 'status'=>3, 'text'=>'Заказ доставлен и вручен адресату. {COMMENT}'),
	 7 => array('name'=>'Готовится к возврату',
		'notify'=>true, 'enabled'=>true, 'status'=>8, 'text'=>'{firstname}, вы не забрали заказ в пункте выдачи Boxberry. Заказ готовится к возврату. {COMMENT}',
		'smsnotify'=>true, 'sms'=>'{firstname}, вы не забрали заказ #{order_id} в пункте выдачи Boxberry. Заказ готовится к возврату. {COMMENT}'),
	 8 => array('name'=>'Возвращено с курьерской доставки',
		'notify'=>true, 'enabled'=>true, 'status'=>8, 'text'=>'{firstname}, курьер не смог вручить вам заказ #{order_id}. Заказ готовится к возврату. {COMMENT}',
		'smsnotify'=>true, 'sms'=>'{firstname}, курьер не смог вручить вам заказ #{order_id}. Заказ готовится к возврату. {COMMENT}'),
	 9 => array('name'=>'Отправлено в пункт приема',
		'notify'=>true, 'enabled'=>true, 'status'=>8, 'text'=>'Покупатель отказался от покупки, заказ отправлен в ИМ. {COMMENT}'),
	10 => array('name'=>'Возвращено в пункт приема',
		'notify'=>true, 'enabled'=>true, 'status'=>8, 'text'=>'Недоставленный заказ вернулся в пункт приема. {COMMENT}'),
	11 => array('name'=>'Возвращено в ИМ',
		'notify'=>true, 'enabled'=>true, 'status'=>13, 'text'=>'Заказ возвращен в ИМ. {COMMENT}')
	);

	private function setConfig() {
		if (!$this->config->get('boxberryupd_set')) {
			foreach($this->STATUS_MAPPING as $code=>$vals) {
				$this->STATUS_MAPPING[$code]['text'] = "{DATE}\n".$vals['text'];
			}
			return;
		}
	
		foreach($this->CONFIG as $key=>$conf) {
			$this->CONFIG[$key] = $this->config->get('boxberryupd_'.$key);
		}
		$this->CONFIG['status']	= $this->config->get('boxberry_updater_status');
		foreach($this->STATUS_MAPPING as $code=>$vals) {
			$this->STATUS_MAPPING[$code]['notify'] = $this->config->get('boxberryupd_mapping'.$code.'_notify');
			$this->STATUS_MAPPING[$code]['enabled'] = $this->config->get('boxberryupd_mapping'.$code.'_enabled');
			$this->STATUS_MAPPING[$code]['status'] = $this->config->get('boxberryupd_mapping'.$code.'_status');
			$this->STATUS_MAPPING[$code]['text'] = $this->config->get('boxberryupd_mapping'.$code.'_text');
			if (isset($this->STATUS_MAPPING[$code]['sms'])) {
				$this->STATUS_MAPPING[$code]['sms'] = $this->config->get('boxberryupd_mapping'.$code.'_sms');
			}
			if (isset($this->STATUS_MAPPING[$code]['smsnotify'])) {
				$this->STATUS_MAPPING[$code]['smsnotify'] = $this->config->get('boxberryupd_mapping'.$code.'_smsnotify');
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
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group`='boxberryupd'");
	}
	
	public function index() {
		$this->load->language('shipping/boxberry_updater');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['boxberryupd_order_statuses'] = implode(',', $this->request->post['boxberryupd_order_statuses']);
			$this->model_setting_setting->editSetting('boxberryupd', $this->request->post);	

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
			'href'      => $this->url->link('shipping/boxberry_updater', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/boxberry_updater', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 		
		
		
		$this->load->model('localisation/order_status');
    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['boxberryupd_order_statuses'])) {
			$this->data['boxberryupd_order_statuses'] = $this->request->post['boxberryupd_order_statuses'];
		} else {
			$this->data['boxberryupd_order_statuses'] = explode(',', $this->config->get('boxberryupd_order_statuses'));
		}
		
		foreach($this->CONFIG as $key=>$conf) {
			if (isset($this->request->post['boxberryupd_'.$key])) {
				$this->data['boxberryupd_'.$key] = $this->request->post['boxberryupd_'.$key];
			} else {
				$this->data['boxberryupd_'.$key] = $conf;
			}
		}
		
		$this->data['status_mapping'] = $this->STATUS_MAPPING;

		if (isset($this->request->post['boxberry_updater_status'])) {
			$this->data['boxberry_updater_status'] = $this->request->post['boxberry_updater_status'];
		} else {
			$this->data['boxberry_updater_status'] = $this->CONFIG['status'];
		}
		
		foreach($this->STATUS_MAPPING as $code=>$val) {
			if (isset($this->request->post['boxberryupd_mapping'.$code.'_notify'])) {
				$this->data['boxberryupd_mapping'.$code.'_notify'] = $this->request->post['boxberryupd_mapping'.$code.'_notify'];
			} else {
				$this->data['boxberryupd_mapping'.$code.'_notify'] = $val['notify'];
			}
			if (isset($this->request->post['boxberryupd_mapping'.$code.'_smsnotify'])) {
				$this->data['boxberryupd_mapping'.$code.'_smsnotify'] = $this->request->post['boxberryupd_mapping'.$code.'_smsnotify'];
			} elseif (isset($val['smsnotify'])) {
				$this->data['boxberryupd_mapping'.$code.'_smsnotify'] = $val['smsnotify'];
			}
			
			if (isset($this->request->post['boxberryupd_mapping'.$code.'_enabled'])) {
				$this->data['boxberryupd_mapping'.$code.'_enabled'] = $this->request->post['boxberryupd_mapping'.$code.'_enabled'];
			} else {
				$this->data['boxberryupd_mapping'.$code.'_enabled'] = $val['enabled'];
			}
			
			if (isset($this->request->post['boxberryupd_mapping'.$code.'_status'])) {
				$this->data['boxberryupd_mapping'.$code.'_status'] = $this->request->post['boxberryupd_mapping'.$code.'_status'];
			} else {
				$this->data['boxberryupd_mapping'.$code.'_status'] = $val['status'];
			}
			
			if (isset($this->request->post['boxberryupd_mapping'.$code.'_text'])) {
				$this->data['boxberryupd_mapping'.$code.'_text'] = $this->request->post['boxberryupd_mapping'.$code.'_text'];
			} else {
				$this->data['boxberryupd_mapping'.$code.'_text'] = $val['text'];
			}

			if (isset($this->request->post['boxberryupd_mapping'.$code.'_sms'])) {
				$this->data['boxberryupd_mapping'.$code.'_sms'] = $this->request->post['boxberryupd_mapping'.$code.'_sms'];
			} elseif (isset($val['sms'])) {
				$this->data['boxberryupd_mapping'.$code.'_sms'] = $val['sms'];
			}
		}
		
		$this->template = 'shipping/boxberry_updater.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/boxberry_updater')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function update() {
		if ($this->ECHO) {
			header('Content-Type: text/html; charset=utf-8');
		}
		$this->setConfig();
    	$this->language->load('sale/order');
		$this->load->model('sale/order');
	
		if (!$this->config->get('boxberry_updater_status')) return false;
		
		$orders = $this->getOrdersToUpdate();
		foreach ($orders as $order) {
			try {
				if (strlen(trim($order['track_no'])) < 8 || strlen(trim($order['track_no'])) >= 10) continue;
				$statuses = $this->getStatuses($order['track_no']);
				$this->handleStatus($order, $statuses);
			} catch(BoxberryException $e) {
				$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].') Boxberry-tracking error: ' . $e->getMessage(), 0);
			}
		}
	}
	
	protected function getOrdersToUpdate() {
		$shipping_code = $this->config->get('boxberryupd_shipping_code');
		$shcode_where = ($shipping_code ? " AND o.shipping_code LIKE '" . $shipping_code . "%'" : '');
		$not_in = ($this->config->get('boxberryupd_order_statuses') ? $this->config->get('boxberryupd_order_statuses') : '0');
		$query = $this->db->query("SELECT o.* FROM `" . DB_PREFIX . "order` o
			WHERE o.track_no <> '' AND CHAR_LENGTH(o.track_no) < 10 $shcode_where AND o.order_status_id <> '0' AND NOT(o.order_status_id IN($not_in)) ORDER BY RAND() LIMIT 40");
		return $query->rows;
	}
	
	
	private function getStatuses($track_no) {
		$url='http://api.boxberry.de/json.php?token='.$this->config->get('boxberryupd_token').'&method=ListStatuses&ImId='.$track_no;
		$handle = fopen($url, "rb");
		$contents = stream_get_contents($handle);
		fclose($handle);
		$data=json_decode($contents, true);
		print_r($data);
		if(count($data)<=0 || isset($data[0]['err'])) {
			 // если произошла ошибка, и ответ не был получен
			throw new BoxberryException($data[0]['err']);
			return false;
		}
		else {
			// все отлично, ответ получен, теперь в массиве $data
			// все статусы по данному заказу в формате:
			/*
				$data[0...n]=array(
					 'Date'=>'Дата статуса в формате ДД/ММ/ГГ Ч:М:C PM/AM',
					 'Name'=>'Наименование',
					 'Comment'=>'Комментарий'
				);
			*/
			return $data;
		}
	}
	
	private function handleStatus($order, $state) {
		$this->load->model('sale/order');
		$return = false;
		foreach ($state as $s) {
			$s = (array) $s;
			
			$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'). Handle tracking '.json_encode($s).'.', 5);
			
			$status = false;
			foreach ($this->STATUS_MAPPING as $STATE) {
				if ($STATE['name'] == $s['Name']) {
					$status = $STATE['status'];
					break;
				}
			}
			if ($status === false) {
				$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'). Unknown status: "'.$s['Name'].'".', 0);
				break;
			}
			
			$comment = $this->getComment($s, $STATE, $order);

			$q1 = $this->db->query("SELECT * FROM `".DB_PREFIX."order_history` WHERE order_id='".(int)$order['order_id']."' AND order_status_id='".(int)$status."' AND comment LIKE '".$this->db->escape($comment)."%' ORDER BY date_added DESC LIMIT 1");
			if ($q1->row) {
				$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'), status: '.$s['Name'].' ('.$status.'). Status already changed.', 3);
				continue;
			}
			
			try {
				$data = array(
					'order_status_id' => $status,
					'notify' => $STATE['notify'],
					'comment' => $comment
				);
				$this->model_sale_order->addOrderHistory($order['order_id'], $data);
				$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'), status: '.$s['Name'].' ('.$status.'). Added order history.', 3);
				if (isset($STATE['smsnotify']) && $STATE['smsnotify']) {
					$this->smsNotify($order, $this->getComment($s, $STATE, $order, 'sms'));
				}
			} catch (Exception $e) {
				$this->log('Order #'.$order['track_no'].' (ID:'.$order['order_id'].'), status: '.$s['Name'].' ('.$status.'). Unable to add order history ('.$e->getMessage().').', 1);
			}
		}
	}

	private function getComment($s, $STATE, $order_info, $key='text') {
		$date = $this->parseDate($s['Date']);
		$text = str_replace('{DATE}', $date, $STATE[$key]);
		$text = str_replace('{COMMENT}', $s['Comment'], $text);
		$text = str_replace('{firstname}', $order_info['firstname'], $text);
		$text = str_replace('{lastname}', $order_info['lastname'], $text);
		$text = str_replace('{order_id}', $order_info['order_id'], $text);
		$text = str_replace('{track_no}', $order_info['track_no'], $text);
		$address_arr = array($order_info['shipping_zone'], $order_info['shipping_city'], $order_info['shipping_address_1'], $order_info['shipping_address_2']);
		$address_arr = array_diff($address_arr, array(''));
		$text = str_replace('{address}', implode(', ', $address_arr), $text);
		return $text;
	}

	private function parseDate($str) {
		$date = date_parse(str_replace('T', ' ', $str));
		return date('d.m.Y H:i:s', mktime( $date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']));
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
		$fp = fopen(DIR_LOGS.'boxberry_updater.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		if ($this->ECHO) echo nl2br(htmlspecialchars($msg))."<br/>\n";
		fclose($fp);
	}
}

class BoxberryException         extends Exception { }