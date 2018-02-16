<?php
class ModelShippingDigiCalc extends Model {
	function getQuote($address) {
		$this->load->language('shipping/digipost');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('auspost_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('digipost_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		$error = '';

		$quote_data = array();

		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('digicalc_weight_class_id'));
			
			
			if ($this->config->get('digicalc_delivery_type_ems') == 'on') {
				$ems = 1;
			} else {
				$ems = 0;
			}
			
			if ($this->config->get('digicalc_all_valued') == '1') {
				$value = $this->cart->getSubTotal();
			} else {
				$value = 0;
			}

			$region = array();
			$region['from'] = $this->config->get('digicalc_postcode');
			$region['to'] = (int)$address['postcode'];

			if (isset($region['from']) && isset($region['to'])){

				$username=$this->config->get('digipost_login');
				$password=$this->config->get('digipost_api_key');

				$url ='https://api.digi-post.ru/calc?to='.$region['to'].'&from='.$region['from'].'&weight='.$weight.'&value='.$value.'&ems='.$ems;
				
				//print_r($url);

				$process = curl_init($url);
				curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
				curl_setopt($process, CURLOPT_TIMEOUT, 6);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$result = curl_exec($process);
				curl_close($process);

				$Response = json_decode($result);
				
				//print_r($Response);

				if ($Response->error > 0) {
					$error = $Response->error;
				}

				if($this->cart->getSubTotal() < $this->config->get('digicalc_cost_free')) {
					if ($this->config->get('digicalc_delivery_type_parcel') && $Response->parcel) {
						$title = $Response->parcel->type;
						if ($this->config->get('digicalc_display_time')) {
							$title .= ' (' . $Response->parcel->eta . ' ' . $this->language->get('text_eta') . ')';
						}
						$cost = $this->config->get('digicalc_round_summ') ? round($Response->parcel->cost) : $Response->parcel->cost;
						$quote_data['parcel'] = array(
							'code'         => 'digicalc.parcel',
							'title'        => $title,
							'cost'         => $cost + $this->config->get('digicalc_addcost'),
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => $cost + $this->config->get('digicalc_addcost')." руб.",
						);
					}
					if ($this->config->get('digicalc_delivery_type_bookpost_1class')  && $Response->bookpost_1class) {
						$title = $Response->bookpost_1class->type;
						if ($this->config->get('digicalc_display_time')) {
							$title .= ' (' . $Response->bookpost_1class->eta . ' ' . $this->language->get('text_eta') . ')';
						}
						$cost = $this->config->get('digicalc_round_summ') ? round($Response->bookpost_1class->cost_nds) : $Response->bookpost_1class->cost_nds;
						
						$quote_data['bookpost_1class'] = array(
							'code'         => 'digicalc.bookpost_1class',
							'title'        => $title,
							'cost'         => $cost + $this->config->get('digicalc_addcost'),
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => $cost + $this->config->get('digicalc_addcost'). " руб."
						);
					}
					if ($this->config->get('digicalc_delivery_type_valued_bookpost') && $Response->valued_bookpost) {
						$title = $Response->valued_bookpost->type;
						if ($this->config->get('digicalc_display_time')) {
							$title .= ' (' . $Response->valued_bookpost->eta . ' ' . $this->language->get('text_eta') . ')';
						}
						$cost = $this->config->get('digicalc_round_summ') ? round($Response->valued_bookpost->cost_nds) : $Response->valued_bookpost->cost_nds;
						$quote_data['valued_bookpost'] = array(
							'code'         => 'digicalc.valued_bookpost',
							'title'        => $title,
							'cost'         => $cost + $this->config->get('digicalc_addcost'),
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => $cost + $this->config->get('digicalc_addcost'). " руб."
						);
					}
					if ($this->config->get('digicalc_delivery_type_bookpost') && $Response->bookpost) {
						$title = $Response->bookpost->type;
						if ($this->config->get('digicalc_display_time')) {
							$title .= ' (' . $Response->bookpost->eta . ' ' . $this->language->get('text_eta') . ')';
						}
						$cost = $this->config->get('digicalc_round_summ') ? round($Response->bookpost->cost_simple_bookpost_cost_nds) : $Response->bookpost->cost_simple_bookpost_cost_nds;
						$quote_data['valued_bookpost'] = array(
							'code'         => 'digicalc.bookpost',
							'title'        => $title,
							'cost'         => $cost + $this->config->get('digicalc_addcost'),
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => $cost + $this->config->get('digicalc_addcost'). " руб."
						);
					}
					if ($this->config->get('digicalc_delivery_type_ems') && $Response->ems) {
						$title = $Response->ems->type;
						if ($this->config->get('digicalc_display_time')) {
							$title .= ' (' . $Response->ems->eta_max . ' ' . $this->language->get('text_eta') . ')';
						}
						$cost = $this->config->get('digicalc_round_summ') ? round($Response->ems->cost) : $Response->ems->cost;
						$quote_data['cost'] = array(
							'code'         => 'digicalc.ems',
							'title'        => $title,
							'cost'         => $cost + $this->config->get('digicalc_addcost'),
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => $cost + $this->config->get('digicalc_addcost'). " руб."
						);
					}
				} else {
					$quote_data['free'] = array(
							'code'         => 'digicalc.free',
							'title'        => "Бесплатная доставка",
							'cost'         => 0,
							'tax_class_id' => $this->config->get('digicalc_tax_class_id'),
							'text'         => "бесплатно"
						);
				}

			} // isset from and to

		} //status

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'digicalc',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('digicalc_sort_order'),
				'error'      => $error
			);
		}

		return $method_data;

	}
}
?>