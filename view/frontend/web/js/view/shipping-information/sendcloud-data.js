define(['uiComponent'], function (c) {
    'use strict';

    return c.extend({
        getSendCloudData: function () {
            var deliveryOptionsData = window.checkoutConfig.scDeliveryOptionsData;

            if (deliveryOptionsData) {
                return JSON.parse(deliveryOptionsData).checkout_payload;
            }
        },
        getName: function () {
            return this.getSendCloudData().shipping_product.name;
        },
        getDeliveryDate: function () {
            return this.getSendCloudData().nominated_day_delivery.formatted_delivery_date;
        }
    });
});
