<template>
    <b-form @submit.prevent="createUser">
        <b-form-group label="Name" label-for="name">
            <b-form-input id="name" v-model="form.name"></b-form-input>
            <b-form-invalid-feedback v-if="errors.name">{{ errors.name[0] }}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Email" label-for="email">
            <b-form-input id="email" v-model="form.email" type="email"></b-form-input>
            <b-form-invalid-feedback v-if="errors.email">{{ errors.email[0] }}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Phone" label-for="phone">
            <b-form-input id="phone" v-model="form.phone" type="tel"></b-form-input>
            <b-form-invalid-feedback v-if="errors.phone">{{ errors.phone[0] }}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Position">
            <b-form-select v-model="form.position_id">
                <b-form-select-option v-for="position in positions" :key="position.id" :value="position.id">
                    {{ position.name }}
                </b-form-select-option>
            </b-form-select>
            <b-form-invalid-feedback v-if="errors.position_id">{{ errors.position_id[0] }}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Photo">
            <b-form-file @change="handleFile"></b-form-file>
            <b-form-invalid-feedback v-if="errors.photo">{{ errors.photo[0] }}</b-form-invalid-feedback>
        </b-form-group>

        <b-button type="submit" variant="success" class="mt-2">Create User</b-button>

        <b-alert v-if="apiResponse" show variant="info" class="mt-3">
            <pre>{{ apiResponse }}</pre>
        </b-alert>
    </b-form>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import {
    BFormSelect, BFormSelectOption,
    BFormFile, BFormInput,
    BFormInvalidFeedback,
    BForm, BFormGroup,
    BAlert, BButton,
} from "bootstrap-vue-next/components";
import axios from 'axios';

const form = ref({ name: '', email: '', phone: '', position_id: '', photo: null });
const positions = ref([]);
const errors = ref({});
const apiResponse = ref(null);

const props = defineProps(['token']);

const fetchPositions = async () => {
    try {
        const response = await axios.get('/api/positions');
        positions.value = response.data.positions;
    } catch (err) {
        errors.value.general = err.response?.data?.message || 'Failed to fetch positions';
    }
};

const handleFile = (event) => {
    form.value.photo = event.target.files[0];
};

const createUser = async () => {
    errors.value = {};
    apiResponse.value = null;

    try {
        const formData = new FormData();
        Object.entries(form.value).forEach(([key, value]) => formData.append(key, value));

        const response = await axios.post('/api/users', formData, {
            headers: { Authorization: `Bearer ${props.token}` },
        });

        apiResponse.value = response.data;
    } catch (err) {
        if (err.response?.data?.fails) {
            errors.value = err.response.data.fails;
        } else {
            errors.value.general = err.response?.data?.message || 'User creation failed';
        }
    }
};

onMounted(fetchPositions);
</script>
