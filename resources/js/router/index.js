import { createWebHistory, createRouter } from 'vue-router'
/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
const AddTask = () => import('@/components/AddTask.vue')
const EditTask = () => import('@/components/EditTask.vue')
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "addTask",
                path: '/add-task',
                component: AddTask,
                meta: {
                    title: `Add Task`
                }
            },
            {
                name: "editTask",
                path: '/edit-task/:id',
                component: EditTask,
                meta: {
                    title: `Edit Task`
                }
            },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    const auth = localStorage.getItem('auth');
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (auth) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (auth) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router