<template>
<div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <b-card :title="header">
    </b-card>
    <b-card  no-body>
      <div>
          <b-alert :variant="this.stage.dpp_total_hours==used_hours()?'success':'danger'" show>Планируется часов: <strong>{{this.stage.dpp_total_hours}}</strong>. Распределено часов: {{used_hours()}}</b-alert>
      </div>
      <b-tabs card>
        <b-tab title="Учебный план" active>
          <h5>Учебный план</h5>
          <b-button @click="rebuild">Сбросить</b-button>
          <table class="table table-bordered">
            <thead>
              <tr><th rowspan="2">Наименование разделов</th><th colspan="6">Трудоемкость, ч</th><th rowspan="2">Планируемые результаты обучения</th></tr>
              <tr><th>Итого</th><th>Лек.</th><th>Пр.</th><th>Лаб.</th><th>Сам. раб.</th><th>Атт.</th></tr>
            </thead>
            <tbody>
              <template v-for="section in sections" >
              <tr :key="'ps_'+section.id">
                <td>
                  <p class="mb-1"><strong>{{section.position}}. {{section.name}}</strong></p>
                  <div>
                    <b-button-group>
                      <b-btn size="sm" v-if="section.position != 1" variant="info icon-btn" class="btn" @click.prevent="move_up(section.id,null)"><i class="ion ion-md-arrow-round-up"></i></b-btn>
                      <b-btn size="sm" v-if="section.position != sections.length" variant="info icon-btn" class="btn" @click.prevent="move_down(section.id,null)"><i class="ion ion-md-arrow-round-down"></i></b-btn>
                      <b-btn size="sm" variant="primary icon-btn" class="btn" @click.prevent="edit_section(section)"><i class="ion ion-md-create"></i></b-btn>
                      <b-btn size="sm" variant="danger icon-btn" v-if="!section.is_blocked" class="btn" @click.prevent="delete_section(section.id)"><i class="ion ion-md-close"></i></b-btn>
                      <b-btn size="sm" variant="info icon-btn" v-if="!section.is_blocked" class="btn" @click.prevent="create_theme(null,section.id)"><i class="ion ion-md-add"></i></b-btn>
                    </b-button-group>
                  </div>
                </td>
                <td>{{section.total_hours}}</td>
                <td>{{section.lection_hours}}</td>
                <td>{{section.practice_hours}}</td>
                <td>{{section.lab_hours}}</td>
                <td>{{section.self_hours}}</td>
                <td>{{section.attestation_hours}}</td>
                <td></td>
              </tr>
              <tr v-for="theme in section.themes" :key="'ts_'+theme.id">
                <td>
                  <p class="mb-1">{{section.position}}.{{theme.position}} {{theme.name}}</p>
                  <div>
                    <b-button-group>
                      <b-btn size="sm" v-if="theme.position != 1" variant="outline-info icon-btn" class="btn" @click.prevent="move_up(theme.id,section.id)"><i class="ion ion-md-arrow-round-up"></i></b-btn>
                      <b-btn size="sm" v-if="theme.position != section.themes.length" variant="outline-info icon-btn" class="btn" @click.prevent="move_down(theme.id,section.id)"><i class="ion ion-md-arrow-round-down"></i></b-btn>
                      <b-btn size="sm" variant="outline-primary icon-btn" class="btn" @click.prevent="edit_theme(theme)"><i class="ion ion-md-create"></i></b-btn>
                      <b-btn size="sm" v-if="!theme.is_blocked" variant="outline-danger icon-btn" class="btn" @click.prevent="delete_theme(theme.id,section.id)"><i class="ion ion-md-close"></i></b-btn>
                    </b-button-group>
                  </div>
                  </td>
                <td>{{theme.total_hours}}</td>
                <td>{{theme.lection_hours}}</td>
                <td>{{theme.practice_hours}}</td>
                <td>{{theme.lab_hours}}</td>
                <td>{{theme.self_hours}}</td>
                <td>{{theme.attestation_hours}}</td>
                <td>
                  <p>Знания: <span v-for="knowledge in theme.zuns.knowledges" :key="'tk_'+knowledge.id">{{knowledge.name}}</span></p>
                  <p v-if="theme.zuns.abilities.length>0">Умения: <span v-for="ability in theme.zuns.abilities" :key="'ta_'+ability.id">{{ability.name}} </span></p>
                  <p v-if="theme.zuns.skills.length>0">Навыки: <span v-for="skill in theme.zuns.skills" :key="'ts_'+skill.id">{{skill.name}}</span></p>
                </td>
              </tr>
              </template>
            </tbody>
          </table>
          <b-button v-b-modal.new_section>Добавить раздел</b-button>
        </b-tab>
        <b-tab title="ЗУН">
          <h5>Сопоставление ЗУН и Тем</h5>
          <h5>Навыки</h5>
          <table class="table table-bordered">
            <thead><tr><th>Навык</th><th>Тема</th></tr></thead>
            <tbody>
              <tr v-for="skill in zuns.skills" :key="'s_'+skill.id">
                <td width="50%">{{skill.name}}</td>
                <td>
                  <div v-for="section in skill.sections" :key="'ss_'+section.id">{{section.name}}</div>
                </td>
              </tr>
            </tbody>
          </table>
          <h5>Умения</h5>
          <table class="table table-bordered">
            <thead><tr><th>Умение</th><th>Тема</th></tr></thead>
            <tbody>
              <tr v-for="ability in zuns.abilities" :key="'a_'+ability.id">
                <td width="50%">{{ability.name}}</td>
                <td>
                  <div v-for="section in ability.sections" :key="'as_'+section.id">{{section.name}}</div>
                </td>
              </tr>
            </tbody>
          </table>
          <h5>Знания</h5>
          <table class="table table-bordered">
            <thead><tr><th>Знание</th><th>Тема</th></tr></thead>
            <tbody>
              <tr v-for="knowledge in zuns.knowledges" :key="'k_'+knowledge.id">
                <td width="50%">{{knowledge.name}}</td>
                <td>
                  <div v-for="section in knowledge.sections" :key="'ks_'+section.id">{{section.name}}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </b-tab>
      </b-tabs>
    </b-card>
