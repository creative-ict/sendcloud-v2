define([], function () {
    'use strict';
    return {
        getRules: function () {
            return {
                'sendcloud_checkout_payload': {
                    'required': true
                }
            }
        }
    }
});
