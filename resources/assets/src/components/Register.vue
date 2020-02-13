<template>
    <div>
        <b-card title="Регистрация">
            <b-alert show variant="success"  v-if="success">Регистрация прошла успешно! Теперь Вы можете <router-link :to="'/login'">войти</router-link> в систему</b-alert>
            <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                <b-alert show variant="info">Заполните поля формы и нажмите кнопку «Зарегистрироваться»</b-alert>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.lastname }">
                    <label for="lastname">Фамилия</label>
                    <input type="text" id="lastname" class="form-control" v-model="lastname" required>
                    <span class="help-block" v-if="error && errors.lastname">{{ errors.lastname }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.firstname }">
                    <label for="firstname">Имя</label>
                    <input type="text" id="firstname" class="form-control" v-model="firstname" required>
                    <span class="help-block" v-if="error && errors.firstname">{{ errors.firstname }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.middlename }">
                    <label for="middlename">Отчество</label>
                    <input type="text" id="middlename" class="form-control" v-model="middlename" required>
                    <span class="help-block" v-if="error && errors.middlename">{{ errors.middlename }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.phone }">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" class="form-control" placeholder="+7(ХХХ)ХХХХХХХ" v-model="phone" required>
                    <span class="help-block" v-if="error && errors.phone">{{ errors.phone }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.password }">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" class="form-control" v-model="password" required>
                    <span class="help-block" v-if="error && errors.password">{{ errors.password }}</span>
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
            <div class="alert alert-danger" v-if="error && !success">
                <p>There was an error, unable to complete registration.</p>
            </div>
        </b-card>
    </div>
</template>

<script> 
    export default {
        data(){
            return {
                lastname: '',
                firstname: '',
                middlename: '',
                email: '',
                phone: '',
                password: '',
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this
                this.$auth.register({
                    data: {
                        firstname: app.firstname,
                        lastname: app.lastname,
                        middlename: app.middlename,
                        email: app.email,
                        phone: app.phone,
                        password: app.password
                    }, 
                    success: function () {
                        app.success = true
                    },
                    error: function (resp) {
                        app.error = true;
                        app.errors = resp.response.data.errors;
                    },
                    redirect: null
                });                
            }
        }
    }
</script>