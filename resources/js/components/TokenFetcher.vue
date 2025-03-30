<template>
    <button @click="fetchToken" class="btn btn-primary">Get Token</button>
    <p v-if="token">Token: {{ token }}</p>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import axios from 'axios';

const token = ref('');
const emit = defineEmits(['token-fetched']);

const fetchToken = async () => {
    try {
        const response = await axios.get('/api/token');
        token.value = response.data.token;
        emit('token-fetched', token.value);
    } catch (error) {
        console.error('Failed to fetch token', error);
    }
};
</script>
