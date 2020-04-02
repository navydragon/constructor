<template>
  <div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <hr />
    <h4>Проектирование результатов</h4>
    <div class="row">
      <div class="col-md-4">
        <h5>Статистика результатов:</h5>
        <ul>
          <li>Компетенций: {{nodes.filter(node => node.tags == "competence").length}} (не заполнено {{nodes.filter(node => node.tags == "competence" && node.valid == false).length}} )</li>
          <li>Навыков: {{nodes.filter(node => node.tags == 'skill').length}} (не заполнено {{nodes.filter(node => node.tags == "skill" && node.valid == false).length}} )</li>
          <li>Умений: {{nodes.filter(node => node.tags == 'ability').length}} (не заполнено {{nodes.filter(node => node.tags == "ability" && node.valid == false).length}} )</li>
          <li>Знаний: {{nodes.filter(node => node.tags == 'knowledge').length}} (не заполнено {{nodes.filter(node => node.tags == "knowledge" && node.valid == false).length}} )</li>
          <li>Из них сквозных знаний: {{nodes.filter(node => node.pid == 0).length}} (не заполнено {{nodes.filter(node => node.pid == 0 && node.valid == false).length}} )</li>
        </ul>
      </div>
      <div class="col-md-8">
        <h5>  
            <span class="text-primary" style="font-size:20px" v-b-popover.hover.top="'Кликните по названию раздела, чтобы узнать, какие знания входят в него'" title="Подсказка">
                 <i class="ion ion-md-information-circle-outline"></i>
            </span>
            Типовое содержание ДПП:
        </h5>
        <div>
        <b-card v-for="part in parts" :key="'part_'+part.id" no-body class="mb-1">
        <b-card-header class="pt-1 pb-1">
            <a class="text-body" href="javascript:void(0)" v-b-toggle="'accordion_'+part.id">
                <i v-if="part.knowledges.length==0" class="ion ion-md-folder"></i>
                <i v-if="part.knowledges.length!=0" class="ion ion-md-folder text-success"></i>
                {{part.name}} (Знаний: {{part.knowledges.length}})
            </a>
        </b-card-header>
        <b-collapse :id="'accordion_'+part.id" accordion="accordion">
            <b-card-body class="p-1">
            <ul>
              <li v-for="knowledge in part.knowledges" :key="'kd'+knowledge.id">{{knowledge.name}}</li>
            </ul>
            </b-card-body>
        </b-collapse>
        </b-card>
        </div>
      </div>
    </div>
    <div>
       <b-dropdown id="dropdown-1" text="Добавить компонент" class="m-md-2">
            <b-dropdown-item @click="create_skill('root')">Добавить навык</b-dropdown-item>
            <b-dropdown-item @click="create_ability('root')">Добавить умение</b-dropdown-item>
            <b-dropdown-item  @click="make_competence('root')">Сформировать компетенцию</b-dropdown-item>
        </b-dropdown>
        <b-dropdown id="dropdown-2" text="Экспорт" class="m-md-2">
            <b-dropdown-item @click="chart.exportPDF({ filename: 'My.pdf' });">Экспорт в PDF</b-dropdown-item>
            <b-dropdown-item @click="chart.exportPNG({ filename: 'My.png' });">Экспорт в PNG</b-dropdown-item>
            <b-dropdown-item :href="'/dpps/' +
            this.$route.params.dpp +
            '/export_zun/' +
            this.stage.zun_version_id">Экспорт в Word</b-dropdown-item>
        </b-dropdown>
      <span
        class="btn btn-success"
        @click="check_stage()"
      >Согласовать результаты и перейти к следующему этапу</span>
    </div>
    <b-alert v-if="errors.length>0" show dismissible variant="danger">
      <strong>Обнаружены ошибки:</strong>
      <ul>
        <li v-for="(error,index) in errors" :key="index">{{error}}</li>
      </ul>
    </b-alert>
    <div></div>
    <div>
        <b-card no-body>
            <b-tabs card>
            <b-tab title="В виде графа" active>
                <div id="tree" ref="tree"></div>
            </b-tab>
            <b-tab title="В виде списка">
                <div v-if="!isBusy">
                    <h5>Сформированные компетенции</h5>
                    <div v-for="comp in nodes.filter(el => el.type=='Компетенция')" :key="comp.id">
                        <h5><i class="ion ion-ios-radio-button-on text-primary"></i> Компетенция: {{comp.name}}</h5>
                        <ul style="padding-left:20px" type="none">
                            <li v-for="skil in nodes.filter(el => el.type.includes('Навык')&&el.pid==comp.id)" :key="skil.id"><i class="ion ion-ios-radio-button-on text-secondary"></i> Навык: {{skil.name}}
                            <ul style="padding-left:20px" type="none">
                                <li v-for="abil in nodes.filter(el => el.type.includes('Умение')&&el.pid==skil.id)" :key="abil.id">
                                    <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                    <ul style="padding-left:20px" type="none">
                                        <li v-for="know in nodes.filter(el => el.type.includes('Знание')&&el.pid==abil.id)" :key="know.id">
                                            <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            </li>
                        </ul>
                        <ul style="padding-left:20px" type="none">
                            <li v-for="abil in nodes.filter(el => el.type.includes('Умение')&&el.pid==comp.id)" :key="abil.id">
                                    <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                    <ul style="padding-left:20px" type="none">
                                        <li v-for="know in nodes.filter(el => el.type.includes('Знание')&&el.pid==abil.id)" :key="know.id">
                                            <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                        </li>
                                    </ul>
                                </li>
                        </ul>
                    </div>
                    <hr>
                    <h5>ЗУН, не прикрепленные к компетенциям</h5>
                    <ul style="padding-left:20px" type="none">
                            <li v-for="skil in nodes.filter(el => el.type.includes('Навык')&&el.pid=='c')" :key="skil.id"><i class="ion ion-ios-radio-button-on text-secondary"></i> Навык: {{skil.name}}
                            <ul style="padding-left:20px" type="none">
                                <li v-for="abil in nodes.filter(el => el.type.includes('Умение')&&el.pid==skil.id)" :key="abil.id">
                                    <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                    <ul style="padding-left:20px" type="none">
                                        <li v-for="know in nodes.filter(el => el.type.includes('Знание')&&el.pid==abil.id)" :key="know.id">
                                            <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            </li>
                    </ul>
                    <ul style="padding-left:20px" type="none">
                        <li v-for="abil in nodes.filter(el => el.type.includes('Умение')&&el.pid=='s')" :key="abil.id">
                                    <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                    <ul style="padding-left:20px" type="none">
                                        <li v-for="know in nodes.filter(el => el.type.includes('Знание')&&el.pid==abil.id)" :key="know.id">
                                            <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                        </li>
                                    </ul>
                        </li>
                    </ul>
                    <hr>
                    <h5>Сквозные знания:</h5>
                    <ul style="padding-left:20px" type="none">
                        <li v-for="know in nodes.filter(el => el.type.includes('Знание')&&el.pid=='th')" :key="know.id">
                            <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                        </li>
                    </ul>
                </div>
            </b-tab>
            </b-tabs>
        </b-card>
    </div>
    
    <new-skill2
      @add_skill="add_skill"
      :ish_version_id="stage.ish_version_id"
      :parent_node="parent_node"
      :key="ns"
    ></new-skill2>
    <new-ability2
      @add_ability="add_ability"
      :ish_version_id="stage.ish_version_id"
      :parent_node="parent_node"
      :key="as"
    ></new-ability2>
    <new-knowledge2
      @add_knowledge="add_knowledge"
      :dtps="parts"
      :ish_version_id="stage.ish_version_id"
      :parent_node="parent_node"
      :key="ks"
    ></new-knowledge2>
    <new-competence2 @add_competence="add_competence" :elems="unattached_elems" :key="cs"></new-competence2>
    <add-parent2
      v-if="edit_type=='parent'"
      @draw_parent="draw_parent"
      :edit_elem="edit_elem"
      :elems="nodes.filter(node => node.tags == 'ability')"
      :key="edit_elem.id"
    ></add-parent2>
    <edit-skill2
      v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='skill'"
      @update_skill="update_skill"
      :edit_elem="edit_elem.id"
      :ish_version_id="stage.ish_version_id"
      :key="'s'+edit_elem.id"
    ></edit-skill2>
    <edit-ability2
      v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='ability'"
      @update_ability="update_ability"
      :edit_elem="edit_elem.id"
      :ish_version_id="stage.ish_version_id"
      :key="'a'+edit_elem.id"
    ></edit-ability2>
    <edit-knowledge2
      v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='knowledge'"
      :dtps="parts"
      @update_knowledge="update_knowledge"
      :edit_elem="edit_elem.id"
      :ish_version_id="stage.ish_version_id"
      :key="'a'+edit_elem.id"
    ></edit-knowledge2>
    <edit-competence2
      v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='competence'"
      @update_competence="update_competence"
      :edit_elem="edit_elem.id"
      :key="'c'+edit_elem.id"
    ></edit-competence2>
    <order-children
      v-if="!isBusy&&edit_elem.id!='0'"
      @update_order="update_order"
      :edit_elem="edit_elem"
      :zun_version="stage.zun_version_id"
      :key="'eo_'+edit_elem.id"
    ></order-children>
  </div>
