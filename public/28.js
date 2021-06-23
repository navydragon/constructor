(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[28],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkStructure.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
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





vue__WEBPACK_IMPORTED_MODULE_1___default.a.use(vue_tables_2__WEBPACK_IMPORTED_MODULE_2__["ClientTable"]);
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "dpp_stage_work_om",
  metaInfo: {
    title: "���������� ��� - ��������� ���������"
  },
  components: {
    ModelSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_0__["ModelSelect"],
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_2__["ClientTable"],
    NewQuestion: _NewQuestion__WEBPACK_IMPORTED_MODULE_3__["default"],
    EditQuestion: _EditQuestion__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  computed: {
    header: function header() {
      return "���������� ��� / " + this.stage.dpp_name + " / " + this.stage.type_name;
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
        text: '������ 1',
        value: 1,
        questions: [{
          text: '������ 1',
          type_name: '����� ��������� ������'
        }]
      }, {
        text: '������ 2',
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
          text: '����� �������',
          type_name: '��� �������',
          edit: '��������'
        },
        texts: {
          count: "Showing {from} to {to} of {count} records|{count} records|���� ������",
          first: 'First',
          last: 'Last',
          filter: "�����:",
          filterPlaceholder: "����� ������",
          limit: "�������:",
          page: "��������:",
          noResults: "�� ������� �� ����� ������",
          filterBy: "Filter by {column}",
          loading: '��������...',
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
          text: '����� �������',
          type_name: '��� �������',
          name: '�������',
          edit: '��������'
        },
        texts: {
          count: "Showing {from} to {to} of {count} records|{count} records|���� ������",
          first: 'First',
          last: 'Last',
          filter: "�����:",
          filterPlaceholder: "����� ������",
          limit: "�������:",
          page: "��������:",
          noResults: "�� ������� �� ����� ������",
          filterBy: "Filter by {column}",
          loading: '��������...',
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
      this.$bvModal.msgBoxConfirm('������������� ������ ������� ������ �' + question.text + '�?').then(function (value) {
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
      this.$bvModal.msgBoxConfirm('������������� ������ ������� �' + task.name + ' (' + task.type_name + ')�?').then(function (value) {
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
  return _c("div")
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