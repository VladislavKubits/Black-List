<template>
    <div class="modal fade" id="callEditModal" tabindex="-1" role="dialog" aria-labelledby="callEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <ul class="nav nav-tabs" id="nav-tabs-call">
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 1}]" href="" @click.prevent="currentPage = 1">Совершить звонок</a>
                    </li>
                    <li class="nav-item">
                        <a :class="['nav-link', {'active': currentPage === 2}]" href="" @click.prevent="currentPage = 2">Назначить звонок</a>
                    </li>
                </ul>
                <div class="modal-header modal-call-header">
                    <h5 class="modal-title" id="callEditModalLabel">Звонок</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="call">
                    <form>
                        <div v-show="currentPage === 1">
                            <div class="field">
                                <div class="name">Автор:</div>
                                <div class="field-value">{{user.name}}</div>
                            </div>
                            <div class="field">
                                <div class="name">Дата и время звонка:</div>
                                <div class="field-value">{{getNewDate()}}</div>
                            </div>
                            <div class="field">
                                <div class="name">Входящий звонок</div>
                                <input type="radio" name="call" value="0" v-model="call.type">
                            </div>
                            <div class="field">
                                <div class="name">Исходящий звонок</div>
                                <input type="radio" name="call" value="1" v-model="call.type">
                            </div>
                            <div class="form-group">
                                <!-- Примечание: -->
                                <label for="message-text" class="col-form-label">Примечание:</label>
                                <textarea class="form-control" id="message-text" @input="changeNotation"></textarea>
                            </div>
                        </div>
                        <div v-show="currentPage === 2" style="padding: 0 1rem;">
                            <div class="row mb-2">
                                <div id="vue-ctk-date-time-picker-start"><span>Дата и время начала:<span class="exaption">&nbsp;*</span></span></div>
                                <vue-ctk-date-time-picker :class="[{'general-field': !validateStatus}, {'valid-data': task.started_at}]" :format="'YYYY-MM-DD HH:mm'" v-model="task.started_at"></vue-ctk-date-time-picker>
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Тема:<span class="exaption">&nbsp;*</span></span></div>
                                <input :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.title}]" type="text" v-model="task.title">
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Описание:<span class="exaption">&nbsp;*</span></span></div>
                                <textarea :class="['form-control', {'general-field': !validateStatus}, {'valid-data': task.description}]" cols="30" rows="3" v-model="task.description"></textarea>
                            </div>
                            <div class="input-group-prepend"><span>Тип:<span class="exaption">&nbsp;*</span></span></div>
                            <div class="row mb-2">
                                <div class="col-6 radio">
                                    <input type="radio" name="type" value="Исходящий звонок" v-model="task.type">
                                    <div class="name">Исходящий звонок</div>
                                </div>
                                <div class="col-6 radio">
                                    <input type="radio" name="type" value="Входящий звонок" v-model="task.type">
                                    <div class="name">Входящий звонок</div>
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
                                    <input type="radio" name="status" value="inactive" v-model="task.status">
                                    <div class="name">неактивный</div>
                                </div>
                                <div class="col-3 radio">
                                    <input type="radio" name="status" value="active" v-model="task.status">
                                    <div class="name">активный</div>
                                </div>
                                <div class="col-3 radio">
                                    <input type="radio" name="status" value="draft" v-model="task.status">
                                    <div class="name">черновик</div>
                                </div>
                                <div class="col-3 radio">
                                    <input type="radio" name="status" value="completed" v-model="task.status">
                                    <div class="name">выполненный</div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-4 checkbox"><input type="checkbox" v-model="task.notification_status">Напомнить за</div>
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
                            </div> -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" @click="saveCall()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import monthNames from '../monthNames';
import environment  from '../environment';
import axios from 'axios';
import $ from 'jquery';
import store from '../store/index';
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import Multiselect from 'vue-multiselect';

export default {
    store,
    components: {
        'vue-ctk-date-time-picker': VueCtkDateTimePicker,
        Multiselect
    },
    props: {
        person: {
            type: Object,
            default: null
        }
    },
    model: {
        prop: 'person',
        event: 'change'
    },
    data: function() {
        return {
            call: {
                type: 0,
                notation: null,
                status: 1,
                person_id: null,
                user_id: null,
                created_at: null
            },
            task: {
                title: null,
                description: null,
                author_id: null,
                responsible_id: null,
                started_at: null,
                finished_at: null,
                status: 'inactive',
                notification_status: false,
                notification_value: 0,
                people: [],
                type: 'Исходящий звонок'
            },
            date: new Date(),
            currentPage: 1,
            validateStatus: true,
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
    methods: {
        getNewDate: function() {
            return `${this.date.getDate()} ${monthNames[this.date.getMonth()]} ${this.date.getFullYear()} ${this.date.getHours()}:${this.date.getMinutes()}:${this.date.getSeconds()}`;
        },
        getUSADate: function() {
            return `${this.date.getFullYear()}-${("0" + Number(this.date.getMonth() + 1)).slice(-2)}-${("0" + this.date.getDate()).slice(-2)} ${("0" + this.date.getHours()).slice(-2)}:${("0" + this.date.getMinutes()).slice(-2)}:${("0" + this.date.getSeconds()).slice(-2)}`;
        },
        saveCall: function() {
            if(this.currentPage === 1) {
                this.call.created_at = this.getUSADate();
                axios
                    .post(`${store.state.url}/call`, this.call)
                    .then(response => {

                        axios
                            .get(`${store.state.url}/person/${this.person.id}`)
                            .then(response => {
                                this.$emit('change', response.data);
                                $('#callEditModal').modal('hide');
                            })
                            .catch((error) => { console.log(error) });
                            
                    })
                    .catch((error) => { console.log(error) });
            } else {
                this.validateStatus = this.task.title && this.task.description && this.task.author_id && this.task.responsible_id && this.task.started_at && this.task.people.length > 0;
                console.log(this.task);
                
                if (this.validateStatus) {
                    this.task.finished_at = this.task.started_at;

                    let command = 'add task';

                    for (const [key, value] of Object.entries(this.task)) {
                        command += value != null && value != '' && value.length > 0 ? `,add ${key}` : '';
                    }

                    let data = {
                        command,
                        task: this.task
                    };

                    axios
                        .post(`${store.state.url}/task`, data)
                        .then(response => {
                            $('#callEditModal').modal('hide');
                        })
                        .catch((error) => { console.log(error) });
                }
            }
        },
        changeType: function(event) {
            this.call.type = Number(event.target.value);
        },
        changeNotation: function(event) {
            this.call.notation = event.target.value;
        }
    },
    mounted() {
        $('#DateTimePicker input').attr("placeholder", "Выберите дату и время");
        
        this.call.user_id = this.user.id;
        this.call.person_id = this.person.id;

        this.task.author_id = this.user.id;
        this.task.responsible_id = this.user.id;
        this.task.people.push({id: this.person.id, initials: this.person.initials});

        axios
            .get(`${store.state.url}/person?paginate=${this.paginate}`)
            .then(response => {
                this.options = response.data;
            });
    }
}
</script>