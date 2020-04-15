<template>
<div class="container" id="history-component">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Информация об истории</div>

                <div class="card-body">
                   <div class="history" v-if="history">
                        <div class="container">
                                <div class="row field">
                                    <div class="col-sm-4">Дата добавления:</div>
                                    <div class="col-sm-8">{{ history.created_at }}</div>
                                </div>
                            <div class="row field">
                                <div class="col-sm-4">Кем добавлена:</div>
                                <div class="col-sm-8">{{ history.user.name }}</div>
                            </div>
                            <div class="row field full" id="history-info">
                                <div class="col-sm-4">Действие:</div>
                                <div class="col-sm-8">{{ history.info }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-around">
                <a href="" @click.prevent="$router.go(-1)" class="btn btn-secondary">Назад</a>
            </div>
        </div>
    </div>
</div>
    
</template>

<script>
    import environment  from '../environment';
    import axios from 'axios';
    import store from '../store/index';

    export default {
        store,
        data: function() {
            return {
                history: null,
                url: null,
                id: null
            }
        },
        mounted(){
            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            this.url = `${ environment.serverUrl }:${ environment.serverPort }`;

            axios
                .get(`${this.url}/history/${this.id}`)
                .then(response => {
                    this.history = response.data;
                    console.log(this.history);
                });
        }
    }
</script>