<template >
  <b-modal
    size="lg"
    id="modal-new-fgos"
    title="Добавить новый ФГОС"
    no-close-on-esc
    no-close-on-backdrop
    @ok="handle_ok"
    ok-title="Сохранить"
    cancel-title="Закрыть"
  >
    <b-form-group id="fieldset-0"  label="Уровень образования" label-size="lg" >
    <b-form-select v-model="new_item.fgos_level_id" :options="fgos_levels" value-field="id" text-field="name"></b-form-select>
    </b-form-group>
    <b-form-group id="fieldset-1" description="Пример: 08.03.01" label="Код" label-size="lg" label-for="input-1">
      <b-form-input id="input-1" v-model="new_item.code" trim></b-form-input>
    </b-form-group>
    <b-form-group id="fieldset-2" description="Пример: Строительство" label="Направление" label-size="lg" label-for="input-2">
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
    name: "new-fgos",
    props: {
      fgos_levels: Array
    },
    data() {
    return {
        new_item: {
            code: '',
            name: '',
            fgos_level_id: '',
        },
        errors: []
    }
    },
    methods:{
        handle_ok(bvModalEvt) {
            bvModalEvt.preventDefault()
            this.errors = []
            if (this.new_item.fgos_level_id == '') {this.errors.push("Не выбран уровень образования")}
            if (this.new_item.code == '') {this.errors.push("Не заполнено поле «Код»")}
            if (this.new_item.name == '') {this.errors.push("Не заполнено поле «Направление»")}
            if(this.errors.length == 0) {
            this.$emit('add_fgos', this.new_item)
        }
        }
    }
}
</script>