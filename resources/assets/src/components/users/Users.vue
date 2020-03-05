<template >
    <div>
        <b-card title="Управление пользователями">
            <b-card-text>
            Ниже отображается таблица всех зарегистрированных в системе пользователей
            </b-card-text>
            <new-user :key="nu" @add_user="add_user"></new-user>
            <b-table :busy="isBusy" bordered hover :table-variant="'light'"  :head-variant="'light'" :items="items" :fields="fields">
                 <template v-slot:table-busy>
                    <div class="text-center text-info my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Загрузка...</strong>
                    </div>
                </template>
                <template v-slot:cell(modify)="data">
                  <b-button @click="discard_password(data.item,data.item.id)" variant="outline-primary">
                           <i class="ion ion-md-key" style="font-size:20px;"></i>
                  </b-button>
                  <!--
                  <b-button @click="delete_user(data.item,data.item.id)" variant="outline-danger">
                           <i class="ion ion-md-close" style="font-size:20px;"></i>
                  </b-button>
                  -->
                </template>
            </b-table>
            
        </b-card>
    </div>
</template>

<script>
  import NewUser from './NewUser'
  export default {
    name: 'users',
    metaInfo: {
        title: 'Управление пользователями'
    },
    components: {
      NewUser
    },
    data() {
      return {
        nu: 0,
        fields: [
          {
            key: 'fullname',
            label: 'ФИО',
            sortable: true
          },
          {
              key: 'email',
              label: 'E-mail',
              sortable: true
          },
          {
              key: 'phone',
              label: 'Телефон',
              sortable: true
          },
          {
            key: 'registered_at',
            label: 'Дата регистрации',
            sortable: true
          },
          {
            key: 'modify',
            label: 'Управление',
            sortable: false,
          }
        ],
        errors: [],
        items: [
          {  },
        ],
        info: "",
        isBusy: true
      }
    },
    methods: {
      send_email(item,id) {
        axios
        .post('/users/send_reg_data', {
          'user_id': id,
        })
        //.then(response => (this.items = response.data))
      },
      discard_password(item,id) {
        this.$bvModal.msgBoxConfirm('Сбросить пароль пользователя «'+item.fullname+'»?')
        .then(value => {
        if (value === true) {
          axios
          .post('/users/discard_password', {
              'user': id
          })
        }
        })
      },
      add_user(data) 
      {
        self = this
        axios
        .post('/users/add_user', {
            'user_data': data
        })
        .then(response => {
          alert('Пользователь зарегистрирован! Ему направлено письмо с данными для входа.')
          self.nu++
          this.items.push(response.data);
        })
      }
    },
    mounted() {
      axios
        .get('/users')
        .then(response => (this.items = response.data))
        .finally(() => (this.isBusy = false));
    }
  }
</script>