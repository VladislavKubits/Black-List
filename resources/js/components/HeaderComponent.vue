<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-violet fixed-top">
      <a class="navbar-brand" href="/">zernovoz.su</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li :class="['nav-item', {'active': location === '/clients'}]">
            <router-link class="nav-link" to="/clients">Клиенты <span class="sr-only">(current)</span></router-link>
          </li>
          <li :class="['nav-item', {'active': location === '/drivers'}]">
            <router-link class="nav-link" to="/drivers">Водители <span class="sr-only">(current)</span></router-link>
          </li>
          <li :class="['nav-item', {'active': location === '/users'}]" v-if="user && user.role === 'Admin'">
            <router-link class="nav-link" to="/users">Пользователи <span class="sr-only">(current)</span></router-link>
          </li>
          <li :class="['nav-item', {'active': location === '/tasks'}]">
            <router-link class="nav-link" to="/tasks">Задачи <span class="sr-only">(current)</span></router-link>
          </li>
          <li :class="['nav-item', {'active': location === '/statistics'}]">
            <router-link class="nav-link" to="/statistics">Статистика <span class="sr-only">(current)</span></router-link>
          </li>
          <!--<li :class="['nav-item', {'active': location === '/register'}]" v-if="!user">
            <a class="nav-link" href="/register">Регистрация <span class="sr-only">(current)</span></a>
          </li>-->
          <li :class="['nav-item', {'active': location === '/login'}]" v-if="!user">
            <a class="nav-link" href="/login">Войти <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item" v-else>
            <a class="nav-link" href="" @click.prevent="logout">Выйти</a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li> -->
        </ul>
        <div class="form-inline my-2 my-lg-0" v-if="user">
            <button type="button" class="btn btn-outline-success my-2 my-sm-0 mr-sm-2" @click="getAddNewPersonUrl()" v-if="location === '/clients' || location === '/drivers' ||
            location === '/person-edit' || location === '/person-add' || location === '/about-person'">Добавить контакт</button>
            <button type="button" class="btn btn-outline-success my-2 my-sm-0 mr-sm-2" @click="getAddNewTaskUrl()" v-if="location === '/tasks'">Добавить задачу</button>
            <!-- <input class="form-control mr-sm-2" type="text" placeholder="Поиск" ref="searchInput">
            <button class="btn btn-outline-success my-2 my-sm-0" @click="search">Поиск</button> -->
        </div>
      </div>
    </nav>
</template>

<script>
    import environment  from '../environment';
    import axios from 'axios';
    import language from '../language';
    import store from '../store/index';

    export default {
        store,
        data: function() {
            return {
                roles: language,
                param1: '',
                param2: 'all',
                sortParam: 'updated_at'
            }
        },
        computed: {
          location () {
            return store.state.location;
          },
          user () {
            return store.state.user;
          }
        },
        mounted() {
            //
        },
        methods: {
            getAddNewPersonUrl() {
                this.$router.push({path: `/person-add`});
            },
            getAddNewTaskUrl() {
              this.$router.push({path: `/task-add`});
            },
            logout: function() {
                axios
                    .get(`${ store.state.url }/logout`)
                    .then(response => {
                        window.location.href = `${ store.state.url }/login`;
                    });
            },
            search() {
                setTimeout(() => {
                    this.param1 = this.$refs.searchInput.value;
                    axios
                        .get(`${environment.serverUrl}:${environment.serverPort}/person?page=1&param1=${this.param1}&param2=${this.param2}&sort_param=${this.sortParam}`)
                        .then(response => {
                            if(window.location.pathname != '/home') {
                                window.location.pathname = '/home';
                                this.people = response.data;
                            }
                        });
                }, 100);
            }
        }
    }
</script>
