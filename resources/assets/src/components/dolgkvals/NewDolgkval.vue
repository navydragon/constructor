<template >
  <b-modal
    size="lg"
    id="modal-new-dolgkval"
    title="Добавить новую профессию"
    no-close-on-esc
    no-close-on-backdrop
    @ok="handle_ok"
    ok-title="Сохранить"
    cancel-title="Закрыть"
  >
    <b-form-group id="fieldset-2" description="Пример: Машинист асфальтоукладчика" label="Название профессии" label-size="lg" label-for="input-2">
      <b-form-input id="input-2" v-model="new_item.name" trim></b-form-input>
    </b-form-group>
    <b-alert show variant="danger" v-if="errors.length>0">
      <strong>Обнаружены ошибки:</strong> 
      <ul><li v-for="(error,index) in errors" :key="index">{{error}}</li></ul>
    </b-alert>
  </b-modal>
</template>

<script>
export default {
    name: "new-dolgkval",
    data() {
    return {
        new_item: {
            code: '',
            name: ''
        },
        errors: []
    }
    },
    methods:{
        handle_ok(bvModalEvt) {
            bvModalEvt.preventDefault()
            this.errors = []
            if (this.new_item.name == '') {this.errors.push("Не заполнено поле «Название профессии».")}
            if(this.errors.length == 0) {
            this.$emit('add_dolgkval', this.new_item)
            this.new_item = '';
        }
        }
    }
}
</script>