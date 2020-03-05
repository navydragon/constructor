<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-newskill" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Создание нового навыка">
            <p>Parent: {{parent_node}}</p>
                <b-alert show >Заполните параметры компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_skill.keyword" required placeholder="Владеть навыком" value="Владеть навыком" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Каким?" class="col">
                    <b-input v-model="new_skill.what" required placeholder="Каким?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                    <b-input v-model="new_skill.with" required placeholder="При помощи чего?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                    <b-input v-model="new_skill.where" required placeholder="При каких условиях?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <p>{{name}}</p>
                </b-form-group>
                </b-form-row>
                <b-alert show variant="danger" v-if="!new_skill.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
  name: "new-skill2",
  metaInfo: {
  title: "Добавить новый навык"
  },
  props: {
      parent_node: String
  },
  data() {
    return  {
      new_skill: {
        keyword: 'Владеть навыком',
        what: '',
        with: '',
        where: '',
        valid: true
      },
      errors: [],
    }
  },
  computed: {
    name() {
      return this.combine_text(this.new_skill)
    }
  },
  methods: {
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        if (this.new_skill.what.length == 0 || this.new_skill.with.length == 0 || this.new_skill.where.length == 0)
        {
          this.new_skill.valid = false
        }else{
          this.new_skill.valid = true
          this.$emit('add_skill', {
            skill_name: this.name,
            skill_data: this.new_skill,
            parent_node: this.parent_node
            }) 
        }
      },
      combine_text (elem) {
        return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
      },
  }
}
</script>