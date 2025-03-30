<template>
    <b-list-group>
        <b-list-group-item v-for="user in users" :key="user.id" class="d-flex align-items-center">
            <b-avatar :src="user.photo" class="me-3"></b-avatar>
            <div>
                <strong>{{ user.name }}</strong> - {{ user.position }}
                <b-button size="sm" variant="outline-primary" class="ms-2" @click="fetchUser(user.id)">Details</b-button>
            </div>
        </b-list-group-item>
    </b-list-group>

    <b-button v-if="nextUrl" @click="fetchUsers(nextUrl)" variant="primary" class="mt-2">Load More</b-button>

    <b-alert v-if="selectedUser" show variant="info" class="mt-3">
        <h5>Selected User</h5>
        <pre>{{ selectedUser }}</pre>
    </b-alert>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import {BListGroup, BAlert, BButton, BAvatar} from "bootstrap-vue-next/components";
import axios from 'axios';

const users = ref([]);
const nextUrl = ref(null);
const selectedUser = ref(null);

const fetchUsers = async (url = '/api/users') => {
    try {
        const response = await axios.get(url);
        users.value = [...users.value, ...response.data.users];
        nextUrl.value = response.data.links.next_url;
    } catch (error) {
        console.error('Failed to fetch users', error);
    }
};

const fetchUser = async (id) => {
    try {
        const response = await axios.get(`/api/users/${id}`);
        selectedUser.value = response.data;
    } catch (error) {
        console.error('Failed to fetch user', error);
    }
};

onMounted(fetchUsers);
</script>
