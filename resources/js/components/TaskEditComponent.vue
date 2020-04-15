<template>
    <div id="person-edit-component">
        <div class="card-header">{{title}}
            <div class="exaption" align="right" v-show="!validateStatus">Заполните указанные поля</div>
        </div>
        <div class="card-body">
            <div class="edit-container">
                <div class="row mb-2">
                    <div class="col-6" id="vue-ctk-date-time-picker-start"><span>Дата и время начала:<span class="exaption">&nbsp;*</span></span></div>
                    <div class="col-6" id="vue-ctk-date-time-picker-finish"><span>Дата и время завершения:</span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6" id="vue-ctk-date-time-picker-start">
                        <vue-ctk-date-time-picker :class="[{'general-field': !validateStatus}, {'valid-data': task.started_at}]" :format="'YYYY-MM-DD HH:mm'" v-model="task.started_at"></vue-ctk-date-time-picker>
                    </div>
                    <div class="col-6" id="vue-ctk-date-time-picker-finish">
                        <vue-ctk-date-time-picker :class="[{'valid-data': task.finished_at}]" :format="'YYYY-MM-DD HH:mm'" v-model="task.finished_at"></vue-ctk-date-time-picker>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Тема:<span class="exaption">&nbsp;*</span></span></div>
                    <input :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.title}]" type="text" v-model="task.title">
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Описание:<span class="exaption">&nbsp;*</span></span></div>
                    <textarea :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.description}]" cols="30" rows="3" v-model="task.description"></textarea>
                </div>
                <div class="row mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Тип:<span class="exaption">&nbsp;*</span></span></div>
                        <select :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.type}]" required v-model="task.type">
                            <option value="Входящий звонок">Входящий звонок</option>
                            <option value="Исходящий звонок">Исходящий звонок</option>
                            <option value="Проверка">Проверка</option>
                            <option value="Другое">Другое</option>
                        </select>
                    </div>
                </div>
                <div class="input-group-prepend"><span>С кем:<span class="exaption">&nbsp;*</span></span></div>
                <div class="row mb-2" v-if="options">
                    <multiselect
                        :class="[{'general-field': !validateStatus}, {'valid-data': task.people.length > 0}]"
                        v-model="task.people"
                        tag-placeholder="Add this as new tag"
                        placeholder="Search or add a tag"
                        label="initials"
                        track-by="id"
                        :options="options"
                        :multiple="true"
                        :taggable="true"
                        :options-limit="10"
                        :allow-empty="false"
                        :hide-selected="true">
                    </multiselect>
                </div>
                <div class="row mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Ответственный:<span class="exaption">&nbsp;*</span></span></div>
                        <select :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.responsible_id}]" required v-model="task.responsible_id">
                            <option v-for="user in userList" :key="user.id" :value="user.id">{{user.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="input-group-prepend"><span>Статус:<span class="exaption">&nbsp;*</span></span></div>
                <div class="row mb-2">
                    <div class="col-3 radio">
                        <input type="radio" name="status" value="inactive" class="task-edit-radio" v-model="task.status">
                        <div class="name">неактивное</div>
                    </div>
                    <div class="col-3 radio">
                        <input type="radio" name="status" value="active" class="task-edit-radio" v-model="task.status">
                        <div class="name">активное</div>
                    </div>
                    <div class="col-3 radio">
                        <input type="radio" name="status" value="draft" class="task-edit-radio" v-model="task.status">
                        <div class="name">черновик</div>
                    </div>
                    <div class="col-3 radio">
                        <input type="radio" name="status" value="completed" class="task-edit-radio" v-model="task.status">
                        <div class="name">выполненное</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 checkbox"><input type="checkbox" class="task-edit-violet-checkbox" v-model="task.notification_status">Напомнить за</div>
                    <div class="col-2">
                        <input class="form-control" type="number" v-model="notificationTimeValue" @input="task.notification_value = notificationTimeValue * measurement">
                    </div>
                    <div class="col-3">
                        <select class="form-control" required v-model="measurement" @change="task.notification_value = notificationTimeValue * measurement">
                            <option :value="1000">секунд</option>
                            <option :value="60000">минут</option>
                            <option :value="3600000">часов</option>
                        </select>
                    </div>
                    <div class="col-3">до начала</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
    import $ from 'jquery';
    import Multiselect from 'vue-multiselect';
    import store from '../store/index';

    export default {
        store,
        props: ['title', 'task', 'validateStatus'],
        components: {
            'vue-ctk-date-time-picker': VueCtkDateTimePicker,
            Multiselect
        },
        data: function() {
            return {
                person_selecting: false,
                options: null,
                paginate: 0,
                notificationTimeValue: 0,
                measurement: 0
            }
        },
        computed: {
            user() {
                return store.state.user;
            },
            userList() {
                return store.state.userList;
            }
        },
        mounted() {

            $('#DateTimePicker input').attr("placeholder", "Выберите дату и время");

            axios
            .get(`${store.state.url}/person?paginate=${this.paginate}`)
            .then(response => {
                this.options = response.data;
            });

            if (this.task.notification_value > 3600000) {
                this.measurement = 3600000;
            } else if (this.task.notification_value > 60000) {
                this.measurement = 60000;
            } else {
                this.measurement = 1000;
            }

            this.notificationTimeValue = this.task.notification_value / this.measurement;
        }
    }
</script>
