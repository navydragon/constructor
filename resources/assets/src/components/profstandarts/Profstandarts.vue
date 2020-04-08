<template>
    <b-card :title="'Профессиональные стандарты'">
        <b-button variant="primary" @click="create_profstandart()">Добавить профстандарт</b-button>
        <hr class="container-m-nx border-light mt-2 mb-2">
        <v-client-table v-if="!isBusy" :data="items" :columns="columns" :options="options">
            <template slot="actions" slot-scope="props">
                <div>
                <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_profstandart(props.row)"><i class="ion ion-md-create"></i></b-btn>
                <b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_profstandart(props.row)"><i class="ion ion-md-close"></i></b-btn>
                </div>
            </template>
        </v-client-table>
        <new-profstandart  @add_profstandart="add_profstandart" :key="n_ps"></new-profstandart>
        <edit-profstandart :edit_item="edit_item"  @update_profstandart="update_profstandart" :key="edit_item.id"></edit-profstandart>
    </b-card>    
</template>
<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>

<script>
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewProfstandart from './NewProfstandart'
import EditProfstandart from './EditProfstandart'
Vue.use(ClientTable)
export default {
    name: 'profstandarts',
    metaInfo: {
        title: 'Профессиональные стандарты'
    }, 
    components: {
      ClientTable,NewProfstandart,EditProfstandart
  },
  data (){
  return {
      isBusy: true,
      n_ps: 'ps',
      edit_item: {},
      items: [],
      columns: ['code','name','actions'],
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
      }
  },
  mounted (){
      self = this;
      axios
        .post('/profstandarts/get_profstandarts')
        .then(function (response) {
            self.items = response.data
        })
        .finally (()=>(self.isBusy=false));
  }

}
</script>