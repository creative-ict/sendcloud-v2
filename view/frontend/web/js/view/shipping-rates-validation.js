define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'SendCloud_SendCloudV2/js/model/shipping-rates-validator',
        'SendCloud_SendCloudV2/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        shippingRatesValidator,
        shippingRatesValidationRules
    ) {
        //TODO: SC-21: CHeck if we need different namespacing here
        'use strict';
        defaultShippingRatesValidator.registerValidator('sendcloudv2servicepoint', shippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('sendcloudv2servicepoint', shippingRatesValidationRules)

        return Component;
    }
);
