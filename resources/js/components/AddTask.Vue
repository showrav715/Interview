<template lang="">
    <div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card shadow sm">
                        <div class="card-body">
                            <h1 class="text-center">Add Task</h1>
                            <hr />
                            <form
                                action="javascript:void(0)"
                                @submit.prevent="addTask"
                                class="row"
                                method="post"
                            >
                                <div
                                    class="col-12"
                                    v-if="
                                        Object.keys(validationErrors).length > 0
                                    "
                                >
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <li
                                                v-for="(
                                                    value, key
                                                ) in validationErrors"
                                                :key="key"
                                            >
                                                {{ value[0] }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label for="title" class="font-weight-bold"
                                        >Title</label
                                    >
                                    <input
                                        type="text"
                                        name="title"
                                        v-model="task.title"
                                        id="name"
                                        placeholder="Enter Title"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group col-12 my-2">
                                    <label
                                        for="details"
                                        class="font-weight-bold"
                                        >Details</label
                                    >
                                    <input
                                        type="text"
                                        name="details"
                                        v-model="task.details"
                                        id="details"
                                        placeholder="Enter Details"
                                        class="form-control"
                                    />
                                </div>

                                <div class="form-group col-12">
                                    <label
                                        for="password"
                                        class="font-weight-bold"
                                        >Assign an User</label
                                    >
                                    <select
                                        name="user_id"
                                        v-model="task.user_id"
                                        id="user_id"
                                        class="form-control"
                                    >
                                        <option value="">Select an User</option>
                                        <option
                                            v-for="user in users"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label
                                        for="password"
                                        class="font-weight-bold"
                                        >Status</label
                                    >
                                    <select
                                        name="status"
                                        v-model="task.status"
                                        id="status"
                                        class="form-control"
                                    >
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                        
                                    </select>
                                </div>

                                <div class="col-12 my-2">
                                    <button
                                        type="submit"
                                        :disabled="processing"
                                        class="btn btn-primary btn-block"
                                    >
                                        {{
                                            processing
                                                ? "Please wait"
                                                : "ADD TASK"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, reactive } from "vue";
import {useRouter} from 'vue-router';
import myaxios from "../myaxios.js";
const router = useRouter();
const validationErrors = ref({});
const processing = ref(false);
const users = ref([]);
const task = reactive({
    name: "",
    details: "",
    user_id: "",
    status:1
});

const headers = ref({});
const token = ref();
onMounted(async () => {
    await myaxios
        .get("/get/users")
        .then((response) => {
            users.value = response.data;
        })
        .catch((error) => {
            console.log(error.response.data);
        });
});

// add a task
const addTask = () => {
    processing.value = true;
    myaxios
       
        .post("/create/task", task)
        .then((response) => {
            router.push({ name: "dashboard" });
        })
        .catch((error) => {
            console.log(error.response.data);
            if (error.response.status == 422) {
                validationErrors.value = error.response.data.errors;
            }
        })
        .finally(() => {
            processing.value = false;
        });
};
</script>
