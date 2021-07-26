<template>
    <div>
      <b-button v-b-modal.modal-adduser variant="primary">Добавить пользователя</b-button>
      <b-modal no-close-on-esc no-close-on-backdrop ok-only @ok="handle_ok" id="modal-adduser" ok-title="Добавить" cancel-title="Закрыть" size="xl" title="Создание нового пользователя">
            <b-alert variant="info" show>Заполните параметры пользователя и нажмите кнопку «Добавить» </b-alert>
            <b-form-row>
              <b-form-group label-size="lg" label-cols-lg="2" label="Фамилия *" class="col">
                  <b-form-input v-model="new_user.lastname" :formatter="formatter" required placeholder="Введите фамилию" />
              </b-form-group> 
            </b-form-row>
            <b-form-row>
              <b-form-group label-size="lg" label-cols-lg="2" label="Имя *" class="col">
                  <b-form-input v-model="new_user.firstname" :formatter="formatter" required placeholder="Введите имя" />
              </b-form-group>
            </b-form-row>
            <b-form-row>
              <b-form-group label-size="lg" label-cols-lg="2" label="Отчество *" class="col">
                  <b-form-input :state="middlename_state" v-model="new_user.middlename" :formatter="formatter" required placeholder="Введите отчество" />
              </b-form-group>
            </b-form-row>
            <b-form-row>
              <b-form-group label-size="lg" label-cols-lg="2" label="E-mail *" class="col">
                  <b-form-input :state="isEmailValid" :type="'email'" v-model="new_user.email" required placeholder="Введите E-mail" />
              </b-form-group>
            </b-form-row>
            <b-form-row>
              <b-form-group label-size="lg" label-cols-lg="2" label="Телефон" class="col">
                  <b-form-input v-model="new_user.phone" required placeholder="Введите телефон (необязательно)" />
              </b-form-group>
            </b-form-row>
            <b-alert v-if="errors.length>0" show variant="danger">Обнаружены ошибки:
              <ul>
                <li v-for="error in errors" :key="error">{{error}}</li>
              </ul>
            </b-alert>
      </b-modal>
    </div>
</template>

<script>
export default {
    name: 'new-user',
    metaInfo: {
        title: 'Создать нового пользователя'
    },
    data() {
    return {
        new_user: {
          lastname : '',
          firstname : '',
          middlename : '',
          email : '',
          phone : '',
        },
        lastname_state: null,
        firstname_state: null,
        middlename_state: null,
        reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,    
        errors: [],
    }
    },
    computed: {
      isEmailValid: function() {
      return (this.new_user.email == "")? null : (this.reg.test(this.new_user.email)) ? true : false;
      },
    },
    methods:{
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        self = this
        this.errors = [];
        if (this.new_user.lastname.length == 0 )
        { this.errors.push("Введите фамилию") }
        if (this.new_user.firstname.length == 0 )
        { this.errors.push("Введите имя") }
        if (this.new_user.middlename.length == 0 )
        { this.errors.push("Введите отчество")  }
        if (this.isEmailValid == false || this.isEmailValid == null )
        { this.errors.push("Введите корректный e-mail") }

        if (this.errors.length == 0)
        {
          axios
          .post('/users/check_email', {
            'email': this.new_user.email,
          })
          .then ( function(response) {
            if (response.data > 0)
            {
              self.errors.push("Пользователь с таким e-mail уже присутствует в системе")
            }else{
              self.$emit('add_user', {
              user_data: self.new_user
              })
            }
          })
        }
      },
      formatter (value) {
        if (value.length > 0)
        {
        return value[0].toUpperCase () + value.substr(1).toLowerCase ();
        }else{
          return ""
        }
      },
    }
}
</script>

