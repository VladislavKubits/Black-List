<template>
    <div class="container" id="tasks-component" v-if="tasks">
        <div class="people-search-table">
            <div class="row justify-content-around search-group mb-sm-4">
                <input class="form-control col-5 black-input" type="text" placeholder="Поиск" ref="searchInput" @input="search">
                <select :class="['edit-field', 'black-select', 'col-2', 'form-control']" required ref="searchStatusSelect" @change="search" title="Выбор клиентов по статусу">
                    <option value="" selected>Все</option>
                    <option value="inactive">Неактивные</option>
                    <option value="active">Активные</option>
                    <option value="draft">Черновики</option>
                    <option value="completed">Выполненные</option>
                </select>
                <select :class="['edit-field', 'black-select', 'form-control', 'col-2']" required ref="sort" @change="search"  title="Сортировка клиентов">
                    <option value="updated_at" selected>Дата обновления</option>
                    <option value="started_at">Дата и время начала</option>
                    <option value="finished_at">Дата и время завершения</option>
                </select>
            </div>
            <table v-if="tasks.data.length > 0" class="table table-hover black-table">
                <thead>
                    <tr>
                        <th scope="col">Заголовок</th>
                        <th scope="col">Организация</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Ответственный</th>
                        <th scope="col">Дата начала</th>
                        <th scope="col">Дата завершения</th>
                        <th scope="col">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="person" v-for="(task, index) in tasks.data" :key="index" @click="getTaskInfoUrl(task.id)">
                        <td>{{task.title ? task.title : ''}}</td>
                        <td>{{task.company ? task.company : ''}}</td>
                        <td>{{task.author.name ? task.author.name : ''}}</td>
                        <td>{{task.responsible.name ? task.responsible.name : ''}}</td>
                        <td>{{task.started_at ? getDate(task.started_at) : ''}}</td>
                        <td>{{task.finished_at ? getDate(task.finished_at) : ''}}</td>
                        <td>{{task.status ? language[task.status] : ''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center" v-if="tasks.last_page > 1">
            <div class="col-md-8 pagination-container">
                <button
                    type="button" class="btn left"
                    :disabled="currentPage === 1"
                    @click="goPrevPage">&#171;
                </button>
                <div class="pagination-btns">
                    <button class="btn" type="button" v-if="visiblyArrea > 10" @click="goPage(1)">1</button>
                    <button class="btn" type="button" v-if="visiblyArrea > 10" @click="goPrevPages">...</button>
                    <button 
                        :class="['btn', {'current': n === currentPage}]" 
                        type="button" 
                        v-for="n in tasks.last_page" :key="n"
                        v-show="n >= visiblyArrea - 9 && n <= visiblyArrea + 9"
                        @click="goPage(n)">{{n}}
                    </button>
                    <button class="btn" type="button" v-if="currentPage < tasks.last_page - 19" @click="goNextPages">...</button>
                    <button class="btn" type="button" v-if="currentPage < tasks.last_page - 19" @click="goPage(tasks.last_page)">{{tasks.last_page}}</button>
                </div>
                <button type="button" class="btn right"
                    :disabled="currentPage >= tasks.last_page"
                    @click="goNextPage">&#187;
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import monthNames from '../monthNames';
    import store from '../store/index';
    import language from '../language';

    export default {
        store,
        data: function() {
            return {
                tasks: null,
                monthNames,
                language
            }
        },
        computed: {
            currentPage() {
                return store.state.tasks.currentPage;
            },
            visiblyArrea() {
                return store.state.tasks.visiblyArrea;
            }
        },
        methods: {
            search(event) {
                setTimeout(() => {
                    store.state.tasks.param1 = this.$refs.searchInput.value;
                    store.state.tasks.param2 = this.$refs.searchStatusSelect.value;
                    store.state.tasks.sortParam = this.$refs.sort.value;
                    axios
                    .get(`${store.state.url}/task?page=1&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                    .then(response => {
                        this.tasks = response.data;
                        store.state.tasks.currentPage = 1;

                        for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                    });
                }, 100);
            },
            getTaskInfoUrl: function(id) {
                this.$router.push({path: `/about-task?id=${id}`});
            },
            goNextPages () {
                if(store.state.tasks.visiblyArrea + 19 > this.tasks.last_page - 9) {
                    store.state.tasks.visiblyArrea = this.tasks.last_page - 9;
                } else {
                    store.state.tasks.visiblyArrea += 19;
                }
                store.state.tasks.currentPage = store.state.tasks.visiblyArrea - 9;

                axios
                .get(`${store.state.url}/task?page=${store.state.tasks.currentPage}&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;

                    for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                });
            },
            goPrevPages () {
                if(store.state.tasks.visiblyArrea - 19 < 10) {
                    store.state.tasks.visiblyArrea = 10;
                } else {
                    store.state.tasks.visiblyArrea -= 19;
                }
                store.state.tasks.currentPage = store.state.tasks.visiblyArrea - 9;

                axios
                .get(`${store.state.url}/task?page=${store.state.tasks.currentPage}&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;

                    for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                });
            },
            goNextPage() {
                axios
                .get(`${store.state.url}/task?page=${store.state.tasks.currentPage + 1}&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;
                    store.state.tasks.currentPage = this.tasks.current_page;

                    if (store.state.tasks.currentPage > store.state.tasks.visiblyArrea + 9) ++store.state.tasks.visiblyArrea;

                    for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                });
            },
            goPrevPage() {
                axios
                .get(`${store.state.url}/task?page=${store.state.tasks.currentPage - 1}&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;
                    store.state.tasks.currentPage = this.tasks.current_page;

                    if (store.state.tasks.currentPage < store.state.tasks.visiblyArrea - 9) --store.state.tasks.visiblyArrea;

                    for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                });
            },
            goPage(n) {
                axios
                .get(`${store.state.url}/task?page=${n}&param1=${encodeURIComponent(store.state.tasks.param1)}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;
                    store.state.tasks.currentPage = n;
                    if (n < store.state.tasks.visiblyArrea - 9) {
                        store.state.tasks.visiblyArrea = n + 9;
                    } else if (n > store.state.tasks.visiblyArrea + 9) {
                        store.state.tasks.visiblyArrea = n - 9;
                    }

                    for(let task of this.tasks) {
                        task.company = "";
                        for(let person of task.people) {
                            task.company += person.initials;
                        }
                    }
                });
            },
            getDate(date_str) {
                let date = new Date(date_str);
                return `${date.getDate()} ${this.monthNames[date.getMonth()]} ${date.getFullYear()}  ${date.getHours()}:${date.getMinutes()}`;
            }
        },
        mounted() {
            axios
                .get(`${store.state.url}/task?param1=${store.state.tasks.param1}&param2=${store.state.tasks.param2}&sort_param=${store.state.tasks.sortParam}`)
                .then(response => {
                    this.tasks = response.data;

                    for(let task of this.tasks.data) {
                        task.company = "";
                        
                        for(let person of task.people) {
                            task.company += ` ${person.initials},`;
                        }
                        task.company = task.company.substring(0, task.company.length - 1);
                    }
                });
        }
    }
</script>
