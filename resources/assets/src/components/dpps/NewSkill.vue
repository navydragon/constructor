<template>
  <span>
    <b-button v-b-modal.modal-addskil variant="secondary">Добавить навык</b-button>
        <!-- Модальное окно создания навыка -->
    <b-modal @ok="handle_ok_skil" id="modal-addskil" ok-title="Добавить навык" cancel-title="Закрыть" size="xl" title="Создание нового навыка">
        <b-alert v-if="!new_skil.show_edit" show variant="info">Выберите компонент на диаграмме для редактирования</b-alert>
        <div v-for="elem in this.new_skil.skilData" :key="elem.id" class="row">
        <div class="col-md-12">
            <organization-chart :datasource="elem" :direction="'l2r'" @node-click="kek">
            <template slot-scope="{ nodeData }">
                <span :class="'btn btn-block ' + nodeData.color"><b-badge v-if="!nodeData.valid" variant="danger">!</b-badge> {{ nodeData.text }}</span>
            </template>
            </organization-chart>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
            <b-card class="mt-3"
            :header="'Редактирование компонента «' + new_skil.editingItem.text + '»'"
            v-if="new_skil.show_edit"
            border-variant="secondary"
            >
            <!-- Редактирование skill -->
            <b-form v-if="new_skil.editingItem.type==='skil'" @submit="nc_onSubmit" @reset="nc_onReset">
                <b-alert show  v-if="!new_skil.editingItem.valid">Заполните параметры компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_skil.editingItem.keyword" required placeholder="Владеть" value="Владеть" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Чем?" class="col">
                    <b-input v-model="new_skil.editingItem.what" required placeholder="Чем?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                    <b-input v-model="new_skil.editingItem.with" required placeholder="При помощи чего?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                    <b-input v-model="new_skil.editingItem.where" required placeholder="При каких условиях?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <b-form-input disabled  :value="ei_fulltext" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-button type="submit" variant="primary">Сохранить</b-button>&nbsp;
                <b-button :disabled="ei_add_disable" v-if="new_skil.editingItem.valid" @click="nc_add_abil_to_skil" variant="success">Добавить умение</b-button>
                &nbsp;
                </b-form-row>
            </b-form>
            <!-- Редактирование abil -->
            <b-form v-if="new_skil.editingItem.type==='abil'" @submit="nc_onSubmit" @reset="nc_onReset">
                <b-alert show  v-if="!new_skil.editingItem.valid">Заполните параметры компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_skil.editingItem.keyword" required placeholder="Уметь" value="Уметь" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Что?" class="col">
                    <b-input v-model="new_skil.editingItem.what" required placeholder="Что?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При помощи чего?" class="col">
                    <b-input v-model="new_skil.editingItem.with" required placeholder="При помощи чего?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="При каких условиях?" class="col">
                    <b-input v-model="new_skil.editingItem.where" required placeholder="При каких условиях?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название" class="col">
                    <b-form-input disabled  :value="ei_fulltext" />
                </b-form-group>
                </b-form-row>
                <b-button type="submit" variant="primary">Сохранить</b-button>
                <b-button :disabled="ei_add_disable" v-if="new_skil.editingItem.valid" @click="nc_add_know_to_abil" variant="warning">Добавить знание</b-button>
                &nbsp;
                <b-button :disabled="!is_deletable(new_skil.skilData[0], new_skil.editingItem,false)" @click="delete_el(new_skil.skilData[0].children, new_skil.editingItem)" variant="danger">Удалить компонент</b-button>
            </b-form>
            <!-- Редактирование know -->
            <b-form v-if="new_skil.editingItem.type==='know'" @submit="nc_onSubmit" @reset="nc_onReset">
                <b-alert show  v-if="!new_skil.editingItem.valid">Заполните параметры компонента</b-alert>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Ключевое слово" class="col">
                    <b-form-input disabled v-model="new_skil.editingItem.keyword" required placeholder="Знать" value="Знать" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Что?" class="col">
                    <b-input v-model="new_skil.editingItem.what" required placeholder="Что?" />
                </b-form-group>
                </b-form-row>
                <b-form-row>
                <b-form-group label-size="lg" label-cols-lg="2" label="Итоговое название:" class="col">
                    <b-form-input disabled  :value="ei_fulltext" />
                </b-form-group>
                </b-form-row>
                <b-button type="submit" variant="primary">Сохранить</b-button>
                &nbsp;
                <b-button :disabled="!is_deletable(new_skil.skilData[0], new_skil.editingItem,false)" @click="delete_el(new_skil.skilData[0].children, new_skil.editingItem)" variant="danger">Удалить компонент</b-button>
            </b-form>
            </b-card>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
            <b-alert
            v-if="this.new_skil.show_errors"
            variant="danger"
            show
            >
            <strong>Обнаружены ошибки!</strong>
            <ul v-for="(error,index) in this.new_skil.errors" :key="index">
            <li>{{error}}</li>
            </ul>
        </b-alert>
        </div>
        </div>
    </b-modal>
  </span>
</template>