<!-- Modals -->
<b-modal id="new_section" ok-title="Добавить раздел" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_section" cancel-title="Закрыть" title="Добавить раздел">
  <b-form-group label-size="lg" label="Название раздела">
    <b-form-input v-model="new_section.name"></b-form-input>
  </b-form-group>
</b-modal>
<b-modal id="new_theme" ok-title="Добавить тему" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_theme" cancel-title="Закрыть" title="Добавить тему">
  <b-form-group label-size="lg" label="Название темы">
    <b-form-input v-model="new_theme.name"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов лекций">
    <b-form-input v-model="new_theme.lection_hours"></b-form-input>
  </b-form-group>      
  <b-form-group label-size="lg" label="Количество часов практик">
    <b-form-input v-model="new_theme.practice_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов лабораторных работ">
    <b-form-input v-model="new_theme.lab_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов самостоятельной работы">
    <b-form-input v-model="new_theme.self_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов итоговой аттестации">
    <b-form-input v-model="new_theme.attestation_hours"></b-form-input>
  </b-form-group>
  <h5>Всего часов на тему: {{parseInt(new_theme.lection_hours)+parseInt(new_theme.practice_hours)+parseInt(new_theme.self_hours)+parseInt(new_theme.lab_hours)+parseInt(new_theme.attestation_hours)}}</h5>
</b-modal>

<b-modal id="edit_section" ok-title="Обновить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_section" cancel-title="Закрыть" title="Редактирование раздела">
  <b-form-group label-size="lg" label="Название раздела">
    <b-form-input :disabled="current_section.name =='Итоговая аттестация'" v-model="current_section.name"></b-form-input>
  </b-form-group>
  <div v-if="current_section.name !='Итоговая аттестация'">
  <b-alert show  v-if="current_section.themes.length>0">Данный раздел содержит темы, поэтому часы для него рассчитываются автоматически</b-alert>
  <b-form-group label-size="lg" label="Количество часов лекций">
    <b-form-input :disabled="current_section.themes.length>0" v-model="current_section.lection_hours"></b-form-input>
  </b-form-group>      
  <b-form-group label-size="lg" label="Количество часов практик">
    <b-form-input :disabled="current_section.themes.length>0" v-model="current_section.practice_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов лабораторных">
    <b-form-input :disabled="current_section.themes.length>0" v-model="current_section.lab_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов самостоятельной работы">
    <b-form-input :disabled="current_section.themes.length>0" v-model="current_section.self_hours"></b-form-input>
  </b-form-group>
  <b-form-group label-size="lg" label="Количество часов аттестации">
    <b-form-input :disabled="current_section.themes.length>0" v-model="current_section.attestation_hours"></b-form-input>
  </b-form-group>
  </div>
  <h5>Всего часов в разделе: {{parseInt(current_section.total_hours)}}</h5>
