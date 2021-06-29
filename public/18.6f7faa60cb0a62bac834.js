(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.common.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_search_select__WEBPACK_IMPORTED_MODULE_0__);
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'dpp_config',
  metaInfo: {
    title: 'Управление ДПП'
  },
  components: {
    ModelSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_0__["ModelSelect"]
  },
  computed: {
    header: function header() {
      return 'Управление ДПП / ' + this.item.name;
    }
  },
  data: function data() {
    var _ref;

    return _ref = {
      item: {},
      fields: [{
        key: 'fullname',
        label: 'ФИО',
        sortable: true
      }, {
        key: 'rolename',
        label: 'Роль',
        sortable: true
      }, {
        key: 'modify',
        label: 'Управление',
        sortable: false
      }],
      users: [],
      new_participant: {
        user: null,
        userState: null,
        role: null,
        roleState: null
      }
    }, _defineProperty(_ref, "users", []), _defineProperty(_ref, "roles", []), _defineProperty(_ref, "existed_error", false), _ref;
  },
  methods: {
    checkFormValidity: function checkFormValidity() {
      var valid = this.$refs.form.checkValidity();
      this.new_participant.userState = valid;
      this.new_participant.roleState = valid;
      return valid;
    },
    resetModal: function resetModal() {
      this.new_participant.user = '';
      this.new_participant.userState = null;
      this.new_participant.role = '';
      this.new_participant.roleState = null;
      this.existed_error = false;
    },
    handleOk: function handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault(); // Trigger submit handler

      this.handleSubmit();
    },
    handleSubmit: function handleSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      } // store_user_role


      this.store_dpp_user_role();
    },
    handleResponse: function handleResponse(response) {
      if (response.data == "exists") {
        this.existed_error = true;
        this.new_participant.roleState = null;
      } else {
        this.resetModal();
        this.$bvModal.hide('add_participants');
        this.item.participants.push(response.data);
      }
    },
    store_dpp_user_role: function store_dpp_user_role() {
      var _this = this;

      axios.post('/add_dpp_user_role', {
        'user': this.new_participant.user,
        'role': this.new_participant.role,
        'dpp': this.$route.params.dpp
      }).then(function (response) {
        return _this.handleResponse(response);
      });
    },
    confirmDelete: function confirmDelete(item) {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('Удалить роль «' + item.rolename + '» пользователя «' + item.fullname + '»?').then(function (value) {
        if (value == true) {
          axios.post('/delete_dpp_user_role', {
            id: item.id
          }).then(function () {
            return _this2.item.participants.splice(_this2.item.participants.indexOf(item), 1);
          });
        }
      })["catch"](function (err) {// An error occurred
      });
    }
  },
  mounted: function mounted() {
    var _this3 = this;

    axios.get('/dpps/' + this.$route.params.dpp + '/config').then(function (response) {
      return _this3.item = response.data;
    }); //.finally(() => (alert('kek')));

    axios.get('/dpp_types').then(function (response) {
      return _this3.dpp_types = response.data;
    });
    axios.get('/users').then(function (response) {
      return _this3.users = response.data;
    });
    axios.get('/roles').then(function (response) {
      return _this3.roles = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200& ***!
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
    "div",
    [
      _c(
        "b-card",
        { attrs: { title: _vm.header } },
        [
          _c(
            "router-link",
            {
              attrs: {
                icon: "ion ion-md-person",
                to: { name: "dpps" },
                exact: true
              }
            },
            [
              _c("b-button", { attrs: { variant: "info" } }, [
                _c("i", {
                  staticClass: "ion ion-ios-arrow-back",
                  staticStyle: { "font-size": "20px" }
                }),
                _vm._v(" Назад\n            ")
              ])
            ],
            1
          ),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _c("h5", [_vm._v("Наименование (тематика) ДПП")]),
          _vm._v(" "),
          _c("p", [_vm._v(_vm._s(_vm.item.name))]),
          _vm._v(" "),
          _c("h5", [_vm._v("Тип ДПП")]),
          _vm._v(" "),
          _c("p", [_vm._v(_vm._s(_vm.item.type_name))]),
          _vm._v(" "),
          _c("h5", [_vm._v("Аннотация")]),
          _vm._v(" "),
          _c("p", [_vm._v("...")]),
          _vm._v(" "),
          _c("h5", [_vm._v("Участники")]),
          _vm._v(" "),
          _c("b-table", {
            attrs: {
              bordered: "",
              hover: "",
              "table-variant": "light",
              "head-variant": "light",
              items: _vm.item.participants,
              fields: _vm.fields
            },
            scopedSlots: _vm._u([
              {
                key: "cell(modify)",
                fn: function(data) {
                  return [
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "outline-danger" },
                        on: {
                          click: function($event) {
                            return _vm.confirmDelete(data.item)
                          }
                        }
                      },
                      [
                        _c("i", {
                          staticClass: "ion ion-md-close",
                          staticStyle: { "font-size": "14px" }
                        })
                      ]
                    )
                  ]
                }
              }
            ])
          }),
          _vm._v(" "),
          _c(
            "b-button",
            {
              directives: [
                {
                  name: "b-modal",
                  rawName: "v-b-modal.add_participants",
                  modifiers: { add_participants: true }
                }
              ],
              attrs: { variant: "primary" }
            },
            [_vm._v("Добавить участника")]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "modal",
          attrs: {
            id: "add_participants",
            title: "Добавить участников",
            "ok-title": "Добавить",
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
                    state: _vm.new_participant.userState,
                    label: "Пользователь",
                    "label-for": "user-input",
                    "invalid-feedback": "Необходимо выбрать пользователя",
                    "label-size": "lg"
                  }
                },
                [
                  _c("model-select", {
                    attrs: {
                      id: "user-input",
                      options: _vm.users,
                      state: _vm.new_participant.userState,
                      placeholder: "Выберите пользователя"
                    },
                    model: {
                      value: _vm.new_participant.user,
                      callback: function($$v) {
                        _vm.$set(_vm.new_participant, "user", $$v)
                      },
                      expression: "new_participant.user"
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
                    state: _vm.new_participant.roleState,
                    label: "Роль",
                    "label-for": "role-input",
                    "invalid-feedback": "Необходимо выбрать роль",
                    "label-size": "lg"
                  }
                },
                [
                  _c("b-form-select", {
                    attrs: {
                      id: "role-input",
                      options: _vm.roles,
                      state: _vm.new_participant.roleState,
                      required: ""
                    },
                    model: {
                      value: _vm.new_participant.role,
                      callback: function($$v) {
                        _vm.$set(_vm.new_participant, "role", $$v)
                      },
                      expression: "new_participant.role"
                    }
                  })
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _vm.existed_error
            ? _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
                _vm._v(
                  "Ошибка! Пользователь уже выполняет указанную роль в данной программе."
                )
              ])
            : _vm._e()
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

/***/ "./resources/assets/src/components/dpps/DppConfig.vue":
/*!************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppConfig.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DppConfig.vue?vue&type=template&id=5eda6200& */ "./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200&");
/* harmony import */ var _DppConfig_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DppConfig.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DppConfig_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/DppConfig.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppConfig_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppConfig.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppConfig_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200&":
/*!*******************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppConfig.vue?vue&type=template&id=5eda6200& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppConfig.vue?vue&type=template&id=5eda6200&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppConfig_vue_vue_type_template_id_5eda6200___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);