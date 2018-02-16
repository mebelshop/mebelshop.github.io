<?php
class ControllerShippingDigiCalc extends Controller {
	private $error = array(); 

	public function index() {   
		$this->language->load('shipping/digicalc');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('digicalc', $this->request->post);             

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_standard'] = $this->language->get('entry_standard');
		$this->data['entry_express'] = $this->language->get('entry_express');
		$this->data['entry_display_time'] = $this->language->get('entry_display_time');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');         
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['digicalc_postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
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
			'href'      => $this->url->link('shipping/digicalc', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('shipping/digicalc', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['digicalc_postcode'])) {
			$this->data['digicalc_postcode'] = $this->request->post['digicalc_postcode'];
		} else {
			$this->data['digicalc_postcode'] = $this->config->get('digicalc_postcode');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_parcel'])) {
			$this->data['digicalc_delivery_type_parcel'] = $this->request->post['digicalc_delivery_type_parcel'];
		} else {
			$this->data['digicalc_delivery_type_parcel'] = $this->config->get('digicalc_delivery_type_parcel');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_bookpost_1class'])) {
			$this->data['digicalc_delivery_type_bookpost_1class'] = $this->request->post['digicalc_delivery_type_bookpost_1class'];
		} else {
			$this->data['digicalc_delivery_type_bookpost_1class'] = $this->config->get('digicalc_delivery_type_bookpost_1class');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_valued_bookpost'])) {
			$this->data['digicalc_delivery_type_valued_bookpost'] = $this->request->post['digicalc_delivery_type_valued_bookpost'];
		} else {
			$this->data['digicalc_delivery_type_valued_bookpost'] = $this->config->get('digicalc_delivery_type_valued_bookpost');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_bookpost '])) {
			$this->data['digicalc_delivery_type_bookpost '] = $this->request->post['digicalc_delivery_type_bookpost '];
		} else {
			$this->data['digicalc_delivery_type_bookpost '] = $this->config->get('digicalc_delivery_type_bookpost ');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_ems'])) {
			$this->data['digicalc_delivery_type_ems'] = $this->request->post['digicalc_delivery_type_ems'];
		} else {
			$this->data['digicalc_delivery_type_ems'] = $this->config->get('digicalc_delivery_type_ems');
		}
		
		if (isset($this->request->post['digicalc_delivery_type_bookpost'])) {
			$this->data['digicalc_delivery_type_bookpost'] = $this->request->post['digicalc_delivery_type_bookpost'];
		} else {
			$this->data['digicalc_delivery_type_bookpost'] = $this->config->get('digicalc_delivery_type_bookpost');
		}
		
		if (isset($this->request->post['digicalc_display_time'])) {
			$this->data['digicalc_display_time'] = $this->request->post['digicalc_display_time'];
		} else {
			$this->data['digicalc_display_time'] = $this->config->get('digicalc_display_time');
		}

		if (isset($this->request->post['digicalc_weight_class_id'])) {
			$this->data['digicalc_weight_class_id'] = $this->request->post['digicalc_weight_class_id'];
		} else {
			$this->data['digicalc_weight_class_id'] = $this->config->get('digicalc_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['digicalc_tax_class_id'])) {
			$this->data['digicalc_tax_class_id'] = $this->request->post['digicalc_tax_class_id'];
		} else {
			$this->data['digicalc_tax_class_id'] = $this->config->get('digicalc_tax_class_id');
		}
		
		//var_dump($this->config->get('digicalc_addcost'));
		
		if (isset($this->request->post['digicalc_addcost'])) {
			$this->data['digicalc_addcost'] = $this->request->post['digicalc_addcost'];
		} elseif (!$this->config->has('digicalc_addcost')) {
			$this->data['digicalc_addcost'] = 0;
		} else {
			$this->data['digicalc_addcost'] = $this->config->get('digicalc_addcost');
		}
		
		if (isset($this->request->post['digicalc_cost_free'])) {
			$this->data['digicalc_cost_free'] = $this->request->post['digicalc_cost_free'];
		} elseif (!$this->config->has('digicalc_cost_free')) {
			$this->data['digicalc_cost_free'] = 999999;
		} else {
			$this->data['digicalc_cost_free'] = $this->config->get('digicalc_cost_free');
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['digicalc_geo_zone_id'])) {
			$this->data['digicalc_geo_zone_id'] = $this->request->post['digicalc_geo_zone_id'];
		} else {
			$this->data['digicalc_geo_zone_id'] = $this->config->get('digicalc_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['digicalc_status'])) {
			$this->data['digicalc_status'] = $this->request->post['digicalc_status'];
		} else {
			$this->data['digicalc_status'] = $this->config->get('digicalc_status');
		}
		
		if (isset($this->request->post['digicalc_round_summ'])) {
			$this->data['digicalc_round_summ'] = $this->request->post['digicalc_round_summ'];
		} else {
			$this->data['digicalc_round_summ'] = $this->config->get('digicalc_round_summ');
		}
		
		if (isset($this->request->post['digicalc_all_valued'])) {
			$this->data['digicalc_all_valued'] = $this->request->post['digicalc_all_valued'];
		} else {
			$this->data['digicalc_all_valued'] = $this->config->get('digicalc_all_valued');
		}

		if (isset($this->request->post['digicalc_sort_order'])) {
			$this->data['digicalc_sort_order'] = $this->request->post['digicalc_sort_order'];
		} else {
			$this->data['digicalc_sort_order'] = $this->config->get('digicalc_sort_order');
		}                               

		$this->template = 'shipping/digicalc.tpl';
		$this->children = array(
			'common/header',        
			'common/footer' 
		);

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/digicalc')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!preg_match('/^[0-9]{6}$/', $this->request->post['digicalc_postcode'])){
			$this->error['digicalc_postcode'] = $this->language->get('error_postcode');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}       
	}
}
?>