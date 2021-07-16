<template>
    <div>
        <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
            <b-card :title="header">
                <b-alert show variant="info">Заполните предолженные поля формы. Для сохранения данных, нажмите кнопку "Сохранить". Для перехода к следующему этапу нажмите кнопку "Перейти к следующему этапу".
                    <br> <em>* так как система работает в тестовом режиме, предусматривается возможность заполнения полей формы ЛЮБЫМИ (более 10 символов) данными, без дополнительной проверки</em>                    
                </b-alert>
                <h4>ОБЩИЕ ПОЛОЖЕНИЯ</h4>
                <h5>Нормативные правовые основания разработки</h5>
                <b-alert show variant="info">Выберите нормативные документы (ПрофСтандарты, КвалТребования, ФГОСы), на основе которых разрабатывается ДПП.</b-alert>
                <h5>Программа разработана на основе:</h5>
                <p><strong>Профессиональных стандартов ({{ish_data.prof_standarts.length}})</strong> <choose-profstandart v-if="!isBusy" @select_profstandarts="select_profstandarts" :profstandarts="ish_data.prof_standarts"></choose-profstandart></p>
                <ul>
                    <li v-for="elem in ish_data.prof_standarts" :key="'ps_'+elem.id">{{elem.code}} - {{elem.name}}</li>
                </ul>
                <p><strong>Установленных квалификационных требований по должностям ({{ish_data.dolg_kvals.length}})</strong> <choose-dolgkval v-if="!isBusy" @select_dolgkvals="select_dolgkvals" :dolgkvals="ish_data.dolg_kvals"></choose-dolgkval></p>
                <ul>
                    <li v-for="elem in ish_data.dolg_kvals" :key="'dk_'+elem.id">{{elem.name}}</li>
                </ul>
                <p><strong>Требований федеральных государственных образовательных стандартов ({{ish_data.fgoses.length}})</strong> <choose-fgos v-if="!isBusy" @select_fgoses="select_fgoses" :fgoses="ish_data.fgoses"></choose-fgos></p>
                <ul>
                    <li v-for="elem in ish_data.fgoses" :key="'fg_'+elem.id">{{elem.code}} - {{elem.name}}</li>
                </ul>
                <hr>
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
                 <div>
                    <b-alert show>Укажите, формирует ли ДПП новую компетенцию или совершенствует имеющуюся. Цель и задачи ДПП . </b-alert>
                </div>
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
                <div>
                    <h5>Планируемые результаты освоения</h5>
                    <p class="text-justify">
                        Программа направлена на:
                    </p>
                    <b-form-radio v-model="ish_data.make_new_competence" name="pres-radio" value="1">Получение у обучающихся новой компетенции, необходимой для профессиональной деятельности</b-form-radio>
                    <b-form-radio v-model="ish_data.make_new_competence" name="pres-radio" value="0">Совершенствование компетенции, необходимой для профессиональной деятельности и (или) повышение профессионального уровня в рамках имеющейся квалификации</b-form-radio>
                </div>
                <hr>
                <h4>ТИПОВАЯ СТРУКТУРА ДПП</h4>
                <h5>Разделы типологии:</h5>
                <new-dtp @add_dtp="add_typology_part" :key="'ds'"></new-dtp>
                <div v-for="(elem,index) in ish_data.typology_parts" :key="elem.id" class="m-3">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="m-0"> {{index+1}}. {{elem.name}}</p>
                        </div>
                        <div class="col-md-3">
                           <b-btn v-if="index!=0" variant="outline-info icon-btn btn-sm" class="btn" @click="move_up(elem)"><i class="ion ion-md-arrow-round-up"></i></b-btn>
                           <b-btn v-if="index!=ish_data.typology_parts.length-1" variant="outline-info icon-btn btn-sm" class="btn" @click="move_down(elem)"><i class="ion ion-md-arrow-round-down"></i></b-btn>
                           <b-btn  @click="edit_typology_part(elem)"  variant="outline-primary btn-sm"> <i class="ion ion-md-create"></i></b-btn>
                           <b-btn  @click="remove_typology_part(elem)"  variant="outline-danger btn-sm"> <i class="ion ion-md-close"></i></b-btn>
                        </div>
                    </div>
                </div>
                <!-- <div>
                    <b-alert show>Выберите наиболее подходящую типовую структуру ДПП. Типовая структура состоит из разделов, которых слудеует придерживаться во время разработки ДПП.</b-alert>
                </div>
                <div v-if="!isBusy" class="row">
                    <div class="col-md-6">
                        <h5>Виды типовых структур</h5>
                        <b-form-radio v-model="ish_data.typology" v-for="elem in ish_data.typologies" :key="elem.id" name="some-radios" :value="elem.id">{{elem.name}}</b-form-radio>

                    </div>
                    <div class="col-md-6">
                    <div  v-for="elem in ish_data.typologies" :key="elem.id"> 
                        <div  v-if="ish_data.typology == elem.id">
                            <h5>Типовые разделы ДПП ({{elem.name}})</h5>
                            <ul>
                                <li v-for="(part,index) in elem.parts" :key="index">{{part.name}}</li>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div> -->
                <hr>
                <h4>НОРМАТИВНО-СПРАВОЧНАЯ ИНФОРМАЦИЯ </h4>
                <div>
                    <b-alert show>Добавьте названия источников НСИ, которые будут использованы в ДПП. Вы также сможете дополнить данный список на последующих этапах разработки ДПП.</b-alert>
                </div>
                <nsis v-if="!isBusy" :ish_version_id="stage.ish_version_id"></nsis>
                <hr>
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
        <b-modal
            id="edit_modal"
            :key="edit_item.id"
            ref="modal"
            title="Редактирование раздела в программе"
            ok-title="Сохранить"
            size="lg"
            cancel-title="Закрыть"
            @ok="update_typology_part">
                <b-form-group
                label="Название раздела"
                invalid-feedback="Необходимо ввести название"
                label-size="lg"
                >
                 <b-form-textarea
                             v-model="edit_item.name"
                            rows="3"
                            max-rows="6"
                            required
                ></b-form-textarea>
                </b-form-group>
         </b-modal>
    </div>
