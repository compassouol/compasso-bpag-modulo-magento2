/**
 * 2019-20 [Compasso UOL Tecnologia]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    Compasso UOL Tecnologia
 *  @copyright 2020 Compasso UOL Tecnologia
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */
/*
 * browser:true
 * global define
 */
define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/set-payment-information',
        'Magento_Checkout/js/action/place-order',
        'Compasso_Bpag/js/model/direct-payment-validator',
        'Compasso_Bpag/js/model/credit-card'
    ],
    function ($, Component, quote, fullScreenLoader, setPaymentInformationAction, placeOrder, directPaymentValidator, creditCard) {
        'use strict';

        var placeOrderActionAllowed = true;

        return Component.extend({
            defaults: {
                template: 'Compasso_Bpag/payment/credit-card-form',
                brazilFlagPath: window.checkoutConfig.brazilFlagPath,
                compassoUolPagamentosCcSessionId: window.checkoutConfig.library.session
            },

            initObservable: function () {
                this._super()
                    .observe([
                        'creditCardDocument'
                    ]);
                return this;
            },

            getGrandTotal: function () {
                var totals = quote.getTotals()();
                var x = (totals ? totals : quote)['grand_total'];
                return parseFloat(x);
            },

            getCompassooUolPagamentosCcMonthsValues: function () {
                var months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                return _.map(months, function (value, key) {
                    return {
                        'value': key + 1,
                        'month': value
                    };
                });
            },

            getCompassoUolPagamentosCcYearsValues: function () {
                var thisYear = (new Date()).getFullYear();
                var maxYear = thisYear + 20;
                var years = [];
                var i = thisYear;
                for (i = thisYear; i < maxYear; i++) {
                    years.push(i);
                }

                return _.map(years, function (value, key) {
                    return {
                        'value': value,
                        'year': value
                    };
                });
            },

            context: function () {
                return this;
            },

            getCode: function () {
                return "compasso_uol_pagamentos_credit_card"
            },

            /**
             * @override
             */
            placeOrder: function () {
                var self = this;

                var baseUri = window.location.origin;
                var paymentData = quote.paymentMethod();
                var messageContainer = this.messageContainer;

                fullScreenLoader.startLoader();
                this.isPlaceOrderActionAllowed(false);

                var param = {
                    method: 'POST',
                    url: baseUri+'/bpag/payment/token',
                    data: {
                        cardNumber: unmask(document.getElementById('compasso_uol_pagamentos_credit_card_number').value),
                        brand: document.getElementById('creditCardBrand').value,
                        cvv: document.getElementById('creditCardCode').value,
                        expMonth: document.getElementById('creditCardExpirationMonth').value,
                        expYear: document.getElementById('creditCardExpirationYear').value
                    },
                    success: function (response) {
                        self.finishOrder(self, paymentData, messageContainer);
                        console.log(response);
                    },
                    error: function (error) {
                        self.isPlaceOrderActionAllowed(true);
                        console.log(error);
                        return;
                    }
                };

                jQuery.ajax(param);

            },

            validatePlaceOrder: function () {
                return validateCreditCardForm();
            },

            isPlaceOrderActionAllowed: function(allowed) {
                if(allowed!=undefined) placeOrderActionAllowed = allowed;
                return placeOrderActionAllowed;
            },

            finishOrder: function (self, paymentData, messageContainer) {
                $.when(setPaymentInformationAction(messageContainer, {
                    'method': self.getCode(),
                    'additional_data': {
                        'credit_card_document': (self.creditCardDocument()) ? self.creditCardDocument() : document.getElementById('creditCardDocument').value,
                        'credit_card_holder_name': document.getElementById('creditCardHolder').value,
                        'credit_card_holder_birthdate': document.getElementById('creditCardHolderBirthdate').value,
                        'credit_card_installment': document.getElementById('creditCardInstallment').value,
                        'credit_card_installment_value': document.getElementById('creditCardInstallmentValue').value
                    }
                })).done(function () {
                    delete paymentData['title'];
                    delete paymentData.__disableTmpl;
                    console.log(paymentData);
                    $.when(placeOrder(paymentData, messageContainer)).done(function () {
                        console.log(window.checkoutConfig.compassoPayment);
                        $.mage.redirect(window.checkoutConfig.compassoPayment);
                    });
                    //return;
                }).fail(function () {
                    alert("falhou");
                    self.isPlaceOrderActionAllowed(true);
                }).always(function () {
                    fullScreenLoader.stopLoader();
                });
            }
        });
    }
);
