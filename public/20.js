(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'dpps',
  metaInfo: {
    title: 'Управление ДПП'
  },
  data: function data() {
    return {
      // Note 'isActive' is left out and will not appear in the rendered table
      fields: [{
        key: 'name',
        label: 'Наименование (тематика)',
        sortable: true
      }, {
        key: 'type_name',
        label: 'Тип',
        sortable: true
      }, {
        key: 'participants',
        label: 'Участников',
        sortable: true
      }, {
        key: 'zuns',
        label: 'ЗУНов',
        sortable: true
      }, {
        key: 'modify',
        label: 'Управление',
        sortable: false
      }],
      items: [{}],
      info: "",
      isBusy: true,
      new_dpp: {
        name: '',
        nameState: null,
        type: '',
        typeState: null,
        actors: []
      },
      dpp_types: []
    };
  },
  computed: {
    active_items: function active_items() {
      return this.items.filter(function (el) {
        return el.is_archieved == 0;
      });
    },
    archieved_items: function archieved_items() {
      return this.items.filter(function (el) {
        return el.is_archieved == 1;
      });
    }
  },
  methods: {
    checkFormValidity: function checkFormValidity() {
      var valid = this.$refs.form.checkValidity();
      this.new_dpp.nameState = valid;
      this.new_dpp.typeState = valid;
      return valid;
    },
    resetModal: function resetModal() {
      this.new_dpp.name = '';
      this.new_dpp.nameState = null;
      this.new_dpp.type = '';
      this.new_dpp.typeState = null;
    },
    handleOk: function handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault(); // Trigger submit handler

      this.handleSubmit();
    },
    handleSubmit: function handleSubmit() {
      var _this = this;

      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      } // store_dpp


      this.store_dpp(); // Hide the modal manually

      this.$nextTick(function () {
        _this.$bvModal.hide('create_dpp');
      });
    },
    store_dpp: function store_dpp() {
      var _this2 = this;

      axios.post('/store_dpp', {
        'name': this.new_dpp.name,
        'type': this.new_dpp.type
      }).then(function (response) {
        return _this2.items.push(response.data);
      })["finally"](function () {
        return _this2.resetModal();
      });
    },
    delete_dpp: function delete_dpp(item, id) {
      var _this3 = this;

      this.$bvModal.msgBoxConfirm('Действительно хотите удалить программу?').then(function (value) {
        if (value === true) {
          axios.post('/dpps/delete', {
            'id': id
          }).then(function () {
            return _this3.items.splice(_this3.items.indexOf(item), 1);
          });
        }
      })["catch"](function (err) {// An error occurred
      });
    },
    archive_dpp: function archive_dpp(item, id) {
      var _this4 = this;

      this.isBusy = true;
      axios.post('/dpps/archive_dpp', {
        'id': id
      }).then(function (response) {
        // this.items.push(response.data)
        var idx = _this4.items.indexOf(item);

        _this4.$set(_this4.items[idx], 'is_archieved', 1);
      })["finally"](function (response) {
        return _this4.isBusy = false;
      });
    },
    unarchive_dpp: function unarchive_dpp(item, id) {
      var _this5 = this;

      this.isBusy = true;
      axios.post('/dpps/unarchive_dpp', {
        'id': id
      }).then(function (response) {
        var idx = _this5.items.indexOf(item);

        _this5.$set(_this5.items[idx], 'is_archieved', 0);
      })["finally"](function (response) {
        return _this5.isBusy = false;
      });
    }
  },
  mounted: function mounted() {
    var _this6 = this;

    axios.get('/dpps').then(function (response) {
      return _this6.items = response.data;
    })["finally"](function () {
      return _this6.isBusy = false;
    });
    axios.get('/dpp_types').then(function (response) {
      return _this6.dpp_types = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076& ***!
  \********************************************************************************************************************************************************************************************************************/
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
        "b-card",
        { attrs: { title: "Управление ДПП" } },
        [
          _c("b-card-text", [
            _vm._v(
              "\n        Ниже отображается таблица всех созданных ДПП\n        "
            )
          ]),
          _vm._v(" "),
          _c(
            "b-button",
            {
              directives: [
                {
                  name: "b-modal",
                  rawName: "v-b-modal.create_dpp",
                  modifiers: { create_dpp: true }
                }
              ],
              attrs: { variant: "primary" }
            },
            [_vm._v("Создать ДПП")]
          ),
          _vm._v(" "),
          _c("hr"),
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
                    { attrs: { title: "Активные программы", active: "" } },
                    [
                      _c("b-table", {
                        attrs: {
                          busy: _vm.isBusy,
                          bordered: "",
                          hover: "",
                          "table-variant": "light",
                          "head-variant": "light",
                          items: _vm.active_items,
                          fields: _vm.fields
                        },
                        scopedSlots: _vm._u([
                          {
                            key: "table-busy",
                            fn: function() {
                              return [
                                _c(
                                  "div",
                                  { staticClass: "text-center text-info my-2" },
                                  [
                                    _c("b-spinner", {
                                      staticClass: "align-middle"
                                    }),
                                    _vm._v(" "),
                                    _c("strong", [_vm._v("Загрузка...")])
                                  ],
                                  1
                                )
                              ]
                            },
                            proxy: true
                          },
                          {
                            key: "cell(participants)",
                            fn: function(data) {
                              return [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(data.item.participants.length) +
                                    "\n                "
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(modify)",
                            fn: function(data) {
                              return [
                                !_vm.isBusy
                                  ? _c(
                                      "router-link",
                                      {
                                        attrs: {
                                          icon: "ion ion-md-eye",
                                          to: {
                                            name: "dpp_inspect",
                                            params: { dpp: data.item.id }
                                          },
                                          exact: true
                                        }
                                      },
                                      [
                                        _c(
                                          "b-button",
                                          {
                                            attrs: { variant: "outline-info" }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-eye",
                                              staticStyle: {
                                                "font-size": "20px"
                                              }
                                            })
                                          ]
                                        ),
                                        _vm._v(" "),
                                        !_vm.isBusy
                                          ? _c(
                                              "router-link",
                                              {
                                                attrs: {
                                                  icon: "ion ion-md-person",
                                                  to: {
                                                    name: "dpp_overview",
                                                    params: {
                                                      dpp: data.item.id,
                                                      role: 1
                                                    }
                                                  },
                                                  exact: true
                                                }
                                              },
                                              [
                                                _c(
                                                  "b-button",
                                                  {
                                                    attrs: {
                                                      variant: "outline-primary"
                                                    }
                                                  },
                                                  [
                                                    _c("i", {
                                                      staticClass:
                                                        "ion ion-md-create",
                                                      staticStyle: {
                                                        "font-size": "20px"
                                                      }
                                                    })
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          : _vm._e()
                                      ],
                                      1
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                !_vm.isBusy
                                  ? _c(
                                      "router-link",
                                      {
                                        attrs: {
                                          icon: "ion ion-md-person",
                                          to: {
                                            name: "dpp_config",
                                            params: { dpp: data.item.id }
                                          },
                                          exact: true
                                        }
                                      },
                                      [
                                        _c(
                                          "b-button",
                                          {
                                            attrs: {
                                              variant: "outline-primary"
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-construct",
                                              staticStyle: {
                                                "font-size": "20px"
                                              }
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _c(
                                  "b-button",
                                  {
                                    attrs: { variant: "outline-primary" },
                                    on: {
                                      click: function($event) {
                                        return _vm.archive_dpp(
                                          data.item,
                                          data.item.id
                                        )
                                      }
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "ion ion-md-archive",
                                      staticStyle: { "font-size": "20px" }
                                    })
                                  ]
                                )
                              ]
                            }
                          }
                        ])
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tab",
                    { attrs: { title: "Архив" } },
                    [
                      _c("b-table", {
                        attrs: {
                          busy: _vm.isBusy,
                          bordered: "",
                          hover: "",
                          "table-variant": "light",
                          "head-variant": "light",
                          items: _vm.archieved_items,
                          fields: _vm.fields
                        },
                        scopedSlots: _vm._u([
                          {
                            key: "table-busy",
                            fn: function() {
                              return [
                                _c(
                                  "div",
                                  { staticClass: "text-center text-info my-2" },
                                  [
                                    _c("b-spinner", {
                                      staticClass: "align-middle"
                                    }),
                                    _vm._v(" "),
                                    _c("strong", [_vm._v("Загрузка...")])
                                  ],
                                  1
                                )
                              ]
                            },
                            proxy: true
                          },
                          {
                            key: "cell(participants)",
                            fn: function(data) {
                              return [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(data.item.participants.length) +
                                    "\n                "
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(modify)",
                            fn: function(data) {
                              return [
                                !_vm.isBusy
                                  ? _c(
                                      "router-link",
                                      {
                                        attrs: {
                                          icon: "ion ion-md-eye",
                                          to: {
                                            name: "dpp_inspect",
                                            params: { dpp: data.item.id }
                                          },
                                          exact: true
                                        }
                                      },
                                      [
                                        _c(
                                          "b-button",
                                          {
                                            attrs: { variant: "outline-info" }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-eye",
                                              staticStyle: {
                                                "font-size": "20px"
                                              }
                                            })
                                          ]
                                        ),
                                        _vm._v(" "),
                                        !_vm.isBusy
                                          ? _c(
                                              "router-link",
                                              {
                                                attrs: {
                                                  icon: "ion ion-md-person",
                                                  to: {
                                                    name: "dpp_overview",
                                                    params: {
                                                      dpp: data.item.id,
                                                      role: 1
                                                    }
                                                  },
                                                  exact: true
                                                }
                                              },
                                              [
                                                _c(
                                                  "b-button",
                                                  {
                                                    attrs: {
                                                      variant: "outline-primary"
                                                    }
                                                  },
                                                  [
                                                    _c("i", {
                                                      staticClass:
                                                        "ion ion-md-create",
                                                      staticStyle: {
                                                        "font-size": "20px"
                                                      }
                                                    })
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          : _vm._e()
                                      ],
                                      1
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                !_vm.isBusy
                                  ? _c(
                                      "router-link",
                                      {
                                        attrs: {
                                          icon: "ion ion-md-person",
                                          to: {
                                            name: "dpp_config",
                                            params: { dpp: data.item.id }
                                          },
                                          exact: true
                                        }
                                      },
                                      [
                                        _c(
                                          "b-button",
                                          {
                                            attrs: {
                                              variant: "outline-primary"
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-construct",
                                              staticStyle: {
                                                "font-size": "20px"
                                              }
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _c(
                                  "b-button",
                                  {
                                    attrs: { variant: "outline-primary" },
                                    on: {
                                      click: function($event) {
                                        return _vm.unarchive_dpp(
                                          data.item,
                                          data.item.id
                                        )
                                      }
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "ion ion-md-arrow-back",
                                      staticStyle: { "font-size": "20px" }
                                    })
                                  ]
                                )
                              ]
                            }
                          }
                        ])
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v("\n       \n        " + _vm._s(this.info) + "\n    ")
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "modal",
          attrs: {
            id: "create_dpp",
            title: "Создать ДПП",
            "ok-title": "Создать",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handleOk }
        },
        [
          _c(
            "form",
            {
              ref: "form",
              on: {
                submit: function($event) {
                  $event.stopPropagation()
                  $event.preventDefault()
                  return _vm.handleSubmit($event)
                }
              }
            },
            [
              _c(
                "b-form-group",
                {
                  attrs: {
                    state: _vm.new_dpp.nameState,
                    label: "Наименование (тематика) ДПП",
                    "label-for": "name-input",
                    "invalid-feedback": "Необходимо ввести наименование",
                    "label-size": "lg"
                  }
                },
                [
                  _c("b-form-input", {
                    attrs: {
                      id: "name-input",
                      state: _vm.new_dpp.nameState,
                      required: ""
                    },
                    model: {
                      value: _vm.new_dpp.name,
                      callback: function($$v) {
                        _vm.$set(_vm.new_dpp, "name", $$v)
                      },
                      expression: "new_dpp.name"
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
                    state: _vm.new_dpp.typeState,
                    label: "Тип ДПП",
                    "label-for": "type-input",
                    "invalid-feedback": "Необходимо выбрать тип ДПП",
                    "label-size": "lg"
                  }
                },
                [
                  _c("b-form-select", {
                    attrs: {
                      id: "type-input",
                      options: _vm.dpp_types,
                      state: _vm.new_dpp.typeState,
                      required: ""
                    },
                    scopedSlots: _vm._u([
                      {
                        key: "first",
                        fn: function() {
                          return undefined
                        },
                        proxy: true
                      }
                    ]),
                    model: {
                      value: _vm.new_dpp.type,
                      callback: function($$v) {
                        _vm.$set(_vm.new_dpp, "type", $$v)
                      },
                      expression: "new_dpp.type"
                    }
                  })
                ],
                1
              )
            ],
            1
          )
        ]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/dpps/Dpps.vue":
/*!*******************************************************!*\
  !*** ./resources/assets/src/components/dpps/Dpps.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dpps.vue?vue&type=template&id=6b4c3076& */ "./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076&");
/* harmony import */ var _Dpps_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dpps.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Dpps_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/Dpps.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dpps_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Dpps.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/Dpps.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dpps_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076&":
/*!**************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Dpps.vue?vue&type=template&id=6b4c3076& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/Dpps.vue?vue&type=template&id=6b4c3076&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dpps_vue_vue_type_template_id_6b4c3076___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);