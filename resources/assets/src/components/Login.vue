<template>
    <div>
        <b-card title="Авторизация">
            <b-alert show variant="info">Введите e-mail и пароль, указанные при регистации, и нажмите кнопку «Вход»</b-alert>
            <form autocomplete="off" @submit.prevent="login" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="E-mail, указанный при регистрации" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Вход</button>
            </form>
            <b-alert v-if="error" show variant="danger"><strong>Ошибка!</strong> Указанное сочетания e-mail и пароля не существует.</b-alert>
        </b-card>
    </div>
</template>

<script>
  export default {
    name: 'login',
    metaInfo: {
        title: 'Авторизация'
    },
    data(){
      return {
        email: null,
        password: null,
        error: false
      }
    },
    methods: {
      login(){
        var app = this
        this.$auth.login({
            params: {
              email: app.email,
              password: app.password
            }, 
            success: function () {},
            error: function () {
                this.error = true
            },
            rememberMe: true,
            redirect: '/',
            fetchUser: true,
        });       
      },
    }
  } 
</script>