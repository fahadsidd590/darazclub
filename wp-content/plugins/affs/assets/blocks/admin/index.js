/**
 * Editor block.
 * 
 * @since 10.1.0
 */
( () => {
    'use strict' ;

    var reactElement = window.wp.element ,
            blocks = window.wp.blocks ,
            wc_blocks_checkout = window.wc.blocksCheckout ,
            blockEditor = window.wp.blockEditor ,
            wp_components = window.wp.components ,
            wc_plugin_data = window.wc.wcSettings ;

    const{
        referal_code_form_title ,
        register_affiliate_form_title
    } = wc_plugin_data.getSetting( 'fs-affiliates-wc-blocks_data' )
    ;
            /**
             * Referal code form block class.
             * 
             * @since 10.1.0
             * @return {JSX.Element} A Wrapper used to display the referal code form in the checkout block.
             */
            const ReferalCodeFormBlock = {
                checkoutSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-register-referal-code-form-block\",\"icon\":\"calculator\",\"keywords\":[\"referral\",\"form\",\"Code\"],\"version\":\"1.0.0\",\"title\":\"Referral Code Form\",\"description\":\"Shows the referal code form layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-order-summary-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
                getElement : function ( e ) {
                    return   reactElement.createElement( wp_components.Disabled , { } , reactElement.createElement( wc_blocks_checkout.TotalsWrapper , { } , ReferalCodeFormBlock.getFormField() ) ) ;
                } ,
                getFormField : function () {
                    return reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-referal-code-form-block' , title : referal_code_form_title } ) ;
                } ,
                edit : function ( attributes ) {
                    return reactElement.createElement( 'div' , blockEditor.useBlockProps() , ReferalCodeFormBlock.getElement() ) ;
                } ,
                save : function ( e ) {
                    return reactElement.createElement( 'div' , blockEditor.useBlockProps.save() ) ;
                }
            } ;

    /**
     * Referral field form block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the referal code field in the checkout block.
     */
    const ReferralFieldBlock = {
        checkoutSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-register-affiliate-block\",\"icon\":\"calculator\",\"keywords\":[\"referral\",\"field\"],\"version\":\"1.0.0\",\"title\":\"Referral Field\",\"description\":\"Shows the referral field layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        getElement : function ( e ) {
            return   reactElement.createElement( wp_components.Disabled , { } , reactElement.createElement( wc_blocks_checkout.TotalsWrapper , { } , ReferralFieldBlock.getFormField() ) ) ;
        } ,
        getFormField : function () {
            return reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-referal-code-form-block' , title : register_affiliate_form_title } ) ;
        } ,
        edit : function ( attributes ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps() , ReferralFieldBlock.getElement() ) ;
        } ,
        save : function ( e ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps.save() ) ;
        }
    } ;

    /**
     * Register Affiliate form block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the referal code form in the checkout block.
     */
    const RegisterAffiliateFormBlock = {
        checkoutSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-register-affiliate-block\",\"icon\":\"calculator\",\"keywords\":[\"register\",\"form\",\"affiliate\"],\"version\":\"1.0.0\",\"title\":\"Register Affiliate Form\",\"description\":\"Shows the register affiliate form layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        getElement : function ( e ) {
            return   reactElement.createElement( wp_components.Disabled , { } , reactElement.createElement( wc_blocks_checkout.TotalsWrapper , { } , RegisterAffiliateFormBlock.getFormField() ) ) ;
        } ,
        getFormField : function () {
            return reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-referal-code-form-block' , title : register_affiliate_form_title } ) ;
        } ,
        edit : function ( attributes ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps() , RegisterAffiliateFormBlock.getElement() ) ;
        } ,
        save : function ( e ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps.save() ) ;
        }
    } ;

    /**
     * Register Checkout Affiliate form block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the checkout affiliate form in the checkout block.
     */
    const CheckoutAffiliateFormBlock = {
        checkoutSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-register-affiliate-block\",\"icon\":\"calculator\",\"keywords\":[\"checkout\",\"form\",\"affiliate\"],\"version\":\"1.0.0\",\"title\":\"Checkout Affiliate Form\",\"description\":\"Shows the checkout affiliate form layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-fields-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        getElement : function ( e ) {
            return   reactElement.createElement( wp_components.Disabled , { } , reactElement.createElement( wc_blocks_checkout.TotalsWrapper , { } , CheckoutAffiliateFormBlock.getFormField() ) ) ;
        } ,
        getFormField : function () {
            return reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-referal-code-form-block' , title : register_affiliate_form_title } ) ;
        } ,
        edit : function ( attributes ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps() , CheckoutAffiliateFormBlock.getElement() ) ;
        } ,
        save : function ( e ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps.save() ) ;
        }
    } ;

    /**
     * Register Wallet Coupon block class.
     * 
     * @since 10.1.0
     * @return {JSX.Element} A Wrapper used to display the wallet coupon in the checkout block.
     */
    const WalletCouponBlock = {
        cartSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-cart-wallet-coupon-block\",\"icon\":\"calculator\",\"keywords\":[\"coupon\",\"wallet\"],\"version\":\"1.0.0\",\"title\":\"Wallet Coupon\",\"description\":\"Shows the wallet coupon layout in the cart block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/cart-order-summary-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        checkoutSchema : JSON.parse( "{\"name\":\"woocommerce/fs-affiliates-wc-checkout-wallet-coupon-block\",\"icon\":\"calculator\",\"keywords\":[\"wallet\",\"coupon\"],\"version\":\"1.0.0\",\"title\":\"Wallet Coupon\",\"description\":\"Shows the wallet coupon layout in the checkout block.\",\"category\":\"woocommerce\",\"supports\":{\"align\":false,\"html\":false,\"multiple\":false,\"reusable\":false},\"attributes\":{\"className\":{\"type\":\"string\",\"default\":\"\"},\"lock\":{\"type\":\"object\",\"default\":{\"remove\":true,\"move\":false}}},\"parent\":[\"woocommerce/checkout-contact-information-block\"],\"textdomain\":\"affs\",\"apiVersion\":2}" ) ,
        getElement : function ( e ) {
            return   reactElement.createElement( wp_components.Disabled , { } , reactElement.createElement( wc_blocks_checkout.TotalsWrapper , { } , WalletCouponBlock.getFormField() ) ) ;
        } ,
        getFormField : function () {
            return reactElement.createElement( wc_blocks_checkout.Panel , { className : 'fs-affiliates-referal-code-form-block' , title : register_affiliate_form_title } ) ;
        } ,
        edit : function ( attributes ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps() , WalletCouponBlock.getElement() ) ;
        } ,
        save : function ( e ) {
            return reactElement.createElement( 'div' , blockEditor.useBlockProps.save() ) ;
        }
    } ;

    // Register inner block of referal code form in the checkout block.  
    blocks.registerBlockType( ReferalCodeFormBlock.checkoutSchema.name , {
        ...ReferalCodeFormBlock.checkoutSchema ,
        edit : ReferalCodeFormBlock.edit ,
        save : ReferalCodeFormBlock.save
    } ) ;

    // Register inner block of referal field in the checkout block.  
    blocks.registerBlockType( ReferralFieldBlock.checkoutSchema.name , {
        ...ReferralFieldBlock.checkoutSchema ,
        edit : ReferralFieldBlock.edit ,
        save : ReferralFieldBlock.save
    } ) ;

    // Register inner block of register affiliate form in the checkout block.  
    blocks.registerBlockType( RegisterAffiliateFormBlock.checkoutSchema.name , {
        ...RegisterAffiliateFormBlock.checkoutSchema ,
        edit : RegisterAffiliateFormBlock.edit ,
        save : RegisterAffiliateFormBlock.save
    } ) ;

    // Register inner block of checkout affiliate form in the checkout block.  
    blocks.registerBlockType( CheckoutAffiliateFormBlock.checkoutSchema.name , {
        ...CheckoutAffiliateFormBlock.checkoutSchema ,
        edit : CheckoutAffiliateFormBlock.edit ,
        save : CheckoutAffiliateFormBlock.save
    } ) ;

    // Register inner block of redeem wallet coupon in the cart block.  
    blocks.registerBlockType( WalletCouponBlock.cartSchema.name , {
        ...WalletCouponBlock.cartSchema ,
        edit : WalletCouponBlock.edit ,
        save : WalletCouponBlock.save
    } ) ;

    // Register inner block of redeem wallet coupon in the checkout block.  
    blocks.registerBlockType( WalletCouponBlock.checkoutSchema.name , {
        ...WalletCouponBlock.checkoutSchema ,
        edit : WalletCouponBlock.edit ,
        save : WalletCouponBlock.save
    } ) ;

} )() ;