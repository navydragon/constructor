<template>
    <div>
      <b-button variant="primary" v-b-modal.modal-1>Добавить источник</b-button>
      <b-modal @ok="handleOk" size="xl" id="modal-1" title="Добавить новый источник НСИ" ok-title="Добавить" cancel-title="Закрыть">
          <div>
            <b-form-group label-size="lg" label-cols="2" description="Выберите тип источника" label="Тип источника *" label-for="nsi_type">
            <b-form-select id="nsi_type" v-model="new_nsi.type" :options="types" value-field="id" text-field="name"></b-form-select>
            </b-form-group>
            <b-form-group label-size="lg" label-cols="2" description="Введите полное название источника" label="Название *" label-for="nsi_name">
             <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
            </b-form-group>
            <b-form-group label-size="lg" label-cols="2" description="Укажите авторов (при их наличии) через запятую в формате Фамилия И.О." label="Автор(-ы)" label-for="nsi_authors">
             <b-form-input id="nsi_authors" v-model="new_nsi.authors"></b-form-input>
            </b-form-group>
            <b-form-group label-size="lg" label-cols="2" description="Укажите выходные данные источника  (город, издательство, год издания)" label="Выходные данные" label-for="nsi_output">
             <b-form-input id="nsi_output" v-model="new_nsi.output"></b-form-input>
            </b-form-group>
            <b-form-group label-size="lg" label-cols="2" description="Для электронных источников и библиотечных систем укажите URL доступа" label="URL" label-for="nsi_url">
             <b-form-input id="nsi_url" v-model="new_nsi.url"></b-form-input>
            </b-form-group>
            <b-alert v-if="errors.length>0" show variant="danger">Обнаружены ошибки:
                <ul>
                    <li v-for="error in errors" :key="error">{{error}}</li>
                </ul>
            </b-alert>
        </div>
      </b-modal>
    </div>
</template>

<script>
export default {
    name: 'new-nsi',
    props: {
       types: Array 
    },
    data () {
    return {
      new_nsi: {
          type: '',
          name: '',
          authors: '',
          output: '',
          url: ''
      },
      errors: []
    }
  },
  methods: {
       handleOk(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.errors = []
        if (this.new_nsi.type.length == 0) { this.errors.push("Не выбран тип источника")}
        if (this.new_nsi.name.length == 0) { this.errors.push("Не введено название")}
        if (this.errors.length == 0)
        {
            this.$emit('add_nsi', {
            nsi_data: this.new_nsi,
            
            })
        }
      },
  }
}
</script>