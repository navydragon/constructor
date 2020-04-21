<template>
    <b-modal id="modal-4"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_object" cancel-title="Закрыть" title="Редактировать объект оценки">
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
  name: "edit-object",
  metaInfo: {
  title: "Редактировать объект оценки"
  },
  props: {
    subjects: Array,
    object_id: Number,
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
    update_object(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('update_object', this.new_object) 
    },
  },
  mounted() {
      axios
      .post('/dpps/tasks/get_task_object',{
          object_id : this.object_id
      })
      .then((response) => this.new_object = response.data)
  }
}
</script>