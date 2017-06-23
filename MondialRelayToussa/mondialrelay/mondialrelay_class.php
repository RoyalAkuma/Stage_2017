<?php
hikashop_get('class.field');
/**
 *
 */
class fieldOpt_mondialrelay_options {
	/**
	 *
	 */
	public function show($value) {
		if(!empty($value)) {
			if(is_string($value))
				$value = hikashop_unserialize($value);
		} else {
			$value = array();
		}

		$ret = '
<table class="table admintable table-stripped">
	<tr>
		<td class="key">Code client Mondial Relay</td>
		<td>
			<input type="text" name="field_options[mondialrelay_options][code_client]" value="'.@$value['code_client'].'" />
		</td>
	</tr>
	<tr>
		<td class="key">Clé d\'API Google Maps</td>
		<td>
			<input type="text" name="field_options[mondialrelay_options][maps_key]" value="'.@$value['maps_key'].'" />
			<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Comment obtenir cette clé</a>
		</td>
	</tr>
</table>';
		return $ret;
	}

	/**
	 *
	 */
	public function save(&$options) {
	}
}

if(class_exists('hikashopFieldText')) {
    class hikashopMondialrelayBrigde extends hikashopFieldText {}
} else {
    class hikashopMondialrelayBrigde extends hikashopText {}
}

/**
 *
 */
class hikashopMondialrelay extends hikashopMondialrelayBrigde {
	/**
	 *
	 */
	private function init($options) {
		static $init = null;
		if($init !== null)
			return $init;

		$doc = JFactory::getDocument();
		hikashop_loadJsLib('jquery');
		$doc->addScript('https://maps.googleapis.com/maps/api/js?sensor=false&key='.urlencode($options['maps_key']));
		$doc->addScript(HIKASHOP_LIVE.'plugins/hikashop/mondialrelay/jquery.plugin.mondialrelay.parcelshoppicker.js');
		$init = true;
		return $init;
	}

	/**
	 *
	 */
	public function display($field, $value, $map, $inside, $options = '', $test = false, $allFields = NULL, $allValues = NULL) {
		if(@$_REQUEST['ctrl']!='checkout' && @$_REQUEST['view']!='checkout'){
			return parent::display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);
		}
		$app = JFactory::getApplication();
		$shipping_address = $app->getUserState( HIKASHOP_COMPONENT.'.shipping_address');

		if(empty($shipping_address)){
			return 'Veuillez entrer votre adresse de livraison au préalable';
		}

		$addressClass = hikashop_get('class.address');
		$shipping_address_data = $addressClass->get($shipping_address);

		if(empty($shipping_address_data->address_post_code)){
			return 'Veuillez entrer votre code postal au préalable';
		}
		if(empty($shipping_address_data->address_country)){
			$code = 'FR';
		}else{
			$zoneClass = hikashop_get('class.zone');
			$country_data = $zoneClass->get($shipping_address_data->address_country);
			if($country_data){
				$code = $country_data->zone_code_2;
			}else{
				$code = 'FR';
			}
		}

		$datepicker_options = @$field->field_options['mondialrelay_options'];
		if(!empty($datepicker_options)) {
			if(is_string($datepicker_options))
				$datepicker_options = hikashop_unserialize($datepicker_options);
		} else {
			$datepicker_options = array();
		}

		if(empty($datepicker_options['code_client'])){
			return 'Veuillez entrer votre code client Mondial Relay dans les options de votre champs personnalisé';
		}

		if(empty($datepicker_options['maps_key'])){
			return 'Veuillez entrer votre clé d\'API Google Maps dans les options de votre champs personnalisé';
		}

		$this->init($datepicker_options);

		$doc = JFactory::getDocument();
		$js = '
			window.hikashop.ready(function(){
					hkjQuery("#'.$this->prefix.@$field->field_namekey.$this->suffix.'_zone_widget").MR_ParcelShopPicker({
							Target: "#'.$this->prefix.@$field->field_namekey.$this->suffix.'",
							TargetDisplayInfoPR: "#'.$this->prefix.@$field->field_namekey.$this->suffix.'",
							Brand: "'.$datepicker_options['code_client'].'",
							Country: "'.$code.'",
							PostCode: "'.str_replace("'","\'",$shipping_address_data->address_post_code).'"
					});
			});  ';

		$doc->addScriptDeclaration($js);
		$html = '<div id="'.$this->prefix.@$field->field_namekey.$this->suffix.'_zone_widget"></div>';
		$html .= '<br/>Point Relais Selectionné : '.parent::display($field,$value,$map,$inside,$options,$test,$allFields,$allValues);
		return $html;
	}
}