(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "dpp_stage_work_structure",
  metaInfo: {
    title: "Разработка ДПП - Структура"
  },
  components: {},
  computed: {
    header: function header() {
      return "Разработка ДПП / " + this.stage.dpp_name + " / " + this.stage.type_name;
    }
  },
  data: function data() {
    return {
      stage: {},
      sections: [],
      new_theme: {
        name: "",
        lection_hours: 0,
        practice_hours: 0,
        self_hours: 0,
        lab_hours: 0,
        attestation_hours: 0,
        parent_id: '',
        knowledges: []
      },
      current_section: {
        themes: []
      },
      current_theme: {
        zuns: {
          knowledges: [],
          abilities: [],
          skills: []
        }
      },
      zuns: {}
    };
  },
  methods: {
    create_theme: function create_theme(knowledge, parent_id) {
      this.new_theme.parent_id = parent_id;

      if (knowledge !== null) {
        this.new_theme.name = knowledge.what.charAt(0).toUpperCase() + knowledge.what.slice(1);
        this.new_theme.knowledges.push(knowledge.id);
      }

      this.$bvModal.show("new_theme");
    },
    count_section_hours: function count_section_hours(section_id) {
      var lec_h = 0;
      var pr_h = 0;
      var self_h = 0;
      var section = this.sections.find(function (elem) {
        return elem.id == section_id;
      });
      var idx = this.sections.indexOf(section);

      for (var i = 0; i < section.themes.length; i++) {
        lec_h += parseInt(section.themes[i].lection_hours);
        pr_h += parseInt(section.themes[i].practice_hours);
        self_h += parseInt(section.themes[i].self_hours);
      }

      section.lection_hours = lec_h;
      section.practice_hours = lec_h;
      section.self_hours = lec_h;
      section.total_hours = section.lection_hours + section.practice_hours + section.self_hours;
      this.sections[idx] = section;
    },
    reset_new_theme: function reset_new_theme() {
      this.new_theme = {
        name: "",
        lection_hours: 0,
        practice_hours: 0,
        self_hours: 0,
        lab_hours: 0,
        attestation_hours: 0,
        parent_id: '',
        knowledges: []
      };
    },
    add_theme: function add_theme() {
      var _this = this;

      self = this;
      axios.post('/dpps/' + this.$route.params.dpp + '/structure/' + this.stage.st_version_id + '/add_theme', {
        'theme_data': this.new_theme
      }).then(function (response) {
        var parent = _this.sections.find(function (elem) {
          return elem.id == _this.new_theme.parent_id;
        });

        parent = _this.sections.indexOf(parent);
        _this.sections[parent] = response.data; //this.count_section_hours(this.new_theme.parent_id)
      })["finally"](function (response) {
        _this.reset_new_theme();
      });
    },
    delete_theme: function delete_theme(id, parent_id) {
      var _this2 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/structure/' + this.stage.st_version_id + '/delete_theme', {
        'id': id
      }).then(function (response) {
        parent = _this2.sections.find(function (elem) {
          return elem.id == parent_id;
        });
        parent = _this2.sections.indexOf(parent);
        _this2.sections[parent] = response.data;
      })["finally"](function (response) {
        _this2.reset_new_theme();
      });
    },
    edit_theme: function edit_theme(theme) {
      this.current_theme = theme;
      this.current_theme.zuns = {
        knowledges: [],
        abilities: [],
        skills: []
      };
      this.$bvModal.show("edit_theme");
    },
    update_theme: function update_theme() {},
    edit_section: function edit_section(section) {
      this.current_section = section;
      this.$bvModal.show("edit_section");
    },
    delete_section: function delete_section(id) {
      var _this3 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/structure/' + this.stage.st_version_id + '/delete_section', {
        'id': id
      }).then(function (response) {
        _this3.sections = _this3.sections.filter(function (elem) {
          return elem.id !== id;
        });
      });
    },
    update_section: function update_section() {},
    move_up: function move_up(id, parent_id) {
      var _this4 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/structure/' + this.stage.st_version_id + '/move_up', {
        'id': id,
        'parent_id': parent_id
      }).then(function (response) {
        if (parent_id == null) {
          var section = _this4.sections.find(function (elem) {
            return elem.id == id;
          });

          var idx = _this4.sections.indexOf(section);

          _this4.sections[idx].position -= 1;
          _this4.sections[idx - 1].position += 1;

          _this4.sections.sort(function (a, b) {
            return parseInt(a.position) - parseInt(b.position);
          });
        } else {
          parent = _this4.sections.find(function (elem) {
            return elem.id == parent_id;
          });
          parent = _this4.sections.indexOf(parent);
          _this4.sections[parent] = response.data;

          _this4.reset_new_theme();
        }
      });
    },
    move_down: function move_down(id, parent_id) {
      var _this5 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/structure/' + this.stage.st_version_id + '/move_down', {
        'id': id,
        'parent_id': parent_id
      }).then(function (response) {
        if (parent_id == null) {
          var section = _this5.sections.find(function (elem) {
            return elem.id == id;
          });

          var idx = _this5.sections.indexOf(section);

          _this5.sections[idx].position += 1;
          _this5.sections[idx + 1].position -= 1;

          _this5.sections.sort(function (a, b) {
            return parseInt(a.position) - parseInt(b.position);
          });
        } else {
          parent = _this5.sections.find(function (elem) {
            return elem.id == parent_id;
          });
          parent = _this5.sections.indexOf(parent);
          _this5.sections[parent] = response.data;

          _this5.reset_new_theme();
        }
      });
    }
  },
  mounted: function mounted() {
    var _this6 = this;

    var self = this;
    axios.get('/dpps/' + this.$route.params.dpp + '/get_stage_data/' + this.$route.params.stage).then(function (response) {
      return _this6.stage = response.data;
    })["finally"](function (response) {
      axios.get('/dpps/' + self.$route.params.dpp + '/structure/' + self.stage.st_version_id + '/get_sections').then(function (response) {
        return self.sections = response.data;
      });
      axios.get('/dpps/' + self.$route.params.dpp + '/structure/' + self.stage.st_version_id + '/get_zuns').then(function (response) {
        return self.zuns = response.data;
      });
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
      _c("b-card", { attrs: { title: _vm.header } }),
      _vm._v(" "),
      _c(
        "b-card",
        { attrs: { "no-body": "" } },
        [
          _c(
            "b-tabs",
            { attrs: { card: "" } },
            [
              _c(
                "b-tab",
                { attrs: { title: "Управление", active: "" } },
                [
                  _c(
                    "div",
                    _vm._l(_vm.sections, function(section) {
                      return _c(
                        "b-card",
                        { key: "s_" + section.id, staticClass: "mt-2 mb-1" },
                        [
                          _c(
                            "b-card-header",
                            {
                              staticClass: "p-1",
                              attrs: { "header-tag": "header", role: "tab" }
                            },
                            [
                              _c(
                                "b-row",
                                [
                                  _c("b-col", { attrs: { cols: "9" } }, [
                                    _c("h4", [
                                      _vm._v(
                                        _vm._s(section.position) +
                                          ". " +
                                          _vm._s(section.name)
                                      )
                                    ])
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "b-col",
                                    [
                                      _c(
                                        "b-button-group",
                                        { staticClass: "ml-auto" },
                                        [
                                          section.position != 1
                                            ? _c(
                                                "b-btn",
                                                {
                                                  staticClass: "btn",
                                                  attrs: {
                                                    variant: "info icon-btn"
                                                  },
                                                  on: {
                                                    click: function($event) {
                                                      $event.preventDefault()
                                                      return _vm.move_up(
                                                        section.id,
                                                        null
                                                      )
                                                    }
                                                  }
                                                },
                                                [
                                                  _c("i", {
                                                    staticClass:
                                                      "ion ion-md-arrow-round-up"
                                                  })
                                                ]
                                              )
                                            : _vm._e(),
                                          _vm._v(" "),
                                          section.position !=
                                          _vm.sections.length
                                            ? _c(
                                                "b-btn",
                                                {
                                                  staticClass: "btn",
                                                  attrs: {
                                                    variant: "info icon-btn"
                                                  },
                                                  on: {
                                                    click: function($event) {
                                                      $event.preventDefault()
                                                      return _vm.move_down(
                                                        section.id,
                                                        null
                                                      )
                                                    }
                                                  }
                                                },
                                                [
                                                  _c("i", {
                                                    staticClass:
                                                      "ion ion-md-arrow-round-down"
                                                  })
                                                ]
                                              )
                                            : _vm._e(),
                                          _vm._v(" "),
                                          _c(
                                            "b-btn",
                                            {
                                              staticClass: "btn",
                                              attrs: {
                                                variant: "primary icon-btn"
                                              },
                                              on: {
                                                click: function($event) {
                                                  $event.preventDefault()
                                                  return _vm.edit_section(
                                                    section
                                                  )
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
                                              staticClass: "btn",
                                              attrs: {
                                                variant: "danger icon-btn"
                                              },
                                              on: {
                                                click: function($event) {
                                                  $event.preventDefault()
                                                  return _vm.delete_section(
                                                    section.id
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c("i", {
                                                staticClass: "ion ion-md-close"
                                              })
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "b-btn",
                                            {
                                              staticClass: "btn",
                                              attrs: {
                                                variant: "info icon-btn"
                                              },
                                              on: {
                                                click: function($event) {
                                                  $event.preventDefault()
                                                  return _vm.create_theme(
                                                    null,
                                                    section.id
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c("i", {
                                                staticClass: "ion ion-md-add"
                                              })
                                            ]
                                          )
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-row",
                                [
                                  _c("b-col", [
                                    _c("h5", [
                                      _vm._v(
                                        "Всего часов: " +
                                          _vm._s(section.total_hours) +
                                          " (Лекции: " +
                                          _vm._s(section.lection_hours) +
                                          ", Практики: " +
                                          _vm._s(section.practice_hours) +
                                          ", Лабораторные: " +
                                          _vm._s(section.lab_hours) +
                                          ", Самостоятельная работа: " +
                                          _vm._s(section.self_hours) +
                                          ", Аттестация: " +
                                          _vm._s(section.attestation_hours) +
                                          ")"
                                      )
                                    ])
                                  ])
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c("hr")
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "mt-2" },
                            [
                              _c(
                                "b-button",
                                {
                                  directives: [
                                    {
                                      name: "b-toggle",
                                      rawName: "v-b-toggle",
                                      value: "ks_" + section.id,
                                      expression: "'ks_'+section.id"
                                    }
                                  ],
                                  attrs: { variant: "warning" }
                                },
                                [
                                  _vm._v(
                                    "Знания (" +
                                      _vm._s(section.knowledges.length) +
                                      ")"
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-collapse",
                                {
                                  staticClass: "mt-2",
                                  attrs: { id: "ks_" + section.id }
                                },
                                [
                                  _c(
                                    "b-list-group",
                                    _vm._l(section.knowledges, function(
                                      knowledge
                                    ) {
                                      return _c(
                                        "b-list-group-item",
                                        { key: "k_" + knowledge.id },
                                        [
                                          _vm._v(
                                            _vm._s(knowledge.name) +
                                              "\r\n                      "
                                          ),
                                          _c(
                                            "span",
                                            { staticClass: "float-right" },
                                            [
                                              _c(
                                                "b-button",
                                                {
                                                  attrs: {
                                                    size: "sm",
                                                    variant: "outline-primary"
                                                  },
                                                  on: {
                                                    click: function($event) {
                                                      return _vm.create_theme(
                                                        knowledge,
                                                        section.id
                                                      )
                                                    }
                                                  }
                                                },
                                                [_vm._v("+")]
                                              )
                                            ],
                                            1
                                          )
                                        ]
                                      )
                                    }),
                                    1
                                  )
                                ],
                                1
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "mt-2" },
                            [
                              _c(
                                "b-button",
                                {
                                  directives: [
                                    {
                                      name: "b-toggle",
                                      rawName: "v-b-toggle",
                                      value: "st_" + section.id,
                                      expression: "'st_'+section.id"
                                    }
                                  ],
                                  attrs: { variant: "info" }
                                },
                                [
                                  _vm._v(
                                    "Темы (" +
                                      _vm._s(section.themes.length) +
                                      ")"
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-collapse",
                                {
                                  staticClass: "mt-2",
                                  attrs: { id: "st_" + section.id }
                                },
                                [
                                  _c(
                                    "b-list-group",
                                    _vm._l(section.themes, function(theme) {
                                      return _c(
                                        "b-list-group-item",
                                        { key: "t_" + theme.id },
                                        [
                                          _c("strong", [
                                            _vm._v(
                                              _vm._s(section.position) +
                                                "." +
                                                _vm._s(theme.position) +
                                                " " +
                                                _vm._s(theme.name)
                                            )
                                          ]),
                                          _vm._v(
                                            " Часов: " +
                                              _vm._s(theme.total_hours) +
                                              " (лек: " +
                                              _vm._s(theme.lection_hours) +
                                              ",пр: " +
                                              _vm._s(theme.practice_hours) +
                                              ", лаб. " +
                                              _vm._s(theme.lab_hours) +
                                              ", сам. " +
                                              _vm._s(theme.self_hours) +
                                              ", атт. " +
                                              _vm._s(theme.attestation_hours) +
                                              ")\r\n                      "
                                          ),
                                          _c(
                                            "b-button-group",
                                            { staticClass: "float-right" },
                                            [
                                              theme.position != 1
                                                ? _c(
                                                    "b-btn",
                                                    {
                                                      staticClass: "btn",
                                                      attrs: {
                                                        size: "sm",
                                                        variant:
                                                          "outline-info icon-btn"
                                                      },
                                                      on: {
                                                        click: function(
                                                          $event
                                                        ) {
                                                          $event.preventDefault()
                                                          return _vm.move_up(
                                                            theme.id,
                                                            section.id
                                                          )
                                                        }
                                                      }
                                                    },
                                                    [
                                                      _c("i", {
                                                        staticClass:
                                                          "ion ion-md-arrow-round-up"
                                                      })
                                                    ]
                                                  )
                                                : _vm._e(),
                                              _vm._v(" "),
                                              theme.position != section.length
                                                ? _c(
                                                    "b-btn",
                                                    {
                                                      staticClass: "btn",
                                                      attrs: {
                                                        size: "sm",
                                                        variant:
                                                          "outline-info icon-btn"
                                                      },
                                                      on: {
                                                        click: function(
                                                          $event
                                                        ) {
                                                          $event.preventDefault()
                                                          return _vm.move_down(
                                                            theme.id,
                                                            section.id
                                                          )
                                                        }
                                                      }
                                                    },
                                                    [
                                                      _c("i", {
                                                        staticClass:
                                                          "ion ion-md-arrow-round-down"
                                                      })
                                                    ]
                                                  )
                                                : _vm._e(),
                                              _vm._v(" "),
                                              _c(
                                                "b-btn",
                                                {
                                                  staticClass: "btn",
                                                  attrs: {
                                                    size: "sm",
                                                    variant:
                                                      "outline-primary icon-btn"
                                                  },
                                                  on: {
                                                    click: function($event) {
                                                      $event.preventDefault()
                                                      return _vm.edit_theme(
                                                        theme
                                                      )
                                                    }
                                                  }
                                                },
                                                [
                                                  _c("i", {
                                                    staticClass:
                                                      "ion ion-md-create"
                                                  })
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "b-btn",
                                                {
                                                  staticClass: "btn",
                                                  attrs: {
                                                    size: "sm",
                                                    variant:
                                                      "outline-danger icon-btn"
                                                  },
                                                  on: {
                                                    click: function($event) {
                                                      $event.preventDefault()
                                                      return _vm.delete_theme(
                                                        theme.id,
                                                        section.id
                                                      )
                                                    }
                                                  }
                                                },
                                                [
                                                  _c("i", {
                                                    staticClass:
                                                      "ion ion-md-close"
                                                  })
                                                ]
                                              )
                                            ],
                                            1
                                          )
                                        ],
                                        1
                                      )
                                    }),
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
                    }),
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-modal",
                    {
                      attrs: {
                        id: "new_theme",
                        "ok-title": "Добавить тему",
                        size: "xl",
                        "no-close-on-esc": "",
                        "no-close-on-backdrop": "",
                        "cancel-title": "Закрыть",
                        title: "Добавить тему"
                      },
                      on: { ok: _vm.add_theme }
                    },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: { "label-size": "lg", label: "Название темы" }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_theme, "name", $$v)
                              },
                              expression: "new_theme.name"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов лекций"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.lection_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.new_theme, "lection_hours", $$v)
                              },
                              expression: "new_theme.lection_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов практик"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.practice_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.new_theme, "practice_hours", $$v)
                              },
                              expression: "new_theme.practice_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов лабораторных работ"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.lab_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.new_theme, "lab_hours", $$v)
                              },
                              expression: "new_theme.lab_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов самостоятельной работы"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.self_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.new_theme, "self_hours", $$v)
                              },
                              expression: "new_theme.self_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов итоговой аттестации"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.new_theme.attestation_hours,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.new_theme,
                                  "attestation_hours",
                                  $$v
                                )
                              },
                              expression: "new_theme.attestation_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("h5", [
                        _vm._v(
                          "Всего часов на тему: " +
                            _vm._s(
                              parseInt(_vm.new_theme.lection_hours) +
                                parseInt(_vm.new_theme.practice_hours) +
                                parseInt(_vm.new_theme.self_hours) +
                                parseInt(_vm.new_theme.lab_hours) +
                                parseInt(_vm.new_theme.attestation_hours)
                            )
                        )
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-modal",
                    {
                      attrs: {
                        id: "edit_section",
                        "ok-title": "Обновить",
                        size: "xl",
                        "no-close-on-esc": "",
                        "no-close-on-backdrop": "",
                        "cancel-title": "Закрыть",
                        title: "Редактирование раздела"
                      },
                      on: { ok: _vm.update_section }
                    },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Название раздела"
                          }
                        },
                        [
                          _c("b-form-input", {
                            model: {
                              value: _vm.current_section.name,
                              callback: function($$v) {
                                _vm.$set(_vm.current_section, "name", $$v)
                              },
                              expression: "current_section.name"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm.current_section.themes.length > 0
                        ? _c("b-alert", { attrs: { show: "" } }, [
                            _vm._v(
                              "Данный раздел содержит темы, поэтому часы для него рассчитываются автоматически"
                            )
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов лекций"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              disabled: _vm.current_section.themes.length > 0
                            },
                            model: {
                              value: _vm.current_section.lection_hours,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.current_section,
                                  "lection_hours",
                                  $$v
                                )
                              },
                              expression: "current_section.lection_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов практик"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              disabled: _vm.current_section.themes.length > 0
                            },
                            model: {
                              value: _vm.current_section.practice_hours,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.current_section,
                                  "practice_hours",
                                  $$v
                                )
                              },
                              expression: "current_section.practice_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов лабораторных"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              disabled: _vm.current_section.themes.length > 0
                            },
                            model: {
                              value: _vm.current_section.lab_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.current_section, "lab_hours", $$v)
                              },
                              expression: "current_section.lab_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов самостоятельной работы"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              disabled: _vm.current_section.themes.length > 0
                            },
                            model: {
                              value: _vm.current_section.self_hours,
                              callback: function($$v) {
                                _vm.$set(_vm.current_section, "self_hours", $$v)
                              },
                              expression: "current_section.self_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            label: "Количество часов аттестации"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              disabled: _vm.current_section.themes.length > 0
                            },
                            model: {
                              value: _vm.current_section.attestation_hours,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.current_section,
                                  "attestation_hours",
                                  $$v
                                )
                              },
                              expression: "current_section.attestation_hours"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("h5", [
                        _vm._v(
                          "Всего часов в разделе: " +
                            _vm._s(
                              parseInt(_vm.current_section.lection_hours) +
                                parseInt(_vm.current_section.practice_hours) +
                                parseInt(_vm.current_section.self_hours) +
                                parseInt(_vm.current_theme.lab_hours) +
                                parseInt(_vm.current_theme.attestation_hours)
                            )
                        )
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-modal",
                    {
                      attrs: {
                        id: "edit_theme",
                        "ok-title": "Обновить",
                        size: "xl",
                        "no-close-on-esc": "",
                        "no-close-on-backdrop": "",
                        "cancel-title": "Закрыть",
                        title: "Редактирование темы"
                      },
                      on: { ok: _vm.update_theme }
                    },
                    [
                      _c(
                        "b-tabs",
                        { attrs: { card: "" } },
                        [
                          _c(
                            "b-tab",
                            {
                              attrs: { title: "Основные параметры", active: "" }
                            },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label: "Название раздела"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value: _vm.current_theme.name,
                                      callback: function($$v) {
                                        _vm.$set(_vm.current_theme, "name", $$v)
                                      },
                                      expression: "current_theme.name"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label: "Количество часов лекций"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value: _vm.current_theme.lection_hours,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme,
                                          "lection_hours",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.lection_hours"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label: "Количество часов практик"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value: _vm.current_theme.practice_hours,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme,
                                          "practice_hours",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.practice_hours"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label: "Количество часов лабораторных"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value: _vm.current_theme.lab_hours,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme,
                                          "lab_hours",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.lab_hours"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label:
                                      "Количество часов самостоятельной работы"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value: _vm.current_theme.self_hours,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme,
                                          "self_hours",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.self_hours"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    "label-size": "lg",
                                    label: "Количество часов аттестации"
                                  }
                                },
                                [
                                  _c("b-form-input", {
                                    model: {
                                      value:
                                        _vm.current_theme.attestation_hours,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme,
                                          "attestation_hours",
                                          $$v
                                        )
                                      },
                                      expression:
                                        "current_theme.attestation_hours"
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c("h5", [
                                _vm._v(
                                  "Всего часов в теме: " +
                                    _vm._s(
                                      parseInt(
                                        _vm.current_theme.lection_hours
                                      ) +
                                        parseInt(
                                          _vm.current_theme.practice_hours
                                        ) +
                                        parseInt(_vm.current_theme.lab_hours) +
                                        parseInt(_vm.current_theme.self_hours) +
                                        parseInt(
                                          _vm.current_theme.attestation_hours
                                        )
                                    )
                                )
                              ])
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "b-tab",
                            { attrs: { title: "ЗУНы" } },
                            [
                              _c("h5", [_vm._v("Знания:")]),
                              _vm._v(" "),
                              _vm.current_theme.lection_hours > 0
                                ? _c("b-form-checkbox-group", {
                                    staticClass: "mb-3",
                                    attrs: {
                                      stacked: "",
                                      options: _vm.zuns.knowledges,
                                      "value-field": "id",
                                      "text-field": "name",
                                      "disabled-field": "notEnabled"
                                    },
                                    model: {
                                      value: _vm.current_theme.zuns.knowledges,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme.zuns,
                                          "knowledges",
                                          $$v
                                        )
                                      },
                                      expression:
                                        "current_theme.zuns.knowledges"
                                    }
                                  })
                                : _c("b-alert", { attrs: { show: "" } }, [
                                    _vm._v(
                                      "Тема не содержит часы на лекции, поэтому Вы не можете прикрепить к ней знания"
                                    )
                                  ]),
                              _vm._v(" "),
                              _c("h5", [_vm._v("Умения:")]),
                              _vm._v(" "),
                              _vm.current_theme.lab_hours > 0 ||
                              _vm.current_theme.practice_hours > 0
                                ? _c("b-form-checkbox-group", {
                                    staticClass: "mb-3",
                                    attrs: {
                                      stacked: "",
                                      options: _vm.zuns.abilities,
                                      "value-field": "id",
                                      "text-field": "name",
                                      "disabled-field": "notEnabled"
                                    },
                                    model: {
                                      value: _vm.current_theme.zuns.abilities,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme.zuns,
                                          "abilities",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.zuns.abilities"
                                    }
                                  })
                                : _c("b-alert", { attrs: { show: "" } }, [
                                    _vm._v(
                                      "Тема не содержит часы на практики/лабораторные, поэтому Вы не можете прикрепить к ней умения"
                                    )
                                  ]),
                              _vm._v(" "),
                              _c("h5", [_vm._v("Навыки:")]),
                              _vm._v(" "),
                              _vm.current_theme.lab_hours > 0 ||
                              _vm.current_theme.practice_hours > 0
                                ? _c("b-form-checkbox-group", {
                                    staticClass: "mb-3",
                                    attrs: {
                                      stacked: "",
                                      options: _vm.zuns.skills,
                                      "value-field": "id",
                                      "text-field": "name",
                                      "disabled-field": "notEnabled"
                                    },
                                    model: {
                                      value: _vm.current_theme.zuns.skills,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.current_theme.zuns,
                                          "skills",
                                          $$v
                                        )
                                      },
                                      expression: "current_theme.zuns.skills"
                                    }
                                  })
                                : _c("b-alert", { attrs: { show: "" } }, [
                                    _vm._v(
                                      "Тема не содержит часы на практики/лабораторные, поэтому Вы не можете прикрепить к ней навыки"
                                    )
                                  ])
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
              ),
              _vm._v(" "),
              _c("b-tab", { attrs: { title: "Учебный план" } }, [
                _c("h5", [_vm._v("Учебный план")]),
                _vm._v(" "),
                _c("table", { staticClass: "table table-bordered" }, [
                  _c(
                    "thead",
                    [
                      _c("tr", [
                        _c("th", { attrs: { rowspan: "2" } }, [
                          _vm._v("Наименование разделов")
                        ]),
                        _c("th", { attrs: { colspan: "6" } }, [
                          _vm._v("Трудоемкость, ч")
                        ]),
                        _c("th", { attrs: { rowspan: "2" } }, [
                          _vm._v("Планируемые результаты обучения")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("tr", [
                        _c("th", [_vm._v("Итого")]),
                        _c("th", [_vm._v("Лек.")]),
                        _c("th", [_vm._v("Пр.")]),
                        _c("th", [_vm._v("Лаб.")]),
                        _c("th", [_vm._v("Сам. раб.")]),
                        _c("th", [_vm._v("Атт.")])
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.sections, function(section) {
                        return _c("tr", { key: "ps_" + section.id }, [
                          _c("td", [
                            _c("strong", [
                              _vm._v(
                                _vm._s(section.position) +
                                  ". " +
                                  _vm._s(section.name)
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.total_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.lection_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.practice_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.lab_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.self_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(section.attestation_hours))]),
                          _vm._v(" "),
                          _c("td", [_vm._v("...")])
                        ])
                      })
                    ],
                    2
                  )
                ])
              ]),
              _vm._v(" "),
              _c("b-tab", { attrs: { title: "ЗУН" } }, [
                _c("h5", [_vm._v("Сопоставление ЗУН и Разделов/Тем")]),
                _vm._v(" "),
                _c("h5", [_vm._v("Навыки")]),
                _vm._v(" "),
                _c("table", { staticClass: "table table-bordered" }, [
                  _c("thead", [
                    _c("tr", [
                      _c("th", [_vm._v("Навык")]),
                      _c("th", [_vm._v("Раздел/Тема")])
                    ])
                  ]),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    _vm._l(_vm.zuns.skills, function(skill) {
                      return _c("tr", { key: "s_" + skill.id }, [
                        _c("td", { attrs: { width: "50%" } }, [
                          _vm._v(_vm._s(skill.name))
                        ]),
                        _vm._v(" "),
                        _c(
                          "td",
                          _vm._l(skill.sections, function(section) {
                            return _c("div", { key: "ss_" + section.id }, [
                              _vm._v(_vm._s(section.name))
                            ])
                          }),
                          0
                        )
                      ])
                    }),
                    0
                  )
                ]),
                _vm._v(" "),
                _c("h5", [_vm._v("Умения")]),
                _vm._v(" "),
                _c("table", { staticClass: "table table-bordered" }, [
                  _c("thead", [
                    _c("tr", [
                      _c("th", [_vm._v("Умение")]),
                      _c("th", [_vm._v("Раздел/Тема")])
                    ])
                  ]),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    _vm._l(_vm.zuns.abilities, function(ability) {
                      return _c("tr", { key: "a_" + ability.id }, [
                        _c("td", { attrs: { width: "50%" } }, [
                          _vm._v(_vm._s(ability.name))
                        ]),
                        _vm._v(" "),
                        _c(
                          "td",
                          _vm._l(ability.sections, function(section) {
                            return _c("div", { key: "as_" + section.id }, [
                              _vm._v(_vm._s(section.name))
                            ])
                          }),
                          0
                        )
                      ])
                    }),
                    0
                  )
                ]),
                _vm._v(" "),
                _c("h5", [_vm._v("Знания")]),
                _vm._v(" "),
                _c("table", { staticClass: "table table-bordered" }, [
                  _c("thead", [
                    _c("tr", [
                      _c("th", [_vm._v("Знание")]),
                      _c("th", [_vm._v("Раздел/Тема")])
                    ])
                  ]),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    _vm._l(_vm.zuns.knowledges, function(knowledge) {
                      return _c("tr", { key: "k_" + knowledge.id }, [
                        _c("td", { attrs: { width: "50%" } }, [
                          _vm._v(_vm._s(knowledge.name))
                        ]),
                        _vm._v(" "),
                        _c(
                          "td",
                          _vm._l(knowledge.sections, function(section) {
                            return _c("div", { key: "ks_" + section.id }, [
                              _vm._v(_vm._s(section.name))
                            ])
                          }),
                          0
                        )
                      ])
                    }),
                    0
                  )
                ])
              ])
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

/***/ "./resources/assets/src/components/dpps/DppStageWorkStructure.vue":
/*!************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkStructure.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DppStageWorkStructure.vue?vue&type=template&id=214334c2& */ "./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2&");
/* harmony import */ var _DppStageWorkStructure_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DppStageWorkStructure.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DppStageWorkStructure_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/DppStageWorkStructure.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkStructure_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkStructure.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkStructure_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2&":
/*!*******************************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkStructure.vue?vue&type=template&id=214334c2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=template&id=214334c2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkStructure_vue_vue_type_template_id_214334c2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);