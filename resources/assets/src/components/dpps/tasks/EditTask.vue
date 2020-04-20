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
                      <b-form-textarea rows="5" v-model="new_task.specification.source"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-size="lg" description="" label="Максимальное время выполнения (минут)">
                      <b-form-input v-model="new_task.specification.time" :type="'number'"></b-form-input>
                  </b-form-group>  
                </div>
                <div v-if="new_task.task_type_id==2">
                  <b-form-group label-size="lg" description="" label="Описание ситуации и постановка задачи">
                    <b-form-textarea rows="10" v-model="new_task.specification.description"></b-form-textarea>
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
                <div class="mb-2" v-html="formatted(new_task.specification.source)"></div>
                <h5>Максимальное время выполнения (минут)</h5>
                <div class="mb-2" v-html="new_task.specification.time"></div>
                <h5></h5>
              </div>
              <div v-if="new_task.task_type_id==2">
                <h5>Описание ситуации и постановка задачи</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.description)"></div>
                <h5>Требования к структуре и оформлению портфолио</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_structure_req)"></div>
                <h5>Требования к оформлению презентации</h5>
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_presentation_req)"></div>
                <h5>Порядок защиты портфолио</h5> 
                <div class="mb-2" v-html="formatted(new_task.specification.portfolio_procedure)"></div> 
                <h5>Максимальное время защиты (минут)</h5>
                <div class="mb-2" v-html="new_task.specification.time"></div>
              </div>
              </b-card>
            </b-tab>            
            <b-tab title="Критерии оценки" >
              <b-card>
                <b-button variant="primary" @click="create_subject">Добавить предмет оценки</b-button>
                <hr>
                <table class="table table-bordered">
                  <thead>
                    <tr><th>Предметы оценки</th><th>Объекты оценки</th></tr>
                  </thead>
                  <tbody>
                    <tr v-if="new_task.subjects.length==0"><td colspan="3"><em>Пока не добавлено ни одного предмета оценки. </em></td></tr>
                    <tr v-for="(subject,index) in new_task.subjects" :key="index">
                      <td>
                        <span><b-btn variant="outline-danger icon-btn" class="btn-sm" @click.prevent="remove_subject(subject)"><i class="ion ion-md-close"></i></b-btn> {{subject.name}} </span>
                        <span v-if="subject.subject_type_id==4"> (и входящие в навык умения)</span> 
                      </td>
                      <td>{{}}</td>
                    </tr>
                  </tbody>
                </table>
                <create-subject :zuns="zuns" @add_subject="add_subject" v-if="s_k!='sk'" :key="s_k"></create-subject>
              </b-card>
                

            </b-tab>
            <b-tab title="Материально-техническое обеспечение">
            </b-tab>
            </b-tabs>          
      </div>
</template>


<script>

import CreateSubject from './CreateSubject'

export default {
  name: "edit-task",
  metaInfo: {
  title: "Создание и редактирование задания"
  },
  components: { CreateSubject},
  data () {
    return {
        new_task: {
            subject_skills:[],
            specification:{}
        },
        s_k: "sk",
        isBusy: true,
        zuns: Array,
        task_subject_types: [],
        errors: []
        }
  },
  methods: {
    update_specification(bvModalEvt) {
        bvModalEvt.preventDefault()
        axios
        .post('/dpps/tasks/update_specification',{
            task_id : this.new_task.id,
            specification: this.new_task.specification
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
        this.$bvModal.msgBoxConfirm('Действительно хотите предмет оценки «'+subject.name+'»?')
        .then(value => {
            if (value === true) 
            {
              axios
              .post('/dpps/tasks/remove_subject', {
                  'id': subject.id
              })
              .then (function (response) {
               self.new_task.subjects = self.new_task.subjects.filter(el => el.id != subject.id)
              })
            }
            })
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
    }
  },
  
  mounted() {
      self = this
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (function (response){ 
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