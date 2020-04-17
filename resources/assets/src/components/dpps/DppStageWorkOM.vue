<template>
<div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <b-card :title="header">
            <b-tabs content-class="mt-3" pills fill>            
            <b-tab title="Знания" active>
                <b-button v-b-modal.modal-addquest variant="primary">Добавить вопрос</b-button>
                <b-dropdown id="dropdown-2" text="Экспорт" class="m-md-2">
                <b-dropdown-item :href="'/dpps/' +
                this.$route.params.dpp +
                '/export_om_questions/' +
                this.stage.om_version_id">Экспорт в Word вопросов</b-dropdown-item>
                </b-dropdown>
                <hr>
                <new-question :knowledges="knowledges" v-on:add_question="add_question" :key="nq_key"></new-question>
                <edit-question v-if="show_edit_window" v-on:update_question="update_question" :key="question_to_edit" :question_to_edit="question_to_edit"></edit-question>
                <h5>Фильтр вопросов по знаниям</h5>
                <model-select id="user-input" :options="knowledges"
                    v-model="knowledge"
                    placeholder="Выберите знание">
                </model-select>
                <hr class="container-m-nx border-light mt-2 mb-2">
                <v-client-table :data="knowledge.questions" :columns="kn_columns" :options="options">
                    <template slot="edit" slot-scope="props">
                        <div>
                        <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_question(props.row.id)"><i class="ion ion-md-create"></i></b-btn>
                        <b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_question(props.row)"><i class="ion ion-md-close"></i></b-btn>
                        </div>
                    </template>
                </v-client-table>
                <hr>
                <h5>Статистика вопросов</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Знание</th>
                            <th>Вопросов</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="kn in knowledges" class="" :key="kn.id">
                            <td>{{kn.name}}</td>
                            <td>{{kn.questions.length}}</td>
                        </tr>
                    </tbody>

                </table>

            </b-tab>
            <b-tab title="Умения/Навыки">
                <b-dropdown id="dropdown-3" text="Добавить" variant="primary" class="m-md-2">
                <b-dropdown-item @click="add_task(1)">Задание на применение умений и навыков в реальных или модельных условиях (Практическое задание)</b-dropdown-item>
                <b-dropdown-item @click="add_task(2)">Задание на оформление и защиту портфолио</b-dropdown-item>
                </b-dropdown>
                <b-dropdown id="dropdown-4" text="Экспорт" class="m-md-2">
                <b-dropdown-item :href="'/dpps/' +
                this.$route.params.dpp +
                '/export_om_questions/' +
                this.stage.om_version_id">Экспорт в Word заданий</b-dropdown-item>
                </b-dropdown>
                <hr>
                <v-client-table :data="tasks" :columns="task_columns" :options="task_options">
                    <template slot="name" slot-scope="props">
                        {{props.row.name}} ({{props.row.type_name}})
                    </template>
                    <template slot="edit" slot-scope="props">
                        <div>
                        <b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_task(props.row.id)"><i class="ion ion-md-create"></i></b-btn>
                        <b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_task(props.row)"><i class="ion ion-md-close"></i></b-btn>
                        </div>
                    </template>
                </v-client-table>
                <edit-task v-if="show_task_edit_window" :zuns="zuns" v-on:update_task="update_task" :key="'t'+task_to_edit" :task_to_edit="task_to_edit"></edit-task>
            </b-tab>
        </b-tabs>
    </b-card>
</div>
</template>

<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>
<script>
import { ModelSelect } from 'vue-search-select'
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewQuestion from './NewQuestion'
import EditQuestion from './EditQuestion'

Vue.use(ClientTable)


