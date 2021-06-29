(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[14],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-tables-2 */ "./node_modules/vue-tables-2/compiled/index.js");
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_tables_2__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _NewProfstandart__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NewProfstandart */ "./resources/assets/src/components/profstandarts/NewProfstandart.vue");
/* harmony import */ var _EditProfstandart__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./EditProfstandart */ "./resources/assets/src/components/profstandarts/EditProfstandart.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




vue__WEBPACK_IMPORTED_MODULE_0___default.a.use(vue_tables_2__WEBPACK_IMPORTED_MODULE_1__["ClientTable"]);
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'profstandarts',
  metaInfo: {
    title: 'Профессиональные стандарты'
  },
  components: {
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_1__["ClientTable"],
    NewProfstandart: _NewProfstandart__WEBPACK_IMPORTED_MODULE_2__["default"],
    EditProfstandart: _EditProfstandart__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  data: function data() {
    return {
      isBusy: true,
      n_ps: 'ps',
      edit_item: {},
      items: [],
      columns: ['code', 'name', 'actions'],
      options: {
        // pagination: { chunk: 5 },
        sortable: ['name', 'code'],
        filterable: ['name', 'code'],
        filterByColumn: true,
        sortIcon: {
          is: 'fa-sort',
          base: 'fas',
          up: 'fa-sort-up',
          down: 'fa-sort-down'
        },
        headings: {
          code: 'Код',
          name: 'Название',
          actions: 'Действия'
        },
        texts: {
          count: "Showing {from} to {to} of {count} records|Записей: {count} |Одна запись",
          first: 'First',
          last: 'Last',
          filter: "Поиск:",
          filterPlaceholder: "текст поиска",
          limit: "Записей:",
          page: "Страница:",
          noResults: "Не найдено ни одной записи",
          filterBy: "Поиск",
          loading: 'Загрузка...',
          defaultOption: 'Select {column}',
          columns: 'Columns'
        }
      }
    };
  },
  methods: {
    create_profstandart: function create_profstandart() {
      this.$bvModal.show("modal-newprofstandart");
    },
    add_profstandart: function add_profstandart(data) {
      self = this;
      axios.post('/profstandarts/add_profstandart', data).then(function (response) {
        self.items.push(response.data);
        self.n_ps += 1;
      });
    },
    remove_profstandart: function remove_profstandart(elem) {
      var self = this;
      this.$bvModal.msgBoxConfirm("Действительно хотите удалить профстандарт «" + elem.name + "»?").then(function (value) {
        if (value === true) {
          axios.post('/profstandarts/remove_profstandart', elem).then(function (response) {
            self.items = self.items.filter(function (item) {
              return item.id != response.data;
            });
          });
        }
      });
    },
    edit_profstandart: function edit_profstandart(elem) {
      var _this = this;

      this.edit_item = elem;
      this.$nextTick(function () {
        _this.$bvModal.show("modal-editprofstandart");
      });
    },
    update_profstandart: function update_profstandart(data) {
      self = this;
      axios.post('/profstandarts/update_profstandart', data).then(function (response) {
        var upd_item = self.items.find(function (item) {
          return item.id == response.data.id;
        });
        upd_item.code = response.data.code;
        upd_item.name = response.data.name;
        self.$bvModal.hide("modal-editprofstandart");
      })["finally"](function () {
        return self.edit_item = {};
      });
    }
  },
  mounted: function mounted() {
    self = this;
    axios.get('/profstandarts/get_profstandarts').then(function (response) {
      self.items = response.data;
    })["finally"](function () {
      return self.isBusy = false;
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe& ***!
  \**************************************************************************************************************************************************************************************************************************************/
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
    "b-card",
    { attrs: { title: "Профессиональные стандарты" } },
    [
      _c(
        "b-button",
        {
          attrs: { variant: "primary" },
          on: {
            click: function($event) {
              return _vm.create_profstandart()
            }
          }
        },
        [_vm._v("Добавить профстандарт")]
      ),
      _vm._v(" "),
      _c("hr", { staticClass: "container-m-nx border-light mt-2 mb-2" }),
      _vm._v(" "),
      !_vm.isBusy
        ? _c("v-client-table", {
            attrs: {
              data: _vm.items,
              columns: _vm.columns,
              options: _vm.options
            },
            scopedSlots: _vm._u(
              [
                {
                  key: "actions",
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
                                  return _vm.edit_profstandart(props.row)
                                }
                              }
                            },
                            [_c("i", { staticClass: "ion ion-md-create" })]
                          ),
                          _vm._v(" "),
                          _c(
                            "b-btn",
                            {
                              staticClass: "btn-sm",
                              attrs: { variant: "outline-danger icon-btn" },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  return _vm.remove_profstandart(props.row)
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
              ],
              null,
              false,
              3329996254
            )
          })
        : _vm._e(),
      _vm._v(" "),
      _c("new-profstandart", {
        key: _vm.n_ps,
        on: { add_profstandart: _vm.add_profstandart }
      }),
      _vm._v(" "),
      _c("edit-profstandart", {
        key: _vm.edit_item.id,
        attrs: { edit_item: _vm.edit_item },
        on: { update_profstandart: _vm.update_profstandart }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/profstandarts/Profstandarts.vue":
/*!*************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/Profstandarts.vue ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Profstandarts.vue?vue&type=template&id=a3e0dcfe& */ "./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe&");
/* harmony import */ var _Profstandarts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Profstandarts.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vendor_libs_vue_data_tables_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Profstandarts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/profstandarts/Profstandarts.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profstandarts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Profstandarts.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profstandarts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe&":
/*!********************************************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe& ***!
  \********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Profstandarts.vue?vue&type=template&id=a3e0dcfe& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/Profstandarts.vue?vue&type=template&id=a3e0dcfe&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profstandarts_vue_vue_type_template_id_a3e0dcfe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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