(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "my_dpps_overview",
  metaInfo: {
    title: "Разработка ДПП"
  },
  data: function data() {
    return {
      dpp: {},
      stage_types: [],
      isBusy: true,
      curStage: false
    };
  },
  computed: {
    header: function header() {
      return 'Разработка ДПП / ' + this.dpp.name;
    }
  },
  methods: {
    setcurStage: function setcurStage(st) {
      this.curStage = st;
    },
    start_work: function start_work(id) {
      var self = this;
      axios.post('/dpps/stages/start', {
        'stage_id': id
      }).then(function () {
        if (self.curStage.stage_type_id == 6) {
          self.$router.push('/my_dpps/' + self.dpp.id + '/stages/' + id + '/work_ish');
        }

        if (self.curStage.stage_type_id == 1) {
          self.$router.push('/my_dpps/' + self.dpp.id + '/stages/' + id + '/work');
        }

        if (self.curStage.stage_type_id == 2) {
          self.$router.push('/my_dpps/' + self.dpp.id + '/stages/' + id + '/work_om');
        }

        if (self.curStage.stage_type_id == 3) {
          self.$router.push('/my_dpps/' + self.dpp.id + '/stages/' + id + '/work_structure');
        }
      });
    }
  },
  mounted: function mounted() {
    var _this = this;

    var self = this;
    axios.get('/dpps/' + this.$route.params.dpp + '/overview').then(function (response) {
      return _this.dpp = response.data;
    })["finally"](function () {
      var a = self.dpp.stages.filter(function (number) {
        return number.id == self.dpp.current_stage_id;
      });
      self.curStage = a[0]; //() => (this.curStage.id = this.dpp.current_stage_id) 
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792& ***!
  \***************************************************************************************************************************************************************************************************************************/
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
        "router-link",
        {
          attrs: {
            icon: "ion ion-md-person",
            to: { name: "my_dpps" },
            exact: true
          }
        },
        [
          _c("b-button", { attrs: { variant: "info" } }, [
            _c("i", {
              staticClass: "ion ion-ios-arrow-back",
              staticStyle: { "font-size": "20px" }
            }),
            _vm._v(" Назад\n              ")
          ])
        ],
        1
      ),
      _vm._v(" "),
      _c("b-card", { attrs: { title: _vm.header } }, [
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-md-4 col-lg-4 col-xl-3" }, [
            _c("h5", [_vm._v("Этапы разработки:")]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "ui-bordered mb-4" },
              _vm._l(_vm.dpp.stages, function(st) {
                return _c(
                  "div",
                  {
                    key: st.id,
                    staticClass: "d-flex align-items-center w-100 py-2 px-3",
                    class: {
                      "bg-lighter font-weight-bold": _vm.curStage == st.id
                    }
                  },
                  [
                    _c(
                      "a",
                      {
                        staticClass: "d-block text-body",
                        attrs: { href: "#" },
                        on: {
                          click: function($event) {
                            return _vm.setcurStage(st)
                          }
                        }
                      },
                      [
                        _vm._v(
                          _vm._s(_vm.dpp.stages.indexOf(st) + 1) +
                            ". " +
                            _vm._s(st.name)
                        )
                      ]
                    )
                  ]
                )
              }),
              0
            )
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-8 col-lg-8 col-xl-9" },
            [
              _vm.curStage == false
                ? _c("b-alert", { attrs: { show: "", variant: "info" } }, [
                    _vm._v("Выберите этап")
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm._l(_vm.dpp.stages, function(st) {
                return _c("div", { key: st.id }, [
                  _vm.curStage.id == st.id
                    ? _c(
                        "div",
                        [
                          _c("h5", [
                            _vm._v(
                              _vm._s(_vm.dpp.stages.indexOf(st) + 1) +
                                ". " +
                                _vm._s(st.name)
                            )
                          ]),
                          _vm._v(" "),
                          _c("p", [
                            _c("strong", [_vm._v("Статус этапа:")]),
                            _vm._v(" " + _vm._s(st.stage_status_name))
                          ]),
                          _vm._v(" "),
                          st.stage_status_id == 1
                            ? _c(
                                "b-button",
                                {
                                  attrs: { block: "", variant: "primary" },
                                  on: {
                                    click: function($event) {
                                      return _vm.start_work(st.id)
                                    }
                                  }
                                },
                                [_vm._v("Начать работу")]
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          st.stage_type_id == 1 &&
                          st.stage_status_id != 1 &&
                          st.stage_status_id != 2
                            ? _c(
                                "router-link",
                                {
                                  attrs: {
                                    icon: "ion ion-md-person",
                                    to: {
                                      name: "dpp_stage_work",
                                      params: { dpp: _vm.dpp.id, stage: st.id }
                                    },
                                    exact: true
                                  }
                                },
                                [
                                  _c(
                                    "b-button",
                                    {
                                      attrs: { block: "", variant: "primary" }
                                    },
                                    [_vm._v("Продолжить работу")]
                                  )
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          st.stage_type_id == 2 &&
                          st.stage_status_id != 1 &&
                          st.stage_status_id != 2
                            ? _c(
                                "router-link",
                                {
                                  attrs: {
                                    icon: "ion ion-md-person",
                                    to: {
                                      name: "dpp_stage_work_om",
                                      params: { dpp: _vm.dpp.id, stage: st.id }
                                    },
                                    exact: true
                                  }
                                },
                                [
                                  _c(
                                    "b-button",
                                    {
                                      attrs: { block: "", variant: "primary" }
                                    },
                                    [_vm._v("Продолжить работу")]
                                  )
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          st.stage_type_id == 3 &&
                          st.stage_status_id != 1 &&
                          st.stage_status_id != 2
                            ? _c(
                                "router-link",
                                {
                                  attrs: {
                                    icon: "ion ion-md-person",
                                    to: {
                                      name: "dpp_stage_work_structure",
                                      params: { dpp: _vm.dpp.id, stage: st.id }
                                    },
                                    exact: true
                                  }
                                },
                                [
                                  _c(
                                    "b-button",
                                    {
                                      attrs: { block: "", variant: "primary" }
                                    },
                                    [_vm._v("Продолжить работу")]
                                  )
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          st.stage_type_id == 6 &&
                          st.stage_status_id != 1 &&
                          st.stage_status_id != 2
                            ? _c(
                                "router-link",
                                {
                                  attrs: {
                                    icon: "ion ion-md-person",
                                    to: {
                                      name: "dpp_stage_work_ish",
                                      params: { dpp: _vm.dpp.id, stage: st.id }
                                    },
                                    exact: true
                                  }
                                },
                                [
                                  _c(
                                    "b-button",
                                    {
                                      attrs: { block: "", variant: "primary" }
                                    },
                                    [_vm._v("Продолжить работу")]
                                  )
                                ],
                                1
                              )
                            : _vm._e()
                        ],
                        1
                      )
                    : _vm._e()
                ])
              })
            ],
            2
          )
        ])
      ])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/dpps/DppOverview.vue":
/*!**************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppOverview.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DppOverview.vue?vue&type=template&id=5f575792& */ "./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792&");
/* harmony import */ var _DppOverview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DppOverview.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DppOverview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/DppOverview.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppOverview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppOverview.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppOverview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792&":
/*!*********************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppOverview.vue?vue&type=template&id=5f575792& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppOverview.vue?vue&type=template&id=5f575792&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppOverview_vue_vue_type_template_id_5f575792___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);