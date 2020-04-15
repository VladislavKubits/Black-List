<template>
    <div class="container"  id="user-list-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table v-if="userList.length > 0" class="table table-hover black-table">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Почта</th>
                            <th scope="col">Роль</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="person" v-for="(user, index) in userList" :key="index" @click="editUser(index)">
                            <td>{{index}}</td>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                            <td>{{language[user.role]}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../store/index';
    import language from '../language';

    export default {
        store,
        data: function() {
            return {
                language
            }
        },
        computed: {
            userList() {
                return store.state.userList;
            }
        },
        methods: {
            editUser(id) {
                this.$router.push({path: `/user-edit?id=${id}`});
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
