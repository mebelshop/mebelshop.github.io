<?php
require_once(DIR_SYSTEM."library/sms.php");

class ModelExtensionShippingDeliverycrm extends Model {
	public $deliverycrm_error;
	public $deliverycrm_success;
	public $URL = 'https://apilr2.r-lab.biz/addtrack.ashx';
	
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
		15=> "Нова Пошта");
		
	function getQuote($address) {
		return array();
	}
	
	public function save($order_id, $track_no, $track_carrier) {
		if (!$this->config->get('deliverycrm_status')) {
			return false;
		}
		$track_no = trim($track_no);
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$order_info['order_id'] = $order_id;

		if ($order_info['track_no'] == $track_no) {
			return false;
		}
				
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET track_no='" . $this->db->escape($track_no) . "', track_carrier='" . $this->db->escape($track_carrier) . "'
			WHERE order_id = '" . (int)$order_id . "'");
		if (!$track_no) {
			return true;
		}
		
		$order_info['track_no'] = $track_no;
		$order_info['track_carrier'] = $track_carrier;
		$carrier_name = isset($this->CARRIERS[$track_carrier]) ? $this->CARRIERS[$track_carrier] : '';
		$order_info['carrier_name'] = $carrier_name;
		if ($this->config->get('deliverycrm_change_status')) {
			$order_info['order_status_id'] = $this->config->get('deliverycrm_order_status');
			$comment = 'Заказу присвоен трек-номер: '.$track_no.', доставка: '.$carrier_name;
			$this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'], $comment, false, true);
		}
		if ($this->config->get('deliverycrm_email_notify')) {
			$comment = $this->getComment($order_info, $this->config->get('deliverycrm_email_text'));
			$this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'], $comment, true, true);
		}
		if ($this->config->get('deliverycrm_sms_notify')) {
			$this->smsNotify($order_info, $this->getComment($order_info, $this->config->get('deliverycrm_sms_text')));
		}
		if ($this->config->get('deliverycrm_export')) {
			$this->exportDeliverycrm($order_info);
		}
		return array('success'=>$this->deliverycrm_success, 'error'=>$this->deliverycrm_error);
	}
	
	protected function smsNotify($order, $message) {
		$options = array(
			'to'       => $order['telephone'],
			'copy'     => '',
			'from'     => $this->config->get('deliverycrm_sms_gate_from'),
			'username'    => $this->config->get('deliverycrm_sms_gate_username'),
			'password' => $this->config->get('deliverycrm_gate_password'),
			'message'  => $message,
			'ext'      => null
		);
			
		$sms = new Sms($this->config->get('deliverycrm_sms_gatename'), $options);
		$sms->send();
		if ($sms->error) {
			$this->deliverycrm_error.= "\n".trim($sms->error);
			$this->log('Заказ #'.$order['track_no'].' (ID:'.$order['order_id'].'). Не удалось отправить SMS, тел.: ' . $order['telephone'] . ' (' . $sms->error . ').', 4);
		}
		else {
			$this->deliverycrm_success.= "\nSMS отправлено.";
		}
	}

	private function getComment($order, $text) {
		foreach ($order as $key=>$val) {
			if (strpos($text, $key) !== false) {
				$text = str_replace('{'.$key.'}', $val, $text);
			}
		}
		return $text;
	}
	
	/**
	* Регистрировать заказ в DeliveryCRM
	* @param array $order_info
	**/
	public function exportDeliverycrm($order_info) {
		$phone = $this->preparePhone($order_info['telephone']);
		
		
		if (!$phone) {
			$this->deliverycrm_error.= "\n".$this->LIVEINFORM_RESP['201'];
			return false;
		}
		$payment_name = trim($order_info['payment_firstname'].' '.$order_info['payment_lastname']);
		$shipping_name = trim($order_info['shipping_firstname'].' '.$order_info['shipping_lastname']);
		$data = array(
			"trackingUserId" => (int)$this->config->get('deliverycrm_user'),
			"trackingRequestKey" => md5($this->config->get('deliverycrm_user').':'.$order_info['track_no'].':'.$this->config->get('deliverycrm_api_id')),
			"testMode" => (bool)(intval($this->config->get('deliverycrm_test_mode')) > 0),
			"trackingData" => array(array(
			"trackingDataReferralId"  => 9,
				"trackingUserClientPhone" => $phone,
				"trackingUserClientTrack" => $order_info['track_no'],
				"trackingUserClientEmail" => $order_info['email'],
				"trackingUserClientName" => $shipping_name ? $shipping_name : $payment_name,
				"trackingUserClientItemCost" => (float)$order_info['total'],
				//"sendToUserEmailFullTracking" => false,
				//"sendToAdminEmailFullTracking" => false,
				"trackingUserClientOrderNumber" => (int)$order_info['order_id'],
				"trackingUserClientDescription" => "OpenCart",
				"trackingUserClientDeliveryServiceId" => (int)$order_info['track_carrier'],
				"trackingUserPaymentType" => 0
			))
		);
		
		$json = json_encode($data, JSON_UNESCAPED_UNICODE);
		
		$ch = curl_init($this->URL);
		curl_setopt_array($ch, array(
		  CURLOPT_PORT => 443,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_SSL_VERIFYHOST => false,
		  CURLOPT_POST           => true,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CONNECTTIMEOUT => 10,
		  CURLOPT_TIMEOUT        => 10,
		  CURLOPT_POSTFIELDS     => $json,
		  CURLOPT_HTTPHEADER     => array(
			//'Content-Type: application/json; charset=utf-8',
			'Content-Type: text/plain',
			//'Content-Length: '.strlen($json)
		  )
		));
		
		$tuData = curl_exec($ch);
		if(curl_errno($ch)){
			$info = curl_getinfo($ch);
			$this->deliverycrm_error.= "\n".'Ошибка сети: '.curl_error($tuCurl).'. потрачено: ' . $info['total_time'] . 'сек., URL: ' . $info['url'];
		} else {
			$data = @json_decode($tuData, true);
			if (isset($data['resultState']) && isset($data['resultInfo'])) {
				if ($data['resultState'] == 0) {
					$this->deliverycrm_success.= "\n".'Отслеживание DeliveryCRM успешно добавлено.';
					return true;
				}
				else {
					$this->deliverycrm_error.= "\n".'Ошибка DeliveryCRM: '.$data['resultInfo'];
				}
			}
			else {
				$this->deliverycrm_error.= "\n".'Неверный ответ сервера DeliveryCRM: '.$tuData;
			}
		}
		return false;
	}
	
	private function preparePhone($phone) {
		if (!$phone)
			return false;
		$phone = preg_replace('/[^\d]/i', '', $phone);
		if (strlen($phone) < 11) {
			$phone = '+7'.$phone;
		}
		else {
			$phone = '+'.$phone;
			if ($phone[1] == 8)
				$phone[1] = 7;
		}
		return $phone;
	}
}
