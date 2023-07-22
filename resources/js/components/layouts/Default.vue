<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="javascript:;"
                    target="_blank">Interview</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link :to="{ name: 'dashboard' }" class="nav-link">Dashboard </router-link>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ user.name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <main class="mt-3">
            <router-view></router-view>
        </main>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const user = ref({});
const token = ref('');
onMounted(async () => {
    user.value = localStorage.getItem('auth') ? JSON.parse(localStorage.getItem('auth')) : {};
    token.value = localStorage.getItem('token') ? JSON.parse(localStorage.getItem('token')) : {};
});

const router = useRouter();

const logout = async () => {
    try {
        await axios.post('/api/logout', {}, {
            headers: {
                Authorization: `Bearer ${token.value}`,
            },
        });
        localStorage.removeItem('auth');
        localStorage.removeItem('token');
        router.push({ name: 'login' });
    } catch (error) {
        console.error(error);
    }
};
</script>
