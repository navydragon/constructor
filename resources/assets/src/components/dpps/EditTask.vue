<template>
    <div>
      <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-edittask" ok-title="Сохранить" cancel-title="Закрыть" size="xl" :title="'Создание и редактирование задания «'+new_task.name+'»('+new_task.type_name+')'">
            <b-tabs content-class="mt-3">            
            <b-tab title="Предмет оценки" active>
                <b-form-group label="Выберите, что будет оценивать данное задание">
                  <b-form-select v-model="new_task.task_subject_type_id" :options="task_subject_types" value-field="id" text-field="name"></b-form-select>
                </b-form-group>
                <div v-if="new_task.task_subject_type_id==2">
                    <b-form-group label="Выберите навык(-и), умения которого(-ых) будут оцениваться">
                        <b-form-select v-model="new_task.subject_skills" :options="skills" value-field="id" text-field="name"></b-form-select>
                    </b-form-group>              
                    <b-form-group label="Выберите умения, которые будут оцениваться">
                        <b-form-checkbox-group
                            v-model="new_task.subject_abilities"
                            stacked
                            :options="abilities"
                            value-field="id"
                            text-field="name"
                            name="abilities"
                        ></b-form-checkbox-group>
                    </b-form-group>
                </div>
                <div v-if="new_task.task_subject_type_id>2">
                    <b-form-group label="Выберите навык(-и), которые будут оцениваться">
                        <b-form-select v-model="new_task.subject_skills" :options="skills" value-field="id" text-field="name"></b-form-select>
                    </b-form-group>
                </div>

            </b-tab>
            <b-tab title="Задание">
            </b-tab>
            <b-tab title="Объекты оценки">
            </b-tab>
            <b-tab title="МТО">
            </b-tab>
            </b-tabs>
        </b-modal>            
      </div>
    </div>
</template>


<script>


export default {
  name: "edit-task",
  metaInfo: {
  title: "Создание и редактирование задания"
  },
  props: {
      task_to_edit: Number,
      zuns: Array,
  },
  data () {
    return {
        new_task: {
            subject_skills:[]
        },
        task_subject_types: [],
        errors: []
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
          if (this.new_task.subject_skills.length == 0)
          {
              return this.zuns.filter(zun => zun.type == 'Умение') 
          }
          return this.zuns.filter(zun => (zun.type == 'Умение')&&(zun.pid == this.new_task.subject_skills))
      }
  },
  methods: {
    handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.errors = []
        
       if (this.errors.length > 0)
       {
           this.show_errors = true
       }else{
           console.log("OK")
           this.$emit('update_task', this.new_task)
       }
    },
    generate_id () {
      return `f${(~~(Math.random() * 1e8)).toString(16)}`
    },
  },
  
  mounted() {
      self = this
      axios
      .post ('/dpps/get_task_subject_types')
      .then ((response) => (this.task_subject_types = response.data))

      axios
        .get('/dpps/get_task_data/'+ this.task_to_edit)
        .then(function (response){ 
            self.new_task = response.data
          })

      


  }
}
</script>