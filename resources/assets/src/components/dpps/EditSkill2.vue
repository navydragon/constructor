<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-editskill" ok-title="Сохранить" cancel-title="Закрыть" size="xl" title="Редактирование навыка">
                <h4>НАЗВАНИЕ НАВЫКА</h4>
                <b-alert show >Заполните параметры названия компонента</b-alert>
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
                <hr>
                <h4>ОБОСНОВАНИЕ</h4>
                <b-alert show >Выберите на основе какой информации формируется навык</b-alert>
                <b-form-radio v-model="new_skill.is_by_expert" name="is_by_expert" value="0">На основе источников НСИ</b-form-radio>
                <b-form-radio v-model="new_skill.is_by_expert" name="is_by_expert" value="1">На основе мнения эксперта</b-form-radio>
                <div class="mt-4" v-if="new_skill.is_by_expert==0">
                <h5>ИСТОЧНИКИ НСИ</h5>
                <b-alert show >Соотнесите навык с источниками НСИ</b-alert>
                <nsi-choose @change_nsi="change_nsi" :mode="'work'" :selected="new_skill.nsis" v-if="!isBusy" :ish_version_id="ish_version_id"></nsi-choose>
                </div>
                <div class="mt-4" v-if="new_skill.is_by_expert==1">
                <h5>МНЕНИЕ ЭКСПЕРТА</h5>
                <b-alert show >Введите комментарий, указав Ф.И.О. эксперта и его обоснование</b-alert>
                <b-form-textarea
                  id="textarea"
                  v-model="new_skill.expert_answer"
                  placeholder="Введите комментарий..."
                  rows="3"
                  max-rows="6"
                ></b-form-textarea>
                </div>
                <hr>
                <b-alert show variant="danger" v-if="!new_skill.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры названия компонента</b-alert>
        </b-modal>
    </div>
</template>

<script>
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
  name: "edit-skill2",
  metaInfo: {
  title: "Редактирование навыка"
  },
  props: {
      ish_version_id: Number,
      edit_elem: String
  },
  components: {NsiChoose},
  data() {
    return  {
      new_skill: {
        keyword: 'Владеть навыком',
        what: '',
        with: '',
        where: '',
        valid: true,
        expert_answer: '',
        is_by_expert: null,
        nsis: []
      },
      errors: [],
      isBusy: true,
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
          this.$emit('update_skill', {
            skill_name: this.name,
            skill_data: this.new_skill,
            }) 
        }
      },
      combine_text (elem) {
        return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
      },
      change_nsi (data) {
       this.new_skill.nsis = data.nsi_data
      },
      generate_id () {
       return `f${(~~(Math.random() * 1e8)).toString(16)}`
      },
  },
  mounted() {
    axios
    .get('/dpps/get_skill_info/'+ this.edit_elem)
    .then((response) => (this.new_skill = response.data) )
    .finally(this.isBusy = false)

  }
}
</script>