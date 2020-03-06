<template>
    <div>
        <b-modal no-close-on-esc no-close-on-backdrop @ok="handle_ok" id="modal-newcompetence" ok-title="Сформировать" cancel-title="Закрыть" size="xl" title="Формирование новой компетенции">
            <h4>НАЗВАНИЕ КОМПЕТЕНЦИИ</h4>
                <b-alert show >Заполните параметры названия компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_competence.keyword" required placeholder="Способен" value="Способен" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="На что?" class="col">
                    <b-input v-model="new_competence.what" required placeholder="На что?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                    <b-input v-model="new_competence.with" required placeholder="При помощи чего?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                    <b-input v-model="new_competence.where" required placeholder="При каких условиях?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <p>{{name}}</p>
                </b-form-group>
                </b-form-row>
                <hr>
                <h4>НАВЫКИ/УМЕНИЯ, КОТОРЫЕ ВОЙДУТ В КОМПЕТЕНЦИЮ</h4>
                <b-alert show >Выберите необходимые компоненты</b-alert>
                <b-form-checkbox-group
                    id="checkbox-group-4"
                    v-model="new_competence.elems"
                    :options="elems"
                    name="elems"
                    value-field = "id"
                    text-field = "name"
                    stacked
                ></b-form-checkbox-group>
                <hr>
                <b-alert show variant="danger" v-if="!new_competence.valid"><strong>Ошибка!</strong> Заполните ВСЕ параметры новой компетенции</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'new-competence2',
    props: {
        elems: Array
    },
    metaInfo: {
      title: "Сформировать компетенцию"
    },
    data() {
    return  {
      new_competence: {
        keyword: 'Способен',
        what: '',
        with: ' ',
        where: ' ',
        valid: true,
        elems: []
      },
      errors: [],
      isBusy: true
    }},
    computed: {
    name() {
      return this.combine_text(this.new_competence)
    }
    },
    methods: {
        handle_ok(bvModalEvt) {
        bvModalEvt.preventDefault()
        if (this.new_competence.what.length == 0 || this.new_competence.with.length == 0 || this.new_competence.where.length == 0)
        {
          this.new_competence.valid = false
        }else{
          this.new_competence.valid = true
          this.$emit('add_competence', {
            competence_name: this.name,
            competence_data: this.new_competence,
            }) 
        }
      },
      combine_text (elem) {
        return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
      },
    }
}
</script>