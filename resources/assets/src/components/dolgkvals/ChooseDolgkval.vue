<template>
    <div>
        <b-button @click="get_data()">Выбрать...</b-button>
        <b-modal
        size="lg"
        id="modal-choose-dk"
        title="Выбор профессиональных стандартов"
        no-close-on-esc
        no-close-on-backdrop
        @ok="handle_ok"
        ok-title="Сохранить"
        cancel-title="Закрыть"
        >
        <b-button variant="primary" @click="create_dolgkval()">Добавить профессию </b-button>
        <hr class="container-m-nx border-light mt-2 mb-2">
        <v-client-table v-if="!isBusy" :data="items" :columns="columns" :options="options">
            <template slot="choose" slot-scope="props">
                <b-form-checkbox button-variant="outline-primary" :value="props.row.id" v-model="selected" name="multi_check" size="lg"></b-form-checkbox>
            </template>
            <template slot="actions" slot-scope="props">
                <div>
                <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_dolgkval(props.row)"><i class="ion ion-md-create"></i></b-btn>
                </div>
            </template>
        </v-client-table>
        <new-dolgkval  @add_dolgkval="add_dolgkval" :key="n_ps"></new-dolgkval>
        <edit-dolgkval :edit_item="edit_item"  @update_dolgkval="update_dolgkval" :key="edit_item.id"></edit-dolgkval>
        </b-modal>
    </div>    
</template>
<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>

<script>
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewDolgkval from './NewDolgkval'
import EditDolgkval from './EditDolgkval'

Vue.use(ClientTable)
export default {
    name: 'choose-dolgkval',
    metaInfo: {
        title: 'Выбор квалификационных требований по должностям'
    }, 
    components: {
      ClientTable,NewDolgkval,EditDolgkval
  },
  props: {
      dolgkvals: Array,
  },
  data (){
  return {
      isBusy: true,
      n_ps: 'dk',
      edit_item: {},
      items: [],
      selected: this.dolgkvals.map(x => x["id"]),
      columns: ['choose','name','actions'],
      options: {
           // pagination: { chunk: 5 },
            sortable: ['name', 'code'],
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                choose: 'Выбрать',
                name: 'Профессия',
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
                filterBy: "Filter by {column}",
                loading: 'Загрузка...',
                defaultOption: 'Select {column}',
                columns: 'Columns'
            }
        }
  }} ,
  methods: {
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.$emit('select_dolgkvals', this.selected)
      },
      create_dolgkval(){
          this.$bvModal.show("modal-new-dolgkval")
      },
      add_dolgkval (data){
          self = this;
            axios
            .post('/dolgkvals/add_dolgkval', data)
            .then(function (response) {
                self.items.push (response.data)
                self.n_ps += 1
            })
           
      },
      remove_dolgkval(elem)
      {
          var self = this
           this.$bvModal.msgBoxConfirm(
            "Действительно хотите удалить профессию «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post('/dolgkvals/remove_dolgkval', elem)
                .then(function (response) {
                    self.items = self.items.filter(item => item.id != response.data)
                })
            }
          });
      },
      edit_dolgkval (elem)
      {
          this.edit_item = elem;
          this.$nextTick(() => {
            this.$bvModal.show("modal-edit-dolgkval")
          });
          
      },
      update_dolgkval (data)
      { 
          self = this;
            axios
            .post('/dolgkvals/update_dolgkval', data)
            .then(function (response) {
                 var upd_item =  self.items.find(item => item.id == response.data.id)
                upd_item.code = response.data.code
                upd_item.name = response.data.name
                self.$bvModal.hide("modal-edit-dolgkval") 
            })
            .finally (()=>(self.edit_item={}))
      },
      get_data()
      {
          self = this;
        axios
            .post('/dolgkvals/get_dolgkvals')
            .then(function (response) {
                self.items = response.data
            })
            .finally (function (response) {
                self.isBusy = false
                self.$bvModal.show("modal-choose-dk")
            });
      }
  },
  mounted (){

  }

}
</script>