<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-newknowledge" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Создание нового знания">
            <p>Parent: {{parent_node}}</p>
                <b-alert show >Заполните параметры компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_knowledge.keyword" required placeholder="Знать" value="Знать" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Что?" class="col">
                    <b-input v-model="new_knowledge.what" required placeholder="Что?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <p>{{name}}</p>
                </b-form-group>
                </b-form-row>
                <b-alert show variant="danger" v-if="!new_knowledge.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
  name: "new-knowledge2",
  metaInfo: {
  title: "Добавить новое знание"
  },
  props: {
      parent_node: String
  },
  data() {
    return  {
      new_knowledge: {
        keyword: 'Знать',
        what: '',
        with: ' ',
        where: ' ',
        valid: true
      },
      errors: [],
    }
  },
  computed: {
    name() {
      return this.combine_text(this.new_knowledge)
    }
  },
  methods: {
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        if (this.new_knowledge.what.length == 0 )
        {
          this.new_knowledge.valid = false
        }else{
          this.new_knowledge.valid = true
          this.$emit('add_knowledge', {
            knowledge_name: this.name,
            knowledge_data: this.new_knowledge,
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