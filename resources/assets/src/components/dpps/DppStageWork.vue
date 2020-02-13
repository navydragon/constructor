<template>
    <b-card :title="header">
        <b-alert show variant="info">
          <h5>Порядок действий:</h5>
          <ol>
              <li>Добавьте навыки, умения и знания, которыми должны обладать слушатели</li>
              <li>Соберите компетенции, добавив в них навыки и умения</li>
              <li>Добавьте «сквозные» знания</li>
          </ol>
        </b-alert>

        <hr class="container-m-nx border-light my-4">
            <new-skill v-on:add_skil="add_skil"></new-skill>
            <new-ability v-on:add_abil="add_abil"></new-ability>
            <b-button v-b-modal.modal-addcomp variant="primary" :disabled="new_comp_disabled">Собрать компетенцию</b-button>
            <b-modal id="modal-addcomp" ok-title="Собрать компетенцию" cancel-title="Закрыть" size="xl" title="Формирование новой компетенции">
            </b-modal>
        <hr class="container-m-nx border-light my-4">
        <!-- Org Chart -->
        <div class="row">
          <div class="col-md-12">
            <h4>Диаграмма связей</h4>
            <organization-chart :datasource="treeData[0]" :direction="'l2r'">
              <template slot-scope="{ nodeData }">
                <span :class="'btn btn-block ' + nodeData.color">{{ nodeData.text }}</span>
              </template>
            </organization-chart>
          </div>
        </div>
        <hr class="container-m-nx border-light my-4">
        <!-- js tree -->
        <div class="row">
          <div class="col-md-6">
            <v-jstree
              :data="treeData"
              :class="{ 'tree-rtl': isRTL }"
              multiple
              allow-batch
              whole-row
              draggable
              @item-click="itemClick">
              <template slot-scope="_">
                  <div style="display: inherit; width: 100px" @click.ctrl="customItemClickWithCtrl" >
                  <i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
                  <span v-html="_.model.text"></span> &nbsp; <span v-if="_.model.type!='folder'"  @click="remove_row_box(_.model, $event)" class="btn btn-outline-danger btn-xs"><i class="ion ion-md-close"></i></span> <span v-if="_.model.type!='folder'" class="btn btn-outline-info btn-xs" v-b-modal.modals-default5><i class="ion ion-md-create"></i></span>
                  </div>
              </template>
            </v-jstree>
          </div>
          <div class="col-md-6">
            &nbsp;
          </div>
        </div>

    </b-card>
</template>

<style src="@/vendor/libs/vue-jstree/vue-jstree.scss" lang="scss"></style>

<script>
import VJstree from 'vue-jstree'
import OrganizationChart from 'vue-organization-chart'
import 'vue-organization-chart/dist/orgchart.css'
import NewSkill from './NewSkill'
import NewAbility from './NewAbility'

export default {
  name: "dpp_stage_work",
  metaInfo: {
    title: "Разработка ДПП"
  },
  components: {
    VJstree, OrganizationChart,NewSkill,NewAbility
  },
  data() {
    return {
      stage: {},
      isBusy: true,
      editingItem: {},
      editingNode: null,
      treeData: [{
      'text': 'Новая компетенция',
      'opened': true,
      'type': 'folder',
      'color': 'btn-outline-dark',
      'children': []
      }],
    }
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
      new_comp_disabled () {
          if (this.treeData[0].children.length > 0)
          {
              return false
          }
          return true
      }
  },
  methods: {
    itemClick (node) {
      console.log(node.model.text + ' clicked !')
      this.editingNode = node
      this.editingItem = node.model
      // Trigger model update by clonning data
      this.treeData = this.treeData.slice(0)
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
    get_zun_versions_data()
    {
        axios
        .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ this.stage.zun_version_id)
        .then(response => (this.treeData[0].children = response.data))
        //.finally(() => (this.isBusy = false) )
    },
  },
  mounted() {
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally( () =>( this.get_zun_versions_data() ) )  
      
  }
}
</script>