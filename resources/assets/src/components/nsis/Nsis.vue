<template>
    <div class="row">
        <div class="col-md-12">
            <div v-if="!mode" >
              <new-nsi @add_nsi="add_nsi" :types="types" :key="nn"></new-nsi>
              <hr>
            </div>
            <h5>Текущие источники ({{nsis.length}})</h5>
              <div class="mb-3" v-if="npas.length>0">
              <h5 >Нормативные правовые акты, нормативная техническая документация, иная документация ({{npas.length}})</h5>
              <b-list-group>
                <b-list-group-item v-for="npa in npas" :key="npa.id">
                  <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_nsi(npa.id)"><i class="ion ion-md-create"></i></b-btn>
                  <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_nsi(npa.id)">X</b-btn>
                  &nbsp;{{npa.name}}
                </b-list-group-item>
              </b-list-group>
              </div>
              <div class="mb-3" v-if="ychs.length>0">
              <h5 >Учебники, монографии ({{ychs.length}})</h5>
              <b-list-group>
                <b-list-group-item v-for="ych in ychs" :key="ych.id">
                  <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_nsi(ych.id)"><i class="ion ion-md-create"></i></b-btn>
                  <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_nsi(ych.id)">X</b-btn>
                  &nbsp;{{ych.name}}</b-list-group-item>
              </b-list-group>
              </div>
              <div class="mb-3" v-if="irs.length>0"> 
              <h5>Интернет ресурсы ({{irs.length}})</h5>
              <b-list-group>
                <b-list-group-item v-for="ir in irs" :key="ir.id">
                  <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_nsi(ir.id)"><i class="ion ion-md-create"></i></b-btn>
                  <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_nsi(ir.id)">X</b-btn>
                  &nbsp;{{ir.name}}</b-list-group-item>
              </b-list-group>
              </div>
              <div class="mb-3" v-if="ebss.length>0">
              <h5>Электронно-библиотечная система ({{ebss.length}})</h5>
              <b-list-group>
                <b-list-group-item v-for="ebs in ebss" :key="ebs.id">
                  <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_nsi(ebs.id)"><i class="ion ion-md-create"></i></b-btn>
                  &nbsp;<b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_nsi(ebs.id)">X</b-btn>
                  &nbsp;{{ebs.name}}</b-list-group-item>
              </b-list-group>
              </div>
              
        </div>
        <edit-nsi @update_nsi="update_nsi" v-if="show_edit_window" :nsi_id="nsi_to_edit" :types="types" :key="nsi_to_edit"></edit-nsi>
    </div>
</template>


<script>
import NewNsi from './NewNsi'
import EditNsi from './EditNsi'
export default {
  name: 'nsis',
  props: {
    ish_version_id: Number,
    mode: String
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
      self = this
        axios
        .post('/nsis/add_nsi', {
            'nsi_data': data.nsi_data,
            'ish_version_id': this.ish_version_id
        })
        .then(response => {
          alert('Источник добавлен!')
          self.nn++
          self.nsis.push(response.data);          
        })
    },
    update_nsi(data) {
      axios
        .post('/nsis/update_nsi', {
            'nsi_data': data.nsi_data
        })
        .then(response => {
          alert('Источник обновлен!')
          self.nsis = self.nsis.filter(nsi => nsi.id != data.nsi_data.id)
          self.nsis.push(response.data);
          self.$bvModal.hide("modal-editnsi")
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
    }
  },
  mounted() {
      self = this
      axios
      .get('/nsis/nsi_types')
      .then(response => (this.types = response.data)) 
       axios
      .get('/nsis/'+self.ish_version_id)
      .then(response => (this.nsis = response.data))       
  },
}
</script>