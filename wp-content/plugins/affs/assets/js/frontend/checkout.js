/* global fs_affiliates_checkout_params */

jQuery ( function ( $ ) {

    var FS_Checkout = {
        init : function () {
            this.trigger_on_page_load () ;
            $ ( document ).on ( 'change' , 'input[type=radio][name=affiliate_referrer_radio]' , this.checkout_affiliate_affs_selection ) ;
            $ ( document ).on ( 'change','select.shipping_method, :input[name^=shipping_method]', this.fs_select_checkout_affiliate ) ;
        } ,
        trigger_on_page_load : function () {
            FS_Checkout.affs_checkout_on_load () ;
            FS_Checkout.fs_select_checkout_affiliate_on_load ('select.shipping_method, :input[name^=shipping_method]:checked') ;         
        } ,
        fs_select_checkout_affiliate_on_load:function($this){
            if(fs_affiliates_checkout_params.is_checkout){
                var data = {
                    action : 'fs_select_checkout_affiliate' ,
                    shipping_method : $($this).val() ,
                    fs_security : fs_affiliates_checkout_params.checkout_affiliate
                };
                $.post(
                    fs_affiliates_checkout_params.ajax_url ,
                    data ,
                    function ( response ) {
                        if ( true === response.success ) {
                            let affiliate_selector = document.getElementById('affiliate_referrer');
                            affiliate_selector.value = response.data.affiliate;
                        } else {
                            window.alert( response.data.error );
                        }
                    }
                );
            }
        },

        fs_select_checkout_affiliate:function(){
            var data = {
                action : 'fs_select_checkout_affiliate' ,
                shipping_method : $(this).val() ,
                fs_security : fs_affiliates_checkout_params.checkout_affiliate
            };
            $.post(
                fs_affiliates_checkout_params.ajax_url ,
                data ,
                function ( response ) {
                    if ( true === response.success ) {
                        let affiliate_selector = document.getElementById('affiliate_referrer');
                        affiliate_selector.value = response.data.affiliate;
                    } else {
                        window.alert( response.data.error );
                    }
                }
            );
        },

        affs_checkout_on_load : function () {

            if ( fs_affiliates_checkout_params.affs_selection == 3 ) {
                FS_Checkout.affiliate_referrer_user_select('input[type=radio][name=affiliate_referrer_radio]') ;
            }

        } ,
        checkout_affiliate_affs_selection : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            FS_Checkout.affiliate_referrer_user_select ( $this ) ;
        } ,

        affiliate_referrer_user_select : function ( $this ) {
            
            if ( $ ( $this ).val ( ) == 1 ) {
                $ ( '#affiliate_referrer_fields' ).show () ;
            } else {
                $ ( '#affiliate_referrer_fields' ).hide () ;
            }

        } ,

    } ;
    FS_Checkout.init () ;

} ) ;