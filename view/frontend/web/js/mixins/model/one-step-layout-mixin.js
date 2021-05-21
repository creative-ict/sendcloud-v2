define([
    'mage/utils/wrapper'
], function (wrapper) {
    'use strict';

    return function (target) {
        target.getCheckoutBlock = wrapper.wrapSuper(
            target.getCheckoutBlock,
            function (blockName) {
                if (blockName === 'shipping_method') {
                    var requestComponent = this.checkoutBlocks[blockName]
                        || this.requestComponent('checkout.steps.shipping-step.shippingAddress');
                    if (requestComponent()) {
                        requestComponent().template = 'SendCloud_SendCloudV2/onepage/shipping/methods';
                    }
                } else {
                   return this._super(blockName);
                }

                return requestComponent;
            }
        );

        return target;
    }
});
