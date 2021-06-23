<template>
    <b-modal id="modal-3"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_theme" cancel-title="Закрыть" title="Добавить тему">
        <b-form-group label-size="lg" label="Название темы">
          <b-form-input v-model="new_theme.name"></b-form-input>
        </b-form-group>
         <b-form-group label-size="lg" description="" label="Описание объекта оценки">
            <b-form-textarea rows="5" v-model="new_object.name"></b-form-textarea>
         </b-form-group>
         <b-form-group label-size="lg" description="" label="Модельный ответ объекта оценки">
            <b-form-textarea rows="10" v-model="new_object.model_answer"></b-form-textarea>
         </b-form-group>      
    </b-modal>
</template>

<script>
export default {
  name: "create-theme",
  metaInfo: {
  title: "Добавить новую тему"
  },
  props: {
    knowledge: Object,
  },
  data (){
  return {
    new_theme: {},
  }
  },  

  methods: {
    add_the,e(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('add_theme', this.new_theme) 
    },
  },
  mounted() {
  }
}
</script>