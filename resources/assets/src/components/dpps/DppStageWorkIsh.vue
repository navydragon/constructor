<template>
    <div>
        <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
            <b-card :title="header">
                <b-alert show variant="info">Заполните предолженные поля формы. Для сохранения данных, нажмите кнопку "Сохранить". Для перехода к следующему этапу нажмите кнопку "Перейти к следующему этапу".
                    <br> <em>* так как система работает в тестовом режиме, предусматривается возможность заполнения полей формы ЛЮБЫМИ (более 10 символов) данными, без дополнительной проверки</em>                    
                </b-alert>
                <h4>ОБЩИЕ ПОЛОЖЕНИЯ</h4>
                <h5>Требования к обучающимся</h5>
                <h5>Требования к уровню профессионального образования </h5>
                <b-form-row class="m-0">
                    <b-form-group>
                        <b-form-checkbox-group :state="ish_data.pl.length>0" id="checkbox-group" :options="prof_levels_arr" stacked v-model="ish_data.pl" name="selectedItems"> </b-form-checkbox-group>
                    </b-form-group>
                </b-form-row>
                <h5>Требования к квалификации</h5>
                <div>
                    <b-form-textarea
                    id="req_user_kval"
                    v-model="ish_data.req_user_kval"
                    :state="ish_data.req_user_kval.length >= 10"
                    placeholder="Квалификация – это совокупность профессиональных компетенций и опыта работника, необходимых для осуществления им профессиональной деятельности."
                    rows="3"
                    ></b-form-textarea>
                    <br>
                </div>
                <h4>ЦЕЛЬ И ЗАДАЧИ ОСВОЕНИЯ</h4>
                <h5>Цель освоения</h5>
                <p class="text-justify">
                    Целью освоения программы являются совершенствование и (или) получение новой компетенции, необходимой для профессиональной деятельности, и (или) повышение профессионального уровня в рамках имеющейся квалификации в области профессиональной деятельности.
                </p>
                <h5>Задачи освоения</h5>
                <div>
                   <p class="text-justify">Задачами освоения программы являются:
                    <ul>
                        <li>приобретение обучающимися знаний, умений и навыков в соответствии с учебным планом и календарным графиком учебного процесса;</li>
                        <li>оценка достижений обучающимися планируемых результатов обучения.</li>
                    </ul>
                    </p>
                </div>
                 <b-alert v-if="show_errors" show variant="danger">
                     <strong>Обнаружены ошибки!</strong>
                     <ul v-for="(error,index) in errors" :key="index">
                         <li>{{error}}</li>
                     </ul>
                 </b-alert>
                <div class="row">
                    <div class="col-md-6"><b-button block variant="primary" @click="save()">Сохранить</b-button></div>
                    <div class="col-md-6"><b-button block variant="success" @click="go_forward()">Перейти к следующему этапу</b-button></div>
                </div>
            </b-card>
    </div>
</template>

<script>
export default {
  name: "dpp_stage_work_ish",
  metaInfo: {
    title: "Разработка ДПП - Исходные данные"
  },
  data () {
      return {
        stage: {},
        show_errors: false,
        errors: [],
        isBusy: true,
        ish_data : {
          req_user_edulevel: '',
          req_user_kval: '',
          target: '',  
          tasks: '',
          pl: [],  
        },
        prof_levels_arr: []
      }
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  methods: {
    get_ish_versions_data()
    {
        axios
        .get('/dpps/'+this.$route.params.dpp+'/get_ish_version_data/'+ this.stage.ish_version_id)
        .then(response => (this.ish_data = response.data))
    },
    save()
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/update_ish_version_data/'+ this.stage.ish_version_id,{
            'ish_data': this.ish_data,
        })
    },
    go_forward()
    {
        this.show_errors = false 
        this.errors = []
        if (this.ish_data.req_user_kval.length < 10) {this.errors.push("Некорректно введены Требования к квалификации")}
        if (this.ish_data.pl.length == 0) {this.errors.push("Не выбраны требования к уровню профессионального образования")}
        if (this.errors.length > 0)
        {
            this.show_errors = true
        }else{
            this.show_errors = false 
            this.save()
            this.$nextTick(() => {
                var self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/'+ this.stage.id+'/go_next')
                .then(() => (this.$router.push('/my_dpps/'+this.$route.params.dpp+'/overview/1')))
            })
        }
    }
  },
  mounted() {
      axios
       .get('/dpps/get_prof_levels')
       .then(response => (this.prof_levels_arr = response.data))

      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally( () =>( this.get_ish_versions_data() ) )  
      
     
  }
}
</script>