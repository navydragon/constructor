(window.webpackJsonp=window.webpackJsonp||[]).push([[18],{Zeex:function(e,t,n){"use strict";n.r(t);var i=n("oELJ"),a=n.n(i),s={name:"tree",data:function(){return{nodes:[{id:1,name:"Denny Curtis выф вы фв ыфвыф выф выф выфвфыв фвы ывфвыфвыфв ыфв ыфв фыв фыв ыфв ыф111111 11",type:"Компетенция"},{id:2,pid:1,name:"Ashley Barnett",type:"Навык"},{id:3,pid:1,name:"Caden Ellison",type:"Навык"},{id:4,pid:2,name:"Elliot Patel",type:"Умение"},{id:5,pid:2,name:"Lynn Hussain",type:"Умение"},{id:6,pid:3,name:"Tanner May",type:"Знание"},{id:7,pid:3,name:"Fran Parsons",type:"Знание"}]}},methods:{oc:function(e,t){a.a.templates.competence=Object.assign({},a.a.templates.ana),a.a.templates.competence.node='<rect x="0" y="0" width="400" height="100"  rx="15" ry="15"/>',a.a.templates.competence.field_0='<foreignObject x="10" y="30" width="390" height="160"><p class="text-primary"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.Sed mollis mollis mi ut ultricies. Nullam magna ipsum, porta vel dui convallis, rutrum imperdiet eros. Aliquam erat volutpat.</p></foreignObject>';for(var n=0;n<this.nodes.length;n++){var i=this.nodes[n];switch(i.type){case"Компетенция":i.tags=["Компетенция"]}}this.chart=new a.a(e,{nodes:t,nodeBinding:{field_0:"name",field_1:"type"},menu:{pdf:{text:"Экспорт PDF"},png:{text:"Экспорт PNG"}},nodeMenu:{details:{text:"Просмотр"},edit:{text:"Редактировать"},add:{text:"Add"},remove:{text:"Удалить"}},tags:{"Компетенция":{template:"competence"}}})},set_width:function(e){return console.log(e),400}},mounted:function(){this.oc(this.$refs.tree,this.nodes)}},d=n("KHd+"),o=Object(d.a)(s,(function(){var e=this.$createElement;return(this._self._c||e)("div",{ref:"tree",attrs:{id:"tree"}})}),[],!1,null,"e383e718",null);t.default=o.exports}}]);