

<template>
    <div>
    <b-button @click="get_data()">Выбрать...</b-button>
    <b-modal
    size="lg"
    id="modal-choose-ps"
    title="Выбор профессиональных стандартов"
    no-close-on-esc
    no-close-on-backdrop
    @ok="handle_ok"
    ok-title="Сохранить"
    cancel-title="Закрыть"
    >
        <b-button variant="primary" @click="create_profstandart()">Добавить профстандарт</b-button>
        <hr class="container-m-nx border-light mt-2 mb-2">
        <v-client-table  :data="items" :columns="columns" :options="options">
            <template slot="choose" slot-scope="props">
                <b-form-checkbox button-variant="outline-primary" :value="props.row.id" v-model="selected" name="multi_check" size="lg"></b-form-checkbox>
            </template>
            <template slot="actions" slot-scope="props">
                <div>
                <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_profstandart(props.row)"><i class="ion ion-md-create"></i></b-btn>
                </div>
            </template>
        </v-client-table>
        <new-profstandart  @add_profstandart="add_profstandart" :key="n_ps"></new-profstandart>
        <edit-profstandart :edit_item="edit_item"  @update_profstandart="update_profstandart" :key="edit_item.id"></edit-profstandart>
    </b-modal>
    </div>
</template>
<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>

<script>
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewProfstandart from './NewProfstandart'
import EditProfstandart from './EditProfstandart'
Vue.use(ClientTable)
export default {
    name: 'choose-profstandart',
    metaInfo: {
        title: 'Выбор профессиональных стандартов'
    }, 
    components: {
      ClientTable,NewProfstandart,EditProfstandart
    },
    props: {
        profstandarts: Array,
    },
  data (){
  return {
      isBusy: true,
      n_ps: 'ps',
      edit_item: {},
      selected: this.profstandarts.map(x => x["id"]),
      items: [],
      columns: ['choose','code','name','actions'],
      options: {
           // pagination: { chunk: 5 },
            sortable: ['name', 'code'],
            filterable:['name','code'],
            filterByColumn: true,
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                choose: 'Выбрать',
                code: 'Код',
                name: 'Название',
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
        this.$emit('select_profstandarts', this.selected)
      },
      create_profstandart(){
          this.$bvModal.show("modal-newprofstandart")
      },
      add_profstandart (data){
          self = this;
            axios
            .post('/profstandarts/add_profstandart', data)
            .then(function (response) {
                self.items.push (response.data)
                self.n_ps += 1
            })
           
      },
      remove_profstandart(elem)
      {
          var self = this
           this.$bvModal.msgBoxConfirm(
            "Действительно хотите удалить профстандарт «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post('/profstandarts/remove_profstandart', elem)
                .then(function (response) {
                    self.items = self.items.filter(item => item.id != response.data)
                })
            }
          });
      },
      edit_profstandart (elem)
      {
          this.edit_item = elem;
          this.$nextTick(() => {
            this.$bvModal.show("modal-editprofstandart")
          });
          
      },
      update_profstandart (data)
      { 
          self = this;
            axios
            .post('/profstandarts/update_profstandart', data)
            .then(function (response) {
                 var upd_item =  self.items.find(item => item.id == response.data.id)
                upd_item.code = response.data.code
                upd_item.name = response.data.name
                self.$bvModal.hide("modal-editprofstandart") 
            })
            .finally (()=>(self.edit_item={}))
      },
      get_data()
      {
          self = this;
        axios
            .post('/profstandarts/get_profstandarts')
            .then(function (response) {
                self.items = response.data
            })
            .finally (function (response) {
                self.isBusy = false
                self.$bvModal.show("modal-choose-ps")
            });
      }
  },
  mounted (){
      
  }

}
</script>
