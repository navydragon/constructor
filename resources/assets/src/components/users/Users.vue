<template >
    <div>
        <b-card title="Управление пользователями">
            <b-card-text>
            Ниже отображается табоица всех зарегистрированных в системе пользователей
            </b-card-text>
            <b-table bordered hover :table-variant="'light'"  :head-variant="'light'" :items="items" :fields="fields"></b-table>
            {{this.info}}
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
            key: 'registered_at',
            label: 'Дата регистрации',
            sortable: true
          },
          {
            key: 'age',
            label: 'Управление',
            sortable: false,
          }
        ],
        items: [
          {  fullname: 'Macdonald' },
        ],
        info: ""
      }
    },
    mounted() {
      axios
        .get('/users')
        .then(response => (this.items = response.data));
    }
  }
</script>