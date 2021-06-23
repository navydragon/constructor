(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[26],{

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
      sections: []
    };
  },
  methods: {},
  mounted: function mounted() {
    var _this = this;

    var self = this;
    axios.get('/dpps/' + this.$route.params.dpp + '/get_stage_data/' + this.$route.params.stage).then(function (response) {
      return _this.stage = response.data;
    })["finally"](function (response) {
      axios.get('/dpps/' + self.$route.params.dpp + '/get_sections/' + self.stage.st_version_id).then(function (response) {
        return self.sections = response.data;
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
              _c("b-tab", { attrs: { title: "Управление", active: "" } }, [
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
                                _c("b-col", { attrs: { cols: "10" } }, [
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
                                                  variant: "primary icon-btn"
                                                },
                                                on: {
                                                  click: function($event) {
                                                    $event.preventDefault()
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
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-arrow-round-down"
                                            })
                                          ]
                                        ),
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
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-create"
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
                                                  variant: "outline-primary"
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
                                  "Темы (" + _vm._s(section.themes.length) + ")"
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
                                      [_vm._v(_vm._s(theme.name))]
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
                )
              ]),
              _vm._v(" "),
              _c(
                "b-tab",
                { attrs: { title: "Таблица" } },
                [_c("b-card-text", [_vm._v("Tab contents 2")])],
                1
              ),
              _vm._v(" "),
              _c(
                "b-tab",
                { attrs: { title: "Знания" } },
                [_c("b-card-text", [_vm._v("Tab contents 2")])],
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