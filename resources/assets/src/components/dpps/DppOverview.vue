<template>
  <div>
    <router-link icon="ion ion-md-person" :to="{name: 'my_dpps'}" :exact="true">
                <b-button  variant="info">
                   <i class="ion ion-ios-arrow-back" style="font-size:20px;"></i> Назад
                </b-button>
    </router-link>
    <b-card :title="header">
      <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-3">
          <h5>Этапы разработки:</h5>  
          <div class="ui-bordered mb-4">
            <div v-for="st in dpp.stages" class="d-flex align-items-center w-100 py-2 px-3" :class="{'bg-lighter font-weight-bold': curStage == st.id}">
              <a href="#" @click="setcurStage(st)" class="d-block text-body">{{dpp.stages.indexOf(st)+1}}. {{st.name}}</a>
            </div>
          </div>
        </div>
        <div class="col-md-8 col-lg-8 col-xl-9">
            <b-alert v-if="curStage==false" show variant="info">Выберите этап</b-alert>
            <div v-for="st in dpp.stages">
                <div v-if="curStage.id == st.id">
                  <h5>{{dpp.stages.indexOf(st)+1}}. {{st.name}}</h5>
                  <p><strong>Статус этапа:</strong> {{st.stage_status_name}}</p>
                  <b-button @click="start_work(st.id)" v-if="st.stage_status_id==1" block variant="primary">Начать работу</b-button>

                  <router-link v-if="st.stage_type_id==1 &&st.stage_status_id!=1 && st.stage_status_id!=2" icon="ion ion-md-person" :to="{name: 'dpp_stage_work', params: { dpp: dpp.id,stage: st.id }}" :exact="true">
                      <b-button  block variant="primary">Продолжить работу</b-button>
                  </router-link>
                  <router-link v-if="st.stage_type_id==2 && st.stage_status_id!=1 && st.stage_status_id!=2" icon="ion ion-md-person" :to="{name: 'dpp_stage_work_om', params: { dpp: dpp.id,stage: st.id }}" :exact="true">
                      <b-button  block variant="primary">Продолжить работу</b-button>
                  </router-link>
                  <router-link v-if="st.stage_type_id==6 && st.stage_status_id!=1 && st.stage_status_id!=2" icon="ion ion-md-person" :to="{name: 'dpp_stage_work_ish', params: { dpp: dpp.id,stage: st.id }}" :exact="true">
                      <b-button  block variant="primary">Продолжить работу</b-button>
                  </router-link>
                  
                </div>
            </div>
        </div>
      </div>
    </b-card>
  </div>
</template>

<script>
export default {
  name: "my_dpps_overview",
  metaInfo: {
    title: "Разработка ДПП"
  },
  data() {
    return {
      dpp: {},
      stage_types: [],
      isBusy: true,
      curStage: false,
    };
  },
  computed: {
    header() {
        return 'Разработка ДПП / '+this.dpp.name
    }
  },
  methods: {
    setcurStage (st){
        this.curStage = st;
    },
    start_work(id){
        var self = this
        axios
        .post('/dpps/stages/start', {
            'stage_id': id
        })
        .then( function () {
          if (self.curStage.stage_type_id == 6)
          {
            self.$router.push('/my_dpps/'+self.dpp.id+'/stages/'+id+'/work_ish')
          }
          if (self.curStage.stage_type_id == 1)
          {
            self.$router.push('/my_dpps/'+self.dpp.id+'/stages/'+id+'/work')
          }
          if (self.curStage.stage_type_id == 2)
          {
            self.$router.push('/my_dpps/'+self.dpp.id+'/stages/'+id+'/work_om')
          }
        }
          )
    }
  },
  mounted () {
        var self = this
        axios
        .get('/dpps/'+ this.$route.params.dpp+'/overview')
        .then(response => (this.dpp = response.data))
        .finally( function(){
         var a = self.dpp.stages.filter(function(number) {
            return number.id == self.dpp.current_stage_id
          });
          self.curStage = a[0]
          //() => (this.curStage.id = this.dpp.current_stage_id) 
        })
    }
};
</script>