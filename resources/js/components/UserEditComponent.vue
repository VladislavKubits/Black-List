<template>
    <div class="container" id="user-edit-component" v-if="user">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Редактирование пользователя
                        <div class="exaption" align="right" v-show="!validateStatus">Все поля должны быть заполнены!</div>
                    </div>
                    <div class="card-body">
                        <div class="edit-container">
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Имя:<span class="exaption">&nbsp;*</span></span></div>
                                <input type="text" v-model="user.name" ref="name" :class="['form-control', 'black-input', {'general-field': !validateStatus}, {'valid-data': user.name}]"/>
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Почта:<span class="exaption">&nbsp;*</span></span></div>
                                <input :class="['form-control', 'black-input', {'valid-data': user.email}, {'general-field': !validateStatus}]" type="text" v-model="user.email">
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Пароль:<span class="exaption">&nbsp;*</span></span></div>
                                <input :class="['form-control', 'black-input', {'valid-data': password}, {'general-field': !validateStatus}]" type="password" v-model="password">
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Повторите пароль:<span class="exaption">&nbsp;*</span></span></div>
                                <input :class="['form-control', 'black-input', {'valid-data': confirmPassword}, {'general-field': !validateStatus}]" type="password" v-model="confirmPassword">
                            </div>
                            <div class="row mb-2">
                                <div class="input-group-prepend"><span>Роль:<span class="exaption">&nbsp;*</span></span></div>
                                <select required :class="['form-control', 'black-input', {'valid-data': user.role}, {'general-field': !validateStatus}]" v-model="user.role">
                                    <option value="User">Пользователь</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-around">
                    <a @click.prevent="$router.go(-1)" href="" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn violet-btn" @click="saveChanges">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import store from '../store/index';

    export default {
        store,
        data: function() {
            return {
                user: null,
                password: null,
                confirmPassword: null,
                id: null,
                validateStatus: true
            }
        },
        methods: {
            saveChanges: function() {

                this.validateStatus = this.user.name && this.user.email && this.password && (this.password === this.confirmPassword);

                if(this.validateStatus) {

                    this.user.password = this.password;

                    axios
                    .put(`${store.state.url}/user/${this.user.id}`, this.user)
                    .then(response => {
                        console.log('Changes complated!');
                        this.$router.go(-1);
                    })
                    .catch((error) => { console.log(error) });
                }
            }
        },
        mounted() {
            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            this.user = store.state.userList[this.id];
        }
    }
</script>