</template>

<script>
import Nsis from '@/components/nsis/Nsis'
import ChooseProfstandart from '@/components/profstandarts/ChooseProfstandart'
import ChooseDolgkval from '@/components/dolgkvals/ChooseDolgkval'
import ChooseFgos from '@/components/fgoses/ChooseFgos'
import Swal from 'sweetalert2'
import NewDtp from '@/components/typologies/NewDppTypologyPart'
export default {
  name: "dpp_stage_work_ish",
  components: {Nsis,ChooseProfstandart,ChooseDolgkval,ChooseFgos,NewDtp},
  metaInfo: {
    title: "Разработка ДПП - Исходные данные"
  },
  data () {
      return {
        stage: {},
        show_errors: false,
        errors: [],
        isBusy: true,
        edit_item: {},
        ish_data : {
          req_user_edulevel: '',
          req_user_kval: '',
          target: '',  
          tasks: '',
          pl: [],
          nsi_types: [],
          nsis: [],
          typologies: [],
          prof_standarts: [],
          dolg_kvals: [],
          fgoses: [],
          typology: {},
          make_new_competence: '',
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
        .finally(() => (this.isBusy=false))
    },
    select_profstandarts(data)
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/ish_version_data/'+ this.stage.ish_version_id+'/select_profstandarts',{data: data})
        .then(response => (this.ish_data.prof_standarts = response.data))
        .finally(() => (this.$bvModal.hide("modal-choose-ps") ))
    },
    select_dolgkvals (data)
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/ish_version_data/'+ this.stage.ish_version_id+'/select_dolgkvals',{data: data})
        .then(response => (this.ish_data.dolg_kvals = response.data))
        .finally(() => (this.$bvModal.hide("modal-choose-dk") ))
    },
    select_fgoses (data)
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/ish_version_data/'+ this.stage.ish_version_id+'/select_fgoses',{data: data})
        .then(response => (this.ish_data.fgoses = response.data))
        .finally(() => (this.$bvModal.hide("modal-choose-fg") ))
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
        if (this.ish_data.typology == null) {this.errors.push("Не выбрано типовое содержание ДПП")}
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
    },
     typology() {
          var t = this.ish_data.typologies.find(el => el.id == this.ish_data.typology)
      },
    
    add_typology_part(data)
    {
        axios
        .post('/dpp_typology_parts/add_dtp',{
            dtp_name: data,
            dpp_id: this.$route.params.dpp,
            ish_version_id: this.ish_data.id,
            typology_id: this.ish_data.typology_id,
            })
        .then((response) => (this.ish_data.typology_parts.push(response.data)))
        .finally(()=>(this.$bvModal.hide("modal-newdtp")))
    },
    edit_typology_part(elem)
    {
         this.edit_item = elem
          this.$nextTick(() => {
                    this.$bvModal.show("edit_modal")
          })
    },
    update_typology_part()
    {
        axios
        .post('/dpp_typology_parts/update_dtp', this.edit_item)
        .then ((response) => {
            var upd_item =  this.ish_data.typology_parts.find(item => item.id == response.data.id)
            upd_item = response.data
            self.$bvModal.hide("edit_modal")
        })
    },
    remove_typology_part(elem)
    {
        Swal.fire({
            title: 'Удалить типовой раздел?',
            text: "Знания, прикрепленные к данному разделу, будут отсоединены",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Удалить',
            cancelButtonText: 'Отмена'
            }).then((result) => {
            if (result.isConfirmed) {
                axios.post("/dpp_typology_parts/remove",{'part':elem.id})
                .then ((response) => (this.ish_data.typology_parts = response.data))
                .finally ((response) =>{
                    Swal.fire(
                    'Успех!',
                    'Типовой раздел удален.',
                    'success'
                    ) })
            }
        })
    },
    move_up(elem)
    {
        axios.post("/dpp_typology_parts/move_up",{'part':elem.id})
        .then ((response) => (this.ish_data.typology_parts = response.data))
    },
    move_down(elem)
    {
        axios.post("/dpp_typology_parts/move_down",{'part':elem.id})
        .then ((response) => (this.ish_data.typology_parts = response.data))
    },
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

<style>
.alert{
    color: black;
}
</style>