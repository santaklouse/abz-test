<template>
    <div class="container">
        <h1 class="my-4">ABZ test API Tester</h1>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="PositionList">
                    <b-card-text>
                        <PositionList @positions-fetched="setPositions" />
                    </b-card-text>
                </b-tab>
                <b-tab title="UserForm">
                    <b-card-text>
                        <UserForm :positions="positions" />
                    </b-card-text>
                </b-tab>
                <b-tab title="UserList">
                    <b-card-text>
                        <UserList />
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
</template>

<script setup>
import {
    BTab, BCard,
    BTabs,
    BCardBody, BCardText,
} from "bootstrap-vue-next";
    import { ref } from 'vue';
    import PositionList from './PositionList.vue';
    import UserForm from './UserForm.vue';
    import UserList from './UserList.vue';

    const positions = ref([]);
    const setPositions = (newPositions) => {
        positions.value = newPositions;
    };
</script>
