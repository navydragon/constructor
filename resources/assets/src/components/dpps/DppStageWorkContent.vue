<template>
<div>
  <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
  <b-card :title="header">
  </b-card>
  <b-card  no-body>
    <table class="table table-bordered">
            <thead>
              <tr><th rowspan="2">Наименование разделов</th><th colspan="3">Трудоемкость, ч</th><th rowspan="2">Контент лекции</th><th rowspan="2">Контент практик/лаб.</th></tr>
              <tr><th>Итого</th><th>Лек.</th><th>Пр/Лаб.</th></tr>
            </thead>
            <tbody>
              <template v-for="section in sections" >
              <tr :key="'ps_'+section.id">
                <td>
                  <p class="mb-1"><strong>{{section.position}}. {{section.name}}</strong></p>
                </td>
                <td>{{section.total_hours}}</td>
                <td>{{section.lection_hours}}</td>
                <td>{{section.practice_hours + section.lab_hours}}</td>
                <td></td>
                <td></td>
              </tr>
              <tr v-for="theme in section.themes" :key="'ts_'+theme.id">
                <td>
                  <p class="mb-1">{{section.position}}.{{theme.position}} {{theme.name}}</p>
                </td>
                <td>{{theme.total_hours}}</td>
                <td>{{theme.lection_hours}}</td>
                <td>{{theme.lab_hours+theme.practice_hours}}</td>
                <td>
                  <span v-if="theme.lections.length==0"><b-button size="sm" @click="create_lection(theme)" variant="primary">Создать</b-button></span>
                  <span v-else><b-button size="sm" @click="edit_lection(theme)" variant="primary">Редактировать</b-button></span>
                </td>
                <td>
                  <div v-if="(theme.lab_hours+theme.practice_hours)>0">
                    <span v-if="theme.lections.length==0">Создать</span>
                    <span v-else>Редактировать</span>
                  </div>
                  <div v-else>
                    <i>Не запланированы</i>
                  </div>
                </td>
              </tr>
              </template>
            </tbody>
          </table>
  </b-card>

</div>
</template>

<script>

export default {
    name: "dpp_stage_work_content",
    metaInfo: {
        title: "Разработка ДПП - Контент"
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
    }
  },
  
  methods: {
    create_lection(theme)
    {
      axios
        .post('/dpps/'+this.$route.params.dpp+'/content/'+this.stage.ct_version_id+'/add_lection', {
                  'theme': theme,
              })
        .then ((response) =>{
         //idx = this.section.themes.indexOf(theme)
         //console.log(idx)
         theme.lections.push(response.data)
        })
        .finally((response) => {
          this.edit_lection(theme)
          //console.log(response.data)
        })
    },
    edit_lection(theme)
    {
      this.$router.push({ path: `/my_dpps/`+this.$route.params.dpp+`/stages/`+ this.stage.id+`/work_content/${theme.lections[0].id}` })
    },
    go_forward()
    {
      axios
      .post('/dpps/'+this.$route.params.dpp+'/'+ this.stage.id+'/go_next')
      .then(() => (this.$router.push('/my_dpps/'+this.$route.params.dpp+'/overview/1')))      
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
        })     
  }
}
</script>