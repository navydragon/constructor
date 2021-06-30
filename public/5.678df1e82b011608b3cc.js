(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "edit-fgos",
  props: {
    edit_item: Object,
    fgos_levels: Array
  },
  data: function data() {
    return {
      new_item: {
        code: '',
        name: '',
        fgos_level_id: ''
      },
      errors: []
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_item.fgos_level_id == '') {
        this.errors.push("Не выбран уровень образования");
      }

      if (this.new_item.code == '') {
        this.errors.push("Не заполнено поле «Код»");
      }

      if (this.new_item.name == '') {
        this.errors.push("Не заполнено поле «Направление»");
      }

      if (this.errors.length == 0) {
        this.$emit('update_fgos', this.new_item);
      }
    }
  },
  mounted: function mounted() {
    self = this;
    axios.post('/fgoses/get_fgos', this.edit_item).then(function (response) {
      self.new_item = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "new-fgos",
  props: {
    fgos_levels: Array
  },
  data: function data() {
    return {
      new_item: {
        code: '',
        name: '',
        fgos_level_id: ''
      },
      errors: []
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_item.fgos_level_id == '') {
        this.errors.push("Не выбран уровень образования");
      }

      if (this.new_item.code == '') {
        this.errors.push("Не заполнено поле «Код»");
      }

      if (this.new_item.name == '') {
        this.errors.push("Не заполнено поле «Направление»");
      }

      if (this.errors.length == 0) {
        this.$emit('add_fgos', this.new_item);
      }
    }
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
    "b-modal",
    {
      attrs: {
        size: "lg",
        id: "modal-edit-fgos",
        title: "Редактирование параметров ФГОС",
        "no-close-on-esc": "",
        "no-close-on-backdrop": "",
        "ok-title": "Сохранить",
        "cancel-title": "Закрыть"
      },
      on: { ok: _vm.handle_ok }
    },
    [
      _c(
        "b-form-group",
        {
          attrs: {
            id: "fieldset-0",
            label: "Уровень образования",
            "label-size": "lg"
          }
        },
        [
          _c("b-form-select", {
            attrs: {
              options: _vm.fgos_levels,
              "value-field": "id",
              "text-field": "name"
            },
            model: {
              value: _vm.new_item.fgos_level_id,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "fgos_level_id", $$v)
              },
              expression: "new_item.fgos_level_id"
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
            id: "fieldset-1",
            description: "Пример: 08.03.01",
            label: "Код",
            "label-size": "lg",
            "label-for": "input-1"
          }
        },
        [
          _c("b-form-input", {
            attrs: { id: "input-1", trim: "" },
            model: {
              value: _vm.new_item.code,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "code", $$v)
              },
              expression: "new_item.code"
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
            id: "fieldset-2",
            description: "Пример: Строительство",
            label: "Направление",
            "label-size": "lg",
            "label-for": "input-2"
          }
        },
        [
          _c("b-form-input", {
            attrs: { id: "input-2", trim: "" },
            model: {
              value: _vm.new_item.name,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "name", $$v)
              },
              expression: "new_item.name"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _vm.errors.length > 0
        ? _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
            _c("strong", [_vm._v("Обнаружены ошибки:")]),
            _vm._v(" "),
            _c(
              "ul",
              _vm._l(_vm.errors, function(error, index) {
                return _c("li", { key: index }, [_vm._v(_vm._s(error))])
              }),
              0
            )
          ])
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
    "b-modal",
    {
      attrs: {
        size: "lg",
        id: "modal-new-fgos",
        title: "Добавить новый ФГОС",
        "no-close-on-esc": "",
        "no-close-on-backdrop": "",
        "ok-title": "Сохранить",
        "cancel-title": "Закрыть"
      },
      on: { ok: _vm.handle_ok }
    },
    [
      _c(
        "b-form-group",
        {
          attrs: {
            id: "fieldset-0",
            label: "Уровень образования",
            "label-size": "lg"
          }
        },
        [
          _c("b-form-select", {
            attrs: {
              options: _vm.fgos_levels,
              "value-field": "id",
              "text-field": "name"
            },
            model: {
              value: _vm.new_item.fgos_level_id,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "fgos_level_id", $$v)
              },
              expression: "new_item.fgos_level_id"
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
            id: "fieldset-1",
            description: "Пример: 08.03.01",
            label: "Код",
            "label-size": "lg",
            "label-for": "input-1"
          }
        },
        [
          _c("b-form-input", {
            attrs: { id: "input-1", trim: "" },
            model: {
              value: _vm.new_item.code,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "code", $$v)
              },
              expression: "new_item.code"
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
            id: "fieldset-2",
            description: "Пример: Строительство",
            label: "Направление",
            "label-size": "lg",
            "label-for": "input-2"
          }
        },
        [
          _c("b-form-input", {
            attrs: { id: "input-2", trim: "" },
            model: {
              value: _vm.new_item.name,
              callback: function($$v) {
                _vm.$set(_vm.new_item, "name", $$v)
              },
              expression: "new_item.name"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _vm.errors.length > 0
        ? _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
            _c("strong", [_vm._v("Обнаружены ошибки:")]),
            _vm._v(" "),
            _c(
              "ul",
              _vm._l(_vm.errors, function(error, index) {
                return _c("li", { key: index }, [_vm._v(_vm._s(error))])
              }),
              0
            )
          ])
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/fgoses/EditFgos.vue":
/*!*************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/EditFgos.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditFgos.vue?vue&type=template&id=5c1954fe& */ "./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe&");
/* harmony import */ var _EditFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditFgos.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EditFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__["render"],
  _EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/fgoses/EditFgos.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditFgos.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe&":
/*!********************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditFgos.vue?vue&type=template&id=5c1954fe& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/EditFgos.vue?vue&type=template&id=5c1954fe&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditFgos_vue_vue_type_template_id_5c1954fe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/fgoses/NewFgos.vue":
/*!************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/NewFgos.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewFgos.vue?vue&type=template&id=6f09ca23& */ "./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23&");
/* harmony import */ var _NewFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewFgos.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NewFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/fgoses/NewFgos.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewFgos.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23&":
/*!*******************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewFgos.vue?vue&type=template&id=6f09ca23& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/NewFgos.vue?vue&type=template&id=6f09ca23&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewFgos_vue_vue_type_template_id_6f09ca23___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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