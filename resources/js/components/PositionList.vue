<template>
    <ul class="list-group">
        <li v-for="position in positions" :key="position.id" class="list-group-item">
            {{ position.name }}
        </li>
    </ul>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const positions = ref([]);

const fetchPositions = async () => {
    try {
        const response = await axios.get('/api/positions');
        positions.value = response.data.positions;
    } catch (error) {
        console.error('Failed to fetch positions', error);
    }
};

onMounted(fetchPositions);
</script>
