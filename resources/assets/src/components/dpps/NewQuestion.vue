<template>
    <div>
      <div>
        <b-button block v-b-modal.modal-addquest variant="primary">Добавить вопрос</b-button>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-addquest" ok-title="Добавить вопрос" cancel-title="Закрыть" size="xl" title="Создание нового вопроса">
        <b-form-group
                :state="new_question.text_state"
                label="Текст вопроса"
                label-for="text-input"
                invalid-feedback="Необходимо ввести текст вопроса"
                label-cols-lg="2"
                label-size="lg"
                >
                <b-form-textarea
                    id="text-input"
                    placeholder="Введите текст вопроса"
                    v-model="new_question.text"
                ></b-form-textarea>
        </b-form-group>
        <b-form-group
                :state="new_question.type_state"
                label="Тип вопроса"
                label-for="type-input"
                invalid-feedback="Необходимо выбрать тип вопроса"
                label-cols-lg="2"
                label-size="lg"
                >
                <b-form-select id="type-input" v-model="new_question.type" :options="type_options">
                </b-form-select>
        </b-form-group>
        <div v-if="new_question.type.id==1">
            <p>Введите варианты ответов и укажите правильный</p>
            <b-table bordered :items="new_question.single_choice_answers" :fields="new_question.single_choice_fields">
                <template v-slot:table-colgroup="scope">
                    <col
                    v-for="field in scope.fields"
                    :key="field.key"
                    :style="{ width: field.key === 'is_right' ? '10%' : '90%' }"
                    >
                </template>
                <template v-slot:cell(text)="data">
                    <b-row>
                    <b-col sm="11">
                        <b-form-input  v-model="data.item.text" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                    </b-col>
                    <b-col sm="1">
                        <b-btn variant="outline-danger  icon-btn" class="btn" @click="remove_single_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
                    </b-col>                        
                    </b-row> 
                </template>
                <template v-slot:cell(is_right)="data">
                    <b-form-radio  button-variant="outline-primary" :value="data.item.id" v-model="new_question.single_choice_right" name="radio-size" size="lg"></b-form-radio>     
                </template>   
            </b-table>
            <b-button variant="primary" @click="add_single_choice_answer">Добавить ответ</b-button>
        </div>
        <div v-if="new_question.type.id==2">
            <p>Введите варианты ответов и укажите правильные</p>
            <b-table bordered :items="new_question.multi_choice_answers" :fields="new_question.multi_choice_fields">
                <template v-slot:table-colgroup="scope">
                    <col
                    v-for="field in scope.fields"
                    :key="field.key"
                    :style="{ width: field.key === 'is_right' ? '10%' : '90%' }"
                    >
                </template>
                <template v-slot:cell(text)="data">
                    <b-row>
                    <b-col sm="11">
                        <b-form-input  v-model="data.item.text" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                    </b-col>
                    <b-col sm="1">
                        <b-btn variant="outline-danger  icon-btn" class="btn" @click="remove_multi_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
                    </b-col>                        
                    </b-row> 
                </template>
                <template v-slot:cell(is_right)="data">
                    <b-form-checkbox button-variant="outline-primary" :value="data.item.id" v-model="new_question.multi_choice_right" name="multi_check" size="lg"></b-form-checkbox>     
                </template>   
            </b-table>
            <b-button variant="primary" @click="add_multi_choice_answer">Добавить ответ</b-button>
        </div>
        <div v-if="new_question.type.id==3">
            3
        </div>
        <div v-if="new_question.type.id==4">
            4
        </div>
        <div v-if="new_question.type.id==5">
            5
        </div>
        </b-modal>            
      </div>
    </div>
</template>
<script>
export default {
  name: "new-question",
  metaInfo: {
  title: "Добавить новый вопрос"
  },
  data () {
  return {
    new_question: {
      text: '',
      text_state: '',
      type: '',
      type_state: '',
      single_choice_answers: [
          {id: this.generate_id(), text: 'Ответ 1', is_right: false },
          {id: this.generate_id(), text: 'Ответ 2', is_right: false },
          {id: this.generate_id(), text: 'Ответ 3', is_right: false },
          {id: this.generate_id(), text: 'Ответ 4', is_right: false }
      ],
      multi_choice_answers: [
          {id: this.generate_id(), text: 'Ответ 1', is_right: false },
          {id: this.generate_id(), text: 'Ответ 2', is_right: false },
          {id: this.generate_id(), text: 'Ответ 3', is_right: false },
          {id: this.generate_id(), text: 'Ответ 4', is_right: false }
      ],
      single_choice_fields: [
        {
        key: 'is_right',
        label: 'Правильный?',
        },
        {
        key: 'text',
        label: 'Текст ответа',
        },
      ],
      multi_choice_fields: [
        {
        key: 'is_right',
        label: 'Правильный?',
        },
        {
        key: 'text',
        label: 'Текст ответа',
        },
      ],
      single_choice_right: '',
      multi_choice_right: []
    },
    type_options: [
        { value: {id:1, type_name: 'Выбор одного правильного ответа'}, text: 'Выбор одного правильного ответа' },
        { value: {id:2, type_name: 'Выбор нескольких правильных ответов'}, text: 'Выбор нескольких правильных ответов' },
        { value: {id:3, type_name: 'Вопрос с открытым ответом'}, text: 'Вопрос с открытым ответом' },
        { value: {id:4, type_name: 'Установление последовательности'}, text: 'Установление последовательности' },
        { value: {id:5, type_name: 'Установление соответствия'}, text: 'Установление соответствия' },
    ]      
  }
  },
  methods: {
    handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
    },
    generate_id () {
      return `f${(~~(Math.random() * 1e8)).toString(16)}`
    },
    add_single_choice_answer () {
      this.new_question.single_choice_answers.push(
          {id: this.generate_id(), text: 'Новый ответ', is_right: false }
      )    
    },
    add_multi_choice_answer () {
      this.new_question.multi_choice_answers.push(
          {id: this.generate_id(), text: 'Новый ответ', is_right: false }
      )    
    },
    remove_single_choice_answer (item) {
        if (this.new_question.single_choice_right == item.id)
        {
            this.new_question.single_choice_right = ''
        }
        var new_arr = this.new_question.single_choice_answers.filter(function(answer){
          return answer.id != item.id
        });
        this.new_question.single_choice_answers = new_arr
    },
    remove_multi_choice_answer (item) {
        var new_arr = this.new_question.multi_choice_answers.filter(function(answer){
          return answer.id != item.id
        });
        this.new_question.multi_choice_answers = new_arr
        new_arr = this.new_question.multi_choice_right.filter(function(answer){
          return answer != item.id
        });
        this.new_question.multi_choice_right = new_arr
    }
  }
}
</script>