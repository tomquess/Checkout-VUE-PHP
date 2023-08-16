<template>
    <div class="popup" v-if="isVisible">
        <div class="popup-content">
            <input v-model="inputData" type="text" />
            <button @click="validateDiscount" type="button">Zaaplikuj zniżkę</button>
            <button @click="closePopup" type="button">Anuluj</button>

        </div>
        <div class="discount-status success" v-if="discountStatus === 'success'">Zniżka zaaplikowana!</div>
        <div class="discount-status" v-if="discountedAmount">Twoja zniżka wynosi: {{ discountedAmount * 100 }}%</div>
        <div class="discount-status error" v-if="discountStatus === 'notfound'">Kod nie istnieje</div>
        <div class="discount-status error" v-if="discountStatus === 'code unactive'">Kod wygasł</div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import axios from 'axios';



const props = defineProps({
    isVisible: Boolean,
    closePopup: Function,
    discountedAmount: Number, // Receive the discountedAmount prop
    discountStatus: String,   // Receive the discountStatus prop
    discountCode: String,   // Receive the discountStatus prop
});

const emits = defineEmits();

const inputData = ref('');






const validateDiscount = async () => {
    try {
        const response = await axios.post(import.meta.env.VITE_APP_BACKEND_URL + '/includes/discountcode.inc.php',
            JSON.stringify({
                code: inputData.value
            }), {
            headers: {
                'Content-Type': 'application/json'
            }
        });
        // Emit an event to update the prop values in the parent component
        emits('update:discountedAmount', response.data.discount);
        emits('update:discountStatus', response.data.status);
        emits('update:discountCode', inputData.value);
        if (response.data.status == 'success') {
            emits('update:discountCode', inputData.value);
        }

    } catch (error) {
        console.error(error);
    }
};

const closePopup = () => {
    props.closePopup();
    emits('update:discountedAmount', 0); // Clear discount data
    emits('update:discountStatus', '');
    emits('update:discountCode', '');
    inputData.value = ''; // Clear the input field
};


</script>
  
<style scoped lang="scss">
$background-primary: #f5f5f5;
$focused: #e8ecfc;

input[type='text'] {
    width: 40%;
    height: 24px;
    margin-bottom: 0.1em;
    padding-left: 1em;
    border: none;
    border: solid 1px #b4aea2;
    border-radius: 5px;
    margin-right: auto;
}
.input:focus {
  background-color: $focused;
}


button {
    height: 24px;
    background-color: $background-primary;
    border: solid;
    border-width: 1px;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    font-size: 0.8em;
    cursor: pointer;
}

.popup-content {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
}

.discount-status {
    padding: 0.25em 0 0 0.5em;
    font-size: 0.8em;
    font-weight: 700;
}
.success {
    color: #008000;
}

.error {
    color: #ff0000;
}
</style>