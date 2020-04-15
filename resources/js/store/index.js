import Vuex from 'vuex';
import environment  from '../environment';
import axios from 'axios';
window.Vue = require('vue');

Vue.use(Vuex);

export default new Vuex.Store ({
   state: {
       user: null,
       url: null,
       userList: null,
       location: '/'
   },
   mutations: {
       location(state, val) {
            state.location = val;
       },
       url(state) {
           state.url = `${ environment.serverUrl }:${ environment.serverPort }`;
       },
       users(state) {
            axios
            .get(`${state.url}/user`)
            .then(response => {
                state.user = response.data.current_user;
                state.userList = response.data.users;
            });
       }
   },
   actions: {
       users(context) {
           context.commit('users');
       },
       location(context, val) {
           context.commit('location', val);
       }
   },
   modules: {
       clients: {
            state: {
                currentPage: 1,
                param1: '',
                param2: '',
                param3: 'клиент',
                sortParam: 'updated_at',
                visiblyArrea: 10
            },
            mutations: {
                currentPage(state, n) {
                    console.log('dfdsfdfs');
                    state.currentPage = n;
                },
                param1(state, val) {
                    state.param1 = val;
                },
                param2(state, val) {
                    state.param2 = val;
                },
                param3(state, val) {
                    state.param3 = val;
                },
                sortParam(state, val) {
                    state.sortParam = val;
                },
                visiblyArrea(state, n) {
                    state.visiblyArrea = n;
                }
            },
            actions: {
                currentPage(context, n) {
                    context.commit('currentPage', n);
                },
                param1(context, val) {
                    context.commit('param1', val);
                },
                param2(context, val) {
                    context.commit('param2', val);
                },
                param3(context, val) {
                    context.commit('param3', val);
                },
                sortParam(context, val) {
                    context.commit('sortParam', val);
                },
                visiblyArrea(context, n) {
                    context.commit('visiblyArrea', n);
                }
            }
       },
        drivers: {
            state: {
                currentPage: 1,
                param1: '',
                param2: '',
                param3: 'водитель',
                sortParam: 'updated_at',
                visiblyArrea: 10
            },
            mutations: {
                currentPage(state, n) {
                    state.currentPage = n;
                },
                param1(state, val) {
                    state.param1 = val;
                },
                param2(state, val) {
                    state.param2 = val;
                },
                param3(state, val) {
                    state.param3 = val;
                },
                sortParam(state, val) {
                    state.sortParam = val;
                },
                visiblyArrea(state, n) {
                    state.visiblyArrea = n;
                }
            },
            actions: {
                currentPage(context, n) {
                    context.commit('currentPage', n);
                },
                param1(context, val) {
                    context.commit('param1', val);
                },
                param2(context, val) {
                    context.commit('param2', val);
                },
                param3(context, val) {
                    context.commit('param3', val);
                },
                sortParam(context, val) {
                    context.commit('sortParam', val);
                },
                visiblyArrea(context, n) {
                    context.commit('visiblyArrea', n);
                }
            }
       },
       tasks: {
        state: {
            currentPage: 1,
            param1: '',
            param2: '',
            sortParam: 'updated_at',
            visiblyArrea: 10
        },
        mutations: {
            currentPage(state, n) {
                state.currentPage = n;
            },
            param1(state, val) {
                state.param1 = val;
            },
            param2(state, val) {
                state.param2 = val;
            },
            sortParam(state, val) {
                state.sortParam = val;
            },
            visiblyArrea(state, n) {
                state.visiblyArrea = n;
            }
        },
        actions: {
            currentPage(context, n) {
                context.commit('currentPage', n);
            },
            param1(context, val) {
                context.commit('param1', val);
            },
            param2(context, val) {
                context.commit('param2', val);
            },
            sortParam(context, val) {
                context.commit('sortParam', val);
            },
            visiblyArrea(context, n) {
                context.commit('visiblyArrea', n);
            }
        }
   }
   }
});