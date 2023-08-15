<script setup>
import { ref, computed, watchEffect } from 'vue';
import { useChallengeV3 } from 'vue-recaptcha/head'
import { useRecaptchaProvider } from 'vue-recaptcha/head'

import axios from 'axios';
import PopupWindow from './PopupWindow.vue';
import PostPopup from './PostPopup.vue';
import LoginPopup from './LoginPopup.vue';

// Login popup data

const showLoginPopup = ref(false);
const closeLoginPopup = () => {
  showLoginPopup.value = false;
}
const showLogin = () => {
  showLoginPopup.value = true;
}

// Post popup data

const showPostPopup = ref(false);
const isSuccess = ref(false);
const deliveryCode = ref('');

const closePostPopup = () => {
  showPostPopup.value = false;
  isSuccess.value = false;
}

// Recaptcha
useRecaptchaProvider()
const response = ref()
const { execute } = useChallengeV3('submit')

// Vue 3 composition API used for dynamic reactivity

const form = ref();

const altaddrChecked = ref(false);
const registerChecked = ref(false);
const deliveryOption = ref('');

// Delivery price depending on selected radio option

const deliveryPrices = {
  paczkomat: 10.99,
  dpd: 18.00,
  dpdpobranie: 22.00,
};

const selectedDeliveryPrice = computed(() => {
  return deliveryPrices[deliveryOption.value] || 0;
});



// Form validation -----------

const nameInput = ref('');
const surnameInput = ref('');
const nationInput = ref('');
const addressInput = ref('');
const postcodeInput = ref('');
const cityInput = ref('');
const phonenumberInput = ref('');
const altAddressInput = ref('');
const altPostcodeInput = ref('');
const altCityInput = ref('');


