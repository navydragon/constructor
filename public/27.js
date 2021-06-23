(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[27],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.common.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_search_select__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-tables-2 */ "./node_modules/vue-tables-2/compiled/index.js");
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_tables_2__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _NewQuestion__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./NewQuestion */ "./resources/assets/src/components/dpps/NewQuestion.vue");
/* harmony import */ var _EditQuestion__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./EditQuestion */ "./resources/assets/src/components/dpps/EditQuestion.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





vue__WEBPACK_IMPORTED_MODULE_1___default.a.use(vue_tables_2__WEBPACK_IMPORTED_MODULE_2__["ClientTable"]);
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "dpp_stage_work_om",
  metaInfo: {
    title: "Разработка ДПП - Оценочные материалы"
  },
  components: {
    ModelSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_0__["ModelSelect"],
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_2__["ClientTable"],
    NewQuestion: _NewQuestion__WEBPACK_IMPORTED_MODULE_3__["default"],
    EditQuestion: _EditQuestion__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  computed: {
    header: function header() {
      return "Разработка ДПП / " + this.stage.dpp_name + " / " + this.stage.type_name;
    }
  },
  data: function data() {
    return {
      show_edit_window: false,
      show_task_edit_window: false,
      question_to_edit: 0,
      task_to_edit: 0,
      nq_key: 0,
      eq_key: 0,
      stage: {},
      tasks: [],
      knowledge: {
        questions: []
      },
      knowledges: [{
        text: 'Знание 1',
        value: 1,
        questions: [{
          text: 'Вопрос 1',
          type_name: 'Выбор вариантов ответа'
        }]
      }, {
        text: 'Знание 2',
        value: 2,
        questions: []
      }],
      zuns: [],
      kn_columns: ['text', 'type_name', 'edit'],
      task_columns: ['name', 'edit'],
      options: {
        pagination: {
          chunk: 5
        },
        sortIcon: {
          is: 'fa-sort',
          base: 'fas',
          up: 'fa-sort-up',
          down: 'fa-sort-down'
        },
        headings: {
          text: 'Текст вопроса',
          type_name: 'Тип вопроса',
          edit: 'Действия'
        },
        texts: {
          count: "Showing {from} to {to} of {count} records|{count} records|Одна запись",
          first: 'First',
          last: 'Last',
          filter: "Поиск:",
          filterPlaceholder: "текст поиска",
          limit: "Записей:",
          page: "Страница:",
          noResults: "Не найдено ни одной записи",
          filterBy: "Filter by {column}",
          loading: 'Загрузка...',
          defaultOption: 'Select {column}',
          columns: 'Columns'
        }
      },
      task_options: {
        pagination: {
          chunk: 5
        },
        sortIcon: {
          is: 'fa-sort',
          base: 'fas',
          up: 'fa-sort-up',
          down: 'fa-sort-down'
        },
        headings: {
          text: 'Текст вопроса',
          type_name: 'Тип вопроса',
          name: 'Задание',
          edit: 'Действия'
        },
        texts: {
          count: "Showing {from} to {to} of {count} records|{count} records|Одна запись",
          first: 'First',
          last: 'Last',
          filter: "Поиск:",
          filterPlaceholder: "текст поиска",
          limit: "Записей:",
          page: "Страница:",
          noResults: "Не найдено ни одной записи",
          filterBy: "Filter by {column}",
          loading: 'Загрузка...',
          defaultOption: 'Select {column}',
          columns: 'Columns'
        }
      }
    };
  },
  methods: {
    add_question: function add_question(questionData) {
      self = this;
      console.log(questionData);
      axios.post('/om_version/' + this.stage.om_version_id + '/add_question', {
        'question_data': questionData
      }).then(function (response) {
        var knowledge = self.knowledges.find(function (kn) {
          return kn.id === questionData.knowledge.id;
        });
        knowledge.questions.push({
          "id": response.data,
          "text": questionData.text,
          "type_name": questionData.type.type_name
        });
        self.nq_key += 1;
      });
    },
    color_kn: function color_kn(knowledge) {
      if (knowledge.length < 4) {
        return 'danger';
      } else {
        return 'success';
      }
    },
    remove_question: function remove_question(question) {
      console.log(question);
      self = this;
      this.$bvModal.msgBoxConfirm('Действительно хотите удалить вопрос «' + question.text + '»?').then(function (value) {
        if (value === true) {
          axios.post('/questions/delete', {
            'id': question.id
          }).then(function (response) {
            console.log(self.knowledges);

            for (var i = 0; i < self.knowledges.length; i++) {
              var quests = self.knowledges[i].questions; //console.log(quests);

              for (var j = 0; j < quests.length; j++) {
                //console.log(quest.id+" - "+question.id);
                if (quests[j].id == question.id) {
                  //console.log('found')
                  var index = self.knowledges[i].questions.indexOf(quests[j]);
                  self.knowledges[i].questions.splice(index, 1);
                }
              }
            }
          });
        }
      })["catch"](function (err) {// An error occurred
      });
    },
    edit_question: function edit_question(question) {
      var _this = this;

      this.question_to_edit = question;
      this.show_edit_window = true;
      this.$nextTick(function () {
        _this.$bvModal.show("modal-editquestion");
      });
    },
    update_question: function update_question(questionData, question_id) {
      self = this;
      console.log(questionData);
      axios.post('/om_version/' + this.stage.om_version_id + '/update_question', {
        'question_data': questionData,
        'question_id': question_id
      }).then(function (response) {
        var knowledge = self.knowledges.find(function (kn) {
          return kn.id === response.data.knowledge_id;
        });
        var question = knowledge.questions.find(function (qu) {
          return qu.id == response.data.id;
        });
        question.text = response.data.text;
        console.log(response);
        self.$bvModal.hide("modal-editquestion");
        self.show_edit_window = false;
      });
    },
    add_task: function add_task(type) {
      var _this2 = this;

      self = this;
      axios.post('/dpps/add_task', {
        'om_version_id': this.stage.om_version_id,
        'type': type
      }).then(function (response) {
        return _this2.$router.push('/my_dpps/' + _this2.$route.params.dpp + '/stages/' + _this2.stage.id + '/work_om/tasks/' + response.data);
      });
    },
    edit_task: function edit_task(task_id) {
      this.$router.push('/my_dpps/' + this.$route.params.dpp + '/stages/' + this.stage.id + '/work_om/tasks/' + task_id);
      /*
      this.task_to_edit = task_id
          this.show_task_edit_window = true
              this.$nextTick(() => {
              this.$bvModal.show("modal-edittask")
              })
      */
    },
    remove_task: function remove_task(task) {
      self = this;
      this.$bvModal.msgBoxConfirm('Действительно хотите задание «' + task.name + ' (' + task.type_name + ')»?').then(function (value) {
        if (value === true) {
          axios.post('/dpps/tasks/remove_task', {
            'id': task.id
          }).then(function (response) {
            self.tasks = self.tasks.filter(function (el) {
              return el.id != task.id;
            });
          })["finally"](function (response) {//self.new_task.subjects = self.new_task.subjects.filter(el => el.id != subject.id)
          });
        }
      });
    },
    update_task: function update_task(data) {},
    go_forward: function go_forward() {
      var _this3 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/' + this.stage.id + '/go_next').then(function () {
        return _this3.$router.push('/my_dpps/' + _this3.$route.params.dpp + '/overview/1');
      });
    }
  },
  mounted: function mounted() {
    var _this4 = this;

    var self = this;
    axios.get('/dpps/' + this.$route.params.dpp + '/get_stage_data/' + this.$route.params.stage).then(function (response) {
      return _this4.stage = response.data;
    })["finally"](function (response) {
      axios.get('/dpps/' + self.stage.dpp_id + '/get_knowledges_to_ov/' + self.stage.om_version_id).then(function (response) {
        return self.knowledges = response.data;
      });
      axios.get('/dpps/get_tasks/' + self.stage.om_version_id).then(function (response) {
        return self.tasks = response.data;
      });
      axios.get("/dpps/" + self.$route.params.dpp + "/get_zuns_to_om/" + self.stage.zun_version_id).then(function (response) {
        return self.zuns = response.data;
      });
    });
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/sass-loader/dist/cjs.js??ref--6-3!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".VueTables__child-row-toggler {\n  display: inline-block;\n  margin: auto;\n  width: 100%;\n  height: 16px;\n  text-align: center;\n  line-height: 16px;\n  cursor: pointer;\n}\n.VueTables__child-row-toggler--closed::before {\n  content: \"+\";\n}\n.VueTables__child-row-toggler--open::before {\n  content: \"-\";\n}\n.VueTables__sortable {\n  position: relative;\n}\n.VueTables__sort-icon {\n  position: absolute;\n  top: 50%;\n  right: 6px;\n  -webkit-transform: translateY(-50%);\n          transform: translateY(-50%);\n}\n[dir=rtl] .VueTables__sort-icon {\n  right: auto;\n  left: 6px;\n}\n.VuePagination nav {\n  display: -ms-flexbox;\n  display: flex;\n  -ms-flex-wrap: wrap;\n      flex-wrap: wrap;\n  -ms-flex-pack: justify;\n      justify-content: space-between;\n  width: 100%;\n}\n.VuePagination .pagination,\n.VuePagination__count {\n  -ms-flex-preferred-size: auto !important;\n      flex-basis: auto !important;\n  -ms-flex-wrap: wrap;\n      flex-wrap: wrap;\n  margin: 0 0 1rem 0;\n  width: auto !important;\n}\n.VueTables__search label,\n.VueTables__limit label {\n  display: inline-block !important;\n  margin-right: .5em;\n}\n[dir=rtl] .VueTables__search label, [dir=rtl]\n.VueTables__limit label {\n  margin-right: 0;\n  margin-left: .5em;\n}\n[dir=\"rtl\"] .pull-left {\n  float: right;\n}\n[dir=\"rtl\"] .pull-right {\n  float: left;\n}\n.default-style .VueTables__sort-icon {\n  color: #a3a4a6;\n  font-size: 0.75rem;\n}\n.default-style .VuePagination__pagination-item > a {\n  padding: 0.375rem 0.25rem;\n  min-width: calc(1.5rem + 2px);\n  text-align: center;\n  font-size: 0.75rem;\n  border-radius: 0.25rem;\n}\n.default-style .VuePagination__count {\n  color: #a3a4a6;\n  font-size: 0.75rem;\n}\n.material-style .VueTables__sort-icon {\n  color: #a3a4a6;\n  font-size: 0.75rem;\n}\n.material-style .VuePagination__pagination-item > a {\n  padding: 0.375rem 0.25rem;\n  min-width: calc(1.5rem + 0px);\n  text-align: center;\n  font-size: 0.75rem;\n  border-radius: 0.125rem;\n}\n.material-style .VuePagination__count {\n  color: #a3a4a6;\n  font-size: 0.75rem;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/sass-loader/dist/cjs.js??ref--6-3!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--6-3!./vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff& ***!
  \******************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "b-button",
        {
          attrs: { variant: "primary" },
          on: {
            click: function($event) {
              return _vm.$router.go(-1)
            }
          }
        },
        [_vm._v("Назад")]
      ),
      _vm._v(" "),
      _c(
        "b-card",
        { attrs: { title: _vm.header } },
        [
          _c(
            "span",
            {
              staticClass: "btn btn-success mb-2",
              on: {
                click: function($event) {
                  return _vm.go_forward()
                }
              }
            },
            [_vm._v("Согласовать результаты и перейти к следующему этапу")]
          ),
          _vm._v(" "),
          _c(
            "b-tabs",
            { attrs: { "content-class": "mt-3", pills: "", fill: "" } },
            [
              _c(
                "b-tab",
                { attrs: { title: "Знания", active: "" } },
                [
                  _c(
                    "b-button",
                    {
                      directives: [
                        {
                          name: "b-modal",
                          rawName: "v-b-modal.modal-addquest",
                          modifiers: { "modal-addquest": true }
                        }
                      ],
                      attrs: { variant: "primary" }
                    },
                    [_vm._v("Добавить вопрос")]
                  ),
                  _vm._v(" "),
                  _c(
                    "b-dropdown",
                    {
                      staticClass: "m-md-2",
                      attrs: { id: "dropdown-2", text: "Экспорт" }
                    },
                    [
                      _c(
                        "b-dropdown-item",
                        {
                          attrs: {
                            href:
                              "/dpps/" +
                              this.$route.params.dpp +
                              "/export_om_questions/" +
                              this.stage.om_version_id
                          }
                        },
                        [_vm._v("Экспорт в Word вопросов")]
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("hr"),
                  _vm._v(" "),
                  _c("new-question", {
                    key: _vm.nq_key,
                    attrs: { knowledges: _vm.knowledges },
                    on: { add_question: _vm.add_question }
                  }),
                  _vm._v(" "),
                  _vm.show_edit_window
                    ? _c("edit-question", {
                        key: _vm.question_to_edit,
                        attrs: { question_to_edit: _vm.question_to_edit },
                        on: { update_question: _vm.update_question }
                      })
                    : _vm._e(),
                  _vm._v(" "),
                  _c("h5", [_vm._v("Фильтр вопросов по знаниям")]),
                  _vm._v(" "),
                  _c("model-select", {
                    attrs: {
                      id: "user-input",
                      options: _vm.knowledges,
                      placeholder: "Выберите знание"
                    },
                    model: {
                      value: _vm.knowledge,
                      callback: function($$v) {
                        _vm.knowledge = $$v
                      },
                      expression: "knowledge"
                    }
                  }),
                  _vm._v(" "),
                  _c("hr", {
                    staticClass: "container-m-nx border-light mt-2 mb-2"
                  }),
                  _vm._v(" "),
                  _c("v-client-table", {
                    attrs: {
                      data: _vm.knowledge.questions,
                      columns: _vm.kn_columns,
                      options: _vm.options
                    },
                    scopedSlots: _vm._u([
                      {
                        key: "edit",
                        fn: function(props) {
                          return [
                            _c(
                              "div",
                              [
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn-sm",
                                    attrs: { variant: "outline-info icon-btn" },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.edit_question(props.row.id)
                                      }
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "ion ion-md-create"
                                    })
                                  ]
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn-sm",
                                    attrs: {
                                      variant: "outline-danger icon-btn"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.remove_question(props.row)
                                      }
                                    }
                                  },
                                  [_c("i", { staticClass: "ion ion-md-close" })]
                                )
                              ],
                              1
                            )
                          ]
                        }
                      }
                    ])
                  }),
                  _vm._v(" "),
                  _c("hr"),
                  _vm._v(" "),
                  _c("h5", [_vm._v("Статистика вопросов")]),
                  _vm._v(" "),
                  _c("table", { staticClass: "table table-bordered" }, [
                    _c("thead", [
                      _c("tr", [
                        _c("th", [_vm._v("Знание")]),
                        _vm._v(" "),
                        _c("th", [_vm._v("Вопросов")])
                      ])
                    ]),
                    _vm._v(" "),
                    _c(
                      "tbody",
                      _vm._l(_vm.knowledges, function(kn) {
                        return _c("tr", { key: kn.id }, [
                          _c("td", [_vm._v(_vm._s(kn.name))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(kn.questions.length))])
                        ])
                      }),
                      0
                    )
                  ])
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "b-tab",
                { attrs: { title: "Умения/Навыки" } },
                [
                  _c(
                    "b-dropdown",
                    {
                      staticClass: "m-md-2",
                      attrs: {
                        id: "dropdown-3",
                        text: "Добавить",
                        variant: "primary"
                      }
                    },
                    [
                      _c(
                        "b-dropdown-item",
                        {
                          on: {
                            click: function($event) {
                              return _vm.add_task(1)
                            }
                          }
                        },
                        [
                          _vm._v(
                            "Задание на применение умений и навыков в реальных или модельных условиях (Практическое задание)"
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-dropdown-item",
                        {
                          on: {
                            click: function($event) {
                              return _vm.add_task(2)
                            }
                          }
                        },
                        [_vm._v("Задание на оформление и защиту портфолио")]
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-dropdown",
                    {
                      staticClass: "m-md-2",
                      attrs: { id: "dropdown-4", text: "Экспорт" }
                    },
                    [
                      _c(
                        "b-dropdown-item",
                        {
                          attrs: {
                            href:
                              "/dpps/" +
                              this.$route.params.dpp +
                              "/export_om_questions/" +
                              this.stage.om_version_id
                          }
                        },
                        [_vm._v("Экспорт в Word заданий")]
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("hr"),
                  _vm._v(" "),
                  _c("v-client-table", {
                    attrs: {
                      data: _vm.tasks,
                      columns: _vm.task_columns,
                      options: _vm.task_options
                    },
                    scopedSlots: _vm._u([
                      {
                        key: "name",
                        fn: function(props) {
                          return [
                            _vm._v(
                              "\r\n                        " +
                                _vm._s(props.row.name) +
                                " (" +
                                _vm._s(props.row.type_name) +
                                ")\r\n                    "
                            )
                          ]
                        }
                      },
                      {
                        key: "edit",
                        fn: function(props) {
                          return [
                            _c(
                              "div",
                              [
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn-sm",
                                    attrs: { variant: "outline-info icon-btn" },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.edit_task(props.row.id)
                                      }
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "ion ion-md-create"
                                    })
                                  ]
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn-sm",
                                    attrs: {
                                      variant: "outline-danger icon-btn"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.remove_task(props.row)
                                      }
                                    }
                                  },
                                  [_c("i", { staticClass: "ion ion-md-close" })]
                                )
                              ],
                              1
                            )
                          ]
                        }
                      }
                    ])
                  }),
                  _vm._v(" "),
                  _vm.show_task_edit_window
                    ? _c("edit-task", {
                        key: "t" + _vm.task_to_edit,
                        attrs: {
                          zuns: _vm.zuns,
                          task_to_edit: _vm.task_to_edit
                        },
                        on: { update_task: _vm.update_task }
                      })
                    : _vm._e()
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkOM.vue":
/*!*****************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkOM.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DppStageWorkOM.vue?vue&type=template&id=2368eaff& */ "./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff&");
/* harmony import */ var _DppStageWorkOM_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DppStageWorkOM.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vendor_libs_vue_data_tables_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _DppStageWorkOM_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/DppStageWorkOM.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkOM_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkOM.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkOM_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff&":
/*!************************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkOM.vue?vue&type=template&id=2368eaff& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkOM.vue?vue&type=template&id=2368eaff&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkOM_vue_vue_type_template_id_2368eaff___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--6-3!./vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_sass_loader_dist_cjs_js_ref_6_3_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ })

}]);