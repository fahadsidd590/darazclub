/**
 * Cart / Checkout block.
 * 
 * @since 10.1.0
 */
( () => {
    'use strict' ;

    var reactElement = window.wp.element ,
            wc_blocks_checkout = window.wc.blocksCheckout ,
            wc_plugin_data = window.wc.wcSettings ,
            wp_data = window.wp.data ,
            notice_ids = [ ] ;

    const {
        createNotice , removeNotice
    } = wp_data.dispatch( 'core/notices' )
    ;
            const{
                referal_code_added_message ,
                redeemed_wallet_amount_message ,
                redeem_wallet_amount_removed_message ,
            } = wc_plugin_data.getSetting( 'fs-affiliates-wc-blocks_data' )
    ;
            /**
             * Checkout referal code form block class.
             * 
             * @since 10.1.0
             * @return {JSX.Element} A Wrapper used to display the referal code form in the checkout page.
             */
            const CheckoutReferalCodeFormBlock = {
                getElement : function ( e ) {

                    if ( ! e.extensions['fs-affiliates'] ) {
                        return '' ;
                    }

                    // Return if the content doest not exists.
                    if ( ! e.extensions['fs-affiliates']['checkout_referal_code_form_html'] ) {
                        return '' ;
                    }

                    return reactElement.createElement( wc_blocks_checkout.TotalsWrapper , null ,
                            reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-block-referal-code-apply-panel' , title : e.extensions['fs-affiliates'].referal_code_title } ,
                                    reactElement.createElement( reactElement.RawHTML , null , e.extensions['fs-affiliates']['checkout_referal_code_form_html'] ) ) ) ;
                }
            } ;

    /**
     * Checkout affiliate form block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the affiliate form in the checkout page.
     */
    const CheckoutAffiliateFormBlock = {
        getElement : function ( e ) {
            
            if ( ! e.extensions['fs-affiliates'] ) {
                return '' ;
            }
             
            // Return if the content doest not exists.
            if ( ! e.extensions['fs-affiliates']['checkout_affiliate_form_field_html'] ) {
                return '' ;
            }

            return reactElement.createElement( reactElement.Fragment , null ,
                    reactElement.createElement( reactElement.RawHTML , null , e.extensions['fs-affiliates']['checkout_affiliate_form_field_html'] ) ) ;
        }
    } ;

    /**
     * Checkout register affiliate form block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the register affiliate form in the checkout page.
     */
    const RegisterAffiliateFormBlock = {
        getElement : function ( e ) {

            if ( ! e.extensions['fs-affiliates'] ) {
                return '' ;
            }
            
            // Return if the content doest not exists.
            if ( ! e.extensions['fs-affiliates']['checkout_register_affiliate_form_html'] ) {
                return '' ;
            }

            
            return reactElement.createElement( wc_blocks_checkout.TotalsWrapper , null ,
                    reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-block-register-affiliate-apply-panel' , title : e.extensions['fs-affiliates'].register_affiliate_form_title } ,
                            reactElement.createElement( reactElement.RawHTML , null , e.extensions['fs-affiliates']['checkout_register_affiliate_form_html'] ) ) ) ;
        }
    } ;

    /**
     * Referral code block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the referral code layout in the checkout page.
     */
    const ReferralFieldBlock = {
        getElement : function ( e ) {

            if ( undefined === e.extensions['fs-affiliates'] ) {
                return '' ;
            }

            // Return if the content doest not exists.
            if ( ! e.extensions['fs-affiliates']['checkout_referral_code_field_html'] ) {
                return '' ;
            }

            return reactElement.createElement( reactElement.Fragment , null ,
                    reactElement.createElement( reactElement.RawHTML , null , e.extensions['fs-affiliates']['checkout_referral_code_field_html'] ) ) ;
        }
    } ;

    /**
     * Handles events for referral form.
     * 
     * @since 10.1.0
     * @type object
     */
    const ReferralFormHandler = {
        init : function () {
            jQuery( document ).on( 'keyup' , '.fs-affiliates-block-referral-code' , this.validate_fund_input_field ) ;
            jQuery( document ).on( 'click' , '.fs-affiliates-block-referral-code_button' , this.apply_referral_code ) ;
        } ,
        validate_fund_input_field : function ( e ) {
            let $this = jQuery( e.currentTarget ) ;
            if ( $this.val() ) {
                jQuery( '.fs-affiliates-block-referral-code_button' ).attr( 'disabled' , false ) ;
            } else {
                jQuery( '.fs-affiliates-block-referral-code_button' ).attr( 'disabled' , true ) ;
            }

            // Hide the error while entering the code value.
            jQuery( '.wc-block-components-validation-error' ).text( '' ) ;
        } ,
        apply_referral_code : function ( e ) {
            e.preventDefault( ) ;
            let $this = jQuery( e.currentTarget ) ,
                    wrapper = jQuery( $this ).closest( '.fs-affiliates-block-referral-code-form_fields' ) ;

            Block( wrapper ) ;
            wc_blocks_checkout.extensionCartUpdate( {
                namespace : 'fs-affiliates' ,
                data : {
                    action : 'apply_referral_code' ,
                    code : wrapper.find( '.wc-block-components-text-input' ).val()
                }
            } ).then( () => {
                createNotice( 'success' , referal_code_added_message , {
                    id : 'fs-affiliates-referal-code-applied' ,
                    context : 'wc/cart' ,
                    type : 'snackbar'
                } ) ;
            } ).catch( err => {
                jQuery( '.wc-block-components-validation-error' ).text( err.message ) ;
            } ).finally( () => {
                unBlock( wrapper ) ;
            } ) ;
        } ,

    } ;

    /**
     * Wallet coupon block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the redeem wallet amount as coupon in the cart/checkout pages.
     */
    const WalletCouponBlock = {
        context: 'wc/checkout',
        getElement : function ( e ) {
            // Remove notices if already to aviod duplicates.
            removeNotices(WalletCouponBlock.context);
            if ( ! e.extensions['fs-affiliates'] ) {
                return '' ;
            }
            
            createNotices(e.extensions['fs-affiliates']['notices'], WalletCouponBlock.context);
            // Return if the content doest not exists.
            if ( ! e.extensions['fs-affiliates']['wallet_coupon_html'] ) {
                return '' ;
            }
            
            WalletCouponBlock.hideWCWalletCouponWrapper() ;
            
            return reactElement.createElement( reactElement.Fragment , null ,
                    reactElement.createElement( wc_blocks_checkout.ExperimentalDiscountsMeta , null ,
                            reactElement.createElement( reactElement.RawHTML , null , e.extensions['fs-affiliates']['wallet_coupon_html'] ) ) ) ;
        } ,
        hideWCWalletCouponWrapper : function () {
            let cart_coupon_wrapper = jQuery( '.wp-block-woocommerce-cart-order-summary-discount-block' ) ,
                    checkout_coupon_wrapper = jQuery( '.wp-block-woocommerce-checkout-order-summary-discount-block' ) ;

            if ( cart_coupon_wrapper.length && 1 === cart_coupon_wrapper.find( '.wc-block-components-totals-item' ).length ) {
                cart_coupon_wrapper.hide() ;
            } else if ( checkout_coupon_wrapper.length && 1 === checkout_coupon_wrapper.find( '.wc-block-components-totals-item' ).length ) {
                checkout_coupon_wrapper.hide() ;
            } else {
                cart_coupon_wrapper.find( '.wc-block-components-totals-coupon__fs_admin' ).hide() ;
                checkout_coupon_wrapper.find( '.wc-block-components-totals-coupon__fs_admin' ).hide() ;
            }
        }
 
    } ;

    /**
     * Handles events for redeem wallet form.
     * 
     * @since 10.1.0
     * @type object
     */
    const RedeemWalletFormHandler = {
        
        init : function () {   
            jQuery( document ).on( 'click' , '.fs_apply_wallet_balance_button' , this.redeem_wallet_balance ) ;
            jQuery( document ).on( 'click' , '.fs-affiliates-block-remove-wallet-amount_link' , this.remove_redeem_wallet_amount ) ;
        } ,
        
        redeem_wallet_balance : function ( e ) {
            e.preventDefault( ) ;
            let $this = jQuery( e.currentTarget ) ,
                    wrapper = jQuery( $this ).closest( '.fs-affiliates-block-redeem-wallet-notice_wrapper' ) ;

            Block( wrapper ) ;
            wc_blocks_checkout.extensionCartUpdate( {
                namespace : 'fs-affiliates' ,
                data : {
                    action : 'apply_redeem_wallet_amount' ,
                }
            } ).then( () => {
                createNotice( 'success' , redeemed_wallet_amount_message , {
                    id : 'fs-affiliates-redeemed-wallet-amount' ,
                    context : 'wc/cart' ,
                    type : 'snackbar'
                } ) ;
            } ).catch( err => {
                jQuery( '.wc-block-components-validation-error' ).text( err.message ) ;
            } ).finally( () => {
                WalletCouponBlock.hideWCWalletCouponWrapper() ;
                unBlock( wrapper ) ;
            } ) ;
        } ,
        remove_redeem_wallet_amount : function ( e ) {
            e.preventDefault( ) ;
            let $this = jQuery( e.currentTarget ) ;
            Block( $this ) ;
            wc_blocks_checkout.extensionCartUpdate( {
                namespace : 'fs-affiliates' ,
                data : {
                    action : 'remove_redeem_wallet_amount'
                }
            } ).then( () => {
                createNotice( 'success' , redeem_wallet_amount_removed_message , {
                    id : 'fs-affiliates-removed-wallet-amount' ,
                    context : 'wc/cart' ,
                    type : 'snackbar'
                } ) ;
            } ).catch( err => {
                alert( err ) ;
            } ).finally( () => {
                unBlock( $this ) ;
            } ) ;
        }
    } ;

    /**
     * Create notices to the block.
     * 
     * @since 10.1.0
     * @param {array} notices
     * @param {string} context
     * @returns {undefined}
     */
    function createNotices( notices , context ) {
        // Add eligible notices.
        if ( notices ) {
            jQuery.each( notices , function ( index , notice ) {
                if ( ! notice.content ) {
                    return null ;
                }

                createNotice( notice.type , '<div class="fs-affiliates-notices-wrapper">' + notice.content + '</div>' , {
                    id : index ,
                    context : context ,
                    isDismissible : true
                } ) ;

                notice_ids.push( index ) ;
            } ) ;
        }
    }

    /**
     * Remove added notices from the block.
     * 
     * @since 10.1.0
     * @param {string} context
     * @returns {undefined}
     */
    function removeNotices( context ) {
        jQuery.each( notice_ids , function ( index , id ) {
            removeNotice( id , context ) ;
        } ) ;
    }

    /**
     * Block
     * 
     * @since 10.1.0
     * @param string id             
     */
    function Block( id ) {
        if ( ! isBlocked( id ) ) {
            jQuery( id ).addClass( 'processing' ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        }
    }
    /**
     * Unblock
     * 
     * @since 10.1.0
     * @param string id             
     */
    function unBlock( id ) {
        jQuery( id ).removeClass( 'processing' ).unblock() ;
    }
    /**
     * Is Blocked
     * 
     * @since 10.1.0
     * @param string id             
     */
    function isBlocked( id ) {
        return jQuery( id ).is( '.processing' ) || jQuery( id ).parents( '.processing' ).length ;
    }

    // Inititalize the events for referral code form. 
    ReferralFormHandler.init() ;

    // Inititalize the events for redeem wallet form. 
    RedeemWalletFormHandler.init() ;

    // Register referral code form inner block in the checkout block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-referal-form-block\",\"icon\":\"calculator\",\"keywords\":[\"referral\",\"form\",\"code\"],\"version\":\"1.0.0\",\"title\":\"Referral Code Form\",\"description\":\"Shows the referal code form layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-order-summary-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : CheckoutReferalCodeFormBlock.getElement
    } ) ;

    // Register referral code field inner block in the checkout block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-referral-block\",\"icon\":\"schedule\",\"keywords\":[\"referral\",\"mandatory\",\"code\"],\"version\":\"1.0.0\",\"title\":\"Referral Code Mandatory Field\",\"description\":\"Shows the mandatory referral code field layout.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : ReferralFieldBlock.getElement
    } ) ;

    // Register affiliate form inner block in the checkout block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-affiliate-form-block\",\"icon\":\"calculator\",\"keywords\":[\"redeem\",\"form\",\"wallet\"],\"version\":\"1.0.0\",\"title\":\"Register Affiliate Form\",\"description\":\"Shows the register affiliate form layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : CheckoutAffiliateFormBlock.getElement
    } ) ;

    // Register checkout affiliate inner block in the checkout block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-register-affiliate-block\",\"icon\":\"schedule\",\"keywords\":[\"affiliate\",\"selection\"],\"version\":\"1.0.0\",\"title\":\"Affiliate Selection\",\"description\":\"Shows the affiliate selection layout.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : RegisterAffiliateFormBlock.getElement
    } ) ;

    // Register wallet coupon inner block in the cart block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-cart-wallet-coupon-block\",\"icon\":\"calculator\",\"keywords\":[\"wallet\",\"coupon\"],\"version\":\"1.0.0\",\"title\":\"Wallet Coupon\",\"description\":\"Shows the affiliate wallet coupon layout in the cart block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/cart-order-summary-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : WalletCouponBlock.getElement
    } ) ;

    // Register wallet coupon inner block in the checkout block.
    wc_blocks_checkout.registerCheckoutBlock( {
        metadata : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-wallet-coupon-block\",\"icon\":\"calculator\",\"keywords\":[\"wallet\",\"coupon\"],\"version\":\"1.0.0\",\"title\":\"Wallet Coupon\",\"description\":\"Shows the affiliate wallet coupon layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-order-summary-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        component : WalletCouponBlock.getElement
    } ) ;

    const getData = {
        'referral_code' : false ,
        'website' : false ,
        'promotion' : false ,
        'uploaded_key' : false ,
        'file_upload' : false ,
        'iagree' : false ,
        'affiliate_referrer' : false ,
    } ;

    jQuery( document ).on( 'change' , '#fs_affiliates_block_referral_code_fields' , function ( e ) {
        updateCheckoutFieldsData( 'referral_code' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#fs-affiliates-website' , function ( e ) {
        updateCheckoutFieldsData( 'website' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#fs-affiliates-promotion' , function ( e ) {
        updateCheckoutFieldsData( 'promotion' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#fs_affiliates_uploaded_file_key' , function ( e ) {
        updateCheckoutFieldsData( 'uploaded_key' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#fs-affiliates-file-upload' , function ( e ) {
        updateCheckoutFieldsData( 'file_upload' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#fs-affiliates-iagree-field' , function ( e ) {
        updateCheckoutFieldsData( 'iagree' , jQuery( this ).val() ) ;
    } ) ;

    jQuery( document ).on( 'change' , '#affiliate_referrer' , function ( e ) {
        updateCheckoutFieldsData( 'affiliate_referrer' , jQuery( this ).val() ) ;
    } ) ;

    const updateCheckoutFieldsData = ( key , value ) => {
        getData[key] = value ;

        window.wp.data.dispatch( window.wc.wcBlocksData.CHECKOUT_STORE_KEY ).__internalSetExtensionData(
                'fs-affiliates' ,
                { 'affiliates_fields' : getData } ,
                false ) ;

    } ;

} )() ;