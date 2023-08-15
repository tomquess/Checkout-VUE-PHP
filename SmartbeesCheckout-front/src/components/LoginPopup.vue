<template>
    <div class="popup-container">
        <div class="popup" v-if="show">
            <button class="close-button" @click.prevent="closeLoginPopup">×</button>
            <form @submit.prevent="login">
                <label for="username">Username:</label>
                <input type="text" required>
                <label for="password">Password:</label>
                <input type="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="overlay" @click.prevent="closeLoginPopup"></div>
    </div>
</template>
  
<script setup>
import { defineProps, ref } from 'vue';

const props = defineProps({
    show: Boolean,
    closeLoginPopup: Function
});

const closeLoginPopup = () => {
    props.closeLoginPopup();
};

const login = () => {
    console.log("login");
};

</script>
  
<style scoped lang="scss">
.popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup {
    position: relative;
    padding: 20px;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1001;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 18px;
    background: none;
    border: none;
    cursor: pointer;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

label {
    font-weight: bold;
}

input {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button[type="submit"] {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
</style>