<template>
    <b-overlay :show="loading" rounded="sm">
        <b-list-group>
            <b-list-group-item v-for="user in users" :key="user.id" class="d-flex align-items-center">
<!--                <i>{{user.id}}</i>   -->
                <b-avatar :src="user.photo" class="me-3"></b-avatar>
                <div>
                    <strong>{{ user.name }}</strong> - {{ user.position }}
                    <b-button v-b-toggle.collapse-1 size="sm" variant="outline-primary" class="ms-2" @click="fetchUser(user.id)">Details</b-button>

                    <b-collapse id="collapse-1" class="mt-2">
                        <b-card>
                            <p class="card-text">Collapse contents Here</p>
                            <b-button v-b-toggle.collapse-1-inner size="sm">Toggle Inner Collapse</b-button>
                            <b-collapse id="collapse-1-inner" class="mt-2">
                                <b-card>Hello!</b-card>
                            </b-collapse>
                        </b-card>
                    </b-collapse>
                </div>
            </b-list-group-item>
        </b-list-group>

        <b-button v-if="nextUrl" @click="fetchUsers(nextUrl)" variant="primary" class="mt-2">Load More</b-button>

        <b-modal size="lg" centered :title="`Selected User #${selectedUser?.user?.id}`" v-model="selectedUser">
            <pre class="pre-scrollable">{{ selectedUser }}</pre>
        </b-modal>
    </b-overlay>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import {
    BListGroup,
    BListGroupItem,
    BButton,
    BAvatar,
    BCollapse,
    BOverlay,
    BCard,
    BModal,
} from "bootstrap-vue-next/components";
import axios from 'axios';

const users = ref([]);
const nextUrl = ref(null);
const selectedUser = ref(null);
const loading = ref(false);
const modalShow = ref(false);

const API_BASE_URL = import.meta.env.VITE_API_URL;
const apiUrl = ref(API_BASE_URL);
const usersApiUrl = `${apiUrl.value}/users`;

const showUserApiUrl = userId => `${usersApiUrl}/${userId}`;

const fetchUsers = async (url = usersApiUrl) => {
    loading.value = true;
    try {
        const response = await axios.get(url);
        users.value = [...users.value, ...response.data.users];
        nextUrl.value = response.data.links.next_url;
    } catch (error) {
        console.error('Failed to fetch users', error);
    } finally {
        loading.value = false;
    }
};

const fetchUser = async (id) => {
    try {
        const response = await axios.get(showUserApiUrl(id))
        selectedUser.value = response.data;
    } catch (error) {
        console.error('Failed to fetch user', error);
    }
};

onMounted(fetchUsers);
</script>
