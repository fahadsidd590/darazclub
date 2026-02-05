<?php

/**
 * Plugin Name: WC Easypay pk
 * Plugin URI: https://www.intelvue.com/wordpress-wc-easypay/?utm=wceasypay-plugin-page
 * Description: A woocommerce payment method Ad-on
 * Version: 1.0.1
 * Author: Intelvue
 * Author URI: https://www.intelvue.com/?utm=wceasypay-plugin-page
 * Requires at least: 4.2
 * Tested up to: 6.1
 *
 * Text Domain: wc-gateway-eppk
 * Domain Path: /languages/
 *
 */
class WC_Easypay_PK_Init
{
    const GATEWAY_SANDBOX_URL   = 'https://easypaystg.easypaisa.com.pk';
    const GATEWAY_URL           = 'https://easypay.easypaisa.com.pk';
    
    /**
     * Initializing wp hooks
     */
    public function __construct()
    {
        add_action( 'plugins_loaded', array($this, 'load_gateway') );
        add_action( 'woocommerce_payment_gateways', array($this, 'add_to_payment_gateways') );
        add_filter( 'query_vars', array($this, 'query_vars') );
        add_action('wp', array($this, 'process'));
        add_action('wp', array($this, 'process_ipn'));
        
    }
    
    /**
     * appending custom query vars. Used in hook
     * 
     * @param  array $vars
     * 
     * @return array
     */
    public function query_vars( $vars )
    {
        $vars[] = "order_id";
        $vars[] = "store_id";
        $vars[] = "postBackURL";
        $vars[] = "auth_token";
        $vars[] = "success_url";
        $vars[] = "url";
        $vars[] = "__easypay";
        return $vars;
    }
    
    
    /**
     * adding payment gateway to woocommerce gateway list. Used in hook
     * 
     * @param  array $gateways 
     * 
     * @return array
     */
    public function add_to_payment_gateways($gateways)
    {
        $gateways[] = 'WC_Easypaypk_Gateway';
        return $gateways;
    }
    
    /**
     * loading payment gateway for woocommerce
     * 
     * @return void
     */
    public function load_gateway()
    {
        require_once __DIR__ . '/class-wc-gateway-easypay-pk.php';
    }
    
    /**
     * process post and post back call for easypay and redirects to easy pay
     * 
     * @return void
     */
    public function process()
    {
        if(get_query_var('__easypay', null) !== null) {
            $easy_pay_action = get_query_var('__easypay');
            global $woocommerce;
            switch($easy_pay_action) {
                case 'post':
                    
                    $order_id = get_query_var('order_id', null);
                    $store_id = $this->get_option('store_id');

                    $order = new WC_Order($order_id);
                    
                    if(!$store_id) {
                        wc_add_notice( __('Error Easy Pay: Invalid store ID.'), 'error' );
                        $this->easypay_pk_ipn_log('Error Easy Pay: Invalid store ID.');
                        wp_redirect($woocommerce->cart->get_checkout_url());
                        return;
                    }

                    if(!$order_id || !$order->post) {
                        wc_add_notice( __('Error Easy Pay: Invalid order ID.'), 'error' );
                        $this->easypay_pk_ipn_log('Error Easy Pay: Invalid order ID.');
                        wp_redirect($woocommerce->cart->get_checkout_url());
                        return;
                    }

                    echo 'Please wait. You are redirecting ...
                            <form action="'.($this->get_gateway_url()).'/easypay/Index.jsf" method="POST" id="form1">
                                <input type="hidden" name="storeId" value="'.$store_id.'" />
                                <input type="hidden" name="amount" value="'.$order->get_total().'" />
                                <input type="hidden" name="postBackURL" value="'.site_url('?__easypay=postback').'" />
                                <input type="hidden" name="orderRefNum" value="'.$order_id.'" />
                            </form>
                            <script>
                            document.getElementById("form1").submit();
                            </script>';
                    exit();
                    break;

                case 'postback':

                    $post_back_url = get_query_var('postBackURL', null);
                    $auth_token = get_query_var('auth_token', null);

                    if(!$auth_token || !$post_back_url) {
                        wc_add_notice( __('Error Easy Pay: Invalid Post back parameters.'), 'error' );
                        $this->easypay_pk_ipn_log('Error Easy Pay: Invalid Post back parameters.');
                        wp_redirect($woocommerce->cart->get_checkout_url());
                    }

                    echo 'Please wait. You are redirecting ...
                        <form action="'.($this->get_gateway_url()).'/easypay/Confirm.jsf" method="POST" id="form2">
                            <input type="hidden" name="postBackURL" value="'.$post_back_url.'" />
                            <input type="hidden" name="auth_token" value="'.$auth_token.'" />
                        </form>
                        <script>
                            document.getElementById("form2").submit();
                        </script>';
                    exit();
                    break;
            }
        }
    }
    
