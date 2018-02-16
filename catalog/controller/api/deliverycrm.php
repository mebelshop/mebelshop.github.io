<?php
class ControllerApiDeliverycrm extends Controller {

	public function save() {
		$this->load->language('api/order');

		$json = array();

		if (!$this->config->get('deliverycrm_ignore_security') && !isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/shipping/deliverycrm');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
				$res = $this->model_extension_shipping_deliverycrm->save($order_id, $this->request->post['track_no'], $this->request->post['track_carrier']);
				$json['success'] = 'Трек-номер (идентификатор отправления) сохранен!';
				
				if (isset($res['error'])) {
					$json['error'] = $res['error'];
				}
				if (isset($res['success'])) {
					$json['success'].= "\n".$res['success'];
				}
			} else {
				$order_id = 0;
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}