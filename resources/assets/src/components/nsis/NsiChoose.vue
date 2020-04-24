<template>
    <div>
    <div class="row">
        <div class="col-md-12">
            <h5>Выбрано источников ({{selected.length}})</h5>
        </div>
    </div>   
            <div class="col-md-6" v-for="type in types" :key="'t'+type.id">
                <h5>{{type.name}} ({{nsis.filter(nsi => nsi.type_id == type.id).length}})</h5>
                <b-form-checkbox-group
                    v-model="selected"
                    :options="nsis.filter(nsi => nsi.type_id == type.id)"
                    name="nsis"
                    value-field = "id"
                    text-field = "name"
                    @input="change_nsi"
                    stacked
                ></b-form-checkbox-group>
                <br>
              </div> 
            <!--
            <div class="row">
              <div class="col-md-6 mb-3" v-if="npas.length>0">
               <b-form-group  label-size="lg" label="Нормативные правовые акты, нормативная техническая документация, иная документация:">
                <b-form-checkbox-group
                    id="checkbox-group-1"
                    v-model="selected"
                    :options="npas"
                    name="nsis"
                    value-field = "id"
                    text-field = "name"
                    @input="change_nsi"
                    stacked
                ></b-form-checkbox-group>
                </b-form-group>
              </div>
              <div class="col-md-6 mb-3" v-if="ychs.length>0">
               <b-form-group label-size="lg" label="Учебники, монографии:">
                <b-form-checkbox-group
                    id="checkbox-group-2"
                    v-model="selected"
                    stacked
                    :options="ychs"
                    name="nsis"
                    value-field = "id"
                    text-field = "name"
                    @input="change_nsi"
                ></b-form-checkbox-group>
                </b-form-group>
              </div>
              <div class="col-md-6 mb-3" v-if="irs.length>0">
               <b-form-group  label-size="lg" label="Интернет ресурсы:">
                <b-form-checkbox-group
                    id="checkbox-group-3"
                    v-model="selected"
                    :options="irs"
                    name="nsis"
                    value-field = "id"
                    text-field = "name"
                    @input="change_nsi"
                    stacked
                ></b-form-checkbox-group>
                </b-form-group>
              </div>
              <div class="col-md-6 mb-3" v-if="ebss.length>0">
               <b-form-group label-size="lg" label="Электронно-библиотечные системы:">
                <b-form-checkbox-group
                    id="checkbox-group-4"
                    v-model="selected"
                    :options="ebss"
                    name="nsis"
                    value-field = "id"
                    text-field = "name"
                    @input="change_nsi"
                    stacked
                ></b-form-checkbox-group>
                </b-form-group>
              </div>
            </div>
            -->

            <new-nsi @add_nsi="add_nsi" :types="types" :key="nn"></new-nsi>             
       <!-- </div> -->
        <edit-nsi @update_nsi="update_nsi" v-if="show_edit_window" :nsi_id="nsi_to_edit" :types="types" :key="nsi_to_edit"></edit-nsi>
    </div>
</template>


<script>
import NewNsi from './NewNsi'
import EditNsi from './EditNsi'
export default {
  name: 'nsi-choose',
  props: {
    ish_version_id: Number,
    selected: Array,
  },
  components: {NewNsi,EditNsi},
  data () {
    return {
      nn: 'n',
      types: [],
      nsis: [],
      isBusy: true,
      show_edit_window: false,
      nsi_to_edit: 0,
      //selected: [],
    }
  },
  computed: {
    npas(){
      return this.nsis.filter(nsi => nsi.type_id == 1)
    },
    ychs(){
      return this.nsis.filter(nsi => nsi.type_id == 2)
    },
    irs(){
      return this.nsis.filter(nsi => nsi.type_id == 3)
    },
    ebss(){
      return this.nsis.filter(nsi => nsi.type_id == 4)
    }
  },
  methods:
  {
    add_nsi(data)
    {
      var self2 = this
        axios
        .post('/nsis/add_nsi', {
            'nsi_data': data.nsi_data,
            'ish_version_id': this.ish_version_id
        })
        .then(response => {
          alert('Источник добавлен!')
          self2.nn++
          self2.nsis.push(response.data);
          this.$emit('add_nsi', {
            nsi_data: response.data,          
            })           
        })
    },
    update_nsi(data) {
      var self2 = this
      axios
        .post('/nsis/update_nsi', {
            'nsi_data': data.nsi_data
        })
        .then(response => {
          alert('Источник обновлен!')
          self2.nsis = self2.nsis.filter(nsi => nsi.id != data.nsi_data.id)
          self2.nsis.push(response.data);
          self2.$bvModal.hide("modal-editnsi")
        })
    },
    remove_nsi(id)
    {
      this.$bvModal.msgBoxConfirm('Действительно хотите источник?')
      .then(value => {
      if (value == true)
      {
      axios
        .post('/nsis/remove_nsi', {
            'nsi_id': id
        })
        .then( (response) => ( this.nsis = this.nsis.filter(nsi => nsi.id != response.data) ))
      }
      })
    },
    edit_nsi(id)
    {
      this.nsi_to_edit = id
      this.show_edit_window = true
      this.$nextTick(() => {
      this.$bvModal.show("modal-editnsi")
            })
    },
    change_nsi(item)
    {
       this.$emit('change_nsi', {
            nsi_data: this.selected,          
            }) 
    }
  },
  mounted() {
      var self2 = this
      axios
      .get('/nsis/nsi_types')
      .then(response => (this.types = response.data)) 
       axios
      .get('/nsis/'+this.ish_version_id)
      .then(response => (this.nsis = response.data))   
  },
}
</script>
