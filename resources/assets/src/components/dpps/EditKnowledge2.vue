<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-editknowledge" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Редактирование знания">
                <h4>НАЗВАНИЕ ЗНАНИЯ</h4>
                <b-alert show >Заполните параметры названия компонента</b-alert>
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
                <h4>СООТВЕТСТВИЕ РАЗДЕЛУ ТИПОВОЙ СТРУКТУРЫ ДПП</h4>
                <b-alert show >Выберите, какому разделу типовой структуры соответсвует данное знание</b-alert>
                <b-form-radio v-for="dtp in dtps" :key="'d'+dtp.id" v-model="new_knowledge.dtp" name="dtps" :value="dtp.id">{{dtp.name}}</b-form-radio>
                <h4 class="mt-2">НОРМАТИВНО-СПРАВОЧНАЯ ИНФОРМАЦИЯ</h4>
                <b-alert show >Соотнесите знание с источниками НСИ</b-alert>
                <nsi-choose @change_nsi="change_nsi" v-if="!isBusy" :selected="new_knowledge.nsis" :ish_version_id="ish_version_id"></nsi-choose>
                <hr>
                <b-alert show variant="danger" v-if="!new_knowledge.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры названия компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
  name: "edit-knowledge2",
  metaInfo: {
  title: "Редактировать знание"
  },
  props: {
      ish_version_id: Number,
      edit_elem: String,
      dtps: Array,
  },
  components: {NsiChoose},
  data() {
    return  {
      new_knowledge: {
        keyword: 'Знать',
        what: '',
        with: ' ',
        where: ' ',
        valid: true,
        nsis: []
      },
      errors: [],
      isBusy: true
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
          this.$emit('update_knowledge', {
            knowledge_name: this.name,
            knowledge_data: this.new_knowledge,
            parent_node: this.parent_node
            }) 
        }
      },
      combine_text (elem) {
        return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
      },
      change_nsi (data) {
       this.new_knowledge.nsis = data.nsi_data
      },
  },
  mounted() {
    axios
    .get('/dpps/get_knowledge_info/'+ this.edit_elem)
    .then((response) => (this.new_knowledge = response.data) )
    .finally(this.isBusy = false)
  }
}
</script>