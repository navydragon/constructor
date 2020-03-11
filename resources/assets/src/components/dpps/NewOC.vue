<template>
    <div>
        <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
        <hr>
        <h4>Проектирование результатов </h4>
        <div class="row">
        <div class="col-md-6">
        <h5>Статистика результатов:</h5>
        <ul>
            <li>Компетенций: {{nodes.filter(node => node.type == 'Компетенция').length}}</li>
            <li>Навыков: {{nodes.filter(node => node.type == 'Навык').length}}</li>
            <li>Умений: {{nodes.filter(node => node.type == 'Умение').length}}</li>
            <li>Знаний: {{nodes.filter(node => node.type == 'Знание').length}}</li>
            <li>Из них сквозных знаний: {{nodes.filter(node => node.pid == 0).length}}</li>
        </ul>
        </div>
        <div class="col-md-6">
            <h5>Типовое содержание ДПП:</h5>
            <ul type="none">
                <li v-for="part in parts" :key="part.id">
                <i v-if="part.knowledges.length==0" class="ion ion-md-folder"></i>
                <i v-if="part.knowledges.length!=0" class="ion ion-md-folder text-success"></i>  
                {{part.name}}
                    <ul>
                        <li v-for="knowledge in part.knowledges" :key="'kd'+knowledge.id">{{knowledge.name}}</li>
                    </ul>

                </li>
            </ul>
        </div>
        </div>
        <div>
            <span class="btn btn-secondary" @click="create_skill(null)">Добавить навык</span>
            <span class="btn btn-success" @click="create_ability(null)">Добавить умение</span>
            <span class="btn btn-primary" @click="make_competence(null)">Сформировать компетенцию</span>
        </div>
        <div>
        </div>
        <div id="tree" ref="tree"></div>
        <new-skill2 @add_skill="add_skill" :ish_version_id="stage.ish_version_id" :parent_node="parent_node" :key="ns"></new-skill2>
        <new-ability2 @add_ability="add_ability" :ish_version_id="stage.ish_version_id" :parent_node="parent_node" :key="as"></new-ability2>
        <new-knowledge2 @add_knowledge="add_knowledge" :dtps="parts" :ish_version_id="stage.ish_version_id" :parent_node="parent_node" :key="ks"></new-knowledge2>
        <new-competence2 @add_competence="add_competence" :elems="unattached_elems" :key="cs"></new-competence2>
        <add-parent2 v-if="edit_type=='parent'" @draw_parent="draw_parent" :edit_elem="edit_elem" :elems="nodes.filter(node => node.type == 'Умение')" :key="edit_elem.id"></add-parent2>
        <edit-skill2 v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='skill'" @update_skill="update_skill" :edit_elem="edit_elem.id" :ish_version_id="stage.ish_version_id" :key="'s'+edit_elem.id"></edit-skill2>
        <edit-ability2 v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='ability'" @update_ability="update_ability" :edit_elem="edit_elem.id" :ish_version_id="stage.ish_version_id" :key="'a'+edit_elem.id"></edit-ability2>
        <edit-knowledge2 v-if="!isBusy&&edit_elem.id!='0'&&edit_type=='knowledge'" :dtps="parts" @update_knowledge="update_knowledge" :edit_elem="edit_elem.id" :ish_version_id="stage.ish_version_id" :key="'a'+edit_elem.id"></edit-knowledge2>
    </div>
</template>

