<template>
    <div>
        <b-button :variant="'primary'" v-b-modal.modal-newdtp>Добавить раздел</b-button>
        <b-modal size="lg" id="modal-newdtp" title="Добавить раздел в типовую структуру" no-close-on-esc no-close-on-backdrop @ok="handle_ok" ok-title="Сохранить">
            <b-form-group
            id="fieldset-1"
            label="Название раздела"
            label-size="lg"
            label-for="input-1"
            >
            <b-form-input id="input-1" v-model="new_dtp" trim></b-form-input>
            </b-form-group>
            <b-alert show variant="danger" v-if="errors.length>0"><strong>Ошибка!</strong> Заполните название раздела</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: "new-dtp",
    metaInfo: {
        title: "Добавить новый раздел"
    },
    data() {
    return {
        new_dtp: '',
        errors: []
    }
    },
    methods: {
      handle_ok(bvModalEvt){
        bvModalEvt.preventDefault()
        this.errors = []
        if (this.new_dtp == ''){
            this.errors.push("Заполните название раздела")
        }
        if(this.errors.length == 0) {
            this.$emit('add_dtp', this.new_dtp)
            this.new_dtp = '';
        }
      }
    }
}
</script>