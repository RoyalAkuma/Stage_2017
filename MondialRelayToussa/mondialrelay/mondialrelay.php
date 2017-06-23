<?php
/**
 *
 */
class plgHikashopMondialrelay extends JPlugin
{
	/**
	 *
	 */
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);

		$this->loadLanguage('plg_hikashop_mondialrelay', JPATH_ADMINISTRATOR );
	}

	/**
	 *
	 */
	public function onFieldsLoad(&$fields, &$options) {
		$me = new stdClass();
		$me->name = 'mondialrelay';
		$me->text = 'Sélecteur point relai Mondial Relay';
		$me->options = array('required', 'default', 'columnname', 'format', 'allow', 'mondialrelay_options');

		$fields[] = $me;

		$opt = new stdClass();
		$opt->name = 'mondialrelay_options';
		$opt->text = JText::_('DATE_PICKER_OPTIONS');
		$opt->obj = 'fieldOpt_mondialrelay_options';

		$options[$opt->name] = $opt;
	}
}

if(defined('HIKASHOP_COMPONENT')) {
	/**
	 *
	 */
	require_once( dirname(__FILE__).DS.'mondialrelay_class.php' );
}