</template>

<script>
import OrgChart from "@balkangraph/orgchart.js/orgchart";
import NewSkill2 from "./NewSkill2";
import NewAbility2 from "./NewAbility2";
import NewKnowledge2 from "./NewKnowledge2";
import NewCompetence2 from "./NewCompetence2";
import AddParent2 from "./AddParent2";
import EditSkill2 from "./EditSkill2";
import EditAbility2 from "./EditAbility2";
import EditKnowledge2 from "./EditKnowledge2";
import EditCompetence2 from "./EditCompetence2";
import OrderChildren from "./OrderChildren";

export default {
  name: "zun2",
  metaInfo: {
    title: "Проектирование результатов"
  },
  data() {
    return {
      ns: "s",
      as: "a",
      ks: "k",
      cs: "c",
      edit_elem: {
        id: "0"
      },
      edit_type: "",
      nodes: [],
      links: [],
      parent_node: null,
      unattached_elems: [],
      stage: {},
      parts: [],
      errors: [],
      isBusy: true
    };
  },
  components: {
    OrgChart,
    NewSkill2,
    NewAbility2,
    NewKnowledge2,
    NewCompetence2,
    AddParent2,
    EditSkill2,
    EditAbility2,
    EditKnowledge2,
    EditCompetence2,
    OrderChildren
  },
  methods: {
    get_zun_versions_data() {
      axios
        .get(
          "/dpps/" +
            this.$route.params.dpp +
            "/get_links/" +
            this.stage.zun_version_id
        )
        .then(response => (this.links = response.data));
      axios
        .get("/dpps/" + this.$route.params.dpp + "/get_typology")
        .then(response => (this.parts = response.data));
      axios
        .get(
          "/dpps/" +
            this.$route.params.dpp +
            "/get_zun_version_data2/" +
            this.stage.zun_version_id
        )
        .then(response => (this.nodes = response.data))
        .finally(() => this.oc(this.$refs.tree, this.nodes));
    },
    oc: function(domEl, x) {
      //  OrgChart.templates.ula.exportMenuButton = '<div style="position:absolute;right:{p}px;top:{p}px; width:40px;height:50px;cursor:pointer" control-export-menu=""  ><i class="fas fa-file-export"></i></div>';
      OrgChart.templates.comp_template = Object.assign(
        {},
        OrgChart.templates.ula
      );
      OrgChart.templates.comp_template.size = [400, 170];
      OrgChart.templates.comp_template.node =
        '<rect x="0" y="0" fill= "#040347" width="400" height="160"  rx="5" ry="5"/>';
      OrgChart.templates.comp_template.link =
        '<path stroke="#000000" stroke-width="1px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
      OrgChart.templates.comp_template.field_1 =
        '<foreignObject x="10" y="5" width="375" height="20">' +
        '<span class="print_type text-white" style="margin:0px; line-height: 100%; font-weight:bolder;">{val}</span></foreignObject>';
      //<span class="btn btn-danger btn-xs"><strong>!</strong></span>
      OrgChart.templates.comp_template.field_0 =
        '<foreignObject x="10" y="35" width="375" height="125">' +
        '<p class="text-white" style="margin:0px; line-height: 100%;"> {val}</p></foreignObject>';

      OrgChart.templates.comp_template.nodeMenuButton =
        '<foreignObject x="365" y="0" width="30" height="25" style="cursor:pointer;">' +
        '<i class="ion ion-ios-more" control-node-menu-id="{id}" style="color: white; font-size: 30px;"></i></foreignObject>';

      OrgChart.templates.skil_template = Object.assign(
        {},
        OrgChart.templates.ula
      );
      OrgChart.templates.skil_template.size = [400, 170];
      OrgChart.templates.skil_template.link =
        '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
      OrgChart.templates.skil_template.node =
        '<rect x="0" y="0" fill= "#040347" width="400" height="160"  rx="5" ry="5"/>';
      OrgChart.templates.skil_template.field_1 =
        '<foreignObject x="10" y="5" width="390" height="50">' +
        '<span class="print_type text-white" style="margin:0px; line-height: 100%; font-weight:bolder;">{val}</span></foreignObject>';
      OrgChart.templates.skil_template.field_0 =
        '<foreignObject x="10" y="35" width="390" height="125">' +
        '<p class="text-white" style="margin:0px; line-height: 100%;"> {val}</p></foreignObject>';

      OrgChart.templates.skil_template.nodeMenuButton =
        '<foreignObject x="365" y="0" width="30" height="25" style="cursor:pointer;">' +
        '<i class="ion ion-ios-more" control-node-menu-id="{id}" style="color: white; font-size: 30px;"></i></foreignObject>';

      OrgChart.templates.abil_template = Object.assign(
        {},
        OrgChart.templates.ula
      );
      OrgChart.templates.abil_template.size = [410, 170];
      OrgChart.templates.abil_template.link =
        '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
      OrgChart.templates.abil_template.node =
        '<rect x="0" y="0" fill= "#040347" width="400" height="160"  rx="5" ry="5"/>';
      OrgChart.templates.abil_template.field_1 =
        '<foreignObject x="10" y="5" width="390" height="50">' +
        '<span class="print_type text-white" style="margin:0px; line-height: 100%; font-weight:bolder;">{val}</span></foreignObject>';
      OrgChart.templates.abil_template.field_0 =
        '<foreignObject x="10" y="35" width="390" height="125">' +
        '<p class="text-white" style="margin:0px; line-height: 100%;"> {val}</p></foreignObject>';
      OrgChart.templates.abil_template.nodeMenuButton =
        '<foreignObject x="365" y="0" width="30" height="25" style="cursor:pointer;">' +
        '<i class="ion ion-ios-more" control-node-menu-id="{id}" style="color: white; font-size: 30px;"></i></foreignObject>';

      OrgChart.templates.know_template = Object.assign(
        {},
        OrgChart.templates.ula
      );
      OrgChart.templates.know_template.size = [400, 110];
      OrgChart.templates.know_template.nodeMenuButton =
        '<foreignObject x="365" y="0" width="30" height="25" control-node-menu-id="{id}" style="cursor:pointer;">' +
        '<i class="ion ion-ios-more"  style="color: white; font-size: 30px;"></i></foreignObject>';
      OrgChart.templates.know_template.link =
        '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
      OrgChart.templates.know_template.node =
        '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>';
      OrgChart.templates.know_template.field_1 =
        '<foreignObject x="10" y="5" width="390" height="50">' +
        '<span class="print_type text-white" style="margin:0px; line-height: 100%; font-weight:bolder;">{val}</span></foreignObject>';
      OrgChart.templates.know_template.field_0 =
        '<foreignObject x="10" y="35" width="390" height="70">' +
        '<p class="text-white" style="margin:0px; line-height: 100%;"> {val}</p></foreignObject>';

      OrgChart.templates.through_template = Object.assign(
        {},
        OrgChart.templates.ula
      );
      OrgChart.templates.through_template.size = [400, 110];
      OrgChart.templates.through_template.node =
        '<rect x="0" y="0" fill= "#B15124" width="400" height="100"  rx="5" ry="5"/>';
      OrgChart.templates.through_template.field_1 = "";
      OrgChart.templates.through_template.field_0 =
        '<foreignObject x="100" y="40" width="390" height="70">' +
        '<p class="" style="font-size:20px; color: white; line-height: 100%;"> {val}</p></foreignObject>';
      OrgChart.templates.through_template.nodeMenuButton =
        '<foreignObject x="365" y="0" width="30" height="25" control-node-menu-id="{id}" style="cursor:pointer;">' +
        '<i class="ion ion-ios-more"  style="color: white; font-size: 30px;"></i></foreignObject>';

      for (var i = 0; i < this.nodes.length; i++) {
        var node = this.nodes[i];
        switch (node.type) {
          case "Компетенция":
            node.tags = ["competence"];
            break;
          case "Навык":
            node.tags = ["skill"];
            break;
          case "Умение":
            node.tags = ["ability"];
            break;
          case "Знание":
            node.tags = ["knowledge"];
            break;
          case "Сквозные знания":
            node.tags = ["through"];
            break;
        }
      }

      for (var i = 0; i < this.nodes.length; i++) {
        if (this.nodes[i].valid == 0) {
          this.nodes[i].type =
            "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> " +
            this.nodes[i].type;
        }
      }

      self = this;
      this.chart = new OrgChart(domEl, {
        nodes: x,
        template: "ula",
        enableDragDrop: true,
        nodeMouseClick: null,
        slinks: this.links,
        showXScroll: OrgChart.scroll.visible,
        showYScroll: OrgChart.scroll.visible,
        lazyLoading: false,
        orderBy: "position",
        zoom: {
          speed: 30,
          smooth: 10
        },
        nodeBinding: {
          field_0: "name",
          field_1: "type"
        },

        scaleInitial: OrgChart.match.boundary,
        nodeMenu: {},

        toolbar: {
          layout: true,
          zoom: true,
          fit: true,
          expandAll: false
        },
        layout: OrgChart.treeRightOffset,
        //orientation: 3,
        tags: {
          competence: {
            nodeMenu: {
              create_skill: {
                text: "Добавить навык",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.create_skill(node);
                }
              },
              create_ability: {
                text: "Добавить умение",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.create_ability(node);
                }
              },
              edit_competence: {
                text: "Редактировать",
                icon: '<i class="fas fa-edit"></i>',
                onClick: function(node) {
                  self.edit_competence(node);
                }
              },
              delete_competence: {
                text: "Удалить компетенцию",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.delete_competence(node);
                }
              },
              order_children: {
                text: "Упорядочить дочерние компоненты",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.order_children(node);
                }
              },
              export_node: {
                  text: "Экспорт",
                  icon: '<i class="fas fa-user-minus"></i>',
                  onClick: function(node) {
                      self.export_node(node);
                  }
              }
            },
            template: "comp_template"
          },
          skill: {
            nodeMenu: {
              create_ability: {
                text: "Добавить умение",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.create_ability(node);
                }
              },
              edit_skill: {
                text: "Редактировать навык",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.edit_skill(node);
                }
              },
              delete_skill: {
                text: "Удалить навык",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.delete_skill(node);
                }
              },
              disconnect: {
                text: "Отсоединить",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.disconnect(node);
                }
              },
              order_children: {
                text: "Упорядочить умения",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.order_children(node);
                }
              },
              export_node: {
                  text: "Экспорт",
                  icon: '<i class="fas fa-user-minus"></i>',
                  onClick: function(node) {
                      self.export_node(node);
                  }
              }
            },
            template: "skil_template"
          },
          ability: {
            nodeMenu: {
              create_knowledge: {
                text: "Добавить знание",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.create_knowledge(node);
                }
              },
              edit_ability: {
                text: "Редактировать умение",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.edit_ability(node);
                }
              },
              delete_ability: {
                text: "Удалить умение",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.delete_ability(node);
                }
              },
              disconnect: {
                text: "Отсоединить",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.disconnect(node);
                }
              },
              order_children: {
                text: "Упорядочить знания",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.order_children(node);
                }
              },
              export_node: {
                  text: "Экспорт",
                  icon: '<i class="fas fa-user-minus"></i>',
                  onClick: function(node) {
                      self.export_node(node);
                  }
              }
            },
            template: "abil_template"
          },
          knowledge: {
            nodeMenu: {
              edit_knowledge: {
                text: "Редактировать знание",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.edit_knowledge(node);
                }
              },
              delete_knowledge: {
                text: "Удалить знание",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.delete_knowledge(node);
                }
              },
              add_parent: {
                text: "Добавить дополнительную связь",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.add_parent(node);
                }
              },
              disconnect: {
                text: "Отсоединить",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.disconnect(node);
                }
              }
            },
            template: "know_template"
          },
          through: {
            nodeMenu: {
              create_knowledge: {
                text: "Добавить знание",
                icon: '<i class="fas fa-user-plus"></i>',
                onClick: function(node) {
                  self.create_knowledge(node);
                }
              },
              order_children: {
                text: "Упорядочить знания",
                icon: '<i class="fas fa-user-minus"></i>',
                onClick: function(node) {
                  self.order_children(node);
                }
              },
              export_node: {
                  text: "Экспорт",
                  icon: '<i class="fas fa-user-minus"></i>',
                  onClick: function(node) {
                      self.export_node(node);
                  }
              }
            },
            template: "through_template"
          }
        }
      });

      this.isBusy = false;
      this.chart.on("exportstart", function(sender, args) {
        //args.content += document.getElementById('myStyles').outerHTML;
        //console.log(args.content)
        args.content +=
          "" +
          "<style>" +
          ".node.competence rect {fIll:#fff; stroke: #000;stroke-width: 2;}" +
          ".node.skill rect {fIll:#fff; stroke: #000;stroke-width: 2;}" +
          ".node.ability rect {fIll:#fff; stroke: #000;stroke-width: 2;}" +
          ".node.knowledge rect {fIll:#fff; stroke: #000;stroke-width: 2;}" +
          "p {color: #000; line-height: 100%;}" +
          "</style>";
      });
      this.chart.on("drop", function(sender, draggedNodeId, droppedNodeId) {
        var dragged = self.nodes.find(el => el.id == draggedNodeId);
        var dropped = self.nodes.find(el => el.id == droppedNodeId);
        var dra_type = dragged.type.replace(
          "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> ",
          ""
        );
        var dro_type = dropped.type.replace(
          "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> ",
          ""
        );
        if (dra_type == dro_type) {
          alert("Ошибка! Невозможно добавить элемент к элементу того же типа");
          return false;
        }
        if (dra_type == "Компетенция") {
          alert("Ошибка! Компетенцию нельзя присоединить к другому элементу");
          return false;
        }
        if (dro_type == "Знание") {
          alert("Ошибка! Невозможно добавить элемент к элементу типа «Знание»");
          return false;
        }
        if (dro_type == "Сквозные знания" && dra_type != "Знание") {
          alert("Ошибка! Сквозным может быть только знание");
          return false;
        }
        if (dro_type == "Навык" && dra_type == "Знание") {
          alert("Ошибка! Знание не может принадлежать навыку");
          return false;
        }

        if (dra_type == "Сквозные знания") {
          alert("Ошибка! Невозможно переместить сквозные знания");
          return false;
        }

        if (dro_type == "Компетенция") {
          var children = self.nodes.filter(el => el.pid == dropped.id);
          var skills = children.filter(child => child.type.includes("Навык"));
          var abilities = children.filter(child =>
            child.type.includes("Умение")
          );
          if (dra_type == "Умение") {
            if (skills.length > 0) {
              alert(
                "Ошибка! Невозможно присоединить умение к компетенции, так как она уже содержит навыки."
              );
              return false;
            }
          }
          if (dra_type == "Навык") {
            if (abilities.length > 0) {
              alert(
                "Ошибка! Невозможно присоединить навык к компетенции, так как она уже содержит умения."
              );
              return false;
            }
          }

          if (dra_type == "Знание") {
            alert("Ошибка! Невозможно присоединить знание к компетенции.");
            return false;
          }
        }

        if (dra_type == "Навык") {
          if (dro_type == "Умение") {
            alert("Ошибка! Невозможно присоединить навык к умению");
            return false;
          }
        }

        axios.post("/dpps/" + self.$route.params.dpp + "/move_elem", {
          elem_type: dra_type,
          elem_id: dragged.id,
          to_type: dro_type,
          to_id: dropped.id
        });
        return true;
      });
    },
    create_skill(node) {
      this.parent_node = node;
      var children = self.nodes.filter(el => el.pid == node);
      var abilities = children.filter(child => child.type.includes("Умение"));
      if (abilities.length > 0) {
        alert(
          "Ошибка! Невозможно добавить навык к компетенции, так как она уже содержит умения."
        );
        return false;
      }
      this.$bvModal.show("modal-newskill");
    },
    add_skill(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/add_skill", {
          zun_version: this.stage.zun_version_id,
          skill_data: data.skill_data,
          parent_node: data.parent_node,
          skill_name: data.skill_name
        })
        .then(function(response) {
          if (response.data.valid == true) {
            var new_type = "Навык";
          } else {
            var new_type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Навык";
          }
          self.chart.addNode({
            id: response.data.new_id,
            pid: response.data.new_parent,
            name: response.data.name,
            valid: response.data.valid,
            type: new_type,
            tags: ["skill"]
          });
          self.chart.center(response.data.new_id);
          //self.chart.draw(OrgChart.action.init);
        })

        .finally(function() {
          //self.chart.draw(OrgChart.action.init);
          //self.oc(self.$refs.tree, self.nodes)
          self.$bvModal.hide("modal-newskill");
          self.ns = self.ns + 1;
        });
    },
    delete_skill(node) {
      self = this;
      var elem = this.nodes.find(el => el.id == node);
      var children = this.nodes.filter(el => el.pid == elem.id);
      if (children.length > 0) {
        alert(
          "Невозможно удалить навык, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести."
        );
      } else {
        this.$bvModal
          .msgBoxConfirm(
            "Действительно хотите удалить навык «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post("/dpps/" + this.$route.params.dpp + "/remove_skill", {
                  skill_id: node
                })
                .then(response => this.chart.removeNode(node))
                .finally(function() {
                  //self.oc(self.$refs.tree, self.nodes)
                });
            }
          });
      }
    },
    edit_skill(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.edit_type = "skill";
      this.$nextTick(() => {
        this.$bvModal.show("modal-editskill");
      });
    },
    update_skill(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/update_skill", {
          skill_data: data.skill_data,
          skill_name: data.skill_name
        })
        .then(function(response) {
          // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
          var upd_node = self.nodes.find(
            node => node.id == response.data.new_id
          );
          upd_node.name = response.data.name;
          upd_node.valid = response.data.valid;
          if (response.data.valid == true) {
            upd_node.type = "Навык";
          } else {
            upd_node.type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Навык";
          }
          self.chart.center(response.data.new_id);
          //self.chart.draw(OrgChart.action.init);
        })

        .finally(function() {
          //self.chart.draw(OrgChart.action.init);
          //self.oc(self.$refs.tree, self.nodes)
          self.$bvModal.hide("modal-editskill");
        });
    },
    create_ability(node) {
      this.parent_node = node;
      var children = self.nodes.filter(el => el.pid == node);
      var skills = children.filter(child => child.type.includes("Навык"));
      if (skills.length > 0) {
        alert(
          "Ошибка! Невозможно добавить умение к компетенции, так как она уже содержит навыки."
        );
        return false;
      }
      this.$bvModal.show("modal-newability");
    },
    add_ability(data) {
      self = this;
      var parent = self.nodes.find(node => node.id == data.parent_node);
      console.log(parent);
      if (parent) {
        if (parent.tags == "skill") {
          var parent_type = "skill";
        }
        if (parent.tags == "competence") {
          var parent_type = "competence";
        }
      } else {
        var parent_type = "no";
      }
      
      axios
        .post("/dpps/" + this.$route.params.dpp + "/add_ability", {
          zun_version: this.stage.zun_version_id,
          ability_data: data.ability_data,
          parent_node: data.parent_node,
          parent_type: parent_type,
          ability_name: data.ability_name
        })
        .then(function(response) {
          if (response.data.valid == true) {
            var new_type = "Навык";
          } else {
            var new_type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Умение";
          }
          self.chart.addNode({
            id: response.data.new_id,
            pid: response.data.new_parent,
            name: response.data.name,
            valid: response.data.valid,
            type: new_type,
            tags: ["ability"]
          });
          self.chart.center(response.data.new_id);
        })
        .finally(function() {
          // self.oc(self.$refs.tree, self.nodes)
          self.$bvModal.hide("modal-newability");
          self.as = self.as + 1;
        });
    },
    edit_ability(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.edit_type = "ability";
      this.$nextTick(() => {
        this.$bvModal.show("modal-editability");
      });
    },
    update_ability(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/update_ability", {
          ability_data: data.ability_data,
          ability_name: data.ability_name
        })
        .then(function(response) {
          // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
          var upd_node = self.nodes.find(
            node => node.id == response.data.new_id
          );
          upd_node.name = response.data.name;
          upd_node.valid = response.data.valid;
          if (response.data.valid == true) {
            upd_node.type = "Умение";
          } else {
            upd_node.type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Умение";
          }
          self.chart.center(response.data.new_id);
        })

        .finally(function() {
          //self.chart.draw(OrgChart.action.init);
          //self.oc(self.$refs.tree, self.nodes)
          self.$bvModal.hide("modal-editability");
        });
    },
    delete_ability(node) {
      self = this;
      var elem = this.nodes.find(el => el.id == node);
      var children = this.nodes.filter(el => el.pid == elem.id);
      if (children.length > 0) {
        alert(
          "Невозможно удалить умение, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести."
        );
      } else {
        this.$bvModal
          .msgBoxConfirm(
            "Действительно хотите удалить умение «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post("/dpps/" + this.$route.params.dpp + "/remove_ability", {
                  ability_id: node
                })
                .then(response => this.chart.removeNode(node))
                .finally(function() {
                  //self.oc(self.$refs.tree, self.nodes)
                });
            }
          });
      }
    },

    create_knowledge(node) {
      this.parent_node = node;
      this.$bvModal.show("modal-newknowledge");
    },
    add_knowledge(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/add_knowledge", {
          zun_version: this.stage.zun_version_id,
          knowledge_data: data.knowledge_data,
          parent_node: data.parent_node,
          knowledge_name: data.knowledge_name
        })
        .then(function(response) {
          if (response.data.valid == true) {
            var new_type = "Знание";
          } else {
            var new_type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Знание";
          }
          self.chart.addNode({
            id: response.data.new_id,
            pid: response.data.new_parent,
            name: response.data.name,
            type: new_type,
            valid: response.data.valid,
            tags: ["knowledge"]
          });
          self.chart.center(response.data.new_id);
        })
        .finally(function() {
          //self.oc(self.$refs.tree, self.nodes)
          self.$bvModal.hide("modal-newknowledge");
          self.ks = self.ks + 1;
        });

      axios
        .get("/dpps/" + this.$route.params.dpp + "/get_typology")
        .then(response => (self.parts = response.data));
    },
    delete_knowledge(node) {
      self = this;
      var elem = this.nodes.find(el => el.id == node);
      var children = this.nodes.filter(el => el.pid == elem.id);
      if (children.length > 0) {
        alert(
          "Невозможно удалить умение, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести."
        );
      } else {
        this.$bvModal
          .msgBoxConfirm(
            "Действительно хотите удалить знание «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post("/dpps/" + this.$route.params.dpp + "/remove_knowledge", {
                  knowledge_id: node
                })
                .then(response => this.chart.removeNode(node))
                .finally(function() {
                  //self.oc(self.$refs.tree, self.nodes)
                });
            }
          });
      }
    },
    edit_knowledge(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.edit_type = "knowledge";
      this.$nextTick(() => {
        this.$bvModal.show("modal-editknowledge");
      });
    },
    update_knowledge(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/update_knowledge", {
          knowledge_data: data.knowledge_data,
          knowledge_name: data.knowledge_name
        })
        .then(function(response) {
          // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
          var upd_node = self.nodes.find(
            node => node.id == response.data.new_id
          );
          upd_node.name = response.data.name;
          upd_node.valid = response.data.valid;
          if (response.data.valid == true) {
            upd_node.type = "Знание";
          } else {
            upd_node.type =
              "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> Знание";
          }
          self.chart.center(response.data.new_id);
        })

        .finally(function() {
          self.$bvModal.hide("modal-editknowledge");
        });

      axios
        .get("/dpps/" + this.$route.params.dpp + "/get_typology")
        .then(response => (self.parts = response.data));
    },
    disconnect(node) {
      self = this;
      var elem = self.nodes.find(el => el.id == node);
      elem.type = elem.type.replace(
        "<span class='btn btn-danger btn-xs'><strong style='font-size:16px;'>!</strong></span> ",
        ""
      );
      axios
        .post("/dpps/" + this.$route.params.dpp + "/disconnect", {
          elem: elem
        })
        .then(response => (elem.pid = null))
        .finally(function() {
          self.chart.draw(OrgChart.action.init);
          self.chart.center(node);
        });
    },
    generate_id() {
      return `f${(~~(Math.random() * 1e8)).toString(16)}`;
    },
    set_width(el) {
      console.log(el);
      return 400;
    },
    make_competence() {
      var elems = this.nodes.filter(
        node => node.pid == null || node.pid == "c" || node.pid == "s"
      );
      elems = elems.filter(node => node.type != "Сквозные знания");
      elems = elems.filter(node => node.type != "Компетенция");
      this.unattached_elems = elems;
      this.$bvModal.show("modal-newcompetence");
    },
    add_competence(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/add_competence", {
          zun_version: this.stage.zun_version_id,
          competence_data: data.competence_data,
          competence_name: data.competence_name
        })
        .then(function(response) {
          self.chart.addNode({
            id: response.data.new_id,
            pid: null,
            name: response.data.name,
            valid: true,
            type: "Компетенция",
            tags: ["competence"]
          });
          for (var i = 0; i < data.competence_data.elems.length; i++) {
            console.log(data.competence_data.elems[i]);
            var old_elem = self.nodes.find(
              node => node.id == data.competence_data.elems[i]
            );
            self.chart.updateNode({
              id: old_elem.id,
              type: old_elem.type,
              tags: old_elem.tags,
              name: old_elem.name,
              pid: response.data.new_id
            });
          }
          self.chart.center(response.data.new_id);
          self.$bvModal.hide("modal-newcompetence");
        });
    },
    delete_competence(node) {
      self = this;
      var elem = this.nodes.find(el => el.id == node);
      var children = this.nodes.filter(el => el.pid == elem.id);
      if (children.length > 0) {
        alert(
          "Невозможно удалить компетенцию, так как к ней привязаны другие элементы. Сначала необходимо удалить их или перенести."
        );
      } else {
        this.$bvModal
          .msgBoxConfirm(
            "Действительно хотите удалить компетенцию «" + elem.name + "»?"
          )
          .then(value => {
            if (value === true) {
              axios
                .post(
                  "/dpps/" + this.$route.params.dpp + "/remove_competence",
                  {
                    competence_id: node
                  }
                )
                .then(response => this.chart.removeNode(node))
                .finally(function() {
                  //self.oc(self.$refs.tree, self.nodes)
                });
            }
          });
      }
    },
    edit_competence(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.edit_type = "competence";
      this.$nextTick(() => {
        this.$bvModal.show("modal-editcompetence");
      });
    },
    update_competence(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/update_competence", {
          competence_data: data.competence_data,
          competence_name: data.competence_name
        })
        .then(function(response) {
          var upd_node = self.nodes.find(
            node => node.id == response.data.new_id
          );
          upd_node.name = response.data.name;
          self.chart.center(response.data.new_id);
        })
        .finally(function() {
          self.$bvModal.hide("modal-editcompetence");
        });
    },
    add_parent(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.edit_type = "parent";
      this.$nextTick(() => {
        this.$bvModal.show("modal-addparent");
      });
      //this.edit_elem.id = node
      //this.chart.addSlink(node, 'a62', '', "blue")
    },
    draw_parent(data) {
      var self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/add_knowledge_link", {
          knowledge_id: this.edit_elem.id,
          ability_id: data
        })
        .then(function(response) {
          self.chart.addSlink(self.edit_elem.id, data, "", "blue");
          self.$bvModal.hide("modal-addparent");
          self.chart.draw(OrgChart.action.init);
        });
    },
    order_children(node) {
      this.edit_elem = this.nodes.find(el => el.id == node);
      this.$nextTick(() => {
        this.$bvModal.show("modal-orderchildren");
      });
    },
    update_order(data) {
      self = this;
      axios
        .post("/dpps/" + this.$route.params.dpp + "/update_order", {
          edit_elem: this.edit_elem,
          children: data
        })
        .then(function(response) {
          for (var i = 0; i<data.length; i++)
          {
               self.nodes.find(node =>  {
                if (node.name == data[i].name)
                {
                    node.position = i+1
                }
               })
            
          }
        })
        .finally (() =>(this.chart.draw(OrgChart.action.init)))
        self.$nextTick(() => {
        self.edit_elem = {id:0}
        self.$bvModal.hide("modal-orderchildren");
        });
        
    },
    check_stage() {
      this.errors = [];
      for (var i = 0; i < this.parts.length; i++) {
        if (this.parts[i].knowledges.length == 0) {
          this.errors.push(
            "К типовому разделу содержания «" +
              this.parts[i].name +
              " не прикреплено ни одного знания»"
          );
        }
      }
      for (i = 0; i < this.nodes.length; i++) {
        if (this.nodes[i].valid == false) {
          this.errors.push("Не заполнен элемент «" + this.nodes[i].name + "»");
        }
      }
      if (this.errors.length == 0) {
        this.go_forward();
      }
    },
    go_forward() {
      var self = this;
      axios
        .post(
          "/dpps/" + this.$route.params.dpp + "/" + this.stage.id + "/go_next"
        )
        .then(() =>
          this.$router.push(
            "/my_dpps/" + this.$route.params.dpp + "/overview/1"
          )
        );
    },
    export_node(node) {
        this.chart.exportPNG({
            filename: "MyOrgChart.png", 
            nodeId: node, 
            openInNewTab: false,
            expandChildren: true, 
            margin: [10,20,10,20],
            header: 'Экспорт',
        });
    },
  },
  mounted() {
    axios
      .get(
        "/dpps/" +
          this.$route.params.dpp +
          "/get_stage_data/" +
          this.$route.params.stage
      )
      .then(response => (this.stage = response.data))
      .finally(() => this.get_zun_versions_data());
  }
};
</script>

<style id="myStyles">
#tree {
  width: 100%;
  height: 100%;
}

path {
  stroke: #000;
  stroke-width: 2;
}
.node.competence rect {
  fill: #040347;
}

.node.skill rect {
  fill: #13465b;
}

.node.ability rect {
  fill: #316950;
}

.node.knowledge rect {
  fill: #dba94c;
}

.print_type {
  text-transform: uppercase;
}

circle {
  stroke: #000;
}
line {
  stroke: #000;
  stroke-width: 2;
}
[control-export-menu] {
  left: 100px;
  top: 50px;
}
[data-id="search-icon"] {
  left: 500px;
}
</style>