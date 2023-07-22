<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            You are logged in as <b>{{ user.email }}</b>
                        </p>

                        <div class="my-3">
                            <router-link
                                :to="{ name: 'addTask' }"
                                class="btn btn-primary my-3"
                                >Add Task</router-link
                            >
                            <table
                                class="table table-bordered table-responsive"
                            >
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Details</th>
                                        <th scope="col">Assign User</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-if="tasks.length != 0"
                                        v-for="task in tasks"
                                        :key="task.id"
                                    >
                                        <th scope="row">{{ task.id }}</th>
                                        <td>{{ task.title }}</td>
                                        <td>{{ task.details }}</td>
                                        <td>{{ task.assign_user }}</td>
                                        <td>
                                            <span
                                                class="text-success p-2"
                                                v-if="task.status == 1"
                                                >Active</span
                                            >
                                            <span
                                                class="text-danger p-2"
                                                v-else=""
                                                >Inactive</span
                                            >
                                        </td>
                                        <td>
                                            <router-link
                                                :to="{
                                                    name: 'editTask',
                                                    params: { id: task.id },
                                                }"
                                                class="btn btn-sm btn-primary mx-2"
                                                >Edit</router-link
                                            >
                                            <button
                                                class="btn btn-sm btn-danger"
                                                @click="deleteTask(task.id)"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td colspan="5" class="text-center">
                                            No tasks found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

import myaxios from "../myaxios";
const user = ref({});
const tasks = ref([]);

// get user data from localStorage 
onMounted(() => {
    user.value = localStorage.getItem("auth")
        ? JSON.parse(localStorage.getItem("auth"))
        : {};
    getTasks();
});

// get all tasks
const getTasks = async () => {
    const { data } = await myaxios.get("/tasks");
    tasks.value = data.data;
};

// delete a task
const deleteTask = (id) => {
    let status = confirm("Are you sure?");
    if (!status) {
        return true;
    }
    myaxios
        .get(`/delete/task/${id}`)
        .then(() => {
            getTasks();
        })
        .catch((error) => {
            console.log(error.data.error);
        });
};
</script>