const isNameValid = computed(() => {
  if (nameInput.value === '') {
    return null; // Initial value, no validation
  }
  return /^[A-Za-z\\-\\'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u.test(nameInput.value) && (nameInput.value.length > 2 && nameInput.value.length < 50);
});

const isSurnameValid = computed(() => {
  if (surnameInput.value === '') {
    return null; // Initial value, no validation
  }
  return /^[A-Za-z\\-\\'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u.test(surnameInput.value) && (surnameInput.value.length > 2 && surnameInput.value.length < 50);
});

const isNationValid = computed(() => {
  if (nationInput.value === '') {
    return null; // Initial value, no validation
  }
  return nationInput.value == 'poland' || nationInput.value == 'other';
});
// Ensure Address is good (i only taken into consideration Polish format)
const isAddressValid = computed(() => {
  if (addressInput.value === '') {
    return null; // Initial value, no validation
  }
  return /^[\p{L}\d\s\-\.,]+ [\p{L}\d\s\-\.,\/]+$/u.test(addressInput.value) && (addressInput.value.length > 2 && addressInput.value.length < 50);
});

const isPostcodeValid = computed(() => {
  if (postcodeInput.value === '') {
    return null; // Initial value, no validation
  }
  return /^\d{2}-\d{3}$/.test(postcodeInput.value) && (postcodeInput.value.length > 2 && postcodeInput.value.length < 12);
});

const isCityValid = computed(() => {
  if (cityInput.value === '') {
    return null; // Initial value, no validation
  }
  return /^[\p{L}\s\-]+$/u.test(cityInput.value) && (cityInput.value.length > 2 && cityInput.value.length < 255);
});

const isPhonenumberValid = computed(() => {
  if (phonenumberInput.value === '') {
    return null; // Initial value, no validation
  }// With optional country code (not the best regex)
  return /^\+?\d{0,4}\d{9}$/.test(phonenumberInput.value) && (phonenumberInput.value.length > 8 && phonenumberInput.value.length < 15);
});

const isAltAddressValid = computed(() => {
  if (altaddrChecked.value) {
    if (altAddressInput.value === '') {
      return null; // Initial value, no validation
    }
    return /^[\p{L}\d\s\-\.,]+ [\p{L}\d\s\-\.,\/]+$/u.test(altAddressInput.value) && (altAddressInput.value.length > 2 && altAddressInput.value.length < 50);
  }
  return true;
});

const isAltPostcodeValid = computed(() => {
  if (altaddrChecked.value) {
    if (altPostcodeInput.value === '') {
      return null; // Initial value, no validation
    }
    return /^\d{2}-\d{3}$/.test(altPostcodeInput.value) && (altPostcodeInput.value.length > 2 && altPostcodeInput.value.length < 12);
  }
  return true;
});

const isAltCityValid = computed(() => {
  if (altaddrChecked.value) {
    if (altCityInput.value === '') {
      return null; // Initial value, no validation
    }
    return /^\d{2}-\d{3}$/.test(altCityInput.value) && (altCityInput.value.length > 2 && altCityInput.value.length < 50);
  }
  return true;
});



const isFormValid = computed(() => {
  // Implement your validation logic here
  return isNameValid.value && isSurnameValid.value && isNationValid.value && isAddressValid.value && isCityValid.value && isPostcodeValid.value && isCityValid.value && isPhonenumberValid.value && isAltAddressValid.value && isAltPostcodeValid.value && isAltCityValid.value;

});

// Popup mechanism -----------
const isPopupVisible = ref(false);

const showPopup = () => {
  isPopupVisible.value = true;
};

const exposedDiscountedAmount = ref(0); // Initialize with a default value
const exposedDiscountStatus = ref('');
const exposedDiscountCode = ref('');


const closePopup = () => {
  isPopupVisible.value = false;
};
// Generate products for testing purposes (possibility of implementing shopping cart)---------------------------


const generateProducts = (count) => {
  const products = [];
  for (let i = 1; i <= count; i++) {
    products.push({
      productId: i,
      name: `Produkt ${i}`,
      price: Math.round(Math.random() * (200 - 5) + 1),
      quantity: Math.round(Math.random() * (3 - 1) + 1),
      totalPrice: 0
    });
    products[i - 1].totalPrice = products[i - 1].price * products[i - 1].quantity

  }
  return products;
};

const products = generateProducts(5); // Generate a N number or products for testing

const totalPrice = computed(() => { // Price before discount without added delivery fee (only sum of the prices of objects * quantity)
  return (products.reduce((total, item) => total + item.totalPrice, 0)).toFixed(2);
});

const totalPriceDiscounted = computed(() => { // Price after discount with added delivery fee (not included in discount)
  return ((products.reduce((total, item) => total + item.totalPrice, 0) + selectedDeliveryPrice.value) * (1 - exposedDiscountedAmount.value)).toFixed(2);
});

// Perform a cleanup after closing the alt address ----------

watchEffect(() => {
  if (!altaddrChecked.value) {
    altAddressInput.value = ''; // Reset altAddressInput to an empty string
    altPostcodeInput.value = '';
    altCityInput.value = '';
  }
});


// Form POST logic ------------------------------------------
const responseMessage = ref('');

const submitForm = async () => {
  if (!isFormValid.value) {
    alert("Error validacji");
    return;
  }

  try {
    const responseRecaptcha = await execute();

    const formData = new FormData(form.value); // Collect form data from reactive object
    formData.append('products', JSON.stringify(products)); // Append non default data
    formData.append('partial_price', totalPrice.value);
    formData.append('full_price', totalPriceDiscounted.value);
    formData.append('discountcode', exposedDiscountCode.value);
    formData.append('discountcode_status', exposedDiscountStatus.value);
    formData.append('reCaptcha', responseRecaptcha);
    const response = await axios.post('http://api.smartbees-zadanie.local:8080/includes/order.inc.php', formData); // Post
    // Handle success
    responseMessage.value = response.data.message;
    deliveryCode.value = response.data.deliverycode;
    showPostPopup.value = true;
    isSuccess.value = true;
  } catch (error) {
    // Handle error
    responseMessage.value = error.response.data.error;
    showPostPopup.value = true;
    isSuccess.value = false;
    console.error('Error submitting form:', error);
  }
};
// end ---------------------------------------------------------
</script>

<template>
  <form ref="form" @submit.prevent="submitForm" method="POST">
    <div class="form-container" id="form-container">
      <div class="form-container__item m-right--2em m-bottom--2em">
        <span class="title">
          <p>1. TWOJE DANE</p>
        </span>
        <div class="login-container">
          <button type="button" @click="showLogin" class="button primary--red">Logowanie</button>
          <span>Masz już konto? kliknij żeby się zalogować</span>
        </div>
        <div class="new-account-container">
          <label for="new-account__checkbox" class="form-control">
            <input type="checkbox" name="newaccount" id="new-account__checkbox" v-model="registerChecked">
            Stwórz nowe konto
          </label>
        </div>
        <div class="new-account-details-container" v-if="registerChecked">
          <input type="text" name="login" id="username" placeholder="Login" class="input">
          <input type="password" name="password" placeholder="Hasło" class="input">
          <input type="password" name="password_confirm" placeholder="Potwierdź hasło" class="input">
        </div>
        <input type="text" name="name" placeholder="Imię *" class="input" required v-model="nameInput" />
        <span class="validation-error" v-if="isNameValid !== null && !isNameValid">Proszę wprowadzić poprawne imię.</span>
        <input type="text" name="surname" placeholder="Nazwisko *" class="input" required v-model="surnameInput">
        <span class="validation-error" v-if="isSurnameValid !== null && !isSurnameValid">Proszę wprowadzić poprawne
          nazwisko.</span>
        <select name="nation" id="nation" class="input" required v-model="nationInput">
          <option value="poland">Polska</option>
          <option value="other">Reszta świata</option>
        </select>
        <span class="validation-error" v-if="isNationValid !== null && !isNationValid">Proszę wybrać poprawną nazwe
          państwa.</span>
        <input type="text" name="address" placeholder="Adres *" class="input" required v-model="addressInput">
        <span class="validation-error" v-if="isAddressValid !== null && !isAddressValid">Proszę wprowadzić poprawny
          adres.</span>
        <input type="text" name="postcode" placeholder="Kod pocztowy*" class="input" required v-model="postcodeInput">
        <span class="validation-error" v-if="isPostcodeValid !== null && !isPostcodeValid">Proszę wprowadzić poprawny kod
          pocztowy (format 00-000).</span>
        <input type="text" name="city" placeholder="Miasto *" class="input" required v-model="cityInput">
        <span class="validation-error" v-if="isCityValid !== null && !isCityValid">Proszę wprowadzić poprawne
          miasto.</span>
        <input type="tel" name="phonenumber" placeholder="Telefon *" class="input" required v-model="phonenumberInput">
        <span class="validation-error" v-if="isPhonenumberValid !== null && !isPhonenumberValid">Proszę wprowadzić
          poprawny numer telefonu.</span>

        <div class="other-address-delivery-container">
          <label for="other_address_delivery" class="form-control">
            <input type="checkbox" class="custom-checkbox" name="other_address_delivery" id="other_address_delivery"
              v-model="altaddrChecked">
            Dostawa pod inny adres
          </label>
        </div>
        <div v-if="altaddrChecked">
          <div class="other-address-delivery-inf">
            <input type="text" name="address-alt" placeholder="Adres *" class="input" required v-model="altAddressInput">
            <span class="validation-error" v-if="isAltAddressValid !== null && !isAltAddressValid">Proszę wprowadzić
              poprawny adres.</span>

            <input type="text" name="postcode-alt" placeholder="Kod pocztowy*" class="input" required
              v-model="altPostcodeInput">
            <span class="validation-error" v-if="isAltPostcodeValid !== null && !isAltPostcodeValid">Proszę wprowadzić
              poprawny kod pocztowy (format 00-000).</span>

            <input type="text" name="city-alt" placeholder="Miasto *" class="input" required v-model="altCityInput">
            <span class="validation-error" v-if="isCityValid !== null && !isCityValid">Proszę wprowadzić poprawne
              miasto.</span>

          </div>
        </div>

      </div>
      <div class="form-container__item m-right--2em m-bottom--2em">
        <span class="title">
          <p>2. METODA DOSTAWY</p>
        </span>
        <div class="form__option">
          <input type="radio" name="deliverytype" id="paczkomaty" class="option__radio--big" value="paczkomat"
            v-model="deliveryOption" required>
          <label for="paczkomaty" class="label--separated label--inpost">Paczkomaty 24/7</label>
          <span class="delivery__price">
            <p>{{ deliveryPrices.paczkomat }}zł</p>
          </span>
        </div>
        <div class="form__option">
          <input type="radio" name="deliverytype" id="kurierdpd" class="option__radio--big" value="dpd"
            v-model="deliveryOption" required>
          <label for="kurierdpd" class="label--separated label--dpd">Kurier DPD</label>
          <span class="delivery__price">
            <p>{{ deliveryPrices.dpd }}zł</p>
          </span>
        </div>
        <div class="form__option">
          <input type="radio" name="deliverytype" id="kurierdpd_pobranie" class="option__radio--big" value="dpdpobranie"
            v-model="deliveryOption" required>
          <label for="kurierdpd_pobranie" class="label--separated label--dpd">Kurier DPD pobranie</label>
          <span class="delivery__price">
            <p>{{ deliveryPrices.dpdpobranie }}zł</p>
          </span>
        </div>

        <span class="title">
          <p>3. METODA PŁATNOŚCI</p>
        </span>
        <div class="form__option" id="form__option--payu" v-if="deliveryOption !== 'dpdpobranie'">
          <input type="radio" name="paymenttype" id="payment__payu" class="option__radio--big" value="payu" required>
          <label for="payu" class="label--separated">PayU</label>
        </div>
        <div class="form__option" id="form__option--upon-receipt"
          v-if="deliveryOption !== 'paczkomat' && deliveryOption !== 'dpd'">
          <input type="radio" name="paymenttype" id="payment__upon-receipt" class="option__radio--big"
            value="upon-receipt" required>
          <label for="upon-receipt" class="label--separated">Płatność przy odbiorze</label>
        </div>
        <div class="form__option" id="form__option--bank-transfer" v-if="deliveryOption !== 'dpdpobranie'">
          <input type="radio" name="paymenttype" id="payment__bank-transfer" class="option__radio--big"
            value="bank-transfer" required>
          <label for="bank-transfer" class="label--separated">Przelew bankowy - zwykły</label>
        </div>
        <button class="button" @click="showPopup" type="button">Dodaj kod rabatowy</button>
        <PopupWindow :isVisible="isPopupVisible" :closePopup="closePopup" :discountedAmount="exposedDiscountedAmount"
          :discountStatus="exposedDiscountStatus" :discountCode="exposedDiscountCode"
          @update:discounted-amount="exposedDiscountedAmount = $event"
          @update:discount-status="exposedDiscountStatus = $event" @update:discount-code="exposedDiscountCode = $event" />

      </div>
      <div class="form-container__item m-left--2em">
        <span class="title">
          <p>4. PODSUMOWANIE</p>
        </span>
        <div v-for="item in products" class="product-container">
          <div class="product__image">
          </div>
          <div class="product__info">
            <div class="product__text product__text--top">
              <span class="product__name">{{ item.name }}</span>
              <span class="product__price">{{ item.totalPrice }}zł</span>
            </div>
            <span class="product__quantity">Ilość: {{ item.quantity }}</span>
          </div>
        </div>
        <div class="summary">
          <div class="summary__partial">
            <span>Suma częściowa</span>
            <span>{{ totalPrice }}zł</span>
          </div>
          <div class="summary__total summary__total--bold">
            <span>Łącznie</span>
            <span>{{ totalPriceDiscounted }}zł</span>
          </div>
        </div>
        <textarea name="comment" class="comment" placeholder="Komentarz"></textarea>
        <div class="newsletter-signup">
          <label for="newsletter-signup__checkbox" class="form-control">
            <input type="checkbox" class="custom-checkbox" name="newsletter-signup-checkbox"
              id="newsletter-signup__checkbox">
            Zapisz się, aby otrzymywać newsletter
          </label>
        </div>
        <div class="law">
          <label for="law__checkbox" class="form-control">
            <input type="checkbox" class="custom-checkbox" name="law-checkbox" id="law__checkbox" required>
            Zapoznałam/em się z Regulaminem zakupów
          </label>
        </div>

        <button class="button checkout--red">POTWIERDŹ ZAKUP</button>
        <PostPopup v-if="showPostPopup" :show="showPostPopup" :isSuccess="isSuccess" :responseMessage="responseMessage"
          :closePostPopup="closePostPopup" :deliveryCode="deliveryCode"/>

      </div>

    </div>

  </form>
  <LoginPopup v-if="showLoginPopup" :show="showLoginPopup" :closeLoginPopup="closeLoginPopup"/>
</template>


<style scoped lang="scss">
$red-primary: #e54444;
$dark-beige: #a99f8e;
$focused: #e8ecfc;
$background-primary: #f5f5f5;

$inpost-logo: url("../assets/inpost-transparent-bg-whitebg.png");
$dpd-logo: url("../assets/dpd-logo-transparent.png");

* {
  box-sizing: border-box;
}

textarea {
  resize: none;

}

textarea.comment {
  height: 10em;
  margin: 1em 0 1em 0;
  padding: 0.5em 0 0 0.5em;
}

.m-right--2em {
  margin-right: 2em;
}

.m-bottom--2em {
  margin-bottom: 2em;
}

.form-container {
  display: flex;
  flex-wrap: wrap;
  transition: 0.5s;
}

.title {
  background-color: $dark-beige;
  width: 100%;
  height: 50px;
  color: $background-primary;
  padding-left: 1em;
  margin-bottom: 5px;
  border-radius: 5px;
  font-weight: bold;
}

.login-container {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding-bottom: 10px;
}

.button {
  width: 100%;
  min-height: 50px;
  margin-bottom: 10px;
  background-color: $background-primary;
  border: solid;
  border-width: 2px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
  font-size: 1em;
  cursor: pointer;
}

.primary--red {
  border-color: $red-primary;
  color: $red-primary;
}

.checkout--red {
  background-color: $red-primary;
  color: $background-primary;
}

.primary--beige-dark {
  border-color: $dark-beige;
  color: $dark-beige;
}

.new-account-container {
  margin-top: 20px;
}


.other-address-delivery-inf {
  width: 100%;
  display: flex;
  flex-direction: column;

}

.other-address-delivery-container {
  margin-top: 20px;
}

.input {
  width: 100%;
  height: 38px;
  margin-top: 24px;
  margin-bottom: 0.1em;
  padding-left: 1em;
  border: none;
  border: solid 1px #b4aea2;
  border-radius: 5px;

}

.input:focus {
  background-color: $focused;
}

.form__option {
  width: 100%;
  margin-bottom: 15px;
  display: flex;
  align-items: center;

}

.label--separated {
  margin-left: 1em;
  display: flex;
  align-items: center;
}


.label--separated::before {
  content: "";
  display: block;
  width: 54px;
  height: auto;
  aspect-ratio: 2/1;
  background-size: contain;
  background-repeat: no-repeat;

}

.label--inpost::before {
  background-image: $inpost-logo;
}

.label--dpd::before {
  background-image: $dpd-logo;
}

.option__radio--big {
  border: 0px;
  height: 2em;
  width: 2em;
  margin-top: 0;
}

.delivery__price {
  margin-left: auto;
  height: 100%;
  margin-right: 1em;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
}



.form-container__item {
  min-width: 100%;

  @media (min-width: 769px) {
    min-width: 0;
    width: 300px;
  }

  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
}

.error-msg {
  min-width: 100%;
}

.product-container {
  min-width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.product__image {
  height: 50px;
  width: 80px;
  background-color: #808080;
  margin: 5px;
}

.product__info {
  width: 100%;
  height: 100%;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}

.product__text {
  width: 100%;
}

.product__text--top {
  display: flex;
  justify-content: space-between;
  font-weight: bold;
  padding-right: 0.5em;
}

.summary {
  width: 100%;
  border-top: 2px dashed #add8e6;
  border-bottom: 2px dashed #add8e6;
  padding: 0.5em;
  margin-bottom: 5px;
}

.summary__partial {
  display: flex;
  width: 100%;
  justify-content: space-between;
  margin-bottom: 1em;
}

.summary__total {
  display: flex;
  width: 100%;
  justify-content: space-between;
}

.summary__total--bold {
  font-weight: bold;
}

.newsletter-signup,
.law {
  margin-bottom: 10px;
}

.validation-error {
  padding: 0.25em 0 0 0.5em;
  color: #ff0000;
  font-size: 0.8em;
  font-weight: 700;
}

.form-control {
  font-size: 1rem;
  display: flex;
  gap: 5px;
  align-items: center;
}

input[type="checkbox"] {
  appearance: none;
  background-color: #fff;
  margin: 0;
  color: gray;
  width: 1.15em;
  height: 1.15em;
  border: 0.1em solid currentColor;
  border-radius: 0.15em;
  transform: translateY(-0.075em);
  display: grid;
  place-content: center;
}

.form-control+.form-control {
  margin-top: 1em;
}

input[type="checkbox"]::before {
  content: "";
  width: 0.65em;
  height: 0.65em;
  transform: scale(0);
  transition: 120ms transform ease-in-out;
  box-shadow: inset 1em 1em $red-primary;
  transform-origin: bottom left;
  clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
}

input[type="checkbox"]:checked::before {
  transform: scale(1);
}

* {
  font-family: 'Open Sans';
}</style>