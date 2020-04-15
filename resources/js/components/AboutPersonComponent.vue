<template>
    <div class="container" id="about-person-component" v-if="person">
        <call-edit-modal-component v-model="person"></call-edit-modal-component>
        <verification-edit-modal-component v-model="person"></verification-edit-modal-component>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 1}]" href="" @click.prevent="currentPage = 1">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 2}]" href="" @click.prevent="currentPage = 2">Звонки</a>
                    </li>
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 3}]" href="" @click.prevent="currentPage = 3">Проверки</a>
                    </li>
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 4}]" href="" @click.prevent="currentPage = 4">История</a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-body about-person-body" v-if="person">
                        <div class="about-person-container container" v-if="currentPage === 1">
                            <div class="title">Информация о клиенте</div>
                            <div class="row field">
                                <div class="col-sm-4">Телефон:</div>
                                <div class="col-sm-8">{{ person.phone ? person.phone : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Статус:</div>
                                <div class="col-sm-4">{{ person.status }}</div>
                                <div class="col-sm-2">Тип записи:</div>
                                <div class="col-sm-2">{{ person.record_type }}</div>
                            </div>
                            <div class="row field" v-if="person.record_type === 'водитель'">
                                <div class="col-sm-4">Тип ТС:</div>
                                <div class="col-sm-8">{{ person.car ? person.car : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Регион:</div>
                                <div class="col-sm-8">{{ person.region ? person.region : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">ФИО:</div>
                                <div class="col-sm-8">{{ person.initials ? person.initials : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Дата рождения:</div>
                                <div class="col-sm-8">{{ person.birth_date ? getDate(person.birth_date) : '' }}</div>
                            </div>
                            <div class="row field" v-if="person.record_type === 'водитель'">
                                <div class="col-sm-4">Паспортные данные:</div>
                                <div class="col-sm-8">{{ person.pasport_data ? person.pasport_data : '' }}</div>
                            </div>
                            <div class="row field" v-if="person.record_type === 'водитель'">
                                <div class="col-sm-4">Кем и когда выдан:</div>
                                <div class="col-sm-8">{{ person.receipt_info ? person.receipt_info : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Электронная почта:</div>
                                <div class="col-sm-8">{{ person.email ? person.email : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Карта:</div>
                                <div class="col-sm-8">{{ person.card ? person.card : '' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">НДС:</div>
                                <div class="col-sm-8">{{ person.NDS ? 'Да' : 'Нет' }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Примечание:</div>
                                <div class="col-sm-8">{{ person.notation ? person.notation : '' }}</div>
                            </div>
                            <div class="row field" v-if="outgoingCallDate">
                                <div class="col-sm-4">Дата исходящего звонка:</div>
                                <div class="col-sm-8">{{ getDate(outgoingCallDate) }}</div>
                            </div>
                            <div class="mb-2" v-if="person.files.length > 0">
                                <div class="row" v-for="(file, index) in person.files" :key="index">
                                    <a :href="file.path" download>{{file.name}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="history-list container" v-else-if="currentPage === 2">
                            <div class="row justify-content-between">
                                <div class="title">Список звонков</div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#callEditModal">+</button>
                            </div>
                            <div class="row" v-if="person.calls.length > 0">
                                <table class="table table-hover black-table">
                                    <thead>
                                        <tr>
                                            <th>Дата звонка</th>
                                            <th>Автор</th>
                                            <th>Тип звонка</th>
                                            <th>Примечание</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="field" v-for="(call, index) in person.calls" :key="index" @click="isLocked(call)">
                                            <td width="165">{{ call.created_at }}</td>
                                            <td>{{ call.user.name }}</td>
                                            <td>{{ !call.type ? 'входящий' : 'исходящий' }}</td>
                                            <td width="275"><p :class="['notation-crop', {'notation-full' : call.isLocked}]">{{ call.notation }}</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="history-not-found row">Звонков не найдено</div>
                        </div>
                        <div class="history-list container" v-else-if="currentPage === 3">
                            <div class="row justify-content-between">
                                <div class="title">Список проверок</div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#verificationEditModal">+</button>
                            </div>
                            <div class="row" v-if="person.verifications.length > 0">
                                <table class="table table-hover black-table">
                                    <thead>
                                        <tr>
                                            <th>Дата проверки</th>
                                            <th>Автор</th>
                                            <th>ФИО проверяющего</th>
                                            <th>Примечание</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="field" v-for="(verification, index) in person.verifications" :key="index" @click="isLocked(call)">
                                            <td width="165">{{ verification.created_at }}</td>
                                            <td>{{ verification.user.name }}</td>
                                            <td>{{ !verification.type ? 'входящий' : 'исходящий' }}</td>
                                            <td width="275"><p :class="['notation-crop', {'notation-full' : verification.isLocked}]">{{ verification.notation }}</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="history-not-found row">Проверок не найдено</div>
                        </div>
                        <div class="history-list" v-else-if="currentPage === 4">
                            <div class="title">Список историй</div>
                            <div v-if="person.histories.length > 0">
                                <table class="table table-hover black-table">
                                    <thead>
                                        <tr>
                                            <th>Дата добавления</th>
                                            <th>Автор</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="field" v-for="(history, index) in person.histories" :key="index" @click="goToHistoryComponent(history.id)">
                                            <td>{{ history.created_at }}</td>
                                            <td>{{ history.user.name }}</td>
                                            <td width="300"><p :class="['history-info']">{{ history.info }}</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="history-not-found">Историй не найдено</div>
                        </div>
                    </div>
                </div>
                <div class="edit-buttons-container justify-content-around">
                    <a @click.prevent="$router.go(-1)" href="" class="btn btn-secondary">Назад</a>
                    <router-link :to="`/person-edit?id=${id}`" class="btn btn-primary">Редактировать</router-link>
                    <button @click="deletePerson" type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import CallEditModalComponent from './CallEditModalComponent';
    import VerificationEditModalComponent from './VerificationEditModalComponent';
    import statuses from '../statuses';
    import $ from 'jquery';
    import { setTimeout } from 'timers';
    import Vue from 'vue'
    import monthNames from '../monthNames';
    import store from '../store/index';
    import carTypes from '../car_types';

    var component = Vue.extend({
        store,
        components: {
            'call-edit-modal-component' : CallEditModalComponent,
            'verification-edit-modal-component' : VerificationEditModalComponent
        },
        data: function() {
            return {
                person: null,
                id: null,
                currentPage: 1,
                statuses: statuses,
                monthNames: monthNames,
                carTypes,
                outgoingCallDate: null
            }
        },
        computed: {
            user() {
                return store.state.user;
            },
            url() {
                return store.state.url;
            }
        },
        methods: {
            getDate(date_str) {
                console.log(date_str);
                
                let date = new Date(date_str);
                return `${date.getDate()} ${this.monthNames[date.getMonth()]} ${date.getFullYear()}`;
            },
            deletePerson: function() {
                axios
                    .delete(`${store.state.url}/person/${this.id}`)
                    .then(response => {
                        this.$router.go(-1);
                    });
            },
            goToHistoryComponent: function(id) {
                this.$router.push({path: `/history?id=${id}`});
            },
            isLocked(call) {
                call.isLocked = !call.isLocked;
            }
        },
        mounted() {

            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            axios
                .get(`${store.state.url}/person/${this.id}`)
                .then(response => {
                    this.person = response.data;
                    
                    if(this.person.record_type === 'водитель' && this.person.car) {
                        let cars = this.person.car.split(',');

                        this.person.car = cars.map(e => { 
                            for(let type of this.carTypes) {
                                if(type.code === Number(e)) return type.name;
                            }
                        }).join(', ');
                    } 

                    if(this.person.tasks.length > 0) {
                        for(let task of this.person.tasks) {
                            if(task.type === 'Исходящий звонок') {
                                this.outgoingCallDate = task.started_at;
                            }
                        }
                    }
                });
        }
    });
    export default component;
</script>
