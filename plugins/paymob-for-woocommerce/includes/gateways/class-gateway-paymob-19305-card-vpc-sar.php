<?php
class Paymob_19305_Card_VPC_SAR_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-19305-card-vpc-sar';
		$this->method_title       = $this->title = __( 'Debit/Credit Card', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Secure Payment via Paymob Checkout', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
	public function admin_options() {
		PaymobAutoGenerate::gateways_method_title( $this->method_title, $this, $this->get_option( 'single_integration_id' ) );
	}
}
