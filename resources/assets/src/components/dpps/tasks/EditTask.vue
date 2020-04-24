<template>
      <div v-if="!isBusy">
            <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
            <h4>{{new_task.name}} ({{new_task.type_name}})</h4>
            <b-tabs content-class="mt-3">
            <b-tab title="Описание" active>
              <b-card>
              <b-button variant="primary" v-b-modal.modal-1>Редактировать</b-button>
              <hr>
              <b-modal id="modal-1"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_specification" cancel-title="Закрыть" title="Редактирование описания">
                <div v-if="new_task.task_type_id==1">
                  <b-form-group label-size="lg" description="" label="Описание ситуации и постановка задачи">
                    <b-form-textarea rows="10" v-model="new_task.specification.description"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Условия выполнения задания">
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Место выполнения">
                      <b-form-textarea v-model="new_task.specification.place"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Источники информации для выполнения">
                      <nsi-choose @add_nsi="add_nsi" @change_nsi="change_nsi" :mode="'work'" :selected="new_task.nsis" :ish_version_id="stage.ish_version_id"></nsi-choose>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Максимальное время выполнения (минут)">
                      <b-form-input v-model="new_task.specification.time" :type="'number'"></b-form-input>
                  </b-form-group>  
                </div>
                <div v-if="new_task.task_type_id==2">
                  <b-form-group label-size="lg" description="" label="Описание ситуации и постановка задачи">
                    <b-form-textarea rows="10" v-model="new_task.specification.description"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Источники информации для выполнения">
                      <nsi-choose @add_nsi="add_nsi" @change_nsi="change_nsi" :mode="'work'" :selected="new_task.nsis" :ish_version_id="stage.ish_version_id"></nsi-choose>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Требования к структуре и оформлению портфолио">
                      <b-form-textarea rows="10" v-model="new_task.specification.portfolio_structure_req"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Требования к оформлению презентации">
                      <b-form-textarea rows="10" v-model="new_task.specification.portfolio_presentation_req"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Порядок защиты портфолио">
                      <b-form-textarea rows="10" v-model="new_task.specification.portfolio_procedure"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Максимальное время защиты (минут)">
                      <b-form-input v-model="new_task.specification.time" :type="'number'"></b-form-input>
                  </b-form-group>  
                </div>
              </b-modal>
              <div v-if="new_task.task_type_id==1">
                <h5>Описание ситуации и постановка задачи</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.description)"></div>
                <h5>Условия выполнения задания</h5>
                <h5>Место выполнения</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.place)"></div>
                <h5>Источники информации для выполнения</h5>
                <div class="mb-2" v-if="new_task.nsis.length==0">Не заполнено</div>
                <ul>
                  <li v-for="nsi in new_task.nsis" :key="'nsi'+nsi">{{search_nsi(nsi)}}</li>
                </ul>
                <h5>Максимальное время выполнения (минут)</h5>
                <div class="mb-2" v-if="new_task.specification.time==null">Не заполнено</div>
                <div class="mb-2" v-html="new_task.specification.time"></div>
                <h5></h5>
              </div>
              <div v-if="new_task.task_type_id==2">
                <h5>Описание ситуации и постановка задачи</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.description)"></div>
                <h5>Источники информации для выполнения</h5>
                <div class="mb-2" v-if="new_task.nsis.length==0">Не заполнено</div>
                <ul>
                  <li v-for="nsi in new_task.nsis" :key="'nsi'+nsi">{{search_nsi(nsi)}}</li>
                </ul>
                <h5>Требования к структуре и оформлению портфолио</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_structure_req)"></div>
                <h5>Требования к оформлению презентации</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_presentation_req)"></div>
                <h5>Порядок защиты портфолио</h5> 
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_procedure)"></div> 
                <h5>Максимальное время защиты (минут)</h5>
                <div class="mb-2" v-if="new_task.specification.time==null">Не заполнено</div>
                <div class="mb-2" v-html="new_task.specification.time"></div>
              </div>
              </b-card>
            </b-tab>            
            <b-tab title="Критерии оценки" >
              <b-card>
                <b-dropdown id="dropdown-2" variant="primary" text="Добавить" class="m-md-2">
                <b-dropdown-item @click="create_subject">Добавить предмет оценки</b-dropdown-item>
                <b-dropdown-item @click="create_object">Добавить объект оценки</b-dropdown-item>
                <b-dropdown-item v-if="new_task.task_type_id==2" @click="create_question">Добавить типовое вопрос для собеседования по материалам портфолио</b-dropdown-item>
                </b-dropdown>
                <hr>
                <h4>Предметы и объекты оценки</h4>
                <table class="table table-bordered">
                  <thead>
                    <tr><th width="50%">Предмет оценки</th><th>Объекты оценки</th></tr>
                  </thead>
                  <tbody>
                    <tr v-if="new_task.subjects.length==0"><td colspan="3"><em>Пока не добавлено ни одного предмета оценки. </em></td></tr>
                    <tr v-for="(subject,index) in new_task.subjects" :key="index">
                      <td>
                        <span><b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_subject(subject)"><i class="ion ion-md-close"></i></b-btn> {{subject.name}} </span> 
                      </td>
                      <td>
                        <div v-for="obj in objects(subject.id)" :key="'o'+obj.id">
                          <span><b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_object(obj)"><i class="ion ion-md-close"></i></b-btn></span> 
                          <span><b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_object(obj.id)"><i class="ion ion-md-create"></i></b-btn></span>
                          {{obj.name}}
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <h4>Модельные ответы</h4>
                <table class="table table-bordered">
                  <thead>
                    <tr><th width="50%">Объект оценки</th><th>Модельный ответ</th></tr>
                  </thead>
                  <tbody>
                    <tr v-if="new_task.objects.length==0"><td colspan="2"><em>Пока не добавлено ни одного объекта оценки. </em></td></tr>
                    <tr v-for="object in new_task.objects" :key="'o_'+object.id">
                      <td v-html="formatted(object.name)"></td>
                      <td v-html="formatted(object.model_answer)"></td>
                    </tr>
                  </tbody>
                </table>
                <div v-if="new_task.task_type_id==2">
                <h4>Типовые вопросы для собеседования по материалам портфолио</h4>
                <table class="table table-bordered">
                  <thead>
                    <tr><th width="30%">Вопрос</th><th width="30%">Модельный ответ</th><th>Действия</th></tr>
                  </thead>
                  <tbody>
                    <tr v-if="new_task.questions.length==0"><td colspan="2"><em>Пока не добавлено ни одного типового вопроса. </em></td></tr>
                    <tr v-for="question in new_task.questions" :key="'q_'+question.id">
                      <td v-html="formatted(question.name)"></td>
                      <td v-html="formatted(question.model_answer)"></td>
                      <td>
                        <span><b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_question(question)"><i class="ion ion-md-close"></i></b-btn></span> 
                        <span><b-btn variant="outline-info icon-btn" class="btn-sm" @click.prevent="edit_question(question.id)"><i class="ion ion-md-create"></i></b-btn></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                </div>
                <create-subject :zuns="zuns" @add_subject="add_subject" v-if="s_k!='sk'" :key="s_k"></create-subject>
                <create-object :subjects="new_task.subjects" @add_object="add_object" v-if="o_k!='ok'" :key="o_k"></create-object>
                <edit-object :subjects="new_task.subjects" :object_id="o_e_k" @update_object="update_object" v-if="o_e_k!='oek'" :key="o_e_k"></edit-object>
                <create-question  @add_question="add_question" v-if="q_k!='qk'" :key="q_k"></create-question>
                <edit-question :question_id="q_e_k" @update_question="update_question" v-if="q_e_k!='qek'" :key="q_e_k"></edit-question>
                
              </b-card>
                

            </b-tab>
            <b-tab title="Материально-техническое обеспечение">
              <mtos :dpp_id="this.$route.params.dpp"></mtos>
            </b-tab>
            </b-tabs>          
      </div>