</b-modal>
<b-modal id="edit_theme" ok-title="Обновить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_theme" cancel-title="Закрыть" title="Редактирование темы">
  <b-tabs v-if="current_theme.id" card>
    <b-tab title="Основные параметры" active>
      <b-form-group label-size="lg" label="Название раздела">
        <b-form-input v-model="current_theme.name"></b-form-input>
      </b-form-group>
      <b-form-group label-size="lg" label="Количество часов лекций">
        <b-form-input v-model="current_theme.lection_hours"></b-form-input>
      </b-form-group>
      <b-alert show v-if="!have_practice(current_theme)">Знание, закрепленное за данной темой является "сквозным" и не относится к какому-либо умению. Поэтому часы на практику/лабораторные ввести невозможно.</b-alert>      
      <b-form-group label-size="lg" label="Количество часов практик">
        <b-form-input :disabled="!have_practice(current_theme)" v-model="current_theme.practice_hours"></b-form-input>
      </b-form-group>
      <b-form-group label-size="lg" label="Количество часов лабораторных">
        <b-form-input :disabled="!have_practice(current_theme)" v-model="current_theme.lab_hours"></b-form-input>
      </b-form-group>
      <b-form-group label-size="lg" label="Количество часов самостоятельной работы">
        <b-form-input v-model="current_theme.self_hours"></b-form-input>
      </b-form-group>
      <b-form-group label-size="lg" label="Количество часов аттестации">
        <b-form-input v-model="current_theme.attestation_hours"></b-form-input>
      </b-form-group>
      <h5>Всего часов в теме: {{parseInt(current_theme.lection_hours)+parseInt(current_theme.practice_hours)+parseInt(current_theme.lab_hours)+parseInt(current_theme.self_hours)+parseInt(current_theme.attestation_hours)}}</h5>
    </b-tab>
    <!-- <b-tab title="ЗУНы">
      <h5>Знания:</h5>
      <b-form-checkbox-group v-if="current_theme.lection_hours>0" stacked
        v-model="current_theme.zuns.knowledges"
        :options="zuns.knowledges"
        class="mb-3"
        value-field="id"
        text-field="name"
        disabled-field="notEnabled"
      ></b-form-checkbox-group>
      <b-alert v-else show>Тема не содержит часы на лекции, поэтому Вы не можете прикрепить к ней знания</b-alert>
      <h5>Умения:</h5>
      <b-form-checkbox-group v-if="(current_theme.lab_hours>0)||(current_theme.practice_hours>0)" stacked
        v-model="current_theme.zuns.abilities"
        :options="zuns.abilities"
        class="mb-3"
        value-field="id"
        text-field="name"
        disabled-field="notEnabled"
      ></b-form-checkbox-group>
      <b-alert v-else show>Тема не содержит часы на практики/лабораторные, поэтому Вы не можете прикрепить к ней умения</b-alert>
      <h5>Навыки:</h5>
      <b-form-checkbox-group v-if="(current_theme.lab_hours>0)||(current_theme.practice_hours>0)" stacked
        v-model="current_theme.zuns.skills"
        :options="zuns.skills"
        class="mb-3"
        value-field="id"
        text-field="name"
        disabled-field="notEnabled"
      ></b-form-checkbox-group>
      <b-alert v-else show>Тема не содержит часы на практики/лабораторные, поэтому Вы не можете прикрепить к ней навыки</b-alert>
    </b-tab> -->
  </b-tabs>
</b-modal>
</div>
</template>
<script>




