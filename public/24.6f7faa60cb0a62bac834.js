(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[24],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'typologies',
  metaInfo: {
    title: 'Управление типологиями содержания ДПП'
  },
  data: function data() {
    return {
      t: 0,
      fields: [{
        key: 'name',
        label: 'Наименование',
        sortable: true
      }, {
        key: 'parts',
        label: 'Разделов',
        sortable: true
      }, {
        key: 'modify',
        label: 'Управление',
        sortable: false
      }],
      items: [],
      info: "",
      isBusy: true,
      new_typology: {
        name: '',
        nameState: null,
        parts: [{
          id: this.generate_id(),
          name: '',
          state: null
        }]
      },
      edit_item: {}
    };
  },
  methods: {
    handleOk: function handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      self = this;
      axios.post('/typologies/add_typology', this.new_typology).then(function (response) {
        self.items.push(response.data);
        self.$bvModal.hide("create_typology");
      })["finally"](function () {
        return self.t = self.t + 1;
      });
    },
    handleEditOk: function handleEditOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      self = this;
      axios.post('/typologies/update_typology', this.edit_item).then(function (response) {
        var upd_item = self.items.find(function (item) {
          return item.id == response.data.id;
        });
        upd_item = response.data;
        self.$bvModal.hide("edit_modal");
      })["finally"](function () {
        return self.t = self.t + 1;
      });
    },
    remove_part: function remove_part(item) {
      self = this;
      axios.post('/typologies/remove_part', item).then(function (response) {
        self.edit_item.parts = self.edit_item.parts.filter(function (elem) {
          return elem.id != item.id;
        });
      });
    },
    generate_id: function generate_id() {
      return "f".concat((~~(Math.random() * 1e8)).toString(16));
    },
    add_part: function add_part() {
      this.new_typology.parts.push({
        id: this.generate_id(),
        name: '',
        state: null
      });
    },
    add_part_to_edit: function add_part_to_edit() {
      this.edit_item.parts.push({
        id: this.generate_id(),
        name: '',
        state: null
      });
    },
    edit_typology: function edit_typology(item) {
      var _this = this;

      this.edit_item = item;
      this.$nextTick(function () {
        _this.$bvModal.show("edit_modal");
      });
    },
    move_up: function move_up(part) {
      var upped = this.edit_item.parts.find(function (elem) {
        return elem.id == part.id;
      });
      var find_pos = upped.position - 1;
      var downed = this.edit_item.parts.find(function (elem) {
        return elem.position == find_pos;
      });
      downed.position = downed.position + 1;
      upped.position = upped.position - 1;
      this.edit_item.parts.sort(function (prev, next) {
        return prev.position - next.position;
      });
    },
    move_down: function move_down(part) {
      var downed = this.edit_item.parts.find(function (elem) {
        return elem.id == part.id;
      });
      var find_pos = downed.position + 1;
      var upped = this.edit_item.parts.find(function (elem) {
        return elem.position == find_pos;
      });
      downed.position = downed.position + 1;
      upped.position = upped.position - 1;
      this.edit_item.parts.sort(function (prev, next) {
        return prev.position - next.position;
      });
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    axios.get('/typologies/get_typologies').then(function (response) {
      return _this2.items = response.data;
    })["finally"](function () {
      return _this2.isBusy = false;
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
        { attrs: { title: "Типологии содержания ДПП" } },
        [
          _c("b-card-text", [
            _vm._v(
              "\n        Ниже отображается таблица всех типовых структур ДПП\n        "
            )
          ]),
          _vm._v(" "),
          _c(
            "b-button",
            {
              directives: [
                {
                  name: "b-modal",
                  rawName: "v-b-modal.create_typology",
                  modifiers: { create_typology: true }
                }
              ],
              attrs: { variant: "primary" }
            },
            [_vm._v("Создать типовую структуру")]
          ),
          _vm._v(" "),
          _c("p"),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _c("b-table", {
            attrs: {
              busy: _vm.isBusy,
              bordered: "",
              hover: "",
              "table-variant": "light",
              "head-variant": "light",
              items: _vm.items,
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
                        _c("b-spinner", { staticClass: "align-middle" }),
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
                key: "cell(parts)",
                fn: function(data) {
                  return [
                    _vm._v(
                      "\n                " +
                        _vm._s(data.item.parts.length) +
                        "\n            "
                    )
                  ]
                }
              },
              {
                key: "cell(modify)",
                fn: function(data) {
                  return [
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "outline-primary" },
                        on: {
                          click: function($event) {
                            return _vm.edit_typology(data.item)
                          }
                        }
                      },
                      [
                        _c("i", {
                          staticClass: "ion ion-md-construct",
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
        "b-modal",
        {
          key: _vm.t,
          ref: "modal",
          attrs: {
            id: "create_typology",
            title: "Создать типовую структуру ДПП",
            "ok-title": "Создать",
            size: "lg",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handleOk }
        },
        [
          _c(
            "b-form-group",
            {
              attrs: {
                state: _vm.new_typology.nameState,
                label: "Название",
                "label-for": "name-input",
                "invalid-feedback": "Необходимо ввести название",
                "label-size": "lg"
              }
            },
            [
              _c("b-form-input", {
                attrs: {
                  id: "name-input",
                  state: _vm.new_typology.nameState,
                  required: ""
                },
                model: {
                  value: _vm.new_typology.name,
                  callback: function($$v) {
                    _vm.$set(_vm.new_typology, "name", $$v)
                  },
                  expression: "new_typology.name"
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c("h5", [_vm._v("Разделы типологии:")]),
          _vm._v(" "),
          _c(
            "b-button",
            { attrs: { variant: "primary" }, on: { click: _vm.add_part } },
            [_vm._v("Добавить раздел")]
          ),
          _vm._v(" "),
          _vm._l(_vm.new_typology.parts, function(elem, index) {
            return _c("div", { key: elem.id, staticClass: "m-3" }, [
              _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-1" }, [
                  _vm._v(
                    "\n                        " +
                      _vm._s(index + 1) +
                      ".\n                    "
                  )
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-11" },
                  [
                    _c("b-form-input", {
                      attrs: { state: elem.nameState, required: "" },
                      model: {
                        value: elem.name,
                        callback: function($$v) {
                          _vm.$set(elem, "name", $$v)
                        },
                        expression: "elem.name"
                      }
                    })
                  ],
                  1
                )
              ])
            ])
          })
        ],
        2
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          key: _vm.edit_item.id,
          ref: "modal",
          attrs: {
            id: "edit_modal",
            title: "Редактирование типовой структуры ДПП",
            "ok-title": "Сохранить",
            size: "lg",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handleEditOk }
        },
        [
          _c(
            "b-form-group",
            {
              attrs: {
                label: "Название",
                "invalid-feedback": "Необходимо ввести название",
                "label-size": "lg"
              }
            },
            [
              _c("b-form-input", {
                attrs: { required: "" },
                model: {
                  value: _vm.edit_item.name,
                  callback: function($$v) {
                    _vm.$set(_vm.edit_item, "name", $$v)
                  },
                  expression: "edit_item.name"
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c("h5", [_vm._v("Разделы типологии:")]),
          _vm._v(" "),
          _c(
            "b-button",
            {
              attrs: { variant: "primary" },
              on: { click: _vm.add_part_to_edit }
            },
            [_vm._v("Добавить раздел")]
          ),
          _vm._v(" "),
          _vm._l(_vm.edit_item.parts, function(elem, index) {
            return _c("div", { key: elem.id, staticClass: "m-3" }, [
              _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-1" }, [
                  _vm._v(
                    "\n                        " +
                      _vm._s(index + 1) +
                      ".\n                    "
                  )
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-9" },
                  [
                    _c("b-form-textarea", {
                      attrs: { rows: "3", "max-rows": "6", required: "" },
                      model: {
                        value: elem.name,
                        callback: function($$v) {
                          _vm.$set(elem, "name", $$v)
                        },
                        expression: "elem.name"
                      }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-2" },
                  [
                    index != 0
                      ? _c(
                          "b-btn",
                          {
                            staticClass: "btn",
                            attrs: { variant: "outline-info icon-btn btn-sm" },
                            on: {
                              click: function($event) {
                                return _vm.move_up(elem)
                              }
                            }
                          },
                          [
                            _c("i", {
                              staticClass: "ion ion-md-arrow-round-up"
                            })
                          ]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    index != _vm.edit_item.parts.length - 1
                      ? _c(
                          "b-btn",
                          {
                            staticClass: "btn",
                            attrs: { variant: "outline-info icon-btn btn-sm" },
                            on: {
                              click: function($event) {
                                return _vm.move_down(elem)
                              }
                            }
                          },
                          [
                            _c("i", {
                              staticClass: "ion ion-md-arrow-round-down"
                            })
                          ]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    _c(
                      "b-btn",
                      {
                        attrs: { variant: "outline-danger btn-sm" },
                        on: {
                          click: function($event) {
                            return _vm.remove_part(elem)
                          }
                        }
                      },
                      [_c("i", { staticClass: "ion ion-md-close" })]
                    )
                  ],
                  1
                )
              ])
            ])
          })
        ],
        2
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/typologies/Typologies.vue":
/*!*******************************************************************!*\
  !*** ./resources/assets/src/components/typologies/Typologies.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Typologies.vue?vue&type=template&id=d1549476& */ "./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476&");
/* harmony import */ var _Typologies_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Typologies.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Typologies_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/typologies/Typologies.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Typologies_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Typologies.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/typologies/Typologies.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Typologies_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476&":
/*!**************************************************************************************************!*\
  !*** ./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Typologies.vue?vue&type=template&id=d1549476& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/typologies/Typologies.vue?vue&type=template&id=d1549476&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Typologies_vue_vue_type_template_id_d1549476___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);