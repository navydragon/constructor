<template>
    <div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    
    <b-card :title="header">
        <b-card no-body>
            <b-tabs card :nav-wrapper-class="'bg-light'">
            <b-tab title="Создание новой компетенции" active>
            <b-alert show variant="info">
                <h5>Порядок действий:</h5>
                <ol>
                    <li>Добавьте навыки, умения и знания, которыми должны обладать слушатели</li>
                    <li>Сформируйте компетенцию</li>
                </ol>
            </b-alert>
            <hr class="container-m-nx border-light my-4">
            <new-skill v-on:add_skil="add_skil"></new-skill>
            <new-ability v-on:add_abil="add_abil"></new-ability>
            <edit-item v-if="show_edit_window" v-on:update_item="update_item" :new_skil="data_to_edit"></edit-item>
            <new-comp :tree_data="treeData[0]" v-on:add_comp="add_comp"></new-comp>
            <hr class="container-m-nx border-light my-4">
            <!-- Org Chart -->
            <div class="row">
            <div class="col-md-12">
                <h4>Диаграмма связей компетенции</h4>
                <organization-chart :datasource="treeData[0]" :direction="'l2r'">
                <template slot-scope="{ nodeData }">
                    <span :class="'btn btn-block ' + nodeData.color">{{ nodeData.text }}</span>
                </template>
                </organization-chart>
            </div>
            </div>
        <hr class="container-m-nx border-light my-4">
        <h4>Редактирование компонентов</h4>
        <!-- js tree -->
        <div class="row">
          <div class="col-md-12">
            <v-jstree
              :data="treeData"
              :class="{ 'tree-rtl': isRTL }"
              multiple
              allow-batch
              whole-row
              
              @item-click="itemClick">
              <template slot-scope="_">
                  <div style="display: inherit; width: 100px" @click.ctrl="customItemClickWithCtrl" >
                  <i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
                  <span v-if="_.model.type!='folder' && _.model.is_main==true"  @click="delete_el_from_tree(_.model, $event)" class="btn btn-outline-danger btn-xs"><i class="ion ion-md-close"></i></span> <span v-if="_.model.type!='folder' && _.model.is_main==true" class="btn btn-outline-info btn-xs" @click="edit_item(_.model)"><i class="ion ion-md-create"></i></span> &nbsp; <span v-html="_.model.text"></span> &nbsp; 
                  </div>
              </template>
            </v-jstree>
          </div>
          <div class="col-md-6">
            &nbsp;
          </div>
        </div>
            </b-tab>
            <b-tab>
                <template v-slot:title>
                    Компетенции ДПП <b-badge variant='primary'>{{attachedTreeData[0].children.length}}</b-badge>
                </template>
              <!-- js tree -->
                <div class="row">
                    <div class="col-md-12">
                        <v-jstree
                        :data="attachedTreeData"
                        :class="{ 'tree-rtl': isRTL }"
                        multiple
                        allow-batch
                        whole-row
                        
                        @item-click="itemClick">
                        <template slot-scope="_">
                            <div style="display: inherit; width: 100px" @click.ctrl="customItemClickWithCtrl" >
                            <i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
                            <span v-if="_.model.type!='folder' && _.model.is_main==true"  @click="delete_el_from_tree(_.model, $event)" class="btn btn-outline-danger btn-xs"><i class="ion ion-md-close"></i></span> &nbsp; <span v-if="_.model.type!='folder' && _.model.is_main==true" class="btn btn-outline-info btn-xs" @click="edit_item(_.model)"><i class="ion ion-md-create"></i></span> &nbsp; <span v-html="_.model.text"></span> &nbsp;  
                            </div>
                        </template>
                        </v-jstree>
                    </div>
                </div>
            </b-tab>
            </b-tabs>
            
            <div class="row">
                <div class="col-md-12"><b-button block variant="success" @click="go_forward()">Согласовать и перейти к следующему этапу</b-button></div>
            </div>
            
        </b-card>


        

    </b-card>
    </div>
</template>

<style src="@/vendor/libs/vue-jstree/vue-jstree.scss" lang="scss"></style>

