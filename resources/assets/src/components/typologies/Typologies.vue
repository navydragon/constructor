<template>
    <div>
         <b-card title="Типологии содержания ДПП">
            <b-card-text>
            Ниже отображается таблица всех типовых структур ДПП
            </b-card-text>
            <b-button variant="primary" v-b-modal.create_typology>Создать типовую структуру</b-button>
            <p></p>
            <hr>
            <b-table :busy="isBusy" bordered hover :table-variant="'light'"  :head-variant="'light'" :items="items" :fields="fields">
                <template v-slot:table-busy>
                    <div class="text-center text-info my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Загрузка...</strong>
                    </div>
                </template>
                <template v-slot:cell(parts)="data">
                    {{data.item.parts.length}}
                </template>
                <!--
                <template v-slot:cell(modify)="data">
                    
                       <b-button  variant="outline-primary">
                           <i class="ion ion-md-construct" style="font-size:20px;"></i>
                       </b-button>
                      <b-button @click="delete_dpp(data.item,data.item.id)" variant="outline-danger">
                           <i class="ion ion-md-close" style="font-size:20px;"></i>
                       </b-button>
               </template>
                -->
            </b-table>
         </b-card>
         <b-modal
            id="create_typology"
            :key="t"
            ref="modal"
            title="Создать типовую структуру ДПП"
            ok-title="Создать"
            size="lg"
            cancel-title="Закрыть"
            @ok="handleOk">
                <b-form-group
                :state="new_typology.nameState"
                label="Название"
                label-for="name-input"
                invalid-feedback="Необходимо ввести название"
                label-size="lg"
                >
                <b-form-input
                    id="name-input"
                    v-model="new_typology.name"
                    :state="new_typology.nameState"
                    required
                ></b-form-input>
                </b-form-group>
                <h5>Разделы типологии:</h5>
                <b-button variant="primary" @click="add_part">Добавить раздел</b-button>
                <div v-for="(elem,index) in new_typology.parts" :key="elem.id" class="m-3">
                    <div class="row">
                        <div class="col-md-1">
                            {{index+1}}.
                        </div>
                        <div class="col-md-11">
                            <b-form-input
                                v-model="elem.name"
                                :state="elem.nameState"
                                required
                            ></b-form-input>
                        </div>
                    </div>
                </div>
         </b-modal>
    </div>
</template>

<script> 
export default {
    name: 'typologies',
    metaInfo: {
        title: 'Управление типологиями содержания ДПП'
    },
    data() {
      return {
        t: 0,
        fields: [
          {
            key: 'name',
            label: 'Наименование',
            sortable: true
          },

          {
            key: 'parts',
            label: 'Разделов',
            sortable: true
          },
          {
            key: 'modify',
            label: 'Управление',
            sortable: false,
          }
        ],
        items: [
        ],
        info: "",
        isBusy: true,
        new_typology : {
            name: '',
            nameState: null,
            parts: [{
                id: this.generate_id(),
                name: '',
                state: null
            }]  
        }
      }
    },
    methods: {
      handleOk(bvModalEvt) {
        bvModalEvt.preventDefault()
        self = this
        axios
        .post('/typologies/add_typology', this.new_typology)
        .then (function (response) {
            self.items.push(response.data)
            self.$bvModal.hide("create_typology")
        })
        .finally( () => (self.t = self.t + 1))
      },
      generate_id () {
        return `f${(~~(Math.random() * 1e8)).toString(16)}`
      },
      add_part () {
          this.new_typology.parts.push( {
              id: this.generate_id(),
              name: '',
              state: null
          })
      }
    },
    mounted () {
        axios
        .get('/typologies/get_typologies')
        .then ((response) => (this.items = response.data))
        .finally(() => this.isBusy = false)
    }
}
</script>