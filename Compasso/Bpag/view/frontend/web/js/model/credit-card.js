/**
 * 2007-2017 [Compasso UOL Tecnologia]
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

function validateCreditCard(self) {
  if (self.validity.valid && removeNumbers(unmask(self.value)) === "" && (self.value.length >= 14 && self.value.length <= 22)) {
    displayError(self, false)
    return true
  } else {
    displayError(self)
    return false
  }
}

function validateCardHolder (self) {
    if (self.validity.tooShort || !self.validity.valid || removeLetters(unmask(self.value)) !== "") {
      displayError(self)
      return false
    } else {
      displayError(self, false)
      return true
    }
  }
  
  function validateCreditCardHolderBirthdate (self) {
    var val = self.value
    var date_regex = /^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/
    if (!(date_regex.test(val))) {
      displayError(self)
      return false
    } else {
      displayError(self, false)
      return true
    }
  }
  
  function validateCreditCardMonth (self) {
    if (self.validity.valid && self.value !== "") {
      displayError(self, false)
      return true
    } else {
      displayError(self)
      return false
    }
  }
  
  function validateCreditCardYear (self) {
    if (self.validity.valid && self.value !== "") {
      displayError(self, false)
      return true
    } else {
      displayError(self)
      return false
    }
  }

function cardInstallmentOnChange(data) {
  data = JSON.parse(data.value)
  document.getElementById('creditCardInstallment').value = data.parcela
  document.getElementById('creditCardInstallmentValue').value = data.valor
  document.getElementById('card_total').innerHTML = data.parcela+' x R$ ' + data.valor;
}

function cardInstallment(data) {
  var select = document.getElementById('card_installment_option')

  data.forEach(function (item) {
    select.options[select.options.length] = new Option(item.parcela + 'x de R$ ' + item.valor, JSON.stringify(item));
  });

  if (data)
    select.removeAttribute('disabled');

}

function validateCreditCardInstallment (self) {
    if (self.validity.valid && self.value != "null") {
      displayError(self, false)
      return true
    } else {
      displayError(self)
      return false
    }
  }

function getInstallments() {
  jQuery.post(this.BASE_URL+'/bpag/payment/installments').done(function(data) {
    cardInstallment(jQuery.parseJSON(data));
  });
}

function getBrand(self) {
  var select = document.getElementById('card_installment_option');
  select.options.length = 0;
  select.options[0] = new Option('Escolha o N° de parcelas', null, true, true);
  select.options[0].disabled = true
  document.getElementById('card_total').innerHTML = 'selecione o número de parcelas';
  if (validateCreditCard(self)) {
    var brandName = getCardType(self.value);
    document.getElementById('creditCardBrand').value = brandName;

    jQuery('.card-brand').remove();
    jQuery('#compasso_uol_pagamentos_credit_card_number').after('<span class="card-brand card-'+brandName+'">&nbsp;</span>');

    getInstallments();
  } else {
    displayError(document.getElementById('compasso_uol_pagamentos_credit_card_number'))
  }
  return false;
}

function getCardType(number)
{

  number = number.replace(/\s/g,"")
  console.log('number:'+number);

  // Mastercard
  // Updated for Mastercard 2017 BINs expansion
  if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number))
    return "master";

  // AMEX
  re = new RegExp("^3[47]");
  if (number.match(re) != null)
    return "amex";

  // Discover
  re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
  if (number.match(re) != null)
    return "discover";

  // Diners
  re = new RegExp("^36");
  if (number.match(re) != null)
    return "diners";

  // Diners - Carte Blanche
  re = new RegExp("^30[0-5]");
  if (number.match(re) != null)
    return "diners";

  // JCB
  re = new RegExp("^35(2[89]|[3-8][0-9])");
  if (number.match(re) != null)
    return "jcb";

  // Visa Electron
  re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
  if (number.match(re) != null)
    return "visa_electron";

  // visa
  var re = new RegExp("^4");
  if (number.match(re) != null)
    return "visa";

  return "default";
}

function validateCreditCardCode(self) {
  return true;
}

function validateCreditCardForm() {
  if (
   validateCreditCard(document.querySelector('#compasso_uol_pagamentos_credit_card_number')) &&
   validateDocumentFinal(document.querySelector('#creditCardDocument')) &&
   validateCardHolder(document.querySelector('#creditCardHolder')) &&
   validateCreditCardHolderBirthdate(document.querySelector('#creditCardHolderBirthdate')) &&
   validateCreditCardMonth(document.querySelector('#creditCardExpirationMonth')) &&
   validateCreditCardYear(document.querySelector('#creditCardExpirationYear')) &&
   validateCreditCardCode(document.querySelector('#creditCardCode')) &&
   validateCreditCardInstallment(document.querySelector('#card_installment_option'))
  ) {
   return true;
  }
  
  validateCreditCard(document.querySelector('#compasso_uol_pagamentos_credit_card_number'))
  validateDocumentFinal(document.querySelector('#creditCardDocument'))
  validateCardHolder(document.querySelector('#creditCardHolder'))
  validateCreditCardHolderBirthdate(document.querySelector('#creditCardHolderBirthdate'))
  validateCreditCardMonth(document.querySelector('#creditCardExpirationMonth'))
  validateCreditCardYear(document.querySelector('#creditCardExpirationYear'))
  validateCreditCardCode(document.querySelector('#creditCardCode'), false)
  validateCreditCardInstallment(document.querySelector('#card_installment_option'))
  return false;
}

function validateCreateToken() {
  if(validateCreditCard(document.querySelector('#compasso_uol_pagamentos_credit_card_number'))
    && validateCreditCardMonth(document.querySelector('#creditCardExpirationMonth'))
    && validateCreditCardYear(document.querySelector('#creditCardExpirationYear'))
    && validateCreditCardCode(document.querySelector('#creditCardCode'))
    && document.getElementById('creditCardBrand').value !== ""
    ) {
      return true
  }

  validateCreditCard(document.querySelector('#compasso_uol_pagamentos_credit_card_number'));
  validateCreditCardMonth(document.querySelector('#creditCardExpirationMonth'));
  validateCreditCardYear(document.querySelector('#creditCardExpirationYear'));
  validateCreditCardCode(document.querySelector('#creditCardCode'));

  return false;
}

/**
 * Return the value of 'el' without letters
 * @param {string} el
 * @returns {string}
 */
function removeLetters(el) {
  return el.replace(/[a-zA-Z]/g, '');

}

/**
 * Return the value of 'el' without numbers
 * @param {string} el
 * @returns {string}
 */
function removeNumbers(el) {
  return el.replace(/[0-9]/g, '');
}