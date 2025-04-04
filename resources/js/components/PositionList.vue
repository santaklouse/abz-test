<template>
    <b-overlay :show="loading" rounded="sm">
        <ul class="list-group">
            <li v-for="position in positions" :key="position.id" class="list-group-item">
                <span>{{ JSON.stringify(position) }}</span>
            </li>
        </ul>
        <b-card class="mt-3" bg-variant="light" header="JSON response">
            <b-card-text>
                <pre v-show="!!apiResponse">{{ JSON.stringify(apiResponse, null, 4) }}</pre>
            </b-card-text>
        </b-card>
    </b-overlay>
</template>

<script setup>
import {ref, onMounted, defineEmits} from 'vue';
import axios from 'axios';
import {
    BOverlay,
    BCard,
    BCardText,

} from "bootstrap-vue-next";

const positions = ref([]);
const loading = ref(false);
const apiResponse = ref(null);

const API_BASE_URL = import.meta.env.VITE_API_URL;
const apiUrl = ref(API_BASE_URL);
const positionsApiUrl = `${apiUrl.value}/positions`;

const emit = defineEmits(['positions-fetched']);

const fetchPositions = async () => {
    loading.value = true;
    try {
        const response = await axios.get(positionsApiUrl);
        positions.value = response.data.positions;
        apiResponse.value = response.data;

        emit('positions-fetched', positions.value);
    } catch (error) {
        console.error('Failed to fetch positions', error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchPositions);
</script>
