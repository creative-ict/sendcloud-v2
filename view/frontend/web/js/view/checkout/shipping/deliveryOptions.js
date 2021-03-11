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
            deliveryMethod: ko.observable(false),
            locale: 'nl-NL'
        },
        deliveryOptionsData: deliveryOptions,
        initialize: function (config) {
            var deliveryMethods;
            this._super();

            checkoutConfig = config.checkoutConfig;
            if (checkoutConfig) {
                deliveryMethods = checkoutConfig.delivery_zones[0].delivery_methods[0];
                this.options.deliveryMethod = deliveryMethods;
            }

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
                "parcel_handover_date": selectedTimeItem.getAttribute('data-parcel-handover-date')
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
                    "sender_address_id": this.options.deliveryMethod.sender_address_id,
                    shipping_product,
                    "nominated_day_delivery": {
                        "delivery_date": selectedTimeItem.getAttribute('data-delivery-date'),
                        "formatted_delivery_date": selectedTimeItem.getAttribute('data-formatted-delivery-date'),
                        "parcel_handover_date": selectedTimeItem.getAttribute('data-parcel-handover-date')
                    }
                }
            }

            result.checkout_payload.shipping_product['selected_functionalities'] = selectedFunctionalities;

            self.deliveryOptionsData(result);
            window.checkoutConfig.scDeliveryOptionsData = JSON.stringify(result);
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
