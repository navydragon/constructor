<template>
  
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
           <div v-for="st in dpp.stages">
               <div v-if="curStage == st.id">
                 <h5>{{dpp.stages.indexOf(st)+1}}. {{st.name}}</h5>
                 <p><strong>Статус этапа:</strong> {{st.stage_status_name}}</p>
                 <b-button @click="start_work(st.id)" v-if="st.stage_status_id==1" block variant="primary">Начать работу</b-button>
                 <router-link v-if="st.stage_status_id==3" icon="ion ion-md-person" :to="{name: 'dpp_stage_work', params: { dpp: dpp.id,stage: st.id }}" :exact="true">
                    <b-button  block variant="primary">Продолжить работу</b-button>
                 </router-link>
               </div>
           </div>
       </div>
    </div>
  </b-card>
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
      curStage: 1,
    };
  },
  computed: {
    header() {
        return 'Разработка ДПП / '+this.dpp.name
    }
  },
  methods: {
    setcurStage (st){
        this.curStage = st.id;
    },
    start_work(id){
        axios
        .post('/dpps/stages/start', {
            'stage_id': id
        })
        .then(() => ( this.$router.push('/my_dpps/'+this.dpp.id+'/stages/'+id+'/work')));
    }
  },
  mounted () {
        axios
        .get('/dpps/'+ this.$route.params.dpp+'/overview')
        .then(response => (this.dpp = response.data))
        .finally(() => (this.curStage = this.dpp.current_stage_id) )
    }
};
</script>