export default {
    name: "dpp_stage_work_structure",
    metaInfo: {
        title: "Разработка ДПП - Структура"
    },
  components: {

  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  data () {
    return {
        stage: {},
        sections: [],
        new_theme:{
          name: "",
          lection_hours: 0,
          practice_hours: 0,
          self_hours: 0,
          lab_hours: 0,
          attestation_hours: 0,
          parent_id: '',
          knowledges:[],
        },
        new_section: {name: ""},
        current_section: {themes:[]},
        current_theme: {knowledges: [],zuns : {knowledges:[],abilities:[],skills:[]}},
        zuns: {}
    }
  },
  
  methods: {
      rebuild()
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/rebuild')
        .then(response => {
          axios 
            .get('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/get_sections')
            .then(response => (this.sections = response.data))
          })
      },
      used_hours(){
        let arr = this.sections;
        arr = arr.reduce(function(accumulator, currentValue) {
          return accumulator + currentValue.total_hours;
        },0);
        return arr
      },
      create_theme(knowledge,parent_id)
      {
        this.new_theme.parent_id = parent_id
        if (knowledge !== null)
        {
          this.new_theme.name = knowledge.what.charAt(0).toUpperCase() + knowledge.what.slice(1);
          this.new_theme.knowledges.push(knowledge.id)
        }
        this.$bvModal.show("new_theme")
      },
      count_section_hours(section_id)
      {
          let lec_h = 0; let pr_h = 0; let self_h = 0;
          let section = this.sections.find(elem => elem.id == section_id)
          let idx = this.sections.indexOf(section)
          for (let i = 0; i< section.themes.length; i++)
          {
            lec_h += parseInt(section.themes[i].lection_hours);
            pr_h += parseInt(section.themes[i].practice_hours);
            self_h += parseInt(section.themes[i].self_hours);
          }
          section.lection_hours = lec_h;
          section.practice_hours = lec_h;
          section.self_hours = lec_h;
          section.total_hours =  section.lection_hours + section.practice_hours + section.self_hours;
          this.sections[idx] = section;
      },
      reset_new_theme()
      {
        this.new_theme = {
          name: "",
          lection_hours: 0,
          practice_hours: 0,
          self_hours: 0,
          lab_hours: 0,
          attestation_hours: 0,
          parent_id: '',
          knowledges:[],
        }
      },
      reset_current_section()
      {
        this.current_section = {themes:[]}
      },
      add_section()
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/add_section', {
                  'section_data': this.new_section,
              })
        .then((response) =>{
          this.sections.push(response.data)
        })
      },
      add_theme()
      {
        self = this
        axios
          .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/add_theme', {
                    'theme_data': this.new_theme,
                })
          .then((response) =>{
            
            let parent = this.sections.find(elem => elem.id == this.new_theme.parent_id)
            parent = this.sections.indexOf(parent)
            this.sections[parent] = response.data
           //this.count_section_hours(this.new_theme.parent_id)
          })
          .finally((response) => { 
            this.reset_new_theme()
          })
      },
      delete_theme (id,parent_id)
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/delete_theme', {
                  'id': id,
              })
        .then ((response) =>{
          parent = this.sections.find(elem => elem.id == parent_id)
          parent = this.sections.indexOf(parent)
          this.sections[parent] = response.data
        })
        .finally((response) => { 
            this.reset_new_theme()
        })
      },
      edit_theme (theme)
      {
        this.current_theme = theme
        //this.current_theme.knowledges = []
        this.current_theme.zuns = {knowledges:[],abilities:[],skills:[]}
        this.$bvModal.show("edit_theme")
      },
      update_theme ()
      {
        axios
          .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/update_theme', {
                'theme_data': this.current_theme,
          })
          .then((response) =>{
            let parent = this.sections.find(elem => elem.id == this.current_theme.parent_id)
            parent = this.sections.indexOf(parent)
            this.sections[parent] = response.data
           //this.count_section_hours(this.new_theme.parent_id)
          })
          .finally((response) => { 
            this.reset_current_section()
        })
      },
      edit_section (section)
      {
        this.current_section = section
        this.$bvModal.show("edit_section")
      },
      delete_section (id)
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/delete_section', {
                'id': id,
              })
        .then ((response) =>{
          this.sections = this.sections.filter(elem => elem.id !== id)
          
        })
      },
      update_section ()
      {
        axios
          .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/update_section', {
                'section_data': this.current_section,
          })
          .then((response) =>{
            let parent = this.sections.find(elem => elem.id == this.current_section.id)
            parent = this.sections.indexOf(parent)
            this.sections[parent] = response.data
            //this.count_section_hours(this.new_theme.parent_id)
          })
          .finally((response) => { 
            this.reset_new_theme()
        })
      },
      move_up(id,parent_id)
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/move_up', {
                'id': id,
                'parent_id': parent_id
              })
        .then ((response) =>{
          if (parent_id == null)
          {
            let section = this.sections.find(elem => elem.id == id)
            let idx = this.sections.indexOf(section)
            this.sections[idx].position -= 1;
            this.sections[idx-1].position += 1;
            this.sections.sort(function(a, b) {
              return parseInt(a.position) - parseInt(b.position)
            })
          }else{
            parent = this.sections.find(elem => elem.id == parent_id)
            parent = this.sections.indexOf(parent)
            this.sections[parent] = response.data
            this.reset_new_theme()
          }
        })
      },
      move_down(id,parent_id)
      {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/structure/'+this.stage.st_version_id+'/move_down', {
                'id': id,
                'parent_id': parent_id
              })
        .then ((response) =>{
          if (parent_id == null)
          {
            let section = this.sections.find(elem => elem.id == id)
            let idx = this.sections.indexOf(section)
            this.sections[idx].position += 1;
            this.sections[idx+1].position -= 1;
            this.sections.sort(function(a, b) {
              return parseInt(a.position) - parseInt(b.position)
            })
          }else{
            parent = this.sections.find(elem => elem.id == parent_id)
            parent = this.sections.indexOf(parent)
            this.sections[parent] = response.data
            this.reset_new_theme()
          }
        })
      },
      have_practice(theme)
      {
        let result = false
        for (let i=0; i< theme.knowledges.length;i++)
        {
          if (theme.knowledges[i].ability_id != null)
          {
            result = true
          }
        }
        return result
      }
  },
  mounted() {
      var self = this
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (function (response){           
          axios 
            .get('/dpps/'+self.$route.params.dpp+'/structure/'+self.stage.st_version_id+'/get_sections')
            .then(response => (self.sections = response.data))
          axios 
            .get('/dpps/'+self.$route.params.dpp+'/structure/'+self.stage.st_version_id+'/get_zuns')
            .then(response => (self.zuns = response.data))
        })

        
  }
}
</script>