</template>


<script>

import CreateSubject from './CreateSubject'
import CreateObject from './CreateObject'
import EditObject from './EditObject'
import CreateQuestion from './CreateQuestion'
import EditQuestion from './EditQuestion'
import NsiChoose from '@/components/nsis/NsiChoose'
import Mtos from '@/components/mtos/Mtos'

export default {
  name: "edit-task",
  metaInfo: {
  title: "Создание и редактирование задания"
  },
  components: { CreateSubject,CreateObject,EditObject,CreateQuestion,EditQuestion,NsiChoose,Mtos},
  data () {
    return {
        new_task: {
            subject_skills:[],
            specification:{
              description: '',
              place: '',
              source: '',
              time: '',
              portfolio_structure_req: '',
              portfolio_presentation_req: '',
              portfolio_procedure: '',
            },
            subjects:[],
            objects: [],
            questions: [],
        },
        s_k: "sk",
        o_k: "ok",
        o_e_k: "oek",
        q_k: "qk",
        q_e_k:"qek",
        isBusy: true,
        zuns: Array,
        task_subject_types: [],
        errors: [],
        stage: {},
        nsis: [],
        }
  },
  methods: {
    update_specification(bvModalEvt) {
        bvModalEvt.preventDefault()
        axios
        .post('/dpps/tasks/update_specification',{
            task_id : this.new_task.id,
            specification: this.new_task.specification,
            nsis: this.new_task.nsis,
        })
        .then( (response) => (this.new_task.specification = response.data))
        .finally(() =>(this.$bvModal.hide('modal-1')))
    },
    create_subject() {
      this.s_k += 1
      this.$nextTick(() => {
            this.$bvModal.show('modal-2')
      })
      
    },
    add_subject(new_subject)
    {
      self = this
      axios
        .post('/dpps/tasks/add_subject',{
            task_id: this.new_task.id,
            subject: new_subject
        })
        .then( function(response) {
          response.data.forEach(element => {
            self.new_task.subjects.push(element)
          })})
        .finally(() =>(this.$bvModal.hide('modal-2')))
    },
    remove_subject (subject)
      {
        self = this;  
        this.$bvModal.msgBoxConfirm('Действительно хотите удалить предмет оценки «'+subject.name+'»?')
        .then(value => {
            if (value === true) 
            {
              axios
              .post('/dpps/tasks/remove_subject', {
                  'id': subject.id
              })
              .then( function (response) {
                self.new_task.objects = self.new_task.objects.filter(el => el.subject_id != subject.id)
              })
              .finally (function (response) {
               self.new_task.subjects = self.new_task.subjects.filter(el => el.id != subject.id)
              })
            }
            })
      },
    create_object() {
      this.o_k += 1
      this.$nextTick(() => {
            this.$bvModal.show('modal-3')
      }) 
    },
    add_object(new_object)
    {
      self = this
      axios
        .post('/dpps/tasks/add_object',{
            task_id: this.new_task.id,
            object: new_object
        })
        .then( function(response) {
            self.new_task.objects.push(response.data)
        })
        .finally(() =>(this.$bvModal.hide('modal-3')))
    },
    edit_object (id)
    {
      this.o_e_k = id
      this.$nextTick(() => {
            this.$bvModal.show('modal-4')
      }) 
    },
    update_object (new_object)
    {
      self = this
      axios
        .post('/dpps/tasks/update_object',{
            object: new_object
        })
        .then( function(response) {
          self.new_task.objects = self.new_task.objects.filter(obj => obj.id != response.data.id)
          self.new_task.objects.push(response.data);

        })
        .finally(() =>(this.$bvModal.hide('modal-4')))
    },
    remove_object (object)
    {
      self = this;  
      this.$bvModal.msgBoxConfirm('Действительно хотите удалить объект оценки «'+object.name+'»?')
      .then(value => {
          if (value === true) 
          {
            axios
            .post('/dpps/tasks/remove_object', {
                'id': object.id
            })
            .then (function (response) {
              self.new_task.objects = self.new_task.objects.filter(el => el.id != object.id)
            })
          }
          })
    },
    objects(subject_id)
    {
      return this.new_task.objects.filter(obj => obj.subject_id == subject_id)
    },
    create_question() {
      this.q_k += 1
      this.$nextTick(() => {
            this.$bvModal.show('modal-5')
      }) 
    },
    add_question(new_object) {
      self = this
      axios
        .post('/dpps/tasks/add_task_question',{
            task_id: this.new_task.id,
            question: new_object
        })
        .then( function(response) {
            self.new_task.questions.push(response.data)
        })
        .finally(() =>(this.$bvModal.hide('modal-5')))
    },
    remove_question (question)
    {
      self = this;  
      this.$bvModal.msgBoxConfirm('Действительно хотите удалить вопрос «'+question.name+'»?')
      .then(value => {
          if (value === true) 
          {
            axios
            .post('/dpps/tasks/remove_task_question', {
                'id': question.id
            })
            .then (function (response) {
              self.new_task.questions = self.new_task.questions.filter(el => el.id != question.id)
            })
          }
          })
    },
    edit_question (id)
    {
      this.q_e_k = id
      this.$nextTick(() => {
            this.$bvModal.show('modal-6')
      }) 
    },
    update_question(new_object)
    {
      self = this
      axios
        .post('/dpps/tasks/update_task_question',{
            question: new_object
        })
        .then( function(response) {
          self.new_task.questions = self.new_task.questions.filter(obj => obj.id != response.data.id)
          self.new_task.questions.push(response.data);

        })
        .finally(() =>(this.$bvModal.hide('modal-6')))
    },
    change_nsi (data) {
       this.new_task.nsis = data.nsi_data
    },
    add_nsi (data) {
      this.nsis.push(data.nsi_data);
    },
    generate_id () {
      return `f${(~~(Math.random() * 1e8)).toString(16)}`
    },
    formatted(text)
    {
      if (text!=null)
      {
      return text.replace(/\r?\n/g,'<br/>');
      }else{
        return "Не заполнено"
      }
    },
    search_nsi(nsi_id)
    {
      var nsi = this.nsis.find(el => el.id == nsi_id)
      if (nsi)
      {return nsi.name }
      
    }
  },
  
  mounted() {
      self = this

      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (function (response){ 
            axios
            .get('/nsis/'+self.stage.ish_version_id)
            .then((response) => (self.nsis = response.data))

            axios
            .get("/dpps/"+self.$route.params.dpp + "/get_zuns_to_om/" + self.stage.zun_version_id)
            .then(response => (self.zuns = response.data));
            })

            axios
            .get('/dpps/get_task_data/'+ this.$route.params.task)
            .then(function (response){ 
            console.log(response.data)
            self.new_task = response.data
            self.isBusy = false
            })

  }
}
</script>