define([
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloudV2/js/view/checkout/shipping/servicePoint'
], function(quote, servicePoint){
    'use strict';

    return function (Component) {
        return Component.extend({
            isPlaceOrderActionAllowed: function () {
                if (typeof quote.shippingMethod() !== 'undefined' && quote.shippingMethod() !== null) {
                    if (quote.shippingMethod().method_code === 'sendcloudv2servicepoint' && !servicePoint().servicePointData()) {
                        return false;
                    }
                }
                return true;
            }
        });
    }
})