export default {
    name: "dpp_stage_work_om",
    metaInfo: {
        title: "Разработка ДПП - Оценочные материалы"
  },
  components: {
      ModelSelect, ClientTable,NewQuestion,EditQuestion
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  data () {
    return {
        show_edit_window: false,
        show_task_edit_window: false,
        question_to_edit: 0,
        task_to_edit: 0,
        nq_key: 0,
        eq_key: 0,
        stage: {},
        tasks: [],
        knowledge: {
            questions: []
        },
        knowledges: [
            {
                text: 'Знание 1',
                value: 1,
                questions: [
                    {
                        text: 'Вопрос 1',
                        type_name: 'Выбор вариантов ответа'
                    }
                ]
            },
            {
                text: 'Знание 2',
                value: 2,
                questions: []
            },
        ],
        zuns: [],
        kn_columns: ['text', 'type_name', 'edit'],
        task_columns: ['name', 'edit'],
        options: {
            pagination: { chunk: 5 },
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                text: 'Текст вопроса',
                type_name: 'Тип вопроса',
                edit: 'Действия'
            },
            texts: {
                count: "Showing {from} to {to} of {count} records|{count} records|Одна запись",
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
        },

        task_options: {
            pagination: { chunk: 5 },
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                text: 'Текст вопроса',
                type_name: 'Тип вопроса',
                edit: 'Действия'
            },
            texts: {
                count: "Showing {from} to {to} of {count} records|{count} records|Одна запись",
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
    }
  },
  methods: {
      add_question (questionData)
      {
          self = this;
          console.log(questionData)
            axios
            .post('/om_version/'+this.stage.om_version_id+'/add_question', {
                    'question_data': questionData,
                })
            .then(function (response) {
                let knowledge = self.knowledges.find(kn => kn.id === questionData.knowledge.id)
                knowledge.questions.push (
                {
                    "id": response.data,
                    "text": questionData.text,
                    "type_name": questionData.type.type_name
                }
                )
                self.nq_key += 1
            });
      },
      color_kn(knowledge)
      {
          if (knowledge.length < 4)
          {
              return 'danger'
          }else{
              return 'success'
          }
      },
      remove_question (question)
      {
        console.log(question)
        self = this;  
        this.$bvModal.msgBoxConfirm('Действительно хотите удалить вопрос «'+question.text+'»?')
          .then(value => {
            if (value === true) {
              axios
              .post('/questions/delete', {
                  'id': question.id
              })
              .then (function (response) {
                console.log (self.knowledges)
                for (let i = 0; i<self.knowledges.length;i++)
                {
                    var quests = self.knowledges[i].questions;
                    //console.log(quests);
                    for (let j = 0; j < quests.length;j++)
                    {
                        //console.log(quest.id+" - "+question.id);
                        if (quests[j].id == question.id)
                        {
                            //console.log('found')
                            var index =  self.knowledges[i].questions.indexOf(quests[j])
                            self.knowledges[i].questions.splice(index, 1)
                        }
                    }
                }
                });
            }
          })
          .catch(err => {
            // An error occurred
          }) 
      },
      edit_question(question)
      {        
            this.question_to_edit = question
            this.show_edit_window = true
            this.$nextTick(() => {
            this.$bvModal.show("modal-editquestion")
            })
      },
      update_question (questionData,question_id)
      {
          self = this;
          console.log(questionData)
            axios
            .post('/om_version/'+this.stage.om_version_id+'/update_question', {
                    'question_data': questionData,
                    'question_id': question_id,
                })
            .then(function (response) {
 
                let knowledge = self.knowledges.find(kn => kn.id === response.data.knowledge_id)
                let question = knowledge.questions.find(qu => qu.id == response.data.id)
                question.text = response.data.text
               console.log(response)
               self.$bvModal.hide("modal-editquestion")
               self.show_edit_window = false
            });
      },
      add_task(type)
      {
          self = this
          axios
            .post('/dpps/add_task', {
                'om_version_id': this.stage.om_version_id,
                'type': type
            })
            .then((response) => (this.$router.push('/my_dpps/'+this.$route.params.dpp+'/stages/'+this.stage.id+'/work_om/tasks/'+response.data)))
      },
      edit_task(task_id){
        this.$router.push('/my_dpps/'+this.$route.params.dpp+'/stages/'+this.stage.id+'/work_om/tasks/'+task_id)
        /*
        this.task_to_edit = task_id
            this.show_task_edit_window = true
                this.$nextTick(() => {
                this.$bvModal.show("modal-edittask")
                })
        */
      },
      update_task(data)
      {
          
      },
  },
  
  mounted() {
      var self = this
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (function (response){ 
              axios
              .get('/dpps/'+self.stage.dpp_id+'/get_knowledges_to_ov/'+ self.stage.om_version_id)
              .then(response => (self.knowledges = response.data))

              axios
              .get('/dpps/get_tasks/'+ self.stage.om_version_id)
              .then(response => (self.tasks = response.data))

              axios
                .get(
                "/dpps/" +
                self.$route.params.dpp +
                "/get_zuns_to_om/" +
                self.stage.zun_version_id
                )
                .then(response => (self.zuns = response.data));
                })

  }
}
</script>