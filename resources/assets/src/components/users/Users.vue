<template >
    <div>
        <b-card title="Управление пользователями">
            <b-card-text>
            Ниже отображается таблица всех зарегистрированных в системе пользователей
            </b-card-text>
            <b-table :busy="isBusy" bordered hover :table-variant="'light'"  :head-variant="'light'" :items="items" :fields="fields">
                 <template v-slot:table-busy>
                    <div class="text-center text-info my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Загрузка...</strong>
                    </div>
                </template>
                <template v-slot:cell(modify)="data">
                  <b-button @click="delete_user(data.item,data.item.id)" variant="outline-danger">
                           <i class="ion ion-md-close" style="font-size:20px;"></i>
                  </b-button>
                </template>
            </b-table>
            
        </b-card>
    </div>
</template>

<script>
  export default {
    name: 'users',
    metaInfo: {
        title: 'Управление пользователями'
    },
    data() {
      return {
        // Note 'isActive' is left out and will not appear in the rendered table
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
        items: [
          {  },
        ],
        info: "",
        isBusy: true
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