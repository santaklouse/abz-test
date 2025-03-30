<template>
    <div class="container">
        <h1 class="my-4">API Tester</h1>
        <TokenFetcher @token-fetched="setToken" />
        <PositionList />
        <UserForm :token="token" />
        <UserList />
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import TokenFetcher from './TokenFetcher.vue';
    import PositionList from './PositionList.vue';
    import UserForm from './UserForm.vue';
    import UserList from './UserList.vue';

    const token = ref('');

    const setToken = (newToken) => {
        token.value = newToken;
    };
</script>