    /**
     * process ipn request
     * 
     * @return void
     */
    public function process_ipn()
    {
        $url            = isset($_REQUEST['url']) ? sanitize_text_field($_REQUEST['url']) : null ;
        
        if(get_query_var('__easypay') == 'ipn' && $url) {
            
            $url_host        = parse_url($url, PHP_URL_HOST);
            $match_host      = parse_url($this->get_gateway_url(), PHP_URL_HOST);
            $host_validated  = $match_host === $url_host;
            
            if(!$host_validated) {
                $this->easypay_pk_ipn_log("Invalid URL Received: " . $url);
                exit();
            }
            
            $data = @json_decode(file_get_contents($url) , true);
                
            if(!$data || !is_array($data) || !isset($data['order_id'])) {
                $this->easypay_pk_ipn_log("Error in ipn data.");
                exit();
            }

            $order_id = $data['order_id'];
            
            
            $order = new WC_Order($order_id);

            if(!$order->post->ID) {
                $this->easypay_pk_ipn_log("Request received but order not found.");
                exit();
            }

            /**
             * Updating order status to processing
             */

            if(strtolower($data['transaction_status']) == 'paid') {
                $order->update_status( 'processing', sprintf( __( 'Response From Easypay PK. Transaction Status is %s and Payment method is %s', 'wc-gateway-eppk' ), $data['transaction_status'], $data['payment_method'] ) );

            } else {
                
                $order->update_status( 'on-hold', sprintf( __( 'Response From Easypay PK. Transaction Status is %s and Payment method is %s', 'wc-gateway-eppk' ), $data['transaction_status'], $data['payment_method'] ) );
            }

            /**
             * Adding ipn data to database as log
             */  
            add_post_meta($order->post->ID, 'EASYPAY_IPN_LOG', $data);
            exit();
        }
    }
    
    /**
     * Gateway url according to mode
     * 
     * @return string
     */
    public function get_gateway_url()
    {
        $sandbox = 'yes' === $this->get_option('sandbox', 'no');
        
        if($sandbox) {
            return self::GATEWAY_SANDBOX_URL;
        }
        
        return self::GATEWAY_URL;
    }
    
    /**
     * log ipn request from easypay
     * @param string $log_text
     * @return void
     */
    public function easypay_pk_ipn_log($log_text) 
    {
        if('yes' === $this->get_option('debug', 'no')) { // if debug mode enable
            
            file_put_contents(__DIR__ . '/easypayipnlog', 
                                  date('Y-m-d H:i:s') 
                                  . "\n\n" 
                                  . $log_text 
                                  . "\nRequested Data: " 
                                  . json_encode($_REQUEST) 
                                  . "\n\n", FILE_APPEND
                             );
            
        }
    }
    
    /**
     * get easy pay gateway option
     * @param  string $option_name
     * @param  mixed [$default = null]
     * @return mixed(array/string)
     */
    public function get_option($option_name, $default = null)
    {
        $settings = get_option('woocommerce_easypay_pk_settings');
                    
        return $settings && isset($settings[$option_name]) ? $settings[$option_name] : $default;
    }
    
    /**
     * handle ipn request here
     */
    public function ipn()
    {
        
    }
}

new WC_Easypay_PK_Init();