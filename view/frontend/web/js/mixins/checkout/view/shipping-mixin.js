define([
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'SendCloud_SendCloudV2/js/view/checkout/shipping/servicePoint',
    'jquery'
], function (quote, $t, servicePoint, $) {
    'use strict';

    return function (Component) {
        return Component.extend({
            validateShippingInformation: function() {
                try{
                    var origResult = this._super(),
                        servicePointData = servicePoint().servicePointData();
                }
                catch(error) {
                    return false;
                }

                if (quote.shippingMethod()['carrier_code'] === 'sendcloudv2servicepoint' && !servicePointData) {
                    var servicePointWrapper = $('#sendcloud-service-point');

                    window.scrollTo({
                        top: servicePointWrapper.offset().top,
                        behavior: "smooth"
                    });

                    return false;
                }

                return origResult;
            }
        });
    }
});
