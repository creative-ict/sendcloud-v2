var config = {
    "map": {
        "*": {
            'SendCloudCheckoutPluginUi': 'https://cdn.jsdelivr.net/npm/@sendcloud/checkout-plugin-ui@1/dist/checkout-plugin-ui.js',
            'SendCloudV2RatesValidationRulesServicepoint' : 'SendCloud_SendCloudV2/js/model/shipping-rates-validation-rules/servicepoint',
            'SendCloudV2RatesValidationRulesDeliveryoptions': 'SendCloud_SendCloudV2/js/model/shipping-rates-validation-rules/deliveryoptions',
            'SendCloudV2RatesValidatorServicepoint': 'SendCloud_SendCloudV2/js/model/shipping-rates-validator/servicepoint',
            'SendCloudV2RatesValidatorDeliveryoptions': 'SendCloud_SendCloudV2/js/model/shipping-rates-validator/servicepoint',
            'SendCloudData': 'SendCloud_SendCloudV2/js/view/shipping-information/sendcloud-data'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping-information': {
                'SendCloud_SendCloudV2/js/mixins/checkout/view/shipping-information': true
            },
            'Magento_Checkout/js/view/shipping': {
                'SendCloud_SendCloudV2/js/mixins/checkout/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/payment/default': {
                'SendCloud_SendCloudV2/js/mixins/checkout/view/payment/default-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'SendCloud_SendCloudV2/js/model/shipping-save-processor/payload-extender': true
            }
        }
    }
};
