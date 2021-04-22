define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'jquery'
], function (wrapper, quote, $) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalAction, payload) {
            var self = this,
                methodCode = quote.shippingMethod()['method_code'],
                carrierCode = quote.shippingMethod()['carrier_code'];

            payload = originalAction(payload);

            if (carrierCode === 'sendcloudv2skeleton') {
                var checkoutPayload = JSON.parse(window.checkoutConfig.scDeliveryOptionsData);
                payload.addressInformation['extension_attributes']['sendcloud_data'] = checkoutPayload;
            } else if (methodCode === 'sendcloudv2servicepoint') {
                var sendCloudAttributes;
                if ($('[name="sendcloud_service_point_id"]').val() > 0) {
                    sendCloudAttributes = {
                        sendcloud_service_point_id: $('[name="sendcloud_service_point_id"]').val(),
                        sendcloud_service_point_name: $('[name="sendcloud_service_point_name"]').val(),
                        sendcloud_service_point_street: $('[name="sendcloud_service_point_street"]').val(),
                        sendcloud_service_point_house_number: $('[name="sendcloud_service_point_house_number"]').val(),
                        sendcloud_service_point_zip_code: $('[name="sendcloud_service_point_zip_code"]').val(),
                        sendcloud_service_point_city: $('[name="sendcloud_service_point_city"]').val(),
                        sendcloud_service_point_country: $('[name="sendcloud_service_point_country"]').val(),
                        sendcloud_service_point_postnumber: $('[name="sendcloud_service_point_postnumber"]').val()
                    };
                    payload.addressInformation['extension_attributes'] = sendCloudAttributes;
                }
            }

            return payload;
        });
    };
});
