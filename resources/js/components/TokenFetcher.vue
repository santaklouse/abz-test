<template>
    <b-overlay :show="loading" rounded="sm">
        <b-button @click="fetchToken" :disabled="loading" variant="primary">Get Token</b-button>
        <pre v-if="apiResponse">{{apiResponse}}</pre>
    </b-overlay>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import {
    BOverlay,
    BButton,
} from "bootstrap-vue-next";
import axios from 'axios';

const token = ref('');
const loading = ref(false);
const emit = defineEmits(['token-fetched']);

const API_BASE_URL = import.meta.env.VITE_API_URL;
const apiUrl = ref(API_BASE_URL);
const tokenApiUrl = `${apiUrl.value}/token`;

const apiResponse = ref(null);

const fetchToken = async () => {
    loading.value = true;
    apiResponse.value = null;
    try {
        const response = await axios.get(tokenApiUrl);
        token.value = response.data.token;
        emit('token-fetched', token.value);
        apiResponse.value = response.data;
    } catch (error) {
        console.error('Failed to fetch token', error);
    } finally {
        loading.value = false;
    }
};
</script>
