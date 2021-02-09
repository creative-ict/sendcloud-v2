define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/set-shipping-information',
    'uiRegistry',
    'SendCloudCheckoutPluginUi'
], function (Component, ko, quote, setShippingInformationAction, registry) {
    'use strict';

    var self = this,
        deliveryOptions = ko.observable(false),
        checkoutConfig = ko.observable(false);

    return Component.extend({
        defaults: {
            template: 'SendCloud_SendCloudV2/checkout/shipping/deliveryOptions'
        },
        options: {
            mountElement: ko.observable(document.querySelector('.sendcloud-delivery-options')),
            nominatedDayDelivery: ko.observable(false),
            // TODO: implement checkoutConfig to replace deliveryMethod
            deliveryMethod: {
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
                    "selected_functionalities": {
                        "signature": true
                    },
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
            },
            locale: 'nl-NL'
        },
        deliveryOptionsData: deliveryOptions,
        initialize: function (config) {
            this._super();

            checkoutConfig = config.checkoutConfig;

            return this;
        },
        initObservable: function () {
            this.selectedMethod = ko.computed(function () {
                var method = quote.shippingMethod();
                return method != null ? method.carrier_code + '_' + method.method_code : null;
            }, this);

            return this;
        },
        renderDeliveryOptions: function () {
            this.getMountElement();

            window.renderScShippingOption(this.options);

            if (!this.nominatedDayDelivery) {
                this.setDefaultData();
            }

            this.options.mountElement.addEventListener('scShippingOptionChange', this.handleScShippingOptionChange.bind(this));
        },
        setDefaultData: function () {
            var self = this,
                selectedTimeItem = self.options.mountElement.querySelector('.sc-delivery-date-list__item--selected time');
            self.nominatedDayDelivery = {
                "delivery_date": selectedTimeItem.getAttribute('data-delivery-date'),
                "formatted_delivery_date": selectedTimeItem.getAttribute('data-formatted-delivery-date'),
                "processing_date": selectedTimeItem.getAttribute('data-parcel-handover-date')
            }
            self.setDeliveryOptionsData();
        },
        getMountElement: function () {
            this.options.mountElement = document.querySelector('.sendcloud-delivery-options');
        },
        handleScShippingOptionChange: function (event) {
            var self = this,
                nominatedDayDelivery = event.detail.data.nominated_day_delivery;

            if (nominatedDayDelivery) {
                self.nominatedDayDelivery = nominatedDayDelivery;
                self.setDeliveryOptionsData();
            }
        },
        setDeliveryOptionsData: function () {
            var self = this,
                selectedTimeItem = self.options.mountElement.querySelector('.sc-delivery-date-list__item--selected time'),
                selectedFunctionalities = this.options.deliveryMethod.shipping_product.selected_functionalities,
                shipping_product = {
                    "code": this.options.deliveryMethod.shipping_product.code,
                    "name": this.options.deliveryMethod.shipping_product.name
                };
            var result = {
                "checkout_payload": {
                    shipping_product,
                    "nominated_day_delivery": {
                        "delivery_date": selectedTimeItem.getAttribute('data-delivery-date'),
                        "formatted_delivery_date": selectedTimeItem.getAttribute('data-formatted-delivery-date'),
                        "processing_date": selectedTimeItem.getAttribute('data-parcel-handover-date')
                    }
                }
            }

            result.checkout_payload.shipping_product['selected_functionalities'] = selectedFunctionalities;

            self.deliveryOptionsData(result);

            window.sessionStorage.setItem("sc-delivery-options-data", JSON.stringify(result));
            // window.sessionStorage.setItem("sc-delivery-options-data", result);
            self.setShippingInformation();

            return result;
        },
        setShippingInformation: function () {
            var shipping = registry.get('checkout.steps.shipping-step.shippingAddress'),
                result;

            result = shipping.validateShippingInformation();
            if (result) {
                setShippingInformationAction();
            }
        }
    })
});
