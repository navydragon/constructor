<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-newknowledge" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Создание нового знания">
            <p>Parent: {{parent_node}}</p>
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
                <hr>
                <h4>ОБОСНОВАНИЕ</h4>
                <b-alert show >Выберите на основе какой информации формируется навык</b-alert>
                <b-form-radio v-model="new_knowledge.is_by_expert" name="is_by_expert" value="0">На основе источников НСИ</b-form-radio>
                <b-form-radio v-model="new_knowledge.is_by_expert" name="is_by_expert" value="1">На основе мнения эксперта</b-form-radio>
                <div class="mt-4" v-if="new_knowledge.is_by_expert==0">
                <h5>ИСТОЧНИКИ НСИ</h5>
                <b-alert show >Соотнесите навык с источниками НСИ</b-alert>
                <nsi-choose @change_nsi="change_nsi" :mode="'work'" :selected="[]" v-if="!isBusy" :ish_version_id="ish_version_id"></nsi-choose>
                </div>
                <div class="mt-4" v-if="new_knowledge.is_by_expert==1">
                <h5>МНЕНИЕ ЭКСПЕРТА</h5>
                <b-alert show >Введите комментарий, указав Ф.И.О. эксперта и его обоснование</b-alert>
                <b-form-textarea
                  id="textarea"
                  v-model="new_knowledge.expert_answer"
                  placeholder="Введите комментарий..."
                  rows="3"
                  max-rows="6"
                ></b-form-textarea>
                </div>
                <hr>
                <b-alert show variant="danger" v-if="!new_knowledge.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры названия компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
  name: "new-knowledge2",
  metaInfo: {
  title: "Добавить новое знание"
  },
  props: {
      parent_node: String,
      ish_version_id: Number,
      dtps: Array
  },
  components: {NsiChoose},
  data() {
    return  {
      new_knowledge: {
        keyword: 'Знать',
        what: '',
        with: ' ',
        where: ' ',
        dtp: '',
        valid: true,
        expert_answer: '',
        is_by_expert: null,
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
      change_nsi (data) {
       this.new_knowledge.nsis = data.nsi_data
      },
  },
  mounted() {
    this.isBusy = false
  }
}
</script>