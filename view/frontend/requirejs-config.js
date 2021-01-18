var config = {
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloudV2/js/model/shipping-save-processor/servicepoint',
            'Amasty_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloudV2/js/model/shipping-save-processor/amasty-servicepoint',
            'SendCloudCheckoutPluginUi': 'https://cdn.jsdelivr.net/npm/@sendcloud/checkout-plugin-ui@1.0.0',
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
            'Mageplaza_Osc/js/model/shipping-save-processor/checkout': {
                'SendCloud_SendCloudV2/js/model/shipping-save-processor/mageplaza-servicepoint': true
            },
            'Mageplaza_Osc/js/view/review/placeOrder': {
                'SendCloud_SendCloudV2/js/mixins/view/review/placeOrder': true
            },
            'Onestepcheckout_Iosc/js/shipping': {
                'SendCloud_SendCloudV2/js/mixins/shipping': true
            }
        }
    }
};
