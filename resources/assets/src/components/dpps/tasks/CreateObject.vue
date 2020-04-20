<template>
    <b-modal id="modal-2"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_subject" cancel-title="Закрыть" title="Добавить предмет оценки">
                  <b-form-group label="Выберите, что будет оценивать данное задание">
                  <b-form-select v-model="new_subject.type_id" :options="task_subject_types" value-field="id" text-field="name"></b-form-select>
                </b-form-group>
                <div v-if="new_subject.type_id==2">
                    <b-form-group label="Выберите навык, умения которого будут оцениваться">
                        <b-form-select v-model="new_subject.skills" :options="skills" value-field="id" text-field="name"></b-form-select>
                    </b-form-group>              
                    <b-form-group label="Выберите умения, которые будут оцениваться">
                        <b-form-checkbox-group
                            v-model="new_subject.abilities"
                            stacked
                            :options="abilities"
                            value-field="id"
                            text-field="name"
                            name="abilities"
                        ></b-form-checkbox-group>
                    </b-form-group>
                </div>
                <div v-if="new_subject.type_id>2">
                    <b-form-group label="Выберите навык(-и), которые будут оцениваться">
                        <b-form-select v-model="new_subject.skills" :options="skills" value-field="id" text-field="name"></b-form-select>
                    </b-form-group>
                </div>
                </b-modal>
</template>

<script>
export default {
  name: "create-object",
  metaInfo: {
  title: "Добавить предмет оценки"
  },
  props: {
    zuns: Array,
  },
  data (){
  return {
    new_subject: {},
    task_subject_types: {}
  }
  },  
  computed: {
      competences() {
          return this.zuns.filter(zun => zun.type == 'Компетенция')
      },
      skills() {
          return this.zuns.filter(zun => zun.type == 'Навык')
      },
      abilities() {
          if (this.new_subject.skills == null)
          {
              return this.zuns.filter(zun => zun.type == 'Умение') 
          }
          return this.zuns.filter(zun => (zun.type == 'Умение')&&(zun.pid == this.new_subject.skills))
      }
  },
  methods: {
    add_subject(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('add_subject', this.new_subject)
        
    },
  },
  mounted() {
      axios
            axios
            .post ('/dpps/get_task_subject_types')
            .then ((response) => (this.task_subject_types = response.data))
  }
}
</script>