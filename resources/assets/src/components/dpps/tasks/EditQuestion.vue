<template>
    <b-modal id="modal-6"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_question" cancel-title="Закрыть" title="Редактировать типовой вопрос для собеседования по материалам портфолио">
         <b-form-group label-size="lg" description="" label="Текст вопроса">
            <b-form-textarea rows="5" v-model="new_object.name"></b-form-textarea>
         </b-form-group>
         <b-form-group label-size="lg" description="" label="Модельный ответ">
            <b-form-textarea rows="10" v-model="new_object.model_answer"></b-form-textarea>
         </b-form-group>      
    </b-modal>
</template>

<script>
export default {
  name: "edit-question",
  metaInfo: {
  title: "Редактировать типовой вопрос для собеседования по материалам портфолио"
  },
  props: {
    question_id: Number,
  },
  data (){
  return {
    new_object: {
        name: '',
        model_answer: '',
        subject_id: ''
    },
  }
  },  

  methods: {
    update_question(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('update_question', this.new_object) 
    },
  },
  mounted() {
      axios
      .post('/dpps/tasks/get_task_question',{
          question_id : this.question_id
      })
      .then((response) => this.new_object = response.data)
  }
}
</script>