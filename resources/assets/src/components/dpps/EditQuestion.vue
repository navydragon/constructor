<template>
    <div>
      <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-editquestion" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Редактирование вопроса">
        <b-form-group
                :state="new_question.text_state"
                label="Выберите знание"
                label-for="knowledge-input"
                invalid-feedback="Знание"
                label-cols-lg="2"
                label-size="lg"
        >
            <p>{{new_question.knowledge.name}}</p>
        </b-form-group>
        <b-form-group
                :state="new_question.type_state"
                label="Тип вопроса"
                label-for="type-input"
                invalid-feedback="Необходимо выбрать тип вопроса"
                label-cols-lg="2"
                label-size="lg"
                >
            <p>{{new_question.type.name}}</p>
        </b-form-group>
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
                        <b-btn :disabled="new_question.single_choice_answers.length == 1" variant="outline-danger  icon-btn" class="btn" @click="remove_single_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
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
                        <b-btn :disabled="new_question.multi_choice_answers.length == 1" variant="outline-danger  icon-btn" class="btn" @click="remove_multi_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
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
            <p>Введите допустимые (правильные) варианты ответов</p>
            <b-table bordered :items="new_question.free_choice_answers" :fields="new_question.free_choice_fields">
                <template v-slot:cell(text)="data">
                    <b-row>
                    <b-col sm="11">
                        <b-form-input  v-model="data.item.text" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                    </b-col>
                    <b-col sm="1">
                        <b-btn :disabled="new_question.free_choice_answers.length == 1" variant="outline-danger  icon-btn" class="btn" @click="remove_free_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
                    </b-col>                        
                    </b-row> 
                </template>  
            </b-table>
            <b-button variant="primary" @click="add_free_choice_answer">Добавить ответ</b-button>
        </div>
        <div v-if="new_question.type.id==4">
            <p>Добавьте ответы и перетаскивайте их (используя иконоку <i class="ion ion-ios-move m-r-1"></i>), установив правильную последовательность ответов.</p>
            <draggable v-model="new_question.sequence_choice_answers" v-bind="{animation: 150, handle: '.ion'}" tag="div" class="sortable-example">
                <div v-for="item in new_question.sequence_choice_answers" :key="item.id" style="margin-bottom:10px">
                    <b-row>
                    <b-col sm="1">
                        <i class="ion ion-ios-move m-r-1" style="font-size: 35px;"></i> &nbsp;
                    </b-col>
                    <b-col sm="10">
                    <b-form-input  v-model="item.text" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                    </b-col>
                    <b-col sm="1">
                        <b-btn :disabled="new_question.sequence_choice_answers.length <= 2" variant="outline-danger  icon-btn" class="btn" @click="remove_sequence_choice_answer(item)">X</b-btn>
                    </b-col>
                    </b-row>
                </div>
            </draggable>
            <b-button variant="primary" @click="add_sequence_choice_answer">Добавить ответ</b-button>
        </div>
        <div v-if="new_question.type.id==5">
            <b-table bordered :items="new_question.accordance_choice_answers" :fields="new_question.accordance_choice_fields">
                <template v-slot:table-colgroup="scope">
                    <col
                    v-for="field in scope.fields"
                    :key="field.key"
                    :style="{ width: field.key === 'is_right' ? '10%' : '45%' }"
                    >
                </template>
                <template v-slot:cell(accord1)="data">
                    <b-form-input  v-model="data.item.accord1" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                </template>
                <template v-slot:cell(accord2)="data">
                    <b-form-input  v-model="data.item.accord2" placeholder="Введите текст ответа"></b-form-input> &nbsp;
                </template>
                <template v-slot:cell(is_right)="data">
                    <b-btn :disabled="new_question.accordance_choice_answers.length < 2" variant="outline-danger  icon-btn" class="btn" @click="remove_accordance_choice_answer(data.item)"><i class="ion ion-md-close"></i></b-btn>     
                </template>   
            </b-table>
            <b-button variant="primary" @click="add_accordance_choice_answer">Добавить ответ</b-button>
        </div>
        <div v-if="show_errors">
            <b-alert show variant="danger"><strong>Обнаружены ошибки!</strong>
            <ul>
                <li v-for="(error, index) in errors" :key="index">{{error}}</li>
            </ul>
            </b-alert>
        </div>
        </b-modal>            
      </div>
    </div>
</template>

<style src="vue-dragula/styles/dragula.css"></style>
<script>

import draggable from 'vuedraggable/src/vuedraggable'
import { ModelSelect } from 'vue-search-select'

