<template>
    <b-modal id="modal-3"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_object" cancel-title="Закрыть" title="Добавить объект оценки">
        <b-form-group label-size="lg" label="Выберите предмет оценки">
          <b-form-select v-model="new_object.subject_id" :options="subjects" value-field="id" text-field="name"></b-form-select>
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
  name: "create-object",
  metaInfo: {
  title: "Добавить объект оценки"
  },
  props: {
    subjects: Array,
  },
  data (){
  return {
    new_object: {},
  }
  },  

  methods: {
    add_object(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('add_object', this.new_object) 
    },
  },
  mounted() {
  }
}
</script>