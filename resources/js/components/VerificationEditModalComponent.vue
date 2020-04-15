<template>
    <div class="modal fade" id="verificationEditModal" tabindex="-1" role="dialog" aria-labelledby="verificationEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationEditModalLabel">Проверка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="verification">
                    <form>
                        <div class="field">
                            <div class="name">Автор:</div>
                            <div class="field-value">{{user.name}}</div>
                        </div>
                        <div class="field">
                            <div class="name">Дата и время проверки:</div>
                            <div class="field-value">{{getNewDate()}}</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="name">ФИО проверяющего:</div>
                            <input class="form-control" type="text" id="message-text" @input="changeInitials">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Примечание:</label>
                            <textarea class="form-control" id="message-text" @input="changeNotation"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" @click="saveVerification()">Сохранить</button>
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

export default {
    store,
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
            verification: {
                initials: null,
                notation: null,
                status: 1,
                person_id: null,
                user_id: null,
                created_at: null
            },
            date: new Date()
        }
    },
    computed: {
        user() {
            return store.state.user;
        }
    },
    methods: {
        getNewDate: function() {
            return `${this.date.getDate()} ${monthNames[this.date.getMonth()]} ${this.date.getFullYear()} ${this.date.getHours()}:${this.date.getMinutes()}:${this.date.getSeconds()}`;
        },
        getUSADate: function() {
            return `${this.date.getFullYear()}-${("0" + Number(this.date.getMonth() + 1)).slice(-2)}-${("0" + this.date.getDate()).slice(-2)} ${("0" + this.date.getHours()).slice(-2)}:${("0" + this.date.getMinutes()).slice(-2)}:${("0" + this.date.getSeconds()).slice(-2)}`;
        },
        saveVerification: function() {
            this.verification.created_at = this.getUSADate();
            axios
                .post(`${store.state.url}/verification`, this.verification)
                .then(response => {

                    axios
                        .get(`${store.state.url}/person/${this.person.id}`)
                        .then(response => {
                            this.$emit('change', response.data);
                            $('#verificationEditModal').modal('hide');
                        })
                        .catch((error) => { console.log(error) });

                })
                .catch((error) => { console.log(error) });
        },
        changeInitials: function(event) {
            this.verification.initials = event.target.value;
        },
        changeNotation: function(event) {
            this.verification.notation = event.target.value;
        }
    },
    mounted() {
        this.verification.user_id = this.user.id;
        this.verification.person_id = this.person.id;
    }
}
</script>