<template>
    <div>
      <b-button variant="primary" v-b-modal.modal-1>Добавить источник</b-button>
      <b-modal @ok="handleOk" size="xl" id="modal-1" title="Добавить новый источник НСИ" ok-title="Добавить" cancel-title="Закрыть">
          <div>
            <b-form-group label-size="lg" label-cols="2" description="Выберите тип источника" label="Тип источника *" label-for="nsi_type">
            <b-form-select id="nsi_type" v-model="new_nsi.type" :options="types" value-field="id" text-field="name"></b-form-select>
            </b-form-group>
            <div v-if="new_nsi.type==1">
              <b-form-group label-size="lg" label-cols="2" description="Пример: ГОСТ 7.53 – 2001. Издания. Международная стандартная нумерация книг" label="Название *" label-for="nsi_name">
              <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Взамен ГОСТ 7.53 – 86" label="Ранее действовавший документ, если есть" label-for="old_name">
              <b-form-input id="old_name" v-model="new_nsi.old_name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Введ. 2002 – 07 – 01" label="Когда введен документ" label-for="start_date">
              <b-form-input id="start_date" v-model="new_nsi.start_date"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Эксмо" label="Издательство" label-for="publishing">
              <b-form-input id="publishing" v-model="new_nsi.publishing"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Москва" label="Город издания" label-for="city">
              <b-form-input id="city" v-model="new_nsi.city"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2002" label="Год издания" label-for="year">
              <b-form-input id="year" v-model="new_nsi.year"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 3" label="Кол-во страниц" label-for="pages">
              <b-form-input id="pages" v-model="new_nsi.pages"></b-form-input>
              </b-form-group>
            </div>
            <div v-if="new_nsi.type==2">
              <b-form-group label-size="lg" label-cols="2" description="Пример: Основы надежности строительных машин" label="Название *" label-for="nsi_name">
              <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 3-е изд., перераб. и доп." label="Издание" label-for="edition">
              <b-form-input id="edition" v-model="new_nsi.edition"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др." label="Автор(-ы)" label-for="nsi_authors">
              <b-form-input id="nsi_authors" v-model="new_nsi.authors"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: пер. с анг. И.Ю.Багровой и Р.З. Пановой, науч. ред. Л.М. Иньковой" label="Редакторы, составители, переводчики" label-for="assistants">
              <b-form-input id="assistants" v-model="new_nsi.assistants"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Эксмо" label="Издательство" label-for="publishing">
              <b-form-input id="publishing" v-model="new_nsi.publishing"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Москва" label="Город издания" label-for="city">
              <b-form-input id="city" v-model="new_nsi.city"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2002" label="Год издания" label-for="year">
              <b-form-input id="year" v-model="new_nsi.year"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 3" label="Кол-во страниц" label-for="pages">
              <b-form-input id="pages" v-model="new_nsi.pages"></b-form-input>
              </b-form-group>
            </div>
            <div v-if="new_nsi.type==4">
              <b-form-group label-size="lg" label-cols="2" description="Пример: Лань" label="Название библиотечной системы *" label-for="nsi_name">
              <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="" label="Адрес сайта, URL" label-for="nsi_url">
                <b-form-input id="nsi_url" v-model="new_nsi.url"></b-form-input>
              </b-form-group>
            </div>

            <div v-if="new_nsi.type==5">
              <b-form-group label-size="lg" label-cols="2" description="Пример: О воинской обязанности и военной службе" label="Полное название нормативного акта *" label-for="nsi_name">
               <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: федеральный закон" label="Тип нормативного документа" label-for="npa_type">
               <b-form-input id="npa_type" v-model="new_nsi.npa_type"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2872-1" label="Номер нормативного акта" label-for="accept_number">
               <b-form-input id="accept_number" v-model="new_nsi.accept_number"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 29.05.1992" label="Дата принятия/утверждения" label-for="accept_date">
               <b-form-input id="accept_date" v-model="new_nsi.accept_date"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Эксмо" label="Где опубликован документ (или издательство)" label-for="publishing">
              <b-form-input id="publishing" v-model="new_nsi.publishing"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Москва" label="Город публикации (издания)" label-for="city">
              <b-form-input id="city" v-model="new_nsi.city"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2002" label="Год публикации (издания)" label-for="year">
              <b-form-input id="year" v-model="new_nsi.year"></b-form-input>
              </b-form-group>
            </div>
            <div v-if="new_nsi.type==6">
              <b-form-group label-size="lg" label-cols="2" description="Пример: Рекомендации по разработке и применению документов технического регулирования в сфере дорожного хозяйства" label="Название ОДМ *" label-for="nsi_name">
               <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 218.1.001-2010" label="Номер документа" label-for="odm_number">
               <b-form-input id="odm_number" v-model="new_nsi.odm_number"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: утв. распоряжением Федерального дорожного агентства от 9 июня 2010 г. N 384-р" label="Утверждение документа" label-for="accept_odm">
               <b-form-input id="accept_odm" v-model="new_nsi.accept_odm"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Эксмо" label="Где опубликован документ (или издательство)" label-for="publishing">
                <b-form-input id="publishing" v-model="new_nsi.publishing"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Москва" label="Город публикации (издания)" label-for="city">
                <b-form-input id="city" v-model="new_nsi.city"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2002" label="Год публикации (издания)" label-for="year">
                <b-form-input id="year" v-model="new_nsi.year"></b-form-input>
              </b-form-group>
            </div>
            <div v-if="new_nsi.type==3">
              <b-form-group label-size="lg" label-cols="2" description="Название книги, статьи и тд." label="Название *" label-for="nsi_name">
                <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="" label="Адрес сайта, URL" label-for="nsi_url">
                <b-form-input id="nsi_url" v-model="new_nsi.url"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др." label="Автор(-ы)" label-for="nsi_authors">
              <b-form-input id="nsi_authors" v-model="new_nsi.authors"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Электрон. версия печ. публикации" label="Примечание" label-for="note">
              <b-form-input id="note" v-model="new_nsi.note"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Эксмо" label="Издательство" label-for="publishing">
              <b-form-input id="publishing" v-model="new_nsi.publishing"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: Москва" label="Город издания" label-for="city">
              <b-form-input id="city" v-model="new_nsi.city"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Пример: 2002" label="Год издания" label-for="year">
              <b-form-input id="year" v-model="new_nsi.year"></b-form-input>
              </b-form-group>             
            </div>
            <div v-if="new_nsi.type==8">
              <b-form-group label-size="lg" label-cols="2" description="Введите название источника" label="Название *" label-for="nsi_name">
              <b-form-input id="nsi_name" v-model="new_nsi.name"></b-form-input>
              </b-form-group>
              <b-form-group label-size="lg" label-cols="2" description="Введите полное описание в соответствии с ГОСТ 7.1-2003" label="Полное библиографическое описание" label-for="fullname">
              <b-form-input id="fullname" v-model="new_nsi.fullname"></b-form-input>
              </b-form-group>
            </div>
            <b-alert v-if="errors.length>0" show variant="danger">Обнаружены ошибки:
                <ul>
                    <li v-for="error in errors" :key="error">{{error}}</li>
                </ul>
            </b-alert>
        </div>
      </b-modal>
    </div>
</template>

<script>
export default {
    name: 'new-nsi',
    props: {
       types: Array 
    },
    data () {
    return {
      new_nsi: {
          type: '',
          name: '',
          fullname: '',
          old_name: '',
          start_date: '',
          accept_date: '',
          accept_number: '',
          accept_odm: '',
          odm_number: '',
          npa_type: '',
          city: '',
          year: '',
          pages: '',
          authors: '',
          assistants: '',
          note: '',
          output: '',
          url: ''
      },
      errors: []
    }
  },
  methods: {
       handleOk(bvModalEvt) {
        bvModalEvt.preventDefault()
        this.errors = []
        if (this.new_nsi.type.length == 0) { this.errors.push("Не выбран тип источника")}
        if (this.new_nsi.name.length == 0) { this.errors.push("Не введено название")}
        if (this.errors.length == 0)
        {
            this.$emit('add_nsi', {
            nsi_data: this.new_nsi,
            
            })
        }
      },
  }
}
</script>