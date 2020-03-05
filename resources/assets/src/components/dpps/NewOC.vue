<template>
    <div>
        <div>
            <span class="btn btn-secondary" @click="create_skill(null)">Добавить навык</span>
            <span class="btn btn-success" @click="create_ability(null)">Добавить умение</span>
        </div>
        <div id="tree" ref="tree"></div>
        <new-skill2 @add_skill="add_skill" :parent_node="parent_node" :key="ns"></new-skill2>
        <new-ability2 @add_ability="add_ability" :parent_node="parent_node" :key="as"></new-ability2>
        <new-knowledge2 @add_knowledge="add_knowledge" :parent_node="parent_node" :key="ks"></new-knowledge2>
    </div>
</template>

<script>

    import OrgChart from '@balkangraph/orgchart.js/orgchart'
    import NewSkill2 from './NewSkill2'
    import NewAbility2 from './NewAbility2'
    import NewKnowledge2 from './NewKnowledge2'
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
                nodes: [],
                parent_node: null
            }
        },
        components: {
            OrgChart, NewSkill2, NewAbility2, NewKnowledge2
        },
        methods: {
            get_zun_versions_data() {
                axios
                .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data2/'+ this.stage.zun_version_id)
                .then(response => (this.nodes = response.data))
                .finally(() => (this.oc(this.$refs.tree, this.nodes)))     
            },
            oc: function(domEl, x) {
                OrgChart.templates.comp_template = Object.assign({}, OrgChart.templates.ula);
                    OrgChart.templates.comp_template.size = [400, 110];
                    OrgChart.templates.comp_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.comp_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">КОМПЕТЕНЦИЯ</span></foreignObject>'                         
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
                    OrgChart.templates.abil_template.size = [400, 110];
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
                    OrgChart.templates.know_template.node = '<rect x="0" y="0" fill= "#040347" width="400" height="100"  rx="5" ry="5"/>'
                    OrgChart.templates.know_template.field_1 = '<foreignObject x="10" y="5" width="390" height="50">'
                    + '<span style="color: white; line-height: 100%; font-weight:bolder;">УМЕНИЕ</span></foreignObject>'                         
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
                    }
                }
                
               self = this;
               this.chart = new OrgChart(domEl, {
                nodes: x,
                enableDragDrop: true,
                nodeMouseClick: null,
                nodeBinding: {
                    field_0: "name",
                    field_1: "type",
                },
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
                layout: OrgChart.treeRightOffset,
                tags: {
                "competence": {
                    nodeMenu:{
                    create_skill: { text: "Добавить навык", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_skill(node) }  },
                	set_target: { text: "Редактировать", icon: '<i class="fas fa-edit"></i>', onClick: function(){ alert('set target') } }
                      },
                    template: "comp_template",
                },
                "skill": {
                    nodeMenu:{
                        create_ability: { text: "Добавить умение", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_ability(node) }  },
                        delete_skill: { text: "Удалить навык", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_skill(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "skil_template"
                },
                "ability": {
                    nodeMenu:{
                        create_knowledge: { text: "Добавить знание", icon: '<i class="fas fa-user-plus"></i>', onClick: function(node){ self.create_knowledge(node) }  },
                        delete_ability: { text: "Удалить умение", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_ability(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "abil_template"
                },
                "knowledge": {
                    nodeMenu:{
                        delete_knowledge: { text: "Удалить знание", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.delete_knowledge(node) }  },
                        disconnect: { text: "Отсоединить", icon: '<i class="fas fa-user-minus"></i>', onClick: function(node){ self.disconnect(node) }  },
                    },
                    template: "know_template"
                },
                "through": {
                        group: true,
                        groupName: "HR Team",
                        template: "group_grey"
                    },
                },
                
                });

                this.chart.on('drop', function (sender, draggedNodeId, droppedNodeId) {
                  var dragged = self.nodes.find(el => el.id == draggedNodeId)
                  var dropped = self.nodes.find(el => el.id == droppedNodeId)
                  if (dragged.type == dropped.type)
                  {
                    alert('Ошибка! Невозможно добавить элемент к элементу того же типа')
                    return false;
                  }
                  if (dropped.type == 'Знание')
                  {
                    alert('Ошибка! Невозможно добавить элемент к элементу типа «Знание»')
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
                .then(response => (this.nodes.push({ id: response.data.id, pid: response.data.competence_id, name: response.data.name, type: "Навык" })))
                .finally( function() {
                    self.oc(self.$refs.tree, self.nodes)
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
                        .then(response => (this.nodes = this.nodes.filter(el => el.id != node)))
                        .finally( function() {
                            self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

            },
            create_ability(node)
            {
               this.parent_node = node
               this.$bvModal.show("modal-newability")
            },
            add_ability(data)
            {
                self = this
                axios
                .post('/dpps/'+this.$route.params.dpp+'/add_ability', {
                    'zun_version': this.stage.zun_version_id,
                    'ability_data': data.ability_data,
                    'parent_node': data.parent_node,
                    'ability_name': data.ability_name,
                })
                .then(response => (this.nodes.push({ id: response.data.id, pid: response.data.skill_id, name: response.data.name, type: "Умение" })))
                .finally( function() {
                    self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-newability")
                    self.as = self.as+1;
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
                        .then(response => (this.nodes = this.nodes.filter(el => el.id != node)))
                        .finally( function() {
                            self.oc(self.$refs.tree, self.nodes)
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
                .then(response => (this.nodes.push({ id: response.data.id, pid: response.data.ability_id, name: response.data.name, type: "Знание" })))
                .finally( function() {
                    self.oc(self.$refs.tree, self.nodes)
                    self.$bvModal.hide("modal-newknowledge")
                    self.ks = self.ks+1;
                }) 
               
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
                        .then(response => (this.nodes = this.nodes.filter(el => el.id != node)))
                        .finally( function() {
                            self.oc(self.$refs.tree, self.nodes)
                        })
                    }}) 
                }

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
            }
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
</style>