(window.webpackJsonp=window.webpackJsonp||[]).push([[27],{SU6m:function(e,t,a){"use strict";a.r(t);var n={name:"my_dpps",metaInfo:{title:"Разработка ДПП"},data:function(){return{isBusy:!0,fields:[{key:"fullname",label:"ФИО",sortable:!0},{key:"rolename",label:"Роль",sortable:!0},{key:"email",label:"E-mail",sortable:!0},{key:"phone",label:"Телефон",sortable:!0},{key:"modify",label:"Управление",sortable:!0}],my_dpps:[]}},mounted:function(){var e=this;axios.get("/my_dpps").then((function(t){return e.my_dpps=t.data})).finally((function(){return e.isBusy=!1}))}},r=a("KHd+"),i=Object(r.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("b-card",{attrs:{title:"Разработка ДПП"}},[a("b-card-text",[e._v("\n    Ниже отображаются все ДПП, где Вы назначены как исполнитель\n    ")]),e._v(" "),a("hr"),e._v(" "),e._l(e.my_dpps,(function(t){return a("div",{key:t.id},[a("b-card",{attrs:{"header-border-variant":"secondary","border-variant":"secondary","header-bg-variant":"light",header:t.name}},[a("h5",[e._v("Статус: "+e._s(t.status_name))]),e._v(" "),a("h5",[e._v("Текущий этап: "+e._s(t.current_stage_name))]),e._v(" "),a("h5",[e._v("Исполнители")]),e._v(" "),a("b-table",{attrs:{bordered:"",hover:"","table-variant":"light","head-variant":"light",items:t.participants,fields:e.fields},scopedSlots:e._u([{key:"cell(modify)",fn:function(t){return[t.item.is_me?a("p",[e.isBusy?e._e():a("router-link",{attrs:{icon:"ion ion-md-person",to:{name:"dpp_overview",params:{dpp:t.item.dpp_id,role:t.item.role_id}},exact:!0}},[a("b-button",{attrs:{variant:"primary"}},[e._v("\n                                Войти как "+e._s(t.item.rolename)+"\n                            ")])],1)],1):e._e()]}}],null,!0)})],1)],1)}))],2)}),[],!1,null,null,null);t.default=i.exports}}]);