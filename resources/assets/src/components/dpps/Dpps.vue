<template >
    <div>
        <b-card title="Управление ДПП">
            <b-card-text>
            Ниже отображается таблица всех созданных ДПП
            </b-card-text>
            <b-button variant="primary" v-b-modal.create_dpp>Создать ДПП</b-button>
            <hr>
            <b-table :busy="isBusy" bordered hover :table-variant="'light'"  :head-variant="'light'" :items="items" :fields="fields">
                <template v-slot:table-busy>
                    <div class="text-center text-info my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Загрузка...</strong>
                    </div>
                </template>
                <template v-slot:cell(participants)="data">
                    {{data.item.participants.length}}
                </template>
                <template v-slot:cell(modify)="data">
                    <router-link v-if="!isBusy" icon="ion ion-md-eye" :to="{ name: 'dpp_inspect', params: {dpp: data.item.id } }" :exact="true">
                       <b-button  variant="outline-info">
                           <i class="ion ion-md-eye" style="font-size:20px;"></i>
                       </b-button>
                    </router-link>
                    <router-link v-if="!isBusy" icon="ion ion-md-person" :to="{ name: 'dpp_config', params: {dpp: data.item.id } }" :exact="true">
                       <b-button  variant="outline-primary">
                           <i class="ion ion-md-construct" style="font-size:20px;"></i>
                       </b-button>
                    </router-link>
                    <b-button @click="delete_dpp(data.item,data.item.id)" variant="outline-danger">
                           <i class="ion ion-md-close" style="font-size:20px;"></i>
                       </b-button>
                </template>
            </b-table>
            {{this.info}}
        </b-card>
         <b-modal
            id="create_dpp"
            ref="modal"
            title="Создать ДПП"
            ok-title="Создать"
            cancel-title="Закрыть"
            @ok="handleOk">
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                :state="new_dpp.nameState"
                label="Наименование (тематика) ДПП"
                label-for="name-input"
                invalid-feedback="Необходимо ввести наименование"
                label-size="lg"
                >
                <b-form-input
                    id="name-input"
                    v-model="new_dpp.name"
                    :state="new_dpp.nameState"
                    required
                ></b-form-input>
                </b-form-group>
                <b-form-group
                :state="new_dpp.typeState"
                label="Тип ДПП"
                label-for="type-input"
                invalid-feedback="Необходимо выбрать тип ДПП"
                label-size="lg"
                >
                <b-form-select id="type-input" v-model="new_dpp.type" :options="dpp_types" :state="new_dpp.typeState" required>
                    <template v-slot:first>
                    <!--<b-form-select-option :value="null" disabled>-- Please select an option --</b-form-select-option>-->
                    </template>
                </b-form-select>
                </b-form-group>
            </form>
         </b-modal>
    </div>
</template>

<script>
  export default {
    name: 'dpps',
    metaInfo: {
        title: 'Управление ДПП'
    },
    data() {
      return {
        // Note 'isActive' is left out and will not appear in the rendered table
        fields: [
          {
            key: 'name',
            label: 'Наименование (тематика)',
            sortable: true
          },
          {
              key: 'type_name',
              label: 'Тип',
              sortable: true
          },
          {
            key: 'participants',
            label: 'Участников',
            sortable: true
          },
          {
            key: 'zuns',
            label: 'ЗУНов',
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
        isBusy: true,
        new_dpp : {
            name: '',
            nameState: null,
            type: '',
            typeState: null,
            actors: []  
        },
        dpp_types: []
      }
    },
    methods: {
      checkFormValidity() {
        const valid = this.$refs.form.checkValidity()
        this.new_dpp.nameState = valid
        this.new_dpp.typeState = valid
        return valid
      },
      resetModal() {
        this.new_dpp.name = ''
        this.new_dpp.nameState = null
        this.new_dpp.type = ''
        this.new_dpp.typeState = null
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
        // store_dpp
        this.store_dpp()
        // Hide the modal manually
        this.$nextTick(() => {
          this.$bvModal.hide('create_dpp')
        })
      },
      store_dpp()
      {
        axios
        .post('/store_dpp', {
            'name': this.new_dpp.name,
            'type': this.new_dpp.type
        })
        .then(response => (this.items.push(response.data)))
        .finally (() => (this.resetModal()));
      },
      delete_dpp(item,id)
      {
        this.$bvModal.msgBoxConfirm('Действительно хотите удалить программу?')
          .then(value => {
            axios
            .post('/dpps/delete', {
                'id': id
            })
            .then ( () => (this.items.splice(this.items.indexOf(item),1)));
          })
          .catch(err => {
            // An error occurred
          })
        
      }
    },
    mounted() {
      axios
        .get('/dpps')
        .then(response => (this.items = response.data))
        .finally(() => (this.isBusy = false));
      axios
        .get('/dpp_types')
        .then(response => (this.dpp_types = response.data));
    }
  }
</script>