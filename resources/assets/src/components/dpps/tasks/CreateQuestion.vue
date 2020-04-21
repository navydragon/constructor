<template>
    <b-modal id="modal-5"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_question" cancel-title="Закрыть" title="Добавить типовой вопрос для собеседования по материалам портфолио">

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
  name: "create-question",
  metaInfo: {
  title: "Добавить типовой вопрос для собеседования по материалам портфолио"
  },
  props: {
  },
  data (){
  return {
    new_object: {},
  }
  },  

  methods: {
    add_question(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('add_question', this.new_object) 
    },
  },
  mounted() {
  }
}
</script>