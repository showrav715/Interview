<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Login</h1>
                        <hr />
                        <form action="javascript:void(0)" class="row" method="post">
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="text" v-model="auth.email" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password" class="font-weight-bold">Password</label>
                                <input type="password" v-model="auth.password" name="password" id="password"
                                    class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <button type="submit" :disabled="processing" @click="login"
                                    class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Login" }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label>Don't have an account? <router-link :to="{ name: 'register' }">Register
                                        Now!</router-link></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const auth = ref({
  email: '',
  password: '',
});
const validationErrors = ref({});
const processing = ref(false);
const router = useRouter();

// login function after set token and user information to localstorate
const login = async () => {
  processing.value = true;
  try {
    await axios.get('/sanctum/csrf-cookie');
    const { data } = await axios.post('/api/login', auth.value);
    if(data.status == 401){
        const array = [];
        array.push(data.message);
        validationErrors.value = array;
      return true;
    }
    localStorage.setItem('auth', JSON.stringify(data.user));
    localStorage.setItem('token', JSON.stringify(data.token));
    router.push({ name: 'dashboard' });
  } catch (error) {
    if (error.response) {
      validationErrors.value = error.response.data.errors;
    }
  } finally {
    processing.value = false;
  }
};
</script>