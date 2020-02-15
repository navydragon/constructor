<template>
    <span>
    <b-button  :disabled="tree_data.children.length==0"  v-b-modal.modal-addcomp variant="primary">Сформировать компетенцию</b-button>
    <b-modal id="modal-addcomp" size="xl" title="Формирование новой компетенции">
        <b-form  @submit="nc_onSubmit" @reset="nc_onReset">
            <b-alert show >Заполните параметры названия и выберите компоненты, которые войдут в компетенцию </b-alert>
            <b-form-row>
            <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                <b-form-input disabled v-model="new_comp.keyword" required placeholder="Владеть" value="Владеть" />
            </b-form-group>
            </b-form-row>
            <b-form-row>
            <b-form-group label-size="lg" label-cols-lg="2" label="На что?" class="col">
                <b-input v-model="new_comp.what" required placeholder="На что?" />
            </b-form-group>
            </b-form-row>
            <b-form-row>
            <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                <b-input v-model="new_comp.with" required placeholder="При помощи чего?" />
            </b-form-group>
            </b-form-row>
            <b-form-row>
            <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                <b-input v-model="new_comp.where" required placeholder="При каких условиях?" />
            </b-form-group>
            </b-form-row>
            <b-form-row>
            <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                <b-form-input disabled  :value="comp_fulltext" />
            </b-form-group>
            </b-form-row>
            <b-form-row>
              <b-form-group label-size="lg" label="Навыки/умения, которые войдут в компетенцию:">
                <b-form-checkbox-group id="checkbox-group" :state="state" stacked v-model="new_comp.selected" name="selectedItems">
                    <b-form-checkbox v-for="(item, index) in tree_data.children" :key="index" :value="item.id" switch>{{item.text}}</b-form-checkbox>
                </b-form-checkbox-group>
                <b-form-invalid-feedback :state="state">Пожалуйста, выберите один или несколько компонентов</b-form-invalid-feedback>
              </b-form-group>
            </b-form-row>
            <b-form-row>
            <b-button type="submit" block variant="primary">Сформировать</b-button>&nbsp;
            </b-form-row>
        </b-form>
            <template v-slot:modal-footer="">
                <span></span>
            </template>
    </b-modal>
    </span>
</template>

<script>
export default {
  name: "new-comp",
  metaInfo: {
  title: "Формирование компетенции"
  },
  props: {
  tree_data: Object
  },
  data () {
    return {
      new_comp: {
          keyword: 'Способен',
          what: '',
          with: '',
          where: '',
          selected: [],
      },
      
      state: null
    }
  },
  computed: {
      comp_fulltext () {
      return this.combine_text(this.new_comp)
      },
  },
  methods: {
    combine_text (elem) {
      return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
    },
    nc_onSubmit (evt) {
      evt.preventDefault()
      var a = this.new_comp.selected
      if (a.length == 0)
      {
          this.state = false
      }else{
          this.state = null
          this.new_comp.text = this.combine_text(this.new_comp)
          this.$emit('add_comp', this.new_comp)
          this.new_comp= {
          keyword: 'Способен',
          what: '',
          with: '',
          where: '',
          selected: [],
          }
          this.$nextTick(() => {
            this.$bvModal.hide('modal-addcomp')
            })
      }
    },
    nc_onReset (evt) {
      evt.preventDefault()
      alert(JSON.stringify(this.form))
    },
  }
}
</script>