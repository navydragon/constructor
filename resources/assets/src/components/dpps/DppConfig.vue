<template>
    <div>
        <b-card :title="header">
            <router-link icon="ion ion-md-person" :to="{name: 'dpps'}" :exact="true">
                <b-button  variant="info">
                   <i class="ion ion-ios-arrow-back" style="font-size:20px;"></i> Назад
                </b-button>
            </router-link>
            <hr>
            <h5>Наименование (тематика) ДПП</h5>
            <p>{{item.name}}</p>
            <h5>Тип ДПП</h5>
            <p>{{item.type_name}}</p>
            <h5>Аннотация</h5>
            <p>...</p>
            <h5>Участники</h5>
            <b-table bordered hover :table-variant="'light'"  :head-variant="'light'" :items="item.participants" :fields="fields">
                <template v-slot:cell(modify)="data">
                       <b-button @click="confirmDelete(data.item)" variant="outline-danger">
                           <i class="ion ion-md-close" style="font-size:14px;"></i>
                       </b-button>
                </template>
            </b-table>
            <b-button variant="primary" v-b-modal.add_participants>Добавить участника</b-button>
        </b-card>
        <b-modal
            id="add_participants"
            ref="modal"
            title="Добавить участников"
            ok-title="Добавить"
            cancel-title="Закрыть"
            @ok="handleOk">
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    :state="new_participant.userState"
                    label="Пользователь"
                    label-for="user-input"
                    invalid-feedback="Необходимо выбрать пользователя"
                    label-size="lg"
                    >
                    <model-select id="user-input" :options="users"
                                    v-model="new_participant.user"
                                    :state="new_participant.userState"
                                    placeholder="Выберите пользователя">
                    </model-select>
                </b-form-group>
                <b-form-group
                    :state="new_participant.roleState"
                    label="Роль"
                    label-for="role-input"
                    invalid-feedback="Необходимо выбрать роль"
                    label-size="lg"
                    >
                    <b-form-select id="role-input" v-model="new_participant.role" :options="roles" :state="new_participant.roleState" required>
                    </b-form-select>
                </b-form-group>
            </form>
            <b-alert v-if="existed_error" show variant="danger">Ошибка! Пользователь уже выполняет указанную роль в данной программе.</b-alert>
         </b-modal>
    </div>
</template>


<script>
  import { ModelSelect } from 'vue-search-select'
  export default {
    name: 'dpp_config',
    metaInfo: {
        title: 'Управление ДПП'
    },
    components: {
      ModelSelect
    },
    computed: {
        header() {
            return 'Управление ДПП / '+this.item.name
        }
    },
    data() {
      return {
       item:{},
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
            key: 'modify',
            label: 'Управление',
            sortable: false,
          }
        ], 
       users: [],
       new_participant: {
           user: null,
           userState: null,
           role: null,
           roleState: null,
       },
       users: [],
       roles: [],
       existed_error: false
      }
    },
    methods: {
        checkFormValidity() {
        const valid = this.$refs.form.checkValidity()
        this.new_participant.userState = valid
        this.new_participant.roleState = valid
        return valid
      },
      resetModal() {
        this.new_participant.user = ''
        this.new_participant.userState = null
        this.new_participant.role = ''
        this.new_participant.roleState = null
        this.existed_error = false;
      },
      handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault()
        // Trigger submit handler
        this.handleSubmit()
      },
      handleSubmit() {
        // Exit when the form isn't valid
        if (!this.checkFormValidity()) {
          return
        }
        // store_user_role
        this.store_dpp_user_role();
      },
      handleResponse(response) {
          if (response.data == "exists"){
                this.existed_error = true
                this.new_participant.roleState = null
            }else{
            this.resetModal()  
            this.$bvModal.hide('add_participants')
            this.item.participants.push(response.data)
            }
      },
      store_dpp_user_role()
      {
        axios
        .post('/add_dpp_user_role', {
            'user': this.new_participant.user,
            'role': this.new_participant.role,
            'dpp': this.$route.params.dpp
        })
        .then(response => (this.handleResponse(response)));
      },
      confirmDelete(item)
      {
           this.$bvModal.msgBoxConfirm('Удалить роль «'+item.rolename+'» пользователя «'+item.fullname+'»?')
          .then(value => {
            if (value == true)
            {
               axios.post('/delete_dpp_user_role', {
                    id: item.id
                })
                .then(() => (this.item.participants.splice(this.item.participants.indexOf(item),1)));
            }
          })
          .catch(err => {
            // An error occurred
          })
      },
    },
    mounted() {
      axios
        .get('/dpps/'+ this.$route.params.dpp+'/config')
        .then(response => (this.item = response.data));
        //.finally(() => (alert('kek')));
      axios
        .get('/dpp_types')
        .then(response => (this.dpp_types = response.data));
      axios
        .get('/users')
        .then(response => (this.users = response.data));
      axios
        .get('/roles')
        .then(response => (this.roles = response.data));
    }
  }
</script>