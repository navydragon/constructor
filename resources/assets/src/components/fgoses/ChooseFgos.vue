<template>
    <div>
        <b-button @click="get_data()">Выбрать...</b-button>
        <b-modal
        size="lg"
        id="modal-choose-fg"
        title="Выбор ФГОСов"
        no-close-on-esc
        no-close-on-backdrop
        @ok="handle_ok"
        ok-title="Сохранить"
        cancel-title="Закрыть"
        >
        <b-button variant="primary" @click="create_fgos()">Добавить ФГОС </b-button>
        <hr class="container-m-nx border-light mt-2 mb-2">
        <v-client-table v-if="!isBusy" :data="items" :columns="columns" :options="options">
            <template slot="choose" slot-scope="props">
                <b-form-checkbox button-variant="outline-primary" :value="props.row.id" v-model="selected" name="multi_check" size="lg"></b-form-checkbox>
            </template>
            <template slot="actions" slot-scope="props">
                <div>
                <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_fgos(props.row)"><i class="ion ion-md-create"></i></b-btn>
                </div>
            </template>
        </v-client-table>
        <new-fgos :fgos_levels="fgos_levels"  @add_fgos="add_fgos" :key="n_ps"></new-fgos>
        <edit-fgos :fgos_levels="fgos_levels" :edit_item="edit_item"  @update_fgos="update_fgos" :key="edit_item.id"></edit-fgos>
        </b-modal>
    </div>   
</template>
<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>

<script>
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewFgos from './NewFgos'
import EditFgos from './EditFgos'

Vue.use(ClientTable)
export default {
    name: 'choose-fgos',
    metaInfo: {
        title: 'Выбор ФГОСов'
    }, 
    props: {
      fgoses: Array,
    },
    components: {
      ClientTable,NewFgos,EditFgos
  },
  data (){
  return {
      isBusy: true,
      n_ps: 'fg',
      edit_item: {},
      selected: this.fgoses.map(x => x["id"]),
      items: [],
      fgos_levels: [],
      columns: ['choose','code','name','level','actions'],
      options: {
           // pagination: { chunk: 5 },
            sortable: ['name', 'code','level'],
            filterable:['name','code','level'],
            filterByColumn: true,
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                choose: 'Выбрать',
                name: 'Направление',
                code: 'Код',
                level: 'Уровень',
                actions: 'Действия'
            },
            texts: {
                count: "Showing {from} to {to} of {count} records|Записей: {count} |Одна запись",
                first: 'First',
                last: 'Last',
                filter: "Поиск:",
                filterPlaceholder: "текст поиска",
                limit: "Записей:",
                page: "Страница:",
                noResults: "Не найдено ни одной записи",
                filterBy: "Поиск",
                loading: 'Загрузка...',
                defaultOption: 'Select {column}',
                columns: 'Columns'
            }
        }
  }} ,
  methods: {
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.$emit('select_fgoses', this.selected)
      },
      create_fgos(){
          this.$bvModal.show("modal-new-fgos")
      },
      add_fgos (data){
          self = this;
            axios
            .post('/fgoses/add_fgos', data)
            .then(function (response) {
                self.items.push (response.data)
                self.n_ps += 1
            })
           
      },
      remove_fgos(elem)
      {
          var self = this
           this.$bvModal.msgBoxConfirm(
            "Действительно хотите удалить ФГОС «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post('/fgoses/remove_fgos', elem)
                .then(function (response) {
                    self.items = self.items.filter(item => item.id != response.data)
                })
            }
          });
      },
      edit_fgos (elem)
      {
          this.edit_item = elem;
          this.$nextTick(() => {
            this.$bvModal.show("modal-edit-fgos")
          });
          
      },
      update_fgos (data)
      { 
          self = this;
            axios
            .post('/fgoses/update_fgos', data)
            .then(function (response) {
                 var upd_item =  self.items.find(item => item.id == response.data.id)
                upd_item.code = response.data.code
                upd_item.name = response.data.name
                upd_item.level = response.data.level
                self.$bvModal.hide("modal-edit-fgos") 
            })
            .finally (()=>(self.edit_item={}))
      },
      get_data()
      {
        self = this;
        axios
        .post('/fgoses/get_fgos_levels')
        .then(function (response) {
            self.fgos_levels = response.data
        })
        this.$nextTick(() => {
        axios
        .post('/fgoses/get_fgoses')
        .then(function (response) {
            self.items = response.data
        })
        .finally (function (response) {
                self.isBusy = false
                self.$bvModal.show("modal-choose-fg")
            });
      });
      }
  },
  mounted (){
     
  }

}
</script>