<template>
    <b-overlay :show="loading" rounded="sm">
        <div class="alert " :class="!!apiResponse?.success ? 'alert-success' : 'alert-danger'" role="alert" v-show="!!apiResponse?.message">
            {{ apiResponse?.message }}
        </div>
        <TokenFetcher @token-fetched="setToken" />
        <b-form @submit.prevent="createUser">
            <b-form-group label="Name" label-for="name">
                <b-form-input id="name" :state="validation.name" v-model="form.name" type="text"></b-form-input>
                <b-form-invalid-feedback  class="d-block" v-for="(item, index) in errors.name" :key="index">{{ item }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Email" label-for="email">
                <b-form-input id="email" :state="validation.email" v-model="form.email" type="text"></b-form-input>
                <b-form-invalid-feedback  class="d-block" v-for="(item, index) in errors.email" :key="index">{{ item }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Phone" label-for="phone">
                <b-form-input id="phone" :state="validation.phone" v-model="form.phone" placeholder="380XXXXXXXXX" type="tel"></b-form-input>
                <b-form-invalid-feedback  class="d-block" v-for="(item, index) in errors.phone" :key="index">{{ item }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Position" label-for="position_id">
                <b-input-group>
                    <template #prepend>
                        <b-dropdown :text="(form.position_id && props.positions.find(p => p.id === form.position_id)?.name) || `Positions`" variant="info">
                            <b-dropdown-item v-for="position in props.positions" :key="position.id" :value="position.id" @click="form.position_id=position.id">
                                {{ position.name }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </template>
                    <b-form-input id="position_id" :state="validation.position_id" v-model="form.position_id"></b-form-input>
                    <b-form-invalid-feedback  class="d-block" v-for="(item, index) in errors.position_id" :key="index">{{ item }}</b-form-invalid-feedback>
                </b-input-group>
            </b-form-group>
            <b-form-group label="Photo">
                <b-form-file :state="validation.photo" @change="handleFile"></b-form-file>
                <b-form-invalid-feedback  class="d-block" v-for="(item, index) in errors.photo" :key="index">{{ item }}</b-form-invalid-feedback>
            </b-form-group>

            <b-button type="submit" variant="success" class="mt-2">Create User</b-button>

            <b-card class="mt-3" bg-variant="light" header="JSON response">
                <b-card-text>
                    <pre v-show="!!apiResponse">{{ JSON.stringify(apiResponse, null, 4) }}</pre>
                </b-card-text>
            </b-card>
        </b-form>
        <b-modal size="lg" centered :title="`Created User #${apiResponse?.user_id}`" v-model="createdUser">
            <pre class="pre-scrollable">{{ apiResponse }}</pre>
        </b-modal>
    </b-overlay>
</template>

<script setup>
import { computed, ref, defineProps } from 'vue';
import TokenFetcher from './TokenFetcher.vue';
import {
    BFormSelect, BFormSelectOption,
    BFormFile, BFormInput,
    BFormInvalidFeedback,
    BForm, BFormGroup,
    BAlert, BButton,
    BOverlay,
    BInputGroup,
    BDropdown, BDropdownItem,
    BModal,
    BCard, BCardText,
} from "bootstrap-vue-next";

import axios from 'axios';

const form = ref({ name: '', email: '', phone: '', position_id: '', photo: null });
const errors = ref({});
const apiResponse = ref(null);
const loading = ref(false);
const token = ref('');
const createdUser = ref(false);

const setToken = (newToken) => {
    token.value = newToken;
};

const props = defineProps(['positions']);

const validation = {
    name: computed(() => !errors.value.name?.length),
    email: computed(() => !errors.value.email?.length),
    phone: computed(() => !errors.value.phone?.length),
    position_id: computed(() => !errors.value.position_id?.length),
    photo: computed(() => !errors.value.photo?.length),
};

const apiUrl = import.meta.env.VITE_API_URL;
const usersApiUrl = `${apiUrl}/users`;

const handleFile = (event) => {
    form.value.photo = event.target.files[0];
};

const createUser = async () => {
    errors.value = {};
    // apiResponse.value = null;
    loading.value = true;

    try {
        const formData = new FormData();
        Object.entries(form.value).forEach(([key, value]) => formData.append(key, value));

        const response = await axios.post(usersApiUrl, formData, {
            headers: { Authorization: `Bearer ${token.value}` },
        });

        apiResponse.value = response.data;
        createdUser.value = true;
    } catch (err) {
        apiResponse.value = err.response?.data;
        if (err.response?.data?.fails) {
            for (let field in err.response.data.fails) {
                errors.value[field] = err.response.data.fails[field];
            }
        }
        errors.value.general = err.response?.data?.message || 'User creation failed';
    } finally {
        loading.value = false;
    }
};
</script>
