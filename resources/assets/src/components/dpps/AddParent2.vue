<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-addparent" ok-title="Добавить связь" cancel-title="Закрыть" size="xl" title="Добавление дополнительной связи к знанию">
            <b-alert show >Выберите умение, к которому добавить дополнительную связь от знания</b-alert>
            <h5>Знание:</h5>
            <p>{{edit_elem.name}}</p>
            <h5>Умения:</h5>
            <b-form-group>
                <b-form-radio  v-for="elem in elems" v-model="selected" :disabled="elem.id == edit_elem.pid" name="skills" :value="elem.id" :key="elem.id">{{elem.name}}</b-form-radio>
            </b-form-group>
            <hr>
                <b-alert show variant="danger" v-if="errors.length>0"><strong>Ошибка!</strong> Выберите умение</b-alert>
        </b-modal>
    </div>
</template>


<script>
export default {
    name: 'add-parent2',
    props : {
        elems: Array,
        edit_elem: Object
    },
    data () {
    return {
        selected: '',
        errors: []
    }
    },
    methods: {
        handle_ok (bvModalEvt) {
            bvModalEvt.preventDefault()
            this.errors = []
            if (this.selected == '')
            {
                this.errors.push ('Выберите умение')
            }else{
                this.$emit('draw_parent', this.selected)
            }
        }
    }
}
</script>