<template>
        <b-card title="Разработка ДПП">
            <b-card-text>
            Ниже отображаются все ДПП, где Вы назначены как исполнитель
            </b-card-text>
            <hr>
            <div v-for="my_dpp in my_dpps">
                <b-card header-border-variant="secondary" border-variant="secondary"  header-bg-variant="light" :header="my_dpp.name">
                    <h5>Статус: {{my_dpp.status_name}}</h5>
                    <h5>Текущий этап: {{my_dpp.current_stage_name}}</h5>
                    <h5>Исполнители</h5>
                    <b-table bordered hover :table-variant="'light'"  :head-variant="'light'" :items="my_dpp.participants" :fields="fields">
                        <template  v-slot:cell(modify)="data">
                            <p v-if="data.item.is_me">
                                <router-link v-if="!isBusy" icon="ion ion-md-person" :to="{ name: 'dpp_overview', params: {dpp: data.item.dpp_id, role: data.item.role_id} }" :exact="true">
                                    <b-button  variant="primary">
                                        Войти как {{data.item.rolename}}
                                    </b-button>
                                </router-link>
                            </p>
                        </template>
                    </b-table>
                </b-card>
            </div>
        </b-card>
</template>

<script>
export default {
    name: 'my_dpps',
    metaInfo: {
        title: 'Разработка ДПП'
    },
    data () {
      return {
        isBusy: true,
        fields: [
          {
            key: 'fullname',
            label: 'ФИО',
            sortable: true
          },
          {
            key: 'rolename',
            label: 'Роль',
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
            key: 'modify',
            label: 'Управление',
            sortable: true
          },
        ],
        my_dpps: [],  
      }
    },
    mounted () {
        axios
        .get('/my_dpps')
        .then(response => (this.my_dpps = response.data))
        .finally(() => (this.isBusy = false));
    }
}
</script>