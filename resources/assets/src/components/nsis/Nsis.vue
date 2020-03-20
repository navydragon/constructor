<template>
    <div class="row">
        <div class="col-md-12">
            <div v-if="!mode" >
              <new-nsi @add_nsi="add_nsi" :types="types" :key="nn"></new-nsi>
              <hr>
            </div>
            <h5>Текущие источники ({{nsis.length}})</h5>
              <div v-for="type in types" :key="'t'+type.id">
                <h5>{{type.name}} ({{nsis.filter(nsi => nsi.type_id == type.id).length}})</h5>
                <b-list-group>
                <b-list-group-item v-for="nsi in nsis.filter(nsi => nsi.type_id == type.id)" :key="nsi.id">
                  <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_nsi(nsi.id)"><i class="ion ion-md-create"></i></b-btn>
                  <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_nsi(nsi.id)">X</b-btn>
                  &nbsp;{{nsi.name}}
                </b-list-group-item>
                </b-list-group>
                <br>
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