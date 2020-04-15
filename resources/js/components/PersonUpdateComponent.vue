<template>
    <div class="container" id="person-update-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <person-edit-component :title="title" :person="person" :validateStatus="validateStatus" :exaptionText="exaptionText" :unique_phone="unique_phone" v-if="person"></person-edit-component>
                </div>
                <div class="row justify-content-around">
                    <a @click.prevent="$router.go(-1)" href="" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary" @click="saveChanges">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import PersonEditComponent from './PersonEditComponent.vue';
    import cloneDeep from 'lodash.clonedeep';
    import store from '../store/index';
    import carTypes from '../car_types';

    export default {
        components: {
            'person-edit-component': PersonEditComponent
        },
        data: function() {
            return {
                person: null,
                id: null,
                validateStatus: true,
                title: 'Редактирование информации о контакте',
                prevPerson: null,
                carTypes,
                unique_phone: false,
                exaptionText: ''
            }
        },
        computed: {
            url() {
                return store.state.url;
            }
        },
        methods: {
            saveChanges: function() {

                this.validateStatus = this.person.phone;

                this.exaptionText = this.unique_phone && this.validateStatus ? 'Такой номер уже существует': 'Заполните номер телефона';

                if(this.validateStatus  && !this.unique_phone) {
                    this.exaptionText = "";

                    delete this.person.histories;
                    delete this.person.calls;
                    delete this.person.verifications;

                    let command = 'update person';

                    for (const [key, value] of Object.entries(this.person)) {
                        console.log(key + " " + this.prevPerson[key]);
                        
                        if(value != this.prevPerson[key] && key != 'tasks') {
                            command += this.prevPerson[key] != null && this.prevPerson[key] != '' ? `,update ${key}` : `,add ${key}`;
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
                    .put(`${this.url}/person/${this.id}`, data)
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
            }
        },
        mounted() {
            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            axios
                .get(`${store.state.url}/person/${this.id}`)
                .then(response => {
                    this.person = response.data;

                    if(this.person.record_type === 'водитель') {
                        let types = this.person.car.split(',');
                    
                        let cars = [];
                        for(let type of types) {
                            cars.push({name: this.carTypes.filter(e => e.code === Number(type))[0].name, code: Number(type)});
                        }

                        this.person.car = cars;
                    }

                    this.prevPerson = cloneDeep(this.person);
                });
        }
    }
</script>
