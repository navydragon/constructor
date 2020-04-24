<template>
    <div class="row">
        <div class="col-md-12">
            <div v-if="!mode" >
              <hr>
            </div>
            <h4>Материально-техническое обеспечение</h4>
              <new-mto :types="types" @add_mto="add_mto" :key="nm"></new-mto>
              <edit-mto v-if="mto_to_edit!=0" :mto_id="mto_to_edit" :types="types" @update_mto="update_mto" :key="mto_to_edit"></edit-mto>
              <hr>
              <div v-for="(type,index_a) in parent_types" :key="'t'+type.id">
                <h5>{{index_a+1}}. {{type.name}}</h5>
                <div v-for="(c_type,index_b) in children_types(type.id)" :key="'t'+c_type.id">
                  <h6>{{index_a+1}}.{{index_b+1}}. {{c_type.name}}</h6>
                  <b-list-group>
                  <b-list-group-item v-for="mto in mtos.filter(el => el.type_id == c_type.id)" :key="'mto_'+mto.id">
                    <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_mto(mto.id)"><i class="ion ion-md-create"></i></b-btn>
                    <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_mto(mto)">X</b-btn>
                    &nbsp;{{mto.name}}, единица измерения: {{mto.measure}}
                  </b-list-group-item>
                  </b-list-group>     
                </div>
                <b-list-group>
                  <b-list-group-item v-for="mto in mtos.filter(el => el.type_id == type.id)" :key="'mto_'+mto.id">
                    <b-btn variant="outline-primary icon-btn btn-xs" class="btn" @click.prevent="edit_mto(mto.id)"><i class="ion ion-md-create"></i></b-btn>
                    <b-btn v-if="!mode" variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_mto(mto)">X</b-btn>
                    &nbsp;{{mto.name}}, единица измерения: {{mto.measure}}
                  </b-list-group-item>
                  </b-list-group>
              </div> 
        </div>
       <!-- <edit-nsi @update_nsi="update_nsi" v-if="show_edit_window" :nsi_id="nsi_to_edit" :types="types" :key="nsi_to_edit"></edit-nsi>-->
    </div>
</template>


<script>
import NewMto from './NewMto'
import EditMto from './EditMto'
export default {
  name: 'mtos',
  props: {
    dpp_id: String,
    mode: String
  },
  components: {NewMto,EditMto},
  data () {
    return {
      nm: 'm',
      types: [],
      mtos: [],
      isBusy: true,
      show_edit_window: false,
      mto_to_edit: 0,
    }
  },
  computed:
  {
    parent_types()
    {
      return this.types.filter(type => type.parent_id == null)
    }
  },
  methods:
  {
    children_types (type_id)
    {
      return this.types.filter(type => type.parent_id == type_id)
    },
    add_mto(data)
    {
      self = this
        axios
        .post('/mtos/add_mto', {
            'mto_data': data.mto_data,
            'type_id': data.type_id,
            'dpp_id': this.dpp_id
        })
        .then(response => {
          alert('МТО добавлено!')
          self.nm = self.nm + 1
          self.mtos.push(response.data)
          self.$bvModal.hide("modal-newmto")          
        })
    },
    update_mto(data) {
      self = this
      axios
        .post('/mtos/update_mto', {
            'type_id': data.type_id,
            'mto_data': data.mto_data
        })
        .then(response => {
          alert('МТО обновлено!')
          self.mtos = self.mtos.filter(mto => mto.id != response.data.id)
          self.mtos.push(response.data);
          self.$bvModal.hide("modal-editmto")
        })
    },
    remove_mto(mto)
    {
      this.$bvModal.msgBoxConfirm('Действительно хотите МТО «'+mto.name+'»?')
      .then(value => {
      if (value == true)
      {
      axios
        .post('/mtos/remove_mto', {
            'mto_id': mto.id
        })
        .then( (response) => ( this.mtos = this.mtos.filter(mto => mto.id != mto.id) ))
      }
      })
    },
    edit_mto(id)
    {
      this.mto_to_edit = id
      this.show_edit_window = true
      this.$nextTick(() => {
      this.$bvModal.show("modal-editmto")
            })
    },   
  },
  mounted() {
      self = this
      axios
      .get('/mtos/mto_types')
      .then(response => (this.types = response.data)) 
       axios
      .get('/mtos/get_mtos/'+self.dpp_id)
      .then(response => (this.mtos = response.data))       
  },
}
</script>