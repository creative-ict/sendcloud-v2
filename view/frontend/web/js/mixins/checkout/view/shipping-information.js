define([
    'jquery',
    'knockout',
    'Magento_Checkout/js/model/quote',
    'SendCloudData'
], function($, ko, quote, sendCloudData){
    'use strict';
    return function(c){
        //if targetModule is a uiClass based object
        var self = this;
        return c.extend({
            defaults: {
                template: 'SendCloud_SendCloudV2/shipping-information'
            },
            initObservable: function () {
                this._super();
                this.selectedMethod = ko.computed(function() {
                    var method = quote.shippingMethod(),
                        selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
                    return selectedMethod;
                }, this);

                this.selectedCarrier = ko.computed(function () {
                    var method = quote.shippingMethod(),
                        selectedCarrier = method != null ? method.carrier_code : null;
                    return selectedCarrier;
                }, this);

                return this;
            },
            getServicePointInformation: function(){
                var address = window.checkoutConfig.servicePointData;

                if (address) {
                    return JSON.parse(address);
                }
            },
            getFormattedSendCloudData: function () {
                if (sendCloudData().getSendCloudData()) {
                    return {
                        name: sendCloudData().getName(),
                        date: sendCloudData().getDeliveryDate()
                    }
                }
                return false;
            }
        });
    };
});
