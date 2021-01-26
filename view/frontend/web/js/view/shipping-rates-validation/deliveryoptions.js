define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'SendCloudV2RatesValidatorDeliveryoptions',
    'SendCloudV2RatesValidationRulesDeliveryoptions'
], function (Component, defaultShippingRatesValidator, defaultShippingRatesValidationRules, scDeliveryOptionsShippingRatesValidator, scDeliveryOptionsShippingRatesValidationRules) {
    'use strict';
    defaultShippingRatesValidator.registerValidator('sendcloudv2skeleton', scDeliveryOptionsShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('sendcloudv2skeleton', scDeliveryOptionsShippingRatesValidationRules);
    return Component;
});
