<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-removeparent" ok-title="Удалить связь" cancel-title="Закрыть" size="xl" title="Удаление дополнительной связи знания">
            <b-alert show >Выберите умение, от которого следует удалить дополнительную связь знания</b-alert>
            <h5>Знание:</h5>
            <p>{{edit_elem.name}}</p>
            <h5>Умения:</h5>
            <b-form-group>
                <b-form-radio  v-for="elem in elems" v-model="selected" name="abilities" :value="elem.to" :key="elem.to">{{print_name(elem.to)}}</b-form-radio>
            </b-form-group>
            <hr>
                <b-alert show variant="danger" v-if="errors.length>0"><strong>Ошибка!</strong> Выберите умение</b-alert>
        </b-modal>
    </div>
</template>


<script>
export default {
    name: 'remove-parent2',
    props : {
        elems: Array,
        nodes: Array,
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
                this.$emit('erase_parent', this.selected)
            }
        },
        print_name(id)
        {
            return this.nodes.find(node => node.id == id).name
        }
    }
}
</script>