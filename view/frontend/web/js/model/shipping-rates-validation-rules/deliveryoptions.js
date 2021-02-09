define([], function () {
    'use strict';
    return {
        getRules: function () {
            return {
                'checkout_payload': {
                    'required': true
                }
            }
        }
    }
});
