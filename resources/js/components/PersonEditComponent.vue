<template>
    <div id="person-edit-component">
        <div class="card-header">{{title}}
            <div class="exaption" align="right">{{exaptionText}}
                <button type="button" class="btn violet-btn my-2 my-sm-0 ml-sm-2" v-if="unique_phone" @click="$router.push({path: `/about-person?id=${unique_phone}`})">Перейти</button>
            </div>
        </div>
        <div class="card-body">
            <div class="edit-container">
                <div class="row mb-2">
                    <div class="col-9 input-group-prepend"><span>Телефон:<span class="exaption">&nbsp;*</span></span></div>
                    <div class="col-3 mb-2">
                        <button type="submit" class="btn violet-btn" v-on:click="CheckPhone">Проверить</button>
                    </div>
                    <input type="text" v-model="person.phone" ref="phone" :class="['form-control', 'black-input', {'general-field': !validateStatus}, {'valid-data': person.phone}]"/>
                </div>
                <div class="row mb-2">
                    <div class="input-group col-6" id="status">
                        <div class="input-group-prepend"><span class="input-group-text">Статус:</span></div>
                        <select :class="['form-control', 'black-select', {'valid-data': person.status}]" required v-model="person.status">
                            <option value="новый">Новый</option>
                            <option value="надёжный">Надёжный</option>
                            <option value="ненадёжный">Ненадёжный</option>
                            <option value="подписанный" v-if="person.record_type === 'клиент'">Подписанный</option>
                        </select>
                    </div>
                    <div class="input-group col-6" id="record_type">
                        <div class="input-group-prepend"><span class="input-group-text">Тип записи:</span></div>
                        <select :class="['form-control', 'black-select', {'valid-data': person.record_type}]" required v-model="person.record_type">
                            <option value="клиент">Клиент</option>
                            <option value="водитель">Водитель</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" v-if="person.record_type === 'водитель'">
                    <div class="input-group-prepend"><span>Машина:</span></div>
                    <multiselect
                        :class="[{'valid-data': person.car.length > 0}]"
                        v-model="person.car"
                        tag-placeholder="Add this as new tag"
                        placeholder="Search or add a tag"
                        label="name"
                        track-by="code"
                        :options="carTypes"
                        :multiple="true"
                        :taggable="true"
                        :options-limit="10"
                        :allow-empty="true"
                        :hide-selected="true">
                    </multiselect>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Регион:</span></div>
                    <!-- <input :class="['form-control', 'black-input', {'valid-data': person.region}]" type="text" v-model="person.region"> -->
                    <select :class="['form-control', 'black-select', {'valid-data': person.region}]" required v-model="person.region">
                        <option value=""></option>
                        <option value="Адыгейская Республика">Адыгейская Республика</option>
                        <option value="Алтайский край">Алтайский край</option>
                        <option value="Архангельская область">Архангельская область</option>
                        <option value="Астраханская область">Астраханская область</option>
                        <option value="Базарно-Карабулакский район">Базарно-Карабулакский район</option>
                        <option value="Республика Башкоркостан">Республика Башкоркостан</option>
                        <option value="Белгородская область">Белгородская область</option>
                        <option value="Брянская область">Брянская область</option>
                        <option value="Владимирская область">Владимирская область</option>
                        <option value="Волгоградская область">Волгоградская область</option>
                        <option value="Вологодская область">Вологодская область</option>
                        <option value="Воронежская область">Воронежская область</option>
                        <option value="Дагестанская Республика">Дагестанская Республика</option>
                        <option value="Ивановская область">Ивановская область</option>
                        <option value="Республика Ингушетия">Республика Ингушетия</option>
                        <option value="Республика Калмыкия">Республика Калмыкия</option>
                        <option value="Калужская область">Калужская область</option>
                        <option value="Карачаево-Черкесская Республика">Карачаево-Черкесская Республика</option>
                        <option value="Кировская область">Кировская область</option>
                        <option value="Республика Коми">Республика Коми</option>
                        <option value="Краснодарский край">Краснодарский край</option>
                        <option value="Красноярский край">Красноярский край</option>
                        <option value="Курская область">Курская область</option>
                        <option value="Ленинградская область">Ленинградская область</option>
                        <option value="Липецкая область">Липецкая область</option>
                        <option value="Республика Марий Эл">Республика Марий Эл</option>
                        <option value="Республика Мордовия">Республика Мордовия</option>
                        <option value="Московская область">Московская область</option>
                        <option value="Нижегородская область">Нижегородская область</option>
                        <option value="город Новороссийск">город Новороссийск</option>
                        <option value="Новосибирская область">Новосибирская область</option>
                        <option value="Омская область">Омская область</option>
                        <option value="Оренбургская область">Оренбургская область</option>
                        <option value="Орловская область">Орловская область</option>
                        <option value="Пензенская область">Пензенская область</option>
                        <option value="Пермский край">Пермский край</option>
                        <option value="город Санкт-Петербург">город Санкт-Петербург</option>
                        <option value="Псковская область">Псковская область</option>
                        <option value="Ростовская область">Ростовская область</option>
                        <option value="Рязанская область">Рязанская область</option>
                        <option value="Самарская область">Самарская область</option>
                        <option value="город Саранск">город Саранск</option>
                        <option value="Саратовская область">Саратовская область</option>
                        <option value="Свердловская область">Свердловская область</option>
                        <option value="Республика Северная Осетия">Республика Северная Осетия</option>
                        <option value="Смоленская область">Смоленская область</option>
                        <option value="Ставропольский край">Ставропольский край</option>
                        <option value="Тамбовская область">Тамбовская область</option>
                        <option value="Республика Татарстан">Республика Татарстан</option>
                        <option value="Тверская область">Тверская область</option>
                        <option value="Томская область">Томская область</option>
                        <option value="Тульская область">Тульская область</option>
                        <option value="Тюменьская область">Тюменьская область</option>
                        <option value="Удмуртская республика">Удмуртская республика</option>
                        <option value="Ульяновская область">Ульяновская область</option>
                        <option value="ЦФО">ЦФО</option>
                        <option value="Челябинская область">Челябинская область</option>
                        <option value="город Черкесск">город Черкесск</option>
                        <option value="Чеченская республика">Чеченская республика</option>
                        <option value="ЮФО">ЮФО</option>
                        <option value="Ярославская область">Ярославская область</option>
                    </select>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>ФИО:</span></div>
                    <input :class="['form-control', 'black-input', {'valid-data': person.initials}]" type="text" v-model="person.initials">
                </div>
                <div class="row mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Дата рождения:</span></div>
                        <vuejs-datepicker :language="ru" v-model="person.birth_date" :class="['form-control', {'valid-data': person.birth_date}]"></vuejs-datepicker>
                    </div>
                </div>
                <div class="row mb-2" v-if="person.record_type === 'водитель'">
                    <div class="input-group-prepend"><span>Паспортные данные:</span></div>
                    <input :class="['form-control', 'black-input', {'valid-data': person.pasport_data}]" type="text" v-model="person.pasport_data" id="passport-data">
                </div>
                <div class="row mb-2" v-if="person.record_type === 'водитель'">
                    <div class="input-group-prepend"><span>Кем и когда выдан:</span></div>
                    <textarea v-model="person.receipt_info" cols="30" rows="2" :class="['form-control', 'black-input', {'valid-data': person.receipt_info}]"></textarea>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Электронная почта:</span></div>
                    <input type="text" v-model="person.email" :class="['form-control', 'black-input', {'valid-data': person.email}]"/>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Карта:</span></div>
                    <input :class="['form-control', 'black-input', {'valid-data': person.card}]" type="text" v-model="person.card" id="card-number">
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>НДС:</span></div>
                    <div class="col-3 ml-3 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" name="select-NDS" v-model="person.NDS" class="person-edit-radio" :value="false">
                            </div>
                        </div>
                        <label class="form-control">Нет</label>
                    </div>
                    <div class="col-3 ml-3 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" name="select-NDS" v-model="person.NDS" class="person-edit-radio" :value="true">
                            </div>
                        </div>
                        <label class="form-control">Да</label>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="input-group-prepend"><span>Примечание:</span></div>
                    <textarea v-model="person.notation" cols="30" rows="4" :class="['form-control', 'black-input', {'valid-data': person.notation}]"></textarea>
                </div>
                <div class="mb-2" v-if="person.files.length > 0">
                    <div class="row" v-for="(file, index) in person.files" :key="index">
                        <div class="col-6">{{file.name}}</div>
                        <div class="col-2 delete-btn" @click="person.files.splice(index, '1')"><span>&#10006;</span></div>
                    </div>
                </div>
                <div class="row mb-2">
                    <download-file-component @changeCurrentFile="pushNewFile" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VuePhoneNumberInput from 'vue-phone-number-input';
    import $ from 'jquery';
    import Datepicker from 'vuejs-datepicker';
    import {ru} from 'vuejs-datepicker/dist/locale';
    import Inputmask from 'inputmask';
    import Multiselect from 'vue-multiselect';
    import carTypes from '../car_types';
    import store from '../store/index';
    import DownloadFileComponent from './DownloadFileComponent';

    export default {
        store,
        components: {
            'vue-phone-number-input': VuePhoneNumberInput,
            'vuejs-datepicker': Datepicker,
            Multiselect,
            'download-file-component': DownloadFileComponent
        },
        data () {
            return {
                ru,
                carTypes
            }
        },
        props: [
            'title',
            'person',
            'validateStatus',
            'exaptionText',
            'unique_phone' ],
        methods: {
            CheckPhone() {
                axios
                    .get(`${store.state.url}/unique_phone?phone=${encodeURIComponent(this.person.phone)}&record_type=${this.person.record_type}`)
                    .then(response => {

                        this.unique_phone = response.data;
                        this.exaptionText = this.unique_phone ? 'Такой номер уже существует': '';
                    });
            },
            pushNewFile(value) {
                console.log(value);
                
                this.person.files.push(value);
            }
        },
        mounted() {

        //    let phoneMask = new Inputmask("(999) 999-99-99");
        //    phoneMask.mask($('#VuePhoneNumberInput_phone_number'));

        //    let pasportDataMask = new Inputmask("99 99:999999");
        //    pasportDataMask.mask($('#passport-data'));

        //    let cardNumberMask = new Inputmask("9999 9999 9999 9999");
        //    cardNumberMask.mask($('#card-number'));
        }
    }
</script>