<script>
import VJstree from 'vue-jstree'
import OrganizationChart from 'vue-organization-chart'
import 'vue-organization-chart/dist/orgchart.css'
import NewSkill from './NewSkill'
import NewAbility from './NewAbility'
import EditItem from './EditItem'
import NewComp from './NewComp'

export default {
  name: "dpp_stage_work",
  metaInfo: {
    title: "Разработка ДПП"
  },
  components: {
    VJstree, OrganizationChart,NewSkill,NewAbility,EditItem,NewComp
  },
  data() {
    return {
      stage: {},
      isBusy: true,
      editingItem: {},
      editingNode: null,
      show_edit_window: false,
      treeData: [{
      'text': 'Новая компетенция',
      'id': 'nc',
      'opened': true,
      'type': 'folder',
      'color': 'btn-outline-dark',
      'children': []
      }],
      attachedTreeData: [{
      'text': 'Программа ДПП',
      'id': 'pr',
      'opened': true,
      'type': 'folder',
      'color': 'btn-outline-dark',
      'children': []
      }],
      data_to_edit: {}
    }
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  methods: {
    itemClick (node) {
      console.log(node.model.text + ' clicked !')
      this.editingNode = node
      this.editingItem = node.model
      // Trigger model update by clonning data
      this.treeData = this.treeData.slice(0)
    },
    form_check (elem) {
      
    },
    add_skil (skilData)
    {
        this.treeData[0].children.push(skilData)
        axios
        .post('/dpps/'+this.stage.dpp_id+'/'+this.stage.zun_version_id+'/add_skill', {
            'skill': skilData,
        })
        .then(() => (this.get_zun_versions_data()));
    },
    add_abil (skilData)
    {
        this.treeData[0].children.push(skilData)
        axios
        .post('/dpps/'+this.stage.dpp_id+'/'+this.stage.zun_version_id+'/add_ability', {
            'ability': skilData,
        })
        .then(() => (this.get_zun_versions_data()));
    },
    add_comp (compData)
    {
        axios
        .post('/dpps/'+this.stage.dpp_id+'/'+this.stage.zun_version_id+'/add_competence', {
            'competence': compData,
        })
        .then((response) => (this.get_zun_versions_data()));
    },
    get_zun_versions_data()
    {
        axios
        .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ this.stage.zun_version_id+'/unattached')
        .then(response => (this.treeData[0].children = response.data))
        //.finally(() => (this.isBusy = false) )
        this.$nextTick(() => {
          var self = this
          axios
          .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ this.stage.zun_version_id+'/attached')
          .then (function (response){
              self.attachedTreeData[0].children = response.data
          })
          //.then(response => (this.attachedTreeData[0].children = response.data))
          //.finally((response) => ( ))
        })
    },
    delete_el_from_tree(model)
    {
        this.$bvModal.msgBoxConfirm('Удалить компонент «' + model.text + '»?')
        .then(value => {
          if (value === true) {
            //  this.remove_row(model.id)
            if (this.editingItem.id !== undefined) {
              axios
               .post('/zuns/'+model.type+'/delete', {
                    'id': model.id,
                })
              var index = this.editingNode.parentItem.indexOf(this.editingItem)
              this.editingNode.parentItem.splice(index, 1)
            }
            console.log(model)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    edit_item(model)
    {        
        this.editingItem = model
        this.data_to_edit = {
            text: '',
            show_edit: false,
            show_errors: false,
            editingItem: {},
            editingNode: null,
            skilData: [model],
            errors: []
        }
        this.show_edit_window = true
        this.$nextTick(() => {
          this.$bvModal.show("modal-edititem")
        })
        return model
    },
    update_item (skilData)
    {
        console.log(skilData)
        axios
        .post('/dpps/'+this.stage.dpp_id+'/'+this.stage.zun_version_id+'/update_elem', {
            'item': skilData,
        })
        .then((response) => (this.get_zun_versions_data()))
        .finally(() => (this.$bvModal.hide("modal-edititem")));
    },
    go_forward()
    {
      var self = this
      axios
      .post('/dpps/'+this.$route.params.dpp+'/'+ this.stage.id+'/go_next')
      .then(() => (this.$router.push('/my_dpps/'+this.$route.params.dpp+'/overview/1')))
    }

  },
  mounted() {
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally( () =>( this.get_zun_versions_data() ) )  
      
  }
}
</script>