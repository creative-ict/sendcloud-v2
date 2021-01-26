define([
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'SendCloud_SendCloudV2/js/view/checkout/shipping/servicePoint',
    'SendCloud_SendCloudV2/js/view/checkout/shipping/deliveryOptions',
    'jquery',
    'ko'
], function (quote, $t, servicePoint, deliveryOptions, $, ko) {
    'use strict';

    return function (Component) {
        return Component.extend({
            shippingMethod: ko.observable(false),
            servicePointData: ko.observable(false),
            deliveryOptionsData: ko.observable(false),
            validateShippingInformation: function() {
                var origResult = this._super(),
                    self = this;

                self.initDataValues();

                if (self.shippingMethod === 'sendcloudv2servicepoint' && !self.validateServicePointData()) {
                    return false;
                }
                if (self.shippingMethod === 'sendcloudv2skeleton' && !self.validateDeliveryOptionsData()) {
                    return false;
                }

                return origResult;
            },
            initDataValues: function () {
                var self = this;

                try {
                    self.servicePointData = servicePoint().servicePointData();
                    self.deliveryOptionsData = deliveryOptions().deliveryOptionsData();
                    self.shippingMethod = quote.shippingMethod()['carrier_code'];
                } catch (error) {
                    return false;
                }
            },
            validateServicePointData: function () {
                var self = this;

                if (!self.servicePointData) {
                    self.scrollToData($('#sendcloud-service-point'));

                    return false;
                }
                return true;
            },
            validateDeliveryOptionsData: function () {
                var self = this;

                if (!self.deliveryOptionsData) {
                    self.scrollToData($('.sendcloud-delivery-options'));

                    return false;
                }
                return true;
            },
            scrollToData: function (wrapper) {
                if (wrapper.length > 0) {
                    window.scrollTo({
                        top: wrapper.offset().top,
                        behavior: "smooth"
                    })
                }
            }
        });
    }
});
