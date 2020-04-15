import PeopleComponent from './components/PeopleComponent.vue';
import WelcomeComponent from './components/WelcomeComponent.vue';
import DriversComponent from './components/DriversComponent.vue';
import TasksComponent from './components/TasksComponent.vue';
import PersonUpdateComponent from './components/PersonUpdateComponent.vue';
import AddPersonComponent from './components/AddPersonComponent.vue';
import AddTaskComponent from './components/AddTaskComponent.vue';
import AboutPersonComponent from './components/AboutPersonComponent.vue';
import AboutTaskComponent from './components/AboutTaskComponent.vue';
import TaskUpdateComponent from './components/TaskUpdateComponent.vue';
import HistoryComponent from './components/HistoryComponent';
import UserListComponent from './components/UserListComponent';
import UserEditComponent from './components/UserEditComponent';
import StatisticsComponent from './components/StatisticsComponent';
import store from './store/index';

async function redirectIfNotAuth (to, from, next) {
    if (store.state.user) {
        if((to.path === "/users" || to.path === "/user-edit") && store.state.user.role != "Admin") {
            store.dispatch('location', "/");
            window.location.pathname = '/';
        } else {
            store.dispatch('location', to.path);
            next();
        }
    } else {
        window.location.pathname = '/login';
    }
};

export default [
    {
        path: '/', 
        component: WelcomeComponent
    },
    {
        path: '/clients',
        component: PeopleComponent,
        beforeEnter: redirectIfNotAuth,
        // meta: {
        //     requiresAuth: true
        // }
    },
    {
        path: '/drivers',
        component: DriversComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/tasks',
        component: TasksComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/about-person',
        component: AboutPersonComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/person-edit',
        component: PersonUpdateComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/person-add',
        component: AddPersonComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/task-add',
        component: AddTaskComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/about-task',
        component: AboutTaskComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/history',
        component: HistoryComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/task-edit',
        component: TaskUpdateComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/users',
        component: UserListComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/user-edit',
        component: UserEditComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path: '/statistics',
        component: StatisticsComponent,
        beforeEnter: redirectIfNotAuth
    },
    {
        path :'*',
        component: WelcomeComponent
    }
];
