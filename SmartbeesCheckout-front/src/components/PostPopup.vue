<template>
    <div class="popup-container">
      <div class="popup" v-if="show">
        <button class="close-button" @click.prevent="closePostPopup">×</button>
        {{ responseMessage }} {{ deliveryCode }}
      </div>
      <div class="overlay" v-if="show" @click.prevent="closePostPopup"></div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, ref } from 'vue';
  
  const props = defineProps({
    show: Boolean,
    isSuccess: Boolean,
    responseMessage: String,
    deliveryCode: String,
    closePostPopup: Function
  });
  
  const closePostPopup = () => {
    props.closePostPopup();
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
  </style>