<?php
if ( ! class_exists( 'WC_Paymob_19305_Card_VPC_SAR_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_19305_Card_VPC_SAR_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-19305-card-vpc-sar';
		}
	}

}
