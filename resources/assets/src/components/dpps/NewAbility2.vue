<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-newability" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Создание нового умения">
            <p>Parent: {{parent_node}}</p>
                <h4>НАЗВАНИЕ УМЕНИЯ</h4>
                <b-alert show >Заполните параметры названия компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_ability.keyword" required placeholder="Уметь" value="Уметь" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Что?" class="col">
                    <b-input v-model="new_ability.what" required placeholder="Что?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                    <b-input v-model="new_ability.with" required placeholder="При помощи чего?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                    <b-input v-model="new_ability.where" required placeholder="При каких условиях?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <p>{{name}}</p>
                </b-form-group>
                </b-form-row>
                <hr>
                <h4>НОРМАТИВНО-СПРАВОЧНАЯ ИНФОРМАЦИЯ</h4>
                <b-alert show >Соотнесите умение с источниками НСИ</b-alert>
                <nsi-choose @change_nsi="change_nsi" v-if="!isBusy" :ish_version_id="ish_version_id"></nsi-choose>
                <hr>
                <b-alert show variant="danger" v-if="!new_ability.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
  name: "new-ability2",
  metaInfo: {
  title: "Добавить новое умение"
  },
  props: {
      parent_node: String,
      ish_version_id: Number
  },
  components: {NsiChoose},
  data() {
    return  {
      new_ability: {
        keyword: 'Уметь',
        what: '',
        with: '',
        where: '',
        valid: true,
        nsis: [],
      },
      errors: [],
      isBusy: true,
    }
  },
  computed: {
    name() {
      return this.combine_text(this.new_ability)
    }
  },
  methods: {
      handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        if (this.new_ability.what.length == 0 || this.new_ability.with.length == 0 || this.new_ability.where.length == 0)
        {
          this.new_ability.valid = false
        }else{
          this.new_ability.valid = true
          this.$emit('add_ability', {
            ability_name: this.name,
            ability_data: this.new_ability,
            parent_node: this.parent_node
            }) 
        }
      },
      combine_text (elem) {
        return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
      },
      change_nsi (data) {
       this.new_ability.nsis = data.nsi_data
      },
  },
  mounted() {
    this.isBusy = false
  }
}
</script>