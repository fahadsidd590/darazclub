<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(class_exists('WC_Payment_Gateway')) {
    
    class WC_Easypaypk_Gateway extends WC_Payment_Gateway 
    {
        /** @var bool Whether or not logging is enabled */
        public static $log_enabled = false;

        /** @var WC_Logger Logger instance */
        public static $log = false;

        
        /**
         * Constructor (Setting up extended parameters from woocommerce gateway class)
         * @return null
         */
        public function __construct()
        {

            $this->id                 = 'easypay_pk';
            $this->has_fields         = false;
            $this->order_button_text  = __( 'Proceed to Easypay', 'wc-gateway-eppk' );
            $this->method_title       = __( 'Easypay PK', 'wc-gateway-eppk' );
            $this->method_description = '';
            $this->supports           = array(
                'products'
            );

            $this->init_form_fields();
            $this->init_settings();

            $this->title         = $this->get_option( 'title' );
            $this->description   = $this->get_option( 'description' );
            $this->sandbox       = 'yes' === $this->get_option( 'sandbox', 'no' );
            $this->debug         = 'yes' === $this->get_option( 'debug', 'no' );
            $this->store_id      = $this->get_option( 'store_id' );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );


        }

        /**
         * Initialize Gateway Settings Form Fields
         */
        public function init_form_fields() {

            $this->form_fields = apply_filters( 'wc_eppk_form_fields', array(

                'enabled' => array(
                    'title'   => __( 'Enable/Disable', 'wc-gateway-eppk' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable Easypay PK', 'wc-gateway-eppk' ),
                    'default' => 'yes',
                    'description' => __( 
                                'Add this ipn URL under your easypay merchant portal. <b>' . site_url('/') . '?__easypay=ipn</b>' 
                                , 'wc-gateway-eppk' 
                    ),
                ),

                'title' => array(
                    'title'       => __( 'Title', 'wc-gateway-eppk' ),
                    'type'        => 'text',
                    'default'     => __( 'Easypay PK', 'wc-gateway-eppk' ),
                    'desc_tip'    => true,
                ),

                'description' => array(
                    'title'       => __( 'Description', 'wc-gateway-eppk' ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-gateway-eppk' ),
                    'default'     => __( 'Please remit payment to Store Name upon pickup or delivery.', 'wc-gateway-eppk' ),
                    'desc_tip'    => true,
                ),

                'instructions' => array(
                    'title'       => __( 'Instructions', 'wc-gateway-eppk' ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc-gateway-eppk' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'store_id' => array(
                    'title'       => __( 'Store ID', 'wc-gateway-eppk' ),
                    'type'        => 'text',
                    'description' => __( 'Your easypay merchant/store ID.', 'wc-gateway-eppk' ),
                    'desc_tip'    => true,
                ),

                'sandbox' => array(
                    'title'   => __( 'Sandbox Mode', 'wc-gateway-eppk' ),
                    'type'    => 'checkbox',
                    'default' => 'no'
                ),
                'debug' => array(
                    'title'   => __( 'Debug Mode', 'wc-gateway-eppk' ),
                    'type'    => 'checkbox',
                    'description' => __( 'Log stores in easypay\'s plugin folder', 'wc-gateway-eppk' ),
                    'default' => 'no'
                )
            ) );
        }

        /**
         * process payment and generate redirect for easy pay
         * @param  integer  $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

            $order = wc_get_order( $order_id );

            // Mark as on-hold (we're awaiting the payment)
            $order->update_status( 'on-hold', __( 'Awaiting Easypay Payment', 'wc-gateway-eppk' ) );

            // Reduce stock levels
            $order->reduce_order_stock();

            // Remove cart
            WC()->cart->empty_cart();

            // Return thankyou redirect
            return array(
                'result'    => 'success',
                'redirect'  => $this->get_return_url( $order )
            );
        }

        /**
         * Generate redirect url
         * @param  WC_Order $order
         * @return string
         */
        public function get_return_url( $order = null ) {
            return $url = add_query_arg(array(
                'success_url' => parent::get_return_url($order),
                'store_id' => $this->store_id,
                'order_id' => $order->id
            ), site_url('?__easypay=post'));
        }

    }
} // end class exists
