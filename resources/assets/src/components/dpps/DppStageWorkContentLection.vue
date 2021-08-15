<template>
<div>
  <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
  <b-overlay :show="isBusy" rounded="sm">
    <b-card :title="header" v-if="lection.parent">
        <h5>Лекция: {{lection.parent.position}}.{{lection.section.position}} {{lection.name}}</h5>
        <h5>Планируемые результаты обучения:</h5>
        <ul><li v-for="knowledge in lection.knowledges" :key="'kn'+knowledge.id">{{knowledge.name}}</li></ul>
        <h5>Трудоёмкость лекции: {{lection.section.total_hours}} ак. час.</h5>
    </b-card>
    <b-card no-body>
        <div>
            <b-tabs content-class="mt-3">
                <b-tab class="m-3" title="Контент" active>      
                    <b-row>
                        <b-col cols="3">
                            <h5>Структура лекции</h5>
                            <em v-if="lection.parts.length<2">Лекция пока не содержит разделы.</em>
                            <b-list-group v-else>
                            <div v-for="part in lection.parts" :key="'p'+part.id">
                            <b-list-group-item  v-if="part.position > 0" :id="'p'+part.id"  button @click="choose_part(part.id)" class="flex-column align-items-start pt-1 pr-1">
                                <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                # {{lection.parent.position}}.{{lection.section.position}}.{{part.position}}
                                </h5>
                                <small>
                                    <b-button-group> 
                                        <b-btn size="sm" v-if="part.position > 1" variant="info icon-btn" class="btn" @click.prevent="move_up(part.id)"><i class="ion ion-md-arrow-round-up"></i></b-btn>
                                        <b-btn size="sm" v-if="part.position != lection.parts.length-1" variant="info icon-btn" class="btn" @click.prevent="move_down(part.id)"><i class="ion ion-md-arrow-round-down"></i></b-btn>
                                        <b-btn size="sm" variant="danger icon-btn" class="btn" @click.prevent="delete_part(part.id)"><i class="ion ion-md-close"></i></b-btn>                
                                    </b-button-group>
                                </small>
                                </div>
                                <p class="mb-1">
                                    {{part.name}}
                                </p>
                                <small>Слов: {{part.words}} Символов: {{part.symbols}}</small>
                            </b-list-group-item>
                            </div>
                            </b-list-group>
                            <hr>
                            <b-button  v-b-modal="'new_part'" size="sm" variant="primary">Добавить раздел</b-button>
                            <b-modal ok-title="Добавить" cancel-title="Отмена" @ok="add_lection_part" id="new_part" no-close-on-backdrop title="Добавить раздел лекции">
                                <b-form-group label="Название раздела">
                                    <b-form-input v-model="new_part.name" trim></b-form-input>
                                </b-form-group>
                            </b-modal>
                        </b-col>
                        <b-col cols="9">
                            <b-form-group v-if="lection.parts.length>1" label="Название раздела">
                                <b-form-input v-model="current_part.name" trim></b-form-input>
                            </b-form-group>
                            <b-form-group v-if="lection.parts.length>1" label="Контент раздела">
                                <ckeditor :editor="editor" v-model="current_part.text" :config="editorConfig"></ckeditor>
                            </b-form-group>
                            <b-form-group v-else label="Контент лекции">
                                <ckeditor :editor="editor" v-model="current_part.text" :config="editorConfig"></ckeditor>
                            </b-form-group>
                            <b-button @click="update_part" variant="primary"> Сохранить</b-button>
                            <b-button @click="check" variant="info">Проверить</b-button>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab class="m-3" title="Тестовые вопросы">
                    <div v-for="knowledge in lection.knowledges" :key="'kn2'+knowledge.id">
                        <ol>
                            <li v-for="question in knowledge.questions" :key="'q'+question.id">{{question.text}}
                                <ul>
                                <li v-for="answer in question.answers" :key="'a'+answer.id">{{answer.text}}</li>
                            </ul>
                            </li>         
                        </ol>
                    </div>          
                </b-tab>
                <b-tab class="m-3" title="Литература">
                    <nsi-choose @change_nsi="change_nsi" :mode="'work'" :selected="lection.nsis" v-if="!isBusy" :ish_version_id="stage.ish_version_id"></nsi-choose>
                </b-tab>
                <b-tab class="m-3" title="МТО">

                </b-tab>
            </b-tabs>
        </div>
    </b-card>
  </b-overlay>
</div>
</template>

