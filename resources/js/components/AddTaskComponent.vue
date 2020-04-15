<template>
    <div class="container" id="add-task-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <task-edit-component :title="title" :task="task" :validateStatus="validateStatus"></task-edit-component>
                </div>
                <div class="row justify-content-around">
                    <a href="" @click.prevent="$router.go(-1)" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary" v-on:click="saveTask">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TaskEditComponent from './TaskEditComponent';
    import environment  from '../environment';
    import axios from 'axios';
    import store from '../store/index';

    export default {
        store,
        components: {
            'task-edit-component': TaskEditComponent
        },
        props: {
            user: {
                type: Object,
                default: null
            }
        },
        data: function () {
            return {
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
                    type: 'Другое'
                },
                validateStatus: true,
                title: 'Добавление нового задания'
            }
        },
        methods: {
            saveTask: function() {

                this.validateStatus = this.task.title && this.task.description && this.task.author_id && this.task.responsible_id && this.task.started_at && this.task.people.length > 0;

                if (this.validateStatus) {

                    let command = 'add task';

                    for (const [key, value] of Object.entries(this.task)) {
                        if(key != 'author' && key != 'responsible' && key != 'people') {
                            command += value != null && value != '' && value.length > 0 ? `,add ${key}` : '';
                        }
                        
                    }

                    let data = {
                        command,
                        task: this.task
                    };

                    axios
                        .post(`${store.state.url}/task`, data)
                        .then(response => {
                            this.$router.go(-1);
                        })
                        .catch((error) => { console.log(error) });
                }
            }
        },
        mounted() {
            this.task.author_id = store.state.user.id;
        }
    }
</script>
