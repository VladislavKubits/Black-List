<template>
    <div class="container" id="about-task-component" v-if="task">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 1}]" href="" @click.prevent="currentPage = 1">Обзор</a>
                    </li>
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 2}]" href="" @click.prevent="currentPage = 2">История</a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-body about-person-body" v-if="task">
                        <div class="about-person-container container" v-if="currentPage === 1">
                            <div class="title">Информация о задаче</div>
                            <div class="row field">
                                <div class="col-sm-4">Заголовок:</div>
                                <div class="col-sm-8">{{ task.title }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Автор:</div>
                                <div class="col-sm-8">{{ task.author.name }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Ответственный:</div>
                                <div class="col-sm-8">{{ task.responsible.name }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Дата начала:</div>
                                <div class="col-sm-8">{{ getDate(task.started_at) }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Дата завершения:</div>
                                <div class="col-sm-8">{{ getDate(task.finished_at) }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Статус:</div>
                                <div class="col-sm-8">{{ language[task.status] }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Описание:</div>
                                <div class="col-sm-8">{{ task.description }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Тип:</div>
                                <div class="col-sm-8">{{ task.type }}</div>
                            </div>
                            <div class="row field">
                                <div class="col-sm-4">Клиенты:</div>
                                <div class="col-sm-8"><div v-for="(person, index) in task.people" :key="index">{{ `${person.initials}: ${person.phone}` }}</div></div>
                            </div>
                        </div>
                        <div class="history-list" v-else-if="currentPage === 2">
                            <div class="title">Список историй</div>
                            <div v-if="task.histories.length > 0">
                                <table class="table table-hover black-table">
                                    <thead>
                                        <tr>
                                            <th>Дата добавления</th>
                                            <th>Автор</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="field" v-for="(history, index) in task.histories" :key="index" @click="goToHistoryComponent(history.id)">
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
                    <a href="" @click.prevent="$router.go(-1)" class="btn btn-secondary">Назад</a>
                    <router-link :to="getTaskEditPageUrl()" class="btn btn-primary">Редактировать</router-link>
                    <button v-show="user.role === 'Admin'" @click="deleteTask" type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Vue from 'vue'
    import monthNames from '../monthNames';
    import store from '../store/index';
    import language from '../language';

    export default {
        store,
        data: function() {
            return {
                task: null,
                id: null,
                currentPage: 1,
                monthNames,
                language
            }
        },
        computed: {
            user() {
                return store.state.user;
            }
        },
        methods: {
            getTaskEditPageUrl: function() {
                return `/task-edit?id=${this.id}`;
            },
            getDate(date_str) {
                let date = new Date(date_str);
                return `${date.getDate()} ${this.monthNames[date.getMonth()]} ${date.getFullYear()}  ${date.getHours()}:${date.getMinutes()}`;
            },
            deleteTask: function() {
                axios
                    .delete(`${store.state.url}/task/${this.id}`)
                    .then(response => {
                        this.$router.go(-1);
                    });
            },
            goToHistoryComponent: function(id) {
                this.$router.push({path: `/history?id=${id}`});
            }
        },
        mounted() {
            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            axios
                .get(`${store.state.url}/task/${this.id}`)
                .then(response => {
                    this.task = response.data;
                });
        }
    }
</script>