<script>
import ClassicEditor from '@navydragon/ckeditor5-constructor-build'
import Swal from 'sweetalert2'
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
    name: "dpp_stage_work_content_lection",
    metaInfo: {
        title: "Разработка ДПП - Контент"
    },
  components: {
      NsiChoose
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  data () {
    return {
        stage: {},
        editor: ClassicEditor,
        editorData: '<p>Content of the editor.</p>',
        content: {},
        lection: {parts:[]},
        words: 0,
        isBusy: true,
        characters: 0,
        new_part : {name: ""},
        current_part: {name: "",text: "Контент..."},
        editorConfig: {
            toolbar: {
					items: [
						'undo',
						'redo',
						'heading',
                        'alignment',
						'|',
						'bold',
						'italic',
						'strikethrough',
						'underline',
						'subscript',
						'superscript',
						'specialCharacters',
						'removeFormat',
						'|',
						'bulletedList',
						'numberedList',
						'outdent',
						'indent',
						'|',
						'imageUpload',
						'blockQuote',
						'insertTable',
						'mediaEmbed',
						'fontBackgroundColor',
						'fontColor',
						'highlight',
						'horizontalLine',
						'imageInsert',
						'pageBreak',
						'|',
						'wproofreader'
					]
				},
			language: 'ru',
            wordCount: {
                onUpdate: stats => {
                // Prints the current content statistics.
                this.current_part.words =  stats.words
                this.current_part.symbols =  stats.characters 
                
            }
            },
            image: {
                toolbar: [
                    'imageTextAlternative',
                //    'imageStyle:full',
                    'imageStyle:side',
                    'toggleImageCaption'
                ],
            }, 
            wproofreader: {
                serviceId: 'zyHAR8htx1Tutf7',
                srcUrl: 'https://svc.webspellchecker.net/spellcheck31/wscbundle/wscbundle.js',
                lang:'ru_RU',
                theme:'gray',
                removeBranding:true,
                localization:'ru',
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'toggleTableCaption'
                ]
            },
            licenseKey: '',
        }

    }
  },
  
  methods: {
    check ()
    {
        let kek = this.current_part.text
       let els = kek.match(/<figure class=\"image\".*?><\/figure>/ig);
       console.log(els)
       // var wscInstances = WEBSPELLCHECKER.getInstances();
       // console.log(wscInstances[0])
       // wscInstances[0].openDialog();
        
    },
    add_lection_part()
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/content/'+this.$route.params.lection+'/add_lection_part',{
                'part_data': this.new_part,
          })
        .then(response => (this.lection.parts.push(response.data)))
        .finally(response => {
            this.choose_part(this.lection.parts[this.lection.parts.length-1].id)
            this.new_part = {name: ""}
            })
    },
    choose_part(id)
    {
        var elems = document.querySelectorAll(".list-group-item");
        [].forEach.call(elems, function(el) {
            el.classList.remove("active");
        });
        if (this.lection.parts.length > 1)
        {
            document.getElementById('p'+id).classList.add("active")
        }    
        this.current_part = this.lection.parts.find(el => el.id == id)
    },
    update_part()
    {
        axios
        .post('/dpps/'+this.$route.params.dpp+'/content/'+this.$route.params.lection+'/update_part',{
                'part_data': this.current_part,
          })
        .then(response => {
             Swal.fire(
                    'Успех!',
                    'Данные сохранены.',
                    'success'
                    )
        })
    },
    move_up(id)
    {
       this.isBusy = true
       axios
        .post('/dpps/'+this.$route.params.dpp+'/content/parts/'+id+'/move_up')
        .then ((response) =>{
            let part = this.lection.parts.find(elem => elem.id == id)
            let part_idx = this.lection.parts.indexOf(part)
            let prev = this.lection.parts.find(elem =>parseInt(elem.position) == parseInt(part.position)-1)
            let prev_idx = this.lection.parts.indexOf(prev)
            let temp = this.lection.parts[part_idx]
            this.lection.parts[part_idx].position -= 1
            this.lection.parts[prev_idx].position += 1
            this.lection.parts[part_idx] = this.lection.parts[prev_idx]
            this.lection.parts[prev_idx] = temp
        })
        .finally ((response) =>{
            this.isBusy = false
        })
    },
    move_down(id)
    {
       this.isBusy = true
       axios
        .post('/dpps/'+this.$route.params.dpp+'/content/parts/'+id+'/move_down')
        .then ((response) =>{
            let part = this.lection.parts.find(elem => elem.id == id)
            let part_idx = this.lection.parts.indexOf(part)
            let next = this.lection.parts.find(elem =>parseInt(elem.position) == parseInt(part.position)+1)
            let next_idx = this.lection.parts.indexOf(next)
            let temp = this.lection.parts[part_idx]
            this.lection.parts[part_idx].position += 1
            this.lection.parts[next_idx].position -= 1
            this.lection.parts[part_idx] = this.lection.parts[next_idx]
            this.lection.parts[next_idx] = temp
        })
        .finally ((response) =>{
            this.isBusy = false
        })
    },
    delete_part(id)
    {
        Swal.fire({
            title: 'Удалить раздел?',
            text: "Контент этого раздела также будет удален",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Удалить',
            cancelButtonText: 'Отмена'
            }).then((result) => {
            if (result.isConfirmed) {
                axios
                .post('/dpps/'+this.$route.params.dpp+'/content/parts/'+id+'/remove')
                .then ((response) =>{
                    let part = this.lection.parts.find(el => el.id == id)
                    let position = part.position
                    this.lection.parts = this.lection.parts.filter(el => el.id != id)
                    for (let i = 0; i < this.lection.parts.length; i++)
                    {
                        if (parseInt(this.lection.parts[i].position) > parseInt(position))
                        {
                            this.lection.parts[i].position --
                        }
                    }
                })
                .finally ((response) =>{
                    this.choose_part(this.lection.parts[this.lection.parts.length-1].id)
                    Swal.fire(
                    'Успех!',
                    'Раздел удален.',
                    'success'
                    ) })
                
            }
        })
    },
    change_nsi (data) {
       this.lection.nsis = data.nsi_data
      },


  },
  mounted() {
      var self = this
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally (response => {
            axios
            .get('/dpps/'+this.$route.params.dpp+'/content/get_lection/'+ this.$route.params.lection)
            .then(response => {
                this.lection = response.data
            })
            .finally (response => {
                if (this.lection.parts.length > 0)
                {
                    this.choose_part(this.lection.parts[this.lection.parts.length-1].id)
                }
                
                this.isBusy = false
            })
        })
  },
  created() {
      
  }
}
</script>