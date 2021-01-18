define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote',
    'SendCloudCheckoutPluginUi'
], function (Component, ko, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'SendCloud_SendCloudV2/checkout/shipping/deliveryOptions'
        },
        initObservable: function () {
            this.selectedMethod = ko.computed(function () {
                var method = quote.shippingMethod();
                return method != null ? method.carrier_code + '_' + method.method_code : null;
            }, this);
            return this;
        },
        renderDeliveryOptions: function () {
            const mountElement = document.querySelector('.sendcloud-delivery-options');
            // TO DO: replace mock data
            const locale = 'en-GB';
            const deliveryMethod = {
                "id": "1",
                "delivery_method_type": "nominated_day_delivery",
                "external_title": "External title",
                "internal_title": "Internal title",
                "enabled": true,
                "show_carrier_information_in_checkout": true,
                "sender_address_id": 1,
                "shipping_product": {
                    "code": "ups:standard",
                    "name": "UPS Standard",
                    "selected_functionalities": {},
                    "carrier_delivery_days": {
                        "monday": null,
                        "tuesday": {
                            "start_time_hours": 10,
                            "start_time_minutes": 15,
                            "end_time_hours": 17,
                            "end_time_minutes": 55
                        },
                        "wednesday": {
                            "start_time_hours": 10,
                            "start_time_minutes": 15,
                            "end_time_hours": 17,
                            "end_time_minutes": 0
                        },
                        "thursday": null,
                        "friday": {
                            "start_time_hours": 8,
                            "start_time_minutes": 0,
                            "end_time_hours": 18,
                            "end_time_minutes": 0
                        },
                        "saturday": null,
                        "sunday": null
                    },
                    "lead_time_hours": 24
                },
                "carrier": {
                    "name": "UPS",
                    "code": "ups",
                    "logo_url": "https://sendcloud-prod-scp-static-files.s3.amazonaws.com/ups/img/logo.svg"
                },
                "parcel_handover_days": {
                    "monday": {
                        "enabled": true,
                        "cut_off_time_hours": 16,
                        "cut_off_time_minutes": 30
                    },
                    "tuesday": {
                        "enabled": true,
                        "cut_off_time_hours": 18,
                        "cut_off_time_minutes": 0
                    },
                    "wednesday": {
                        "enabled": false,
                        "cut_off_time_hours": 18,
                        "cut_off_time_minutes": 0
                    },
                    "thursday": {
                        "enabled": true,
                        "cut_off_time_hours": 18,
                        "cut_off_time_minutes": 0
                    },
                    "friday": {
                        "enabled": true,
                        "cut_off_time_hours": 15,
                        "cut_off_time_minutes": 0
                    },
                    "saturday": null,
                    "sunday": null
                }
            }

            window.renderScShippingOption({mountElement, deliveryMethod, locale});
        }
    })
});
