<template>
    <div>
        <b-button :variant="'info'" v-b-modal.modal-choosedtp>Выбрать существующую структуру</b-button>
        <b-modal size="lg" id="modal-choosedtp" title="Выбрать существующую структуру" no-close-on-esc no-close-on-backdrop @ok="handle_ok" ok-title="Сохранить">
            <b-form-group label="Название структуры" label-size="lg">
                <b-form-radio v-model="selected" v-for="item in items" :key="'ts_'+item.id" name="typologies" :value="item.id">{{item.name}}</b-form-radio>
            </b-form-group>

            <div  v-for="elem in items" :key="elem.id"> 
                <div  v-if="selected == elem.id">
                    <h5>Разделы типовой структуры «{{elem.name}}»</h5>
                    <ul>
                        <li v-for="(part,index) in elem.parts" :key="index">{{part.name}}</li>
                    </ul>
                </div>
            </div>

            <b-alert show variant="danger" v-if="errors.length>0"><strong>Ошибка!</strong> Выберите структуру</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: "choose-dtp",
    metaInfo: {
        title: "Выбор существующей структуры"
    },
    data() {
    return {
        new_dtp: '',
        selected: "",
        items: [],
        errors: [],
        selected_typology: {}
    }
    },
    methods: {
      handle_ok(bvModalEvt){
        bvModalEvt.preventDefault()
        this.errors = []
        if (this.selected == ''){
            this.errors.push("Выберите типовую структуру")
        }
        if(this.errors.length == 0) {
            this.$emit('choose_dtp', this.selected)
            this.selected = '';
        }
      },
      select_typology()
      {
          this.selected_typology = this.items.find(elem => elem.id = this.selected)
      }
    },
    mounted() {
         axios
        .get('/typologies/get_typologies')
        .then ((response) => (this.items = response.data))
        .finally(() => this.isBusy = false)
    }
}
</script>