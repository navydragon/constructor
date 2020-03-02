<template>
<div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <b-card :title="header">
            <b-tabs content-class="mt-3" pills fill>
            <b-tab title="Знания" active>
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
                <new-question :knowledges="knowledges" v-on:add_question="add_question" :key="nq_key"></new-question>
                <edit-question v-if="show_edit_window" v-on:update_question="update_question" :key="question_to_edit" :question_to_edit="question_to_edit"></edit-question>
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
            <b-tab title="Умения">
                <p>В разработке</p>
            </b-tab>
            <b-tab title="Навыки">
                <p>В разработке</p>
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
        question_to_edit: 0,
        nq_key: 0,
        eq_key: 0,
        stage: {},
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
        kn_columns: ['text', 'type_name', 'edit'],
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
                edit: 'Управление'
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
          })

         
          
          
          //.finally((response) => ( ))
  }
}
</script>