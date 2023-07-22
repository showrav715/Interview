<template lang="">
    <div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card shadow sm">
                        <div class="card-body">
                            <h1 class="text-center">Edit Task</h1>
                            <hr />
                            <form
                                action="javascript:void(0)"
                                @submit.prevent="updateTask"
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
                                        :value="task.status"
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
                                                : "Update Task"
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
import { useRouter, useRoute } from "vue-router";
import myaxios from "../myaxios.js";
const router = useRouter();
const route = useRoute();
const validationErrors = ref({});
const processing = ref(false);
const users = ref([]);
const task = reactive({
    id: "",
    title: "",
    details: "",
    user_id: "",
    status: "",
});

const headers = ref({});
const token = ref();
// get users and editable task
onMounted(async () => {
    getTask();
    await myaxios
        .get("/get/users")
        .then((response) => {
            users.value = response.data;
        })
        .catch((error) => {
            console.log(error.response.data);
        });
});

// get task using router params id
const getTask = () => {
    myaxios.get(`get/task/${route.params.id}`).then(({ data }) => {
        task.id = data.id;
        task.title = data.title;
        task.details = data.details;
        task.user_id = data.user_id;
        task.status = data.status;
    });
};

// update task 
const updateTask = () => {
    processing.value = true;
    myaxios

        .post(`/update/task/${task.id}`, task)
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
