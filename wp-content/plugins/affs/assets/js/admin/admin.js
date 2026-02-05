jQuery(function ($) {
    var FS_Admin = {
        init: function () {
            this.trigger_on_page_load();
            // Date Filters
            $(document).on('change', '.fs-date-filter-type', this.date_filter_type);
            $(document).on('change', '#fs_affiliate_log_deletion', this.enable_log_deletion);
        }, trigger_on_page_load: function () {
            FS_Admin.toggle_date_filter_type('.fs-date-filter-type');
            FS_Admin.toggle_enable_log_deletion('#fs_affiliate_log_deletion');
        },date_filter_type: function (event) {
            event.preventDefault();
            FS_Admin.toggle_date_filter_type($(event.currentTarget));
        }, toggle_date_filter_type: function ($this) {           
            if ('custom_range' === $($this).val()) {
                $('.fs-custom-date-range').show();
            } else {
                $('.fs-custom-date-range').hide();
            }
        }, enable_log_deletion: function (e) {
            e.preventDefault();
            FS_Admin.toggle_enable_log_deletion($(e.currentTarget));
        }, toggle_enable_log_deletion: function ($this) {           
            if ( true == $($this).is(':checked')) {
                $('.fs-show-if-log-deletion-enable').closest('tr').show();
            } else {
                $('.fs-show-if-log-deletion-enable').closest('tr').hide();
            }
        },
    };
    FS_Admin.init();
    // Field validation error tips
    $(document.body)

            .on('fs_affiliates_add_error_tip', function (e, element, error_type) {
                var offset = element.position();

                if (element.parent().find('.fs_affiliates_error_tip').length === 0) {
                    element.after('<div class="fs_affiliates_error_tip ' + error_type + '">' + fs_affiliates_admin_params[error_type] + '</div>');
                    element.parent().find('.fs_affiliates_error_tip')
                            .css('left', offset.left + element.width() - (element.width() / 2) - ($('.fs_affiliates_error_tip').width() / 2))
                            .css('top', offset.top + element.height())
                            .fadeIn('200');
                }
            })

            .on('fs_affiliates_remove_error_tip', function (e, element, error_type) {
                element.parent().find('.fs_affiliates_error_tip.' + error_type).fadeOut('100', function () {
                    $(this).remove();
                });
            })

            .on('click', function () {
                $('.fs_affiliates_error_tip').fadeOut('100', function () {
                    $(this).remove();
                });
            })

            .on('click', '.fs-delete-data', function () {
                if (!confirm(fs_affiliates_admin_params.delete_confirm_msg)) {
                    return false;
                }
            })

            .on('click', '.fs-affiliates-payment-remainder-warning', function (event) {
                var $this = $(event.currentTarget);
                var data = $this.attr('data-attr');

                if ('no' == data) {
                    alert(fs_affiliates_admin_params.payment_method_warning);
                    return false;
                }
            })

            .on('click', '.fs_form_submit_button', function (event) {
                var $this = $(event.currentTarget);
                $this.attr('disabled', 'disabled');
                $this.parents('form').submit();
            })

            .on('keyup', '.fs_affiliates_input_price[type=text]', function () {
                var regex, error;

                regex = new RegExp('[^\-0-9\%\\' + fs_affiliates_admin_params.mon_decimal_point + ']+', 'gi');
                error = 'non_decimal_error';

                var value = $(this).val();
                var newvalue = value.replace(regex, '');

                if (value !== newvalue) {
                    $(document.body).triggerHandler('fs_affiliates_add_error_tip', [$(this), error]);
                } else {
                    $(document.body).triggerHandler('fs_affiliates_remove_error_tip', [$(this), error]);
                }
            })

            .on('change', '.fs_affiliates_input_price[type=text]', function () {
                var regex;

                if ($(this).is('.fs_affiliates_input_price')) {
                    regex = new RegExp('[^\-0-9\%\\' + fs_affiliates_admin_params.mon_decimal_point + ']+', 'gi');
                }

                var value = $(this).val();
                var newvalue = value.replace(regex, '');

                if (value !== newvalue) {
                    $(this).val(newvalue);
                }
            })
});
