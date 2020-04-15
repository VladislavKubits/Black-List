<template>
    <div class="container" id="people-component" v-if="people">
        <div class="people-search-table">
            <div class="row justify-content-around search-group mb-sm-4">
                <input class="form-control black-input col-5" type="text" placeholder="Поиск" ref="searchInput" @input="search">
                <select :class="['edit-field', 'col-2', 'form-control', 'black-select']" required ref="searchStatusSelect" @change="search" title="Выбор клиентов по статусу">
                    <option value="" selected>Все</option>
                    <option value="новый">Новые</option>
                    <option value="надёжный">Надёжные</option>
                    <option value="ненадёжный">Ненадёжные</option>
                </select>
                <select :class="['edit-field', 'form-control', 'col-2', 'black-select']" required ref="sort" @change="search"  title="Сортировка клиентов">
                    <option value="updated_at" selected>Дата обновления</option>
                    <option value="initials">ФИО</option>
                    <option value="phone">Телефон</option>
                </select>
            </div>
            <table v-if="people.data.length > 0" class="table table-hover black-table">
                <thead>
                    <tr>
                        <th scope="col">Телефон</th>
                        <th scope="col">Статус</th>
                        <!-- <th scope="col">Тип записи</th> -->
                        <th scope="col">ФИО</th>
                        <!-- <th scope="col">Дата рождения</th> -->
                        <th scope="col">Дата исходящего звонка</th>
                        <!-- <th scope="col">Паспорт</th>
                        <th scope="col">Когда/кем выдан</th> -->
                        <th scope="col">Дата обновления</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="person" v-for="(person, index) in people.data" :key="index" @click="getPersonInfoUrl(person.id)">
                        <td>{{person.phone ? person.phone : ''}}</td>
                        <td class="status">
                            <div class="image" :data-title="person.status" :style="{'background': statuses[person.status]}"></div>
                        </td>
                        <!-- <td>
                            <span>{{person.record_type ? person.record_type : ''}}</span>
                            <ion-icon v-if="person.record_type === 'водитель'" :name="'logo-model-s'"></ion-icon>
                        </td> -->
                        <td>{{person.initials ? person.initials : ''}}</td>
                        <!-- <td>{{person.birth_date ? getDate(person.birth_date) : ''}}</td> -->
                        <td>{{getOutgoingCallDate(person)}}</td>
                        <!-- <td>{{person.pasport_data ? person.pasport_data : ''}}</td>
                        <td>{{person.receipt_info ? person.receipt_info : ''}}</td> -->
                        <td>{{person.updated_at}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center" v-if="people.last_page > 1">
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
                        v-for="n in people.last_page" :key="n"
                        v-show="n >= visiblyArrea - 9 && n <= visiblyArrea + 9"
                        @click="goPage(n)">{{n}}
                    </button>
                    <button class="btn" type="button" v-if="currentPage < people.last_page - 19" @click="goNextPages">...</button>
                    <button class="btn" type="button" v-if="currentPage < people.last_page - 19" @click="goPage(people.last_page)">{{people.last_page}}</button>
                </div>
                <button type="button" class="btn right"
                    :disabled="currentPage >= people.last_page"
                    @click="goNextPage">&#187;
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { setTimeout } from 'timers';
    import monthNames from '../monthNames';
    import statuses from '../statuses';
    import store from '../store/index';

    export default {
        store,
        data: function() {
            return {
                people: null,
                monthNames: monthNames,
                statuses: statuses,
                paginate: true
            }
        },
        computed: {
            currentPage() {
                return store.state.drivers.currentPage;
            },
            visiblyArrea() {
                return store.state.drivers.visiblyArrea;
            }
        },
        methods: {
            getOutgoingCallDate(person) {
                if(person.outgoing_call.length > 0) {
                    return person.outgoing_call[0].started_at;
                } else return '';
            },
            getPersonInfoUrl: function(id) {
                this.$router.push({path: `/about-person?id=${id}`});
            },
            goNextPages () {
                if(store.state.drivers.visiblyArrea + 19 > this.people.last_page - 9) {
                    store.state.drivers.visiblyArrea = this.people.last_page - 9;
                } else {
                    store.state.drivers.visiblyArrea += 19;
                }
                store.state.drivers.currentPage = store.state.drivers.visiblyArrea - 9;

                axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&page=${store.state.drivers.currentPage}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                });
            },
            goPrevPages () {
                if(store.state.drivers.visiblyArrea - 19 < 10) {
                    store.state.drivers.visiblyArrea = 10;
                } else {
                    store.state.drivers.visiblyArrea -= 19;
                }
                store.state.drivers.currentPage = store.state.drivers.visiblyArrea - 9;

                axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&page=${store.state.drivers.currentPage}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                });
            },
            goNextPage() {
                axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&page=${store.state.drivers.currentPage + 1}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                    store.state.drivers.currentPage = this.people.current_page;

                    if (store.state.drivers.currentPage > store.state.drivers.visiblyArrea + 9) ++store.state.drivers.visiblyArrea;
                });
            },
            goPrevPage() {
                axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&page=${store.state.drivers.currentPage - 1}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                    store.state.drivers.currentPage = this.people.current_page;

                    if (store.state.drivers.currentPage < store.state.drivers.visiblyArrea - 9) --store.state.drivers.visiblyArrea;
                });
            },
            goPage(n) {
                axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&page=${n}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                    store.state.drivers.currentPage = n;
                    if (n < store.state.drivers.visiblyArrea - 9) {
                        store.state.drivers.visiblyArrea = n + 9;
                    } else if (n > store.state.drivers.visiblyArrea + 9) {
                        store.state.drivers.visiblyArrea = n - 9;
                    }
                });
            },
            getDate($date_str) {
                let $date = new Date($date_str);
                return `${$date.getDate()} ${this.monthNames[$date.getMonth()]} ${$date.getFullYear()}`;
            },
            search(event) {
                setTimeout(() => {
                    store.state.drivers.param1 = this.$refs.searchInput.value;
                    store.state.drivers.param2 = this.$refs.searchStatusSelect.value;
                    //store.state.drivers.param3 = this.$refs.searchRecordTypeSelect.value;
                    store.state.drivers.sortParam = this.$refs.sort.value;
                    axios
                    .get(`${store.state.url}/person?paginate=${this.paginate}&page=1&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                    .then(response => {
                        this.people = response.data;
                        store.state.drivers.currentPage = 1;
                    });
                }, 100);
            }
        },
        mounted() {
            axios
                .get(`${store.state.url}/person?paginate=${this.paginate}&param1=${encodeURIComponent(store.state.drivers.param1)}&param2=${store.state.drivers.param2}&param3=${store.state.drivers.param3}&sort_param=${store.state.drivers.sortParam}`)
                .then(response => {
                    this.people = response.data;
                });
        }
    }
</script>
