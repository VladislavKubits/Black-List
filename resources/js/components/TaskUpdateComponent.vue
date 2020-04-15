<template>
    <div class="container" id="task-update-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <task-edit-component :title="title" :task="task" :validateStatus="validateStatus" v-if="task"></task-edit-component>
                </div>
                <div class="row justify-content-around">
                    <a href="" @click.prevent="$router.go(-1)" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary" @click="saveChanges">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import TaskEditComponent from './TaskEditComponent.vue';
    import cloneDeep from 'lodash.clonedeep';
    import store from '../store/index';

    export default {
        components: {
            'task-edit-component': TaskEditComponent
        },
        data: function() {
            return {
                task: null,
                id: null,
                validateStatus: true,
                title: 'Редактирование задачи',
                prevTask: null
            }
        },
        methods: {
            saveChanges: function() {

                this.validateStatus = this.task.title && this.task.description && this.task.author_id && this.task.responsible_id && this.task.started_at && this.task.people.length > 0;

                if(this.validateStatus) {
                    delete this.task.histories;

                    let command = 'update task';

                    for (const [key, value] of Object.entries(this.task)) {
                        if(value != this.prevTask[key] && key != 'author' && key != 'responsible' && key != 'people') {
                            command += this.prevTask[key] != null && this.prevTask[key] != '' ? `,update ${key}` : `,add ${key}`;
                        }
                    }

                    let data = {
                        command,
                        task: this.task
                    };

                    axios
                    .put(`${store.state.url}/task/${this.id}`, data)
                    .then(response => {
                        this.$router.go(-1);
                    })
                    .catch((error) => { console.log(error) });
                }
            }
        },
        mounted() {
            this.id = Number(window.location.search.replace(/[^0-9]/gim,''));
            axios
                .get(`${store.state.url}/task/${this.id}`)
                .then(response => {
                    this.task = response.data;
                    this.prevTask = cloneDeep(this.task);
                });
        }
    }
</script>