<script>

    import OrgChart from '@balkangraph/orgchart.js/orgchart'
    import NewSkill2 from './NewSkill2'
    import NewAbility2 from './NewAbility2'
    import NewKnowledge2 from './NewKnowledge2'
    import NewCompetence2 from './NewCompetence2'
    import AddParent2 from './AddParent2'
    import EditSkill2 from './EditSkill2'
    import EditAbility2 from './EditAbility2'
    import EditKnowledge2 from './EditKnowledge2'
    
    export default {
        name: 'zun2',
        metaInfo: {
            title: "Проектирование результатов"
        },
        data() {
            return {
                ns: 's',
                as: 'a',
                ks: 'k',
                cs: 'c',
                edit_elem: {
                    id: '0'
                },
                edit_type: '',
                nodes: [],
                links: [],
                parent_node: null,
                unattached_elems: [],
                stage: {},
                parts: [],
                isBusy: true
            }
        },
        components: {
            OrgChart, NewSkill2, NewAbility2, NewKnowledge2, NewCompetence2, AddParent2,EditSkill2,EditAbility2,EditKnowledge2
        },
        methods: {
            get_zun_versions_data() {
                axios
                .get('/dpps/'+this.$route.params.dpp+'/get_links/'+ this.stage.zun_version_id)
                .then(response => (this.links = response.data))
                axios
                .get('/dpps/'+this.$route.params.dpp+'/get_typology')
                .then(response => (this.parts = response.data))
                axios
                .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data2/'+ this.stage.zun_version_id)
                .then(response => (this.nodes = response.data))
                .finally(() => (this.oc(this.$refs.tree, this.nodes)))     
            },
            oc: function(domEl, x) {
              //  OrgChart.templates.ula.exportMenuButton = '<div style="position:absolute;right:{p}px;top:{p}px; width:40px;height:50px;cursor:pointer" control-export-menu=""  ><i class="fas fa-file-export"></i></div>';
                OrgChart.templates.comp_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.comp_template.size = [400, 110];
                    OrgChart.templates.comp_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.comp_template.link = '<path stroke="#000000" stroke-width="1px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />'
                    OrgChart.templates.comp_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">КОМПЕТЕНЦИЯ</span></foreignObject>'                         
                    //<span class="btn btn-danger btn-xs"><strong>!</strong></span>
                    OrgChart.templates.comp_template.field_0 = '<foreignObject x="10" y="35" width="390" height="160">'
                    + '<p class="" style="color: white; line-height: 100%;"> {val}</p></foreignObject>'
                    OrgChart.templates.comp_template.nodeMenuButton = 
                    '<g style="cursor:pointer;" transform="matrix(1,0,0,1,93,15)" control-node-menu-id="{id}">'
                    + '<rect x="280" y="60" fill="#000000" fill-opacity="0" width="22" height="22">'
                    + '</rect>'
                    + '<rect x="275" y="-9" width="28" stroke="#040347" height="23" fill="none" rx="10" ry="10"/>'
                    + '<circle cx="283" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="290" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="297" cy="3" r="3" fill="#FFF"></circle>'
                    + '</g>';

                    OrgChart.templates.skil_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.skil_template.size = [400, 110];
                    OrgChart.templates.skil_template.link = '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />'
                    OrgChart.templates.skil_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.skil_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">НАВЫК</span></foreignObject>'                         
                    OrgChart.templates.skil_template.field_0 = '<foreignObject x="10" y="35" width="390" height="160">'
                    + '<p class="" style="color: white; line-height: 100%;"> {val}</p></foreignObject>'
                    OrgChart.templates.skil_template.nodeMenuButton = 
                    '<g style="cursor:pointer;" transform="matrix(1,0,0,1,93,15)" control-node-menu-id="{id}">'
                    + '<rect x="280" y="60" fill="#000000" fill-opacity="0" width="22" height="22">'
                    + '</rect>'
                    + '<rect x="275" y="-9" width="28" height="23" fill="none" rx="10" ry="10"/>'
                    + '<circle cx="283" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="290" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="297" cy="3" r="3" fill="#FFF"></circle>'
                    + '</g>';
                    
                    OrgChart.templates.abil_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.abil_template.size = [410, 110];
                    OrgChart.templates.abil_template.link = '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />'
                    OrgChart.templates.abil_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.abil_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">УМЕНИЕ</span></foreignObject>'                         
                    OrgChart.templates.abil_template.field_0 = '<foreignObject x="10" y="35" width="390" height="160">'
                    + '<p class="" style="color: white; line-height: 100%;"> {val}</p></foreignObject>'
                    OrgChart.templates.abil_template.nodeMenuButton = 
                    '<g style="cursor:pointer;" transform="matrix(1,0,0,1,93,15)" control-node-menu-id="{id}">'
                    + '<rect x="280" y="60" fill="#000000" fill-opacity="0" width="22" height="22">'
                    + '</rect>'
                    + '<rect x="275" y="-9" width="28" height="23" fill="none" rx="10" ry="10"/>'
                    + '<circle cx="283" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="290" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="297" cy="3" r="3" fill="#FFF"></circle>'
                    + '</g>';

                    OrgChart.templates.know_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.know_template.size = [400, 110];
                    OrgChart.templates.know_template.link = '<path stroke="#000000" stroke-width="2px" fill="none" link-id="[{id}][{child-id}]" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />'
                    OrgChart.templates.know_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.know_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">ЗНАНИЕ</span></foreignObject>'                         
                    OrgChart.templates.know_template.field_0 = '<foreignObject x="10" y="35" width="390" height="160">'
                    + '<p class="" style="color: white; line-height: 100%;"> {val}</p></foreignObject>'
                    OrgChart.templates.know_template.nodeMenuButton = 
                    '<g style="cursor:pointer;" transform="matrix(1,0,0,1,93,15)" control-node-menu-id="{id}">'
                    + '<rect x="280" y="60" fill="#000000" fill-opacity="0" width="22" height="22">'
                    + '</rect>'
                    + '<rect x="275" y="-9" width="28" height="23" fill="none" rx="10" ry="10"/>'
                    + '<circle cx="283" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="290" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="297" cy="3" r="3" fill="#FFF"></circle>'
                    + '</g>';

                    OrgChart.templates.through_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.through_template.size = [400, 110];
                    OrgChart.templates.through_template.node = '<rect x="0" y="0" fill= "#B15124" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.through_template.field_1 = ''                         
                    OrgChart.templates.through_template.field_0 = '<foreignObject x="100" y="40" width="390" height="160">'
                    + '<p class="" style="font-size:20px; color: white; line-height: 100%;"> {val}</p></foreignObject>'
                    OrgChart.templates.through_template.nodeMenuButton = 
                    '<g style="cursor:pointer;" transform="matrix(1,0,0,1,93,15)" control-node-menu-id="{id}">'
                    + '<rect x="280" y="60" fill="#000000" fill-opacity="0" width="22" height="22">'
                    + '</rect>'
                    + '<rect x="275" y="-9" width="28" height="23" fill="none" rx="10" ry="10"/>'
                    + '<circle cx="283" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="290" cy="3" r="3" fill="#FFF"></circle>'
                    + '<circle cx="297" cy="3" r="3" fill="#FFF"></circle>'
                    + '</g>';

                
                
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
                
               self = this;
               this.chart = new OrgChart(domEl, {
                nodes: x,
                template: "ula",
                enableDragDrop: true,
                nodeMouseClick: null,
                slinks: this.links,
                showXScroll: OrgChart.scroll.visible,
                showYScroll: OrgChart.scroll.visible,
                nodeBinding: {
                    field_0: "name",
                    field_1: "type",
                },
                scaleInitial: OrgChart.match.boundary,
                menu: {
                    pdf: { text: "Экспорт PDF" },
                    png: { text: "Экспорт PNG" }
                },
                nodeMenu:{
               
                },
                toolbar: {
                    layout: true,
                    zoom: true,
                    fit: true,
                    expandAll: false
                },
                //layout: OrgChart.treeRightOffset,
                orientation: 3,
                tags: {
                "competence": {
                    nodeMenu:{
                    create_skill: { text: "Добавить навык", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_skill(node) }  },
                    create_ability: { text: "Добавить умение", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_ability(node) }  },
                    set_target: { text: "Редактировать", icon: '<i class="fas fa-edit"></i>', onClick: function(){ alert('set target') } },
                    delete_competence: { text: "Удалить компетенцию", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_competence(node) }  },
                      },
                    template: "comp_template",
                },
                "skill": {
                    nodeMenu:{
                        create_ability: { text: "Добавить умение", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_ability(node) }  },
                        edit_skill: { text: "Редактировать навык", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.edit_skill(node) }  },
                        delete_skill: { text: "Удалить навык", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_skill(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "skil_template"
                },
                "ability": {
                    nodeMenu:{
                        create_knowledge: { text: "Добавить знание", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_knowledge(node) }  },
                        edit_ability: { text: "Редактировать умение", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.edit_ability(node) }  },
                        delete_ability: { text: "Удалить умение", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_ability(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "abil_template"
                },
                "knowledge": {
                    nodeMenu:{
                        edit_knowledge: { text: "Редактировать знание", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.edit_knowledge(node) }  },
                        delete_knowledge: { text: "Удалить знание", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_knowledge(node) }  },
                        add_parent: { text: "Добавить дополнительную связь", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.add_parent(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "know_template"
                },
                "through": {
                    nodeMenu:{
                        create_knowledge: { text: "Добавить знание", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_knowledge(node) }  },
                    },
                    template: "through_template",
                    },
                },
                
                });
                this.isBusy = false
                this.chart.on('drop', function (sender, draggedNodeId, droppedNodeId) {
                  var dragged = self.nodes.find(el => el.id == draggedNodeId)
                  var dropped = self.nodes.find(el => el.id == droppedNodeId)
                  if (dragged.type == dropped.type)
                  {
                    alert('Ошибка! Невозможно добавить элемент к элементу того же типа')
                    return false;
                  }
                  if (dragged.type == 'Компетенция')
                  {
                    alert('Ошибка! Компетенцию нельзя присоединить к другому элементу')
                    return false;
                  }
                  if (dropped.type == 'Знание')
                  {
                    alert('Ошибка! Невозможно добавить элемент к элементу типа «Знание»')
                    return false;
                  }
                  if (dropped.type == 'Сквозные знания' && dragged.type != 'Знание')
                  {
                    alert('Ошибка! Сквозным может быть только знание')
                    return false;
                  }
                  if (dropped.type == 'Навык' && dragged.type == 'Знание')
                  {
                    alert('Ошибка! Знание не может принадлежать навыку')
                    return false;
                  }
                  
                  if (dragged.type == 'Навык')
                  {
                      if (dropped.type == 'Умение')
                      {
                        alert('Ошибка! Невозможно присоединить навык к умению')
                        return false;
                      }
                  }

                    axios
                    .post('/dpps/'+self.$route.params.dpp+'/move_elem', {
                        'elem_type': dragged.type,
                        'elem_id': dragged.id,
                        'to_type': dropped.type,
                        'to_id': dropped.id,
                    })
                    return true
                });
            
            },
            create_skill(node)
            {
               this.parent_node = node
               this.$bvModal.show("modal-newskill")
            },
            add_skill(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_skill', {
                    'zun_version': this.stage.zun_version_id,
                    'skill_data': data.skill_data,
                    'parent_node': data.parent_node,
                    'skill_name': data.skill_name,
                })
                .then( function(response) {
                    self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
                    self.chart.center(response.data.new_id);
                    //self.chart.draw(OrgChart.action.init);
                })
                    
                .finally( function() {
                    //self.chart.draw(OrgChart.action.init);
                    //self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-newskill")
                    self.ns = self.ns+1;
                }) 
               
            },
            delete_skill (node)
            {
                self = this
                var elem = this.nodes.find(el => el.id == node)
                var children = this.nodes.filter(el => el.pid == elem.id)
                if (children.length > 0)   
                {
                    alert("Невозможно удалить навык, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести.")
                }else{
                    this.$bvModal.msgBoxConfirm('Действительно хотите удалить навык «'+elem.name+'»?')
                    .then(value => {
                    if (value === true) {
                        axios
                        .post('/dpps/'+this.$route.params.dpp+'/remove_skill', {
                            'skill_id': node,
                        })
                        .then(response => (this.chart.removeNode(node)))
                        .finally( function() {
                            //self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

            },
            edit_skill(node)
            {
                this.edit_elem = this.nodes.find(el => el.id == node)
                this.edit_type = 'skill'
                this.$nextTick(() => {
                    this.$bvModal.show("modal-editskill")
                })
            },
            update_skill(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/update_skill', {
                    'skill_data': data.skill_data,
                    'skill_name': data.skill_name,
                })
                .then( function(response) {
                   // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
                   var upd_node = self.nodes.find(node => node.id == response.data.new_id)
                   upd_node.name = response.data.name
                   self.chart.center(response.data.new_id);
                    //self.chart.draw(OrgChart.action.init);
                })
                    
                .finally( function() {
                    //self.chart.draw(OrgChart.action.init);
                    //self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-editskill")
                }) 
            },
            create_ability(node)
            {
               this.parent_node = node
               this.$bvModal.show("modal-newability")
            },
            add_ability(data)
            {
                self = this
                var parent = self.nodes.find(node => node.id == data.parent_node)
                console.log(parent)
                if (parent)
                {
                    if (parent.type == 'Навык')
                    {
                        var parent_type = 'skill'
                    }
                    if (parent.type == 'Компетенция')
                    {
                        var parent_type = 'competence'
                    }
                }else{
                    var parent_type = 'no'
                }
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_ability', {
                    'zun_version': this.stage.zun_version_id,
                    'ability_data': data.ability_data,
                    'parent_node': data.parent_node,
                    'parent_type': parent_type,
                    'ability_name': data.ability_name,
                })
                .then( function (response) {
                    self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Умение",tags:["ability"] })
                    self.chart.center(response.data.new_id);
                })
                .finally( function() {
                   // self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-newability")
                    self.as = self.as+1;
                }) 
               
            },
            edit_ability(node)
            {
                this.edit_elem = this.nodes.find(el => el.id == node)
                this.edit_type = 'ability'
                this.$nextTick(() => {
                    this.$bvModal.show("modal-editability")
                })
            },
            update_ability(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/update_ability', {
                    'ability_data': data.ability_data,
                    'ability_name': data.ability_name,
                })
                .then( function(response) {
                   // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
                   var upd_node = self.nodes.find(node => node.id == response.data.new_id)
                   upd_node.name = response.data.name
                   self.chart.center(response.data.new_id);
                })
                    
                .finally( function() {
                    //self.chart.draw(OrgChart.action.init);
                    //self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-editability")
                }) 
            },
            delete_ability (node)
            {
                self = this
                var elem = this.nodes.find(el => el.id == node)
                var children = this.nodes.filter(el => el.pid == elem.id)
                if (children.length > 0)   
                {
                    alert("Невозможно удалить умение, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести.")
                }else{
                    this.$bvModal.msgBoxConfirm('Действительно хотите удалить умение «'+elem.name+'»?')
                    .then(value => {
                    if (value === true) {
                        axios
                        .post('/dpps/'+this.$route.params.dpp+'/remove_ability', {
                            'ability_id': node,
                        })
                        .then(response => (this.chart.removeNode(node)))
                        .finally( function() {
                            //self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

            },
            
            create_knowledge(node)
            {
               this.parent_node = node
               this.$bvModal.show("modal-newknowledge")
            },
            add_knowledge(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_knowledge', {
                    'zun_version': this.stage.zun_version_id,
                    'knowledge_data': data.knowledge_data,
                    'parent_node': data.parent_node,
                    'knowledge_name': data.knowledge_name,
                })
                .then(function(response) {
                    self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Знание",tags:["knowledge"] })
                    self.chart.center(response.data.new_id);
                    })
                .finally( function() {
                    //self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-newknowledge")
                    self.ks = self.ks+1;
                }) 
               
                axios
                    .get('/dpps/'+this.$route.params.dpp+'/get_typology')
                    .then(response => (self.parts = response.data))
            },
            delete_knowledge (node)
            {
                self = this
                var elem = this.nodes.find(el => el.id == node)
                var children = this.nodes.filter(el => el.pid == elem.id)
                if (children.length > 0)   
                {
                    alert("Невозможно удалить умение, так как к нему привязаны другие элементы. Сначала необходимо удалить их или перенести.")
                }else{
                    this.$bvModal.msgBoxConfirm('Действительно хотите удалить знание «'+elem.name+'»?')
                    .then(value => {
                    if (value === true) {
                        axios
                        .post('/dpps/'+this.$route.params.dpp+'/remove_knowledge', {
                            'knowledge_id': node,
                        })
                        .then(response => (this.chart.removeNode(node)))
                        .finally( function() {
                            //self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

            },
            edit_knowledge(node)
            {
                this.edit_elem = this.nodes.find(el => el.id == node)
                this.edit_type = 'knowledge'
                this.$nextTick(() => {
                    this.$bvModal.show("modal-editknowledge")
                })
            },
            update_knowledge(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/update_knowledge', {
                    'knowledge_data': data.knowledge_data,
                    'knowledge_name': data.knowledge_name,
                })
                .then( function(response) {
                   // self.chart.addNode({ id: response.data.new_id, pid: response.data.new_parent, name: response.data.name, type: "Навык",tags:["skill"] })
                   var upd_node = self.nodes.find(node => node.id == response.data.new_id)
                   upd_node.name = response.data.name
                   self.chart.center(response.data.new_id);
                })
                    
                .finally( function() {
                    //self.chart.draw(OrgChart.action.init);
                    //self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-editknowledge")
                }) 

                axios
                    .get('/dpps/'+this.$route.params.dpp+'/get_typology')
                    .then(response => (self.parts = response.data))
            },
            disconnect (node)
            {
                self = this
                var elem = self.nodes.find(el => el.id == node)
                axios
                .post('/dpps/'+this.$route.params.dpp+'/disconnect', {
                    'elem': elem,
                })
                .then(response => (elem.pid = null))
                .finally( function() {
                    self.oc(self.$refs.tree, self.nodes)
                })
            },
            generate_id () {
                return `f${(~~(Math.random() * 1e8)).toString(16)}`
            },
            set_width (el) {
                console.log(el)
                return 400
            },
            make_competence()
            {
               var elems = this.nodes.filter(node => node.pid == null)
               elems = elems.filter(node => node.type != 'Сквозные знания')
               elems = elems.filter(node => node.type != 'Компетенция')
               this.unattached_elems = elems
               this.$bvModal.show("modal-newcompetence")
            },
            add_competence(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_competence', {
                    'zun_version': this.stage.zun_version_id,
                    'competence_data': data.competence_data,
                    'competence_name': data.competence_name,
                })
                .then(function(response){

                    self.chart.addNode({ id: response.data.new_id, pid: null, name: response.data.name, type: "Компетенция",tags:["competence"] })
                    for (var i = 0; i<data.competence_data.elems.length;i++)
                    {
                        console.log(data.competence_data.elems[i])
                        var old_elem = self.nodes.find(node => node.id == data.competence_data.elems[i])
                        self.chart.updateNode({ id: old_elem.id, type: old_elem.type, tags: old_elem.tags, name: old_elem.name, pid: response.data.new_id});
                    }
                    self.chart.center(response.data.new_id)
                    self.$bvModal.hide("modal-newcompetence")
                })

            },
            delete_competence(node)
            {
                self = this
                var elem = this.nodes.find(el => el.id == node)
                var children = this.nodes.filter(el => el.pid == elem.id)
                if (children.length > 0)   
                {
                    alert("Невозможно удалить компетенцию, так как к ней привязаны другие элементы. Сначала необходимо удалить их или перенести.")
                }else{
                    this.$bvModal.msgBoxConfirm('Действительно хотите удалить компетенцию «'+elem.name+'»?')
                    .then(value => {
                    if (value === true) {
                        axios
                        .post('/dpps/'+this.$route.params.dpp+'/remove_competence', {
                            'competence_id': node,
                        })
                        .then(response => (this.chart.removeNode(node)))
                        .finally( function() {
                            //self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

            },
            add_parent(node)
            {
                this.edit_elem = this.nodes.find(el => el.id == node)
                this.edit_type = 'parent'
                this.$nextTick(() => {
                    this.$bvModal.show("modal-addparent")
                })
                //this.edit_elem.id = node
                //this.chart.addSlink(node, 'a62', '', "blue") 
            },
            draw_parent (data)
            {   
               var self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_knowledge_link',{
                    'knowledge_id': this.edit_elem.id,
                    'ability_id': data
                })
                .then( function (response) {
                    self.chart.addSlink(self.edit_elem.id, data, '', "blue")
                    self.$bvModal.hide("modal-addparent")
                    self.chart.draw(OrgChart.action.init);
                })

            },
        },
        mounted(){
        axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        .finally( () =>( this.get_zun_versions_data() ) )  
        }
    }
</script>

<style id="myStyles">

#tree {
    width: 100%;
    height: 100%;
}

.node.competence rect {
     fill: #040347; 
}

.node.skill rect {
    fill: #13465B;
}

.node.ability rect {
    fill: #316950;
}

.node.knowledge rect {
    fill: #DBA94C;
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