<script>
import VJstree from 'vue-jstree'
import OrganizationChart from 'vue-organization-chart'
import 'vue-organization-chart/dist/orgchart.css'
export default {
  name: "new-skill",
  metaInfo: {
  title: "Добавить новый навык"
  },
  components: {
    VJstree, OrganizationChart
  },
  data () {
    return {
      new_skil: {
      text: '',
      show_edit: false,
      show_errors: false,
      editingItem: {},
      editingNode: null,
      skilData: [{
        // 'id': this.generate_id(),
        'text': 'Новый навык',
        'opened': true,
        'type': 'skil',
        'color': 'btn-secondary',
        'icon': 'ion ion-ios-radio-button-on text-secondary',
        'valid': false,
        'keyword': 'Владеть',
        'what': '',
        'with': '',
        'where': '',
        'children': []
      }],
      errors: []
    }
    }
  },
  computed: {
    ei_fulltext () {
      return this.combine_text(this.new_skil.editingItem)
      },
    ei_add_disable () {
      if (this.new_skil.editingItem.children.length < 3) {
        return false
      }
      return true
    },
  },
  methods: {
      handle_ok_skil (bvModalEvt) {
        bvModalEvt.preventDefault()
        this.new_skil.errors = []
        if (this.check_skil(this.new_skil.skilData) > 0) {
            this.new_skil.show_errors = true
        } else {
            this.new_skil.show_errors = false
            this.$emit('add_skil', this.new_skil.skilData[0]);
        // this.treeData[0].children.push(this.new_skil.skilData[0])
            this.new_skil.skilData = [{
            'id': this.generate_id(),
            'text': 'Новый навык',
            'opened': true,
            'type': 'skil',
            'color': 'btn-secondary',
            'icon': 'ion ion-ios-radio-button-on text-secondary',
            'valid': false,
            'children': [],
            'keyword': 'Владеть',
            'what': '',
            'with': '',
            'where': ''
            }]
            this.$nextTick(() => {
            this.$bvModal.hide('modal-addskil')
            })
        }
    },
    kek (node) {
      console.log(node)
      this.new_skil.editingNode = node
      this.new_skil.editingItem = node
      console.log(this.new_skil.editingItem)
      this.new_skil.show_edit = true
    },
    nc_onSubmit (evt) {
      var newType, newTitle, newColor, newIcon, keyword, what, _with, where
      evt.preventDefault()
      this.new_skil.editingItem.text = this.combine_text(this.new_skil.editingItem)
      this.new_skil.editingItem.valid = true
      switch (this.new_skil.editingItem.type) {
        case 'skil':
          newType = 'abil'
          newTitle = 'Умение'
          newColor = 'btn-success'
          newIcon = 'ion ion-ios-radio-button-on text-success'
          keyword = 'Уметь'
          what = ''
          _with = ''
          where = ''
          break
        case 'abil':
          newType = 'know'
          newTitle = 'Знание'
          newColor = 'btn-warning'
          newIcon = 'ion ion-ios-radio-button-on text-warning'
          keyword = 'Знать'
          what = ''
          _with = ''
          where = ''
          break
      }
      if ((this.new_skil.editingItem.type !== 'know') && (this.new_skil.editingItem.children.length === 0)) {
        for (var i = 1; i <= 2; i++) {
          this.new_skil.editingItem.children.push({
            'id': this.generate_id(),
            'text': newTitle + ' ' + (this.new_skil.editingItem.children.length + 1),
            'opened': true,
            'valid': false,
            'type': newType,
            'title': newTitle,
            'color': newColor,
            'icon': newIcon,
            'keyword': keyword,
            'what': what,
            'with': _with,
            'where': where,
            'children': []
          })
        }
      }
    },
    nc_onReset (evt) {
      evt.preventDefault()
      alert(JSON.stringify(this.form))
    },
    combine_text (elem) {
      return elem.keyword + ' ' + elem.what + ' ' + elem.with + ' ' + elem.where
    },
    generate_id () {
      return `f${(~~(Math.random() * 1e8)).toString(16)}`
    },
    nc_add_abil_to_skil () {
      this.new_skil.editingItem.children.push({
        'id': this.generate_id(),
        'text': 'Умение ' + (this.new_skil.editingItem.children.length + 1),
        'opened': true,
        'valid': false,
        'type': 'abil',
        'title': 'Умение',
        'color': 'btn-success',
        'icon': 'ion ion-ios-radio-button-on text-success',
        'keyword': 'Уметь',
        'what': '',
        'with': '',
        'where': '',
        'children': []
      })
    },
    nc_add_know_to_abil () {
      this.new_skil.editingItem.children.push({
        'id': this.generate_id(),
        'text': 'Знание ' + (this.new_skil.editingItem.children.length + 1),
        'opened': true,
        'valid': false,
        'type': 'know',
        'title': 'Знание',
        'color': 'btn-warning',
        'icon': 'ion ion-ios-radio-button-on text-warning',
        'keyword': 'Знать',
        'what': '',
        'with': '',
        'where': '',
        'children': []
      })
    },
    check_skil (obj) {
      var errors = 0
      for (var i = 0; i < obj.length; i++) {
        if (obj[i].valid === false) {
          errors++
          this.new_skil.errors.push('Не заполнен компонент «' + obj[i].text + '»')
        }
        if (obj[i].children.length > 0) {
          errors += this.check_skil(obj[i].children)
        }
      }
      return errors
    },
    delete_el (data,el)
    {
       for (var i = 0; i < data.length; i++)
       {
           if (data[i].id == el.id)
           {
               data.splice(data.indexOf(el),1)
               this.new_skil.editingItem = {}
               this.new_skil.show_edit = false
               return true
           }
           if (data[i].children.length > 0)
           {
                this.delete_el(data[i].children,el)
           }
           
       }
    },
    is_deletable (data,el,found)
    {
       for (var i = 0; i < data.children.length; i++)
       {
           if (data.children[i].id == el.id)
           {
              //console.log('found '+data.children[i].text+' in '+data.text+'('+data.children.length+')')
              if (data.children.length > 2)
              {
               //console.log('can_be_deleted')
               found = true
              }
           }
            if (data.children[i].children.length > 0)
            {
                found =  this.is_deletable(data.children[i],el,found)
            }
           
       }
       return found
    }
  }
  
}
</script>

