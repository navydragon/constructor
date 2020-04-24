<template>
    <div>
      <b-button variant="primary" v-b-modal.modal-newmto>Добавить МТО</b-button>
      <b-modal @ok="handleOk" size="xl" id="modal-newmto" title="Добавить МТО" ok-title="Добавить" cancel-title="Закрыть">
        <div>
            <b-form-group label-size="lg" label="Выберите тип МТО" >
            <b-form-select @change="child_type_selected=''" v-model="parent_type_selected" :options="parent_types" value-field="id" text-field="name"></b-form-select>  
            </b-form-group>
            <b-form-group v-if="type_has_children" label-size="lg" label="Уточните тип МТО" >
            <b-form-select v-model="child_type_selected" :options="children_types" value-field="id" text-field="name"></b-form-select>  
            </b-form-group>
            <div v-if="selected_type != null">
                <b-form-group label-size="lg" label="Название МТО" >
                    <b-form-input  v-model="new_mto.name"></b-form-input>
                </b-form-group>
                <b-form-group label-size="lg" label="Единица измерения МТО" >
                    <b-form-input  v-model="new_mto.measure"></b-form-input>
                </b-form-group>
            </div>
            <b-alert v-if="errors.length>0" show variant="danger">Обнаружены ошибки:
                <ul>
                    <li v-for="(error,index) in errors" :key="'e'+index">{{error}}</li>
                </ul>
            </b-alert>
        </div>
      </b-modal>
    </div>
</template>

<script>
export default {
    name: 'new-mto',
    props: {
       types: Array 
    },
    data () {
    return {
      parent_type_selected: '',
      child_type_selected: '',
      new_mto: {
          name: '',
          measure: '',
      },
      errors: []
    }
  },
  computed: {
    parent_types(){
        return this.types.filter(el => el.parent_id == null)
    },
    children_types(){
        var parent_type = this.types.find(el => el.id == this.parent_type_selected)
        return this.types.filter(el => el.parent_id == parent_type.id)
    },
    type_has_children() {
        var parent_type = this.types.find(el => el.id == this.parent_type_selected)
        if (parent_type)
        {
            var children_types = this.types.filter(el => el.parent_id == parent_type.id)
            if (children_types.length > 0)
            {
                return true
            }
        }
        return false
    },
    selected_type(){
        if (this.parent_type_selected != '')
        {
            var parent_type = this.types.find(el => el.id == this.parent_type_selected)
            var children_types = this.types.filter(el => el.parent_id == parent_type.id)
            if (children_types.length == 0)
            {
                return this.parent_type_selected
            }else{
                if (this.child_type_selected != '')
                {
                    return this.child_type_selected
                }
            }
        }
        return null
    }
  },
  methods: {
       handleOk(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.errors = []
        if (this.selected_type == null)
        {
            this.errors.push("Выберите/уточните тип МТО")
        }else{
            if (this.new_mto.name == '') {
               this.errors.push("Введите название МТО") 
            }
            if (this.new_mto.measure == '') {
               this.errors.push("Введите единицу измерения МТО") 
            }
        }
        if (this.errors.length == 0)
        {
            this.$emit('add_mto', {
            type_id: this.selected_type,
            mto_data: this.new_mto,
            })
        }
      },
  }
}
</script>