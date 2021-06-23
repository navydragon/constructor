<template>
<div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <b-card :title="header">
    </b-card>
    <b-card  no-body>
      <b-tabs card>
        <b-tab title="Управление" active>
          <div>
            <b-card v-for="section in sections"  :key="'s_'+section.id" class="mt-2 mb-1">
              <b-card-header header-tag="header" class="p-1" role="tab">
                <b-row>
                  <b-col cols="10">
                    <h4>{{section.position}}. {{section.name}}</h4>
                  </b-col>
                  <b-col>
                    <b-button-group class="ml-auto">
                            <b-btn v-if="section.position != 1" variant="primary icon-btn" class="btn" @click.prevent=""><i class="ion ion-md-arrow-round-up"></i></b-btn>
                            <b-btn variant="primary icon-btn" class="btn" @click.prevent=""><i class="ion ion-md-arrow-round-down"></i></b-btn>
                            <b-btn variant="primary icon-btn" class="btn" @click.prevent=""><i class="ion ion-md-create"></i></b-btn>
                      </b-button-group>
                  </b-col>
                </b-row>
                <hr>
              </b-card-header>
              <div class="mt-2">
                <b-button v-b-toggle="'ks_'+section.id" variant="warning">Знания ({{section.knowledges.length}})</b-button>
                <b-collapse :id="'ks_'+section.id" class="mt-2">
                  <b-list-group>
                    <b-list-group-item v-for="knowledge in section.knowledges" :key="'k_'+knowledge.id">{{knowledge.name}}
                      <span class="float-right"><b-button variant="outline-primary">+</b-button></span>
                    </b-list-group-item>
                  </b-list-group>
                </b-collapse>
              </div>
              <div class="mt-2">
                <b-button v-b-toggle="'st_'+section.id" variant="info">Темы ({{section.themes.length}})</b-button>
                <b-collapse :id="'st_'+section.id" class="mt-2">
                  <b-list-group>
                    <b-list-group-item v-for="theme in section.themes" :key="'t_'+theme.id">{{theme.name}}</b-list-group-item>
                  </b-list-group>
                </b-collapse>
              </div>
            </b-card>
          </div>
        </b-tab>
        <b-tab title="Таблица">
          <b-card-text>Tab contents 2</b-card-text>
        </b-tab>
        <b-tab title="Знания">
          <b-card-text>Tab contents 2</b-card-text>
        </b-tab>
      </b-tabs>
    </b-card>
      
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

    }
  },
  
  methods: {
      
  },
  mounted() {
      var self = this
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (function (response){ 
            
            axios
              .get('/dpps/'+self.$route.params.dpp+'/get_sections/'+ self.stage.st_version_id)
              .then(response => (self.sections = response.data))
        })
  }
}
</script>