export default {
  name: "edit-question",
  metaInfo: {
  title: "Отредактировать вопрос"
  },
  components: {
    draggable, ModelSelect
  },
  props: {
      knowledges: Array,
      question_to_edit: Number,
  },
  data () {
    return {
        show_errors: false,
        errors: [],
        new_question: {
        knowledge: {},
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
        free_choice_answers: [
            {id: this.generate_id(), text: 'Ответ 1', is_right: true },
        ],
        multi_choice_answers: [
            {id: this.generate_id(), text: 'Ответ 1', is_right: false },
            {id: this.generate_id(), text: 'Ответ 2', is_right: false },
            {id: this.generate_id(), text: 'Ответ 3', is_right: false },
            {id: this.generate_id(), text: 'Ответ 4', is_right: false }
        ],
        sequence_choice_answers: [
            {id: this.generate_id(), text: 'Ответ 1', is_right: true },
            {id: this.generate_id(), text: 'Ответ 2', is_right: true },
            {id: this.generate_id(), text: 'Ответ 3', is_right: true },
            {id: this.generate_id(), text: 'Ответ 4', is_right: true }
        ],
        accordance_choice_answers: [
            {id: this.generate_id(), accord1: 'Элемент 1', accord2: 'Соответствующий элемент 1', is_right: true },
            {id: this.generate_id(), accord1: 'Элемент 2', accord2: 'Соответствующий элемент 2', is_right: true },
            {id: this.generate_id(), accord1: 'Элемент 3', accord2: 'Соответствующий элемент 3', is_right: true },
            {id: this.generate_id(), accord1: 'Элемент 4', accord2: 'Соответствующий элемент 4', is_right: true }
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
        free_choice_fields: [
            {
            key: 'text',
            label: 'Текст правильного ответа',
            },
        ],
        accordance_choice_fields: [
            {
            key: 'accord1',
            label: 'Элемент',
            },
            {
            key: 'accord2',
            label: 'Соответствующий элемент',
            },
            {
            key: 'is_right',
            label: 'Удаление',
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
        this.errors = []
        this.show_errors = false
        if (this.new_question.text.length < 5) { this.errors.push("Не введен текст вопроса")}
        else {
        switch (this.new_question.type.id)
        {
            case 1:
                if (this.new_question.single_choice_right == "")
                { this.errors.push("Не выбран правильный вариант ответа")}
                for (var i = 0; i < this.new_question.single_choice_answers.length; i++)
                {
                    if (this.new_question.single_choice_answers[i].text == '')
                    {
                        this.errors.push("Не введен один из вариантов ответа")
                    }
                }
            break;
            case 2:
                if (this.new_question.multi_choice_right.length < 2)
                { this.errors.push("Для данного типа вопроса необходимо выбрать более одного правильного варианта ответа")}
                for (var i = 0; i < this.new_question.multi_choice_answers.length; i++)
                {
                    if (this.new_question.multi_choice_answers[i].text == '')
                    {
                        this.errors.push("Не введен вариантов ответа")
                    }
                }
            break;
            case 3:
                for (var i = 0; i < this.new_question.free_choice_answers.length; i++)
                {
                    if (this.new_question.free_choice_answers[i].text == '')
                    {
                        this.errors.push("Не введен вариантов ответа")
                    }
                }
            break;
            case 4:
                for (var i = 0; i < this.new_question.sequence_choice_answers.length; i++)
                {
                    if (this.new_question.sequence_choice_answers[i].text == '')
                    {
                        this.errors.push("Не введен вариантов ответа")
                    }
                }
            break;
            case 5:
                for (var i = 0; i < this.new_question.accordance_choice_answers.length; i++)
                {
                    if (this.new_question.accordance_choice_answers[i].accord1 == '' || this.new_question.accordance_choice_answers[i].accord2 == '')
                    {
                        this.errors.push("Не введен вариантов ответа")
                    }
                }
            break;
        }
       }
       if (this.errors.length > 0)
       {
           this.show_errors = true
       }else{
           console.log("OK")
           this.$emit('update_question', this.new_question,this.question_to_edit)
       }
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
    add_free_choice_answer () {
      this.new_question.free_choice_answers.push(
          {id: this.generate_id(), text: 'Новый ответ', is_right: true }
      )    
    },
    add_sequence_choice_answer() {
        this.new_question.sequence_choice_answers.push(
          {id: this.generate_id(), text: 'Новый ответ', is_right: true }
      )  
    },
    add_accordance_choice_answer() {
        this.new_question.accordance_choice_answers.push(
          {id: this.generate_id(), accord1: 'Новый элемент',  accord2: 'Новый соответствующий элемент', is_right: true }
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
    },
    remove_free_choice_answer (item) {
        var new_arr = this.new_question.free_choice_answers.filter(function(answer){
          return answer.id != item.id
        });
        this.new_question.free_choice_answers = new_arr
    },
    remove_sequence_choice_answer (item) {
        var new_arr = this.new_question.sequence_choice_answers.filter(function(answer){
          return answer.id != item.id
        });
        this.new_question.sequence_choice_answers = new_arr
    },
    remove_accordance_choice_answer (item) {
        var new_arr = this.new_question.accordance_choice_answers.filter(function(answer){
          return answer.id != item.id
        });
        this.new_question.accordance_choice_answers = new_arr
    },
  },
  
  mounted() {
      self = this
      axios
        .get('/dpps/get_question_data/'+ this.question_to_edit)
        .then(function (response){ 
              self.new_question.knowledge = response.data.knowledge
              self.new_question.type = response.data.type
              self.new_question.text = response.data.text
              console.log(response.data.ans_arr)
              switch (response.data.type.id) {
                  case 1: 
                  self.new_question.single_choice_answers = response.data.ans_arr 
                  self.new_question.single_choice_right = response.data.single_choice_right 
                  break;
                  case 2: 
                  self.new_question.multi_choice_answers = response.data.ans_arr 
                  self.new_question.multi_choice_right = response.data.multi_choice_right
                  break;
                  case 3: 
                  self.new_question.free_choice_answers = response.data.ans_arr 
                  break;
                  case 4: 
                  self.new_question.sequence_choice_answers = response.data.ans_arr 
                  break;
                  case 5: 
                  self.new_question.accordance_choice_answers = response.data.ans_arr 
                  break;
                  default: break;
              }
          })

        //.finally (function (response){ 
        //      axios
        //      .get('/dpps/'+self.stage.dpp_id+'/get_knowledges_to_ov/'+ self.stage.om_version_id)
        //      .then(response => (self.knowledges = response.data))
        //  })
  }
}
</script>