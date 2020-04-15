<template>
    <div class="container" id="add-person-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <person-edit-component :title="title" :person="person" :validateStatus="validateStatus" :exaptionText="exaptionText" :unique_phone="unique_phone"></person-edit-component>
                </div>
                <div class="row justify-content-around">
                    <a @click.prevent="$router.go(-1)" href="" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary" @click="savePerson">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import environment  from '../environment';
    import axios from 'axios';
    import PersonEditComponent from './PersonEditComponent.vue';
    import store from '../store/index';

    export default {
        store,
        components: {
            'person-edit-component': PersonEditComponent
        },
        data: function() {
            return {
                person: {
                    car: [],
                    region: null,
                    initials: null,
                    birth_date: null,
                    pasport_data: null,
                    receipt_info: null,
                    phone: null,
                    email: null,
                    card: null,
                    notation: null,
                    status: 'новый',
                    record_type: 'клиент',
                    NDS: false,
                    files: []
                },
                validateStatus: true,
                title: 'Добавление нового контакта',
                unique_phone: false,
                exaptionText: ''
            }
        },
        methods: {
            savePerson: function() {
                axios
                    .get(`${store.state.url}/unique_phone?phone=${encodeURIComponent(this.person.phone)}&record_type=${this.person.record_type}`)
                    .then(response => {

                        this.unique_phone = response.data;

                        this.validateStatus = this.person.phone;

                        this.exaptionText = this.unique_phone && this.validateStatus ? 'Такой номер уже существует': 'Заполните номер телефона';

                        if (this.validateStatus && !this.unique_phone) {
                            this.exaptionText = "";

                            let command = 'add person';

                            for (const [key, value] of Object.entries(this.person)) {
                                if (key != 'files') {
                                    command += value != null && value != '' ? `,add ${key}` : '';
                                }
                            }

                            if (this.person.record_type === 'водитель' && this.person.car.length > 0) {
                                let cars_str = this.person.car.map(e => e.code).join(",");
                                this.person.car = cars_str;
                            } else {
                                this.person.car = null;
                            }

                            let data = {
                                command,
                                person: this.person
                            };

                            axios
                                .post(`${store.state.url}/person`, data)
                                .then(response => {
                                    console.log('Changes complated!');
                                    if(this.person.record_type === 'клиент') {
                                        this.$router.push({path: `/clients`});
                                    } else {
                                        this.$router.push({path: `/drivers`});
                                    }
                                })
                                .catch((error) => { console.log(error) });
                        }
                    })
                    .catch((error) => { return false; }
                );
            }
        },
        mounted() {
            //
        }
    }
</script>
