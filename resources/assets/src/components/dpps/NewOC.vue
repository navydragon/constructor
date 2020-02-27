<template>
    <div id="tree" ref="tree"></div>
</template>

<script>

    import OrgChart from '@balkangraph/orgchart.js/orgchart'

    export default {

        name: 'tree',
        data() {
            return {
                nodes: [
                    { id: 1, name: "Denny Curtis выф вы фв ыфвыф выф выф выфвфыв фвы ывфвыфвыфв ыфв ыфв фыв фыв ыфв ыф111111 11", type: "Компетенция"},
                    { id: 2, pid: 1, name: "Ashley Barnett", type: "Навык" },
                    { id: 3, pid: 1, name: "Caden Ellison", type: "Навык" },
                    { id: 4, pid: 2, name: "Elliot Patel", type: "Умение" },
                    { id: 5, pid: 2, name: "Lynn Hussain", type: "Умение" },
                    { id: 6, pid: 3, name: "Tanner May", type: "Знание" },
                    { id: 7, pid: 3, name: "Fran Parsons", type: "Знание" }
                ]
            }
        },

        methods: {
            oc: function(domEl, x) {
                OrgChart.templates.competence = Object.assign({}, OrgChart.templates.ana);
                OrgChart.templates.competence.node = 
                '<rect x="0" y="0" width="400" height="100"  rx="15" ry="15"/>'
                OrgChart.templates.competence.field_0 = '<foreignObject x="10" y="30" width="390" height="160">'
                + '<p class="text-primary"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.Sed mollis mollis mi ut ultricies. Nullam magna ipsum, porta vel dui convallis, rutrum imperdiet eros. Aliquam erat volutpat.</p></foreignObject>'
               
                for (var i = 0; i < this.nodes.length; i++) {
                    var node = this.nodes[i];
                    switch (node.type) {
                        case "Компетенция":
                            node.tags = ["Компетенция"];
                            break;
                        case "Навык":
                        case "Умение":
                        case "Знание":
                    }
                }
               
               this.chart = new OrgChart(domEl, {
                nodes: x,
                nodeBinding: {
                    field_0: "name",
                    field_1: "type",
                },
                menu: {
                    pdf: { text: "Экспорт PDF" },
                    png: { text: "Экспорт PNG" }
                },
                nodeMenu:{
                details: {text:"Просмотр"},
            	edit: {text:"Редактировать"},
            	add: {text:"Add"},
            	remove: {text:"Удалить"}
                },
                tags: {
                "Компетенция": {
                    template: "competence"
                }
                },
                });



            },
            set_width (el) {
                console.log(el)
                return 400
            }
        },

        mounted(){
        this.oc(this.$refs.tree, this.nodes)
        }
    }
</script>

<style scoped>
</style>