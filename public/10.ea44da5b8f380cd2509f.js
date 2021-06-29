(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuedraggable_src_vuedraggable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuedraggable/src/vuedraggable */ "./node_modules/vuedraggable/src/vuedraggable.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.common.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_search_select__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: "edit-question",
  metaInfo: {
    title: "Отредактировать вопрос"
  },
  components: {
    draggable: vuedraggable_src_vuedraggable__WEBPACK_IMPORTED_MODULE_0__["default"],
    ModelSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_1__["ModelSelect"]
  },
  props: {
    knowledges: Array,
    question_to_edit: Number
  },
  data: function data() {
    return {
      show_errors: false,
      errors: [],
      new_question: {
        knowledge: {},
        text: '',
        text_state: '',
        type: '',
        type_state: '',
        single_choice_answers: [{
          id: this.generate_id(),
          text: 'Ответ 1',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 2',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 3',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 4',
          is_right: false
        }],
        free_choice_answers: [{
          id: this.generate_id(),
          text: 'Ответ 1',
          is_right: true
        }],
        multi_choice_answers: [{
          id: this.generate_id(),
          text: 'Ответ 1',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 2',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 3',
          is_right: false
        }, {
          id: this.generate_id(),
          text: 'Ответ 4',
          is_right: false
        }],
        sequence_choice_answers: [{
          id: this.generate_id(),
          text: 'Ответ 1',
          is_right: true
        }, {
          id: this.generate_id(),
          text: 'Ответ 2',
          is_right: true
        }, {
          id: this.generate_id(),
          text: 'Ответ 3',
          is_right: true
        }, {
          id: this.generate_id(),
          text: 'Ответ 4',
          is_right: true
        }],
        accordance_choice_answers: [{
          id: this.generate_id(),
          accord1: 'Элемент 1',
          accord2: 'Соответствующий элемент 1',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: 'Элемент 2',
          accord2: 'Соответствующий элемент 2',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: 'Элемент 3',
          accord2: 'Соответствующий элемент 3',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: 'Элемент 4',
          accord2: 'Соответствующий элемент 4',
          is_right: true
        }],
        single_choice_fields: [{
          key: 'is_right',
          label: 'Правильный?'
        }, {
          key: 'text',
          label: 'Текст ответа'
        }],
        multi_choice_fields: [{
          key: 'is_right',
          label: 'Правильный?'
        }, {
          key: 'text',
          label: 'Текст ответа'
        }],
        free_choice_fields: [{
          key: 'text',
          label: 'Текст правильного ответа'
        }],
        accordance_choice_fields: [{
          key: 'accord1',
          label: 'Элемент'
        }, {
          key: 'accord2',
          label: 'Соответствующий элемент'
        }, {
          key: 'is_right',
          label: 'Удаление'
        }],
        single_choice_right: '',
        multi_choice_right: []
      },
      type_options: [{
        value: {
          id: 1,
          type_name: 'Выбор одного правильного ответа'
        },
        text: 'Выбор одного правильного ответа'
      }, {
        value: {
          id: 2,
          type_name: 'Выбор нескольких правильных ответов'
        },
        text: 'Выбор нескольких правильных ответов'
      }, {
        value: {
          id: 3,
          type_name: 'Вопрос с открытым ответом'
        },
        text: 'Вопрос с открытым ответом'
      }, {
        value: {
          id: 4,
          type_name: 'Установление последовательности'
        },
        text: 'Установление последовательности'
      }, {
        value: {
          id: 5,
          type_name: 'Установление соответствия'
        },
        text: 'Установление соответствия'
      }]
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];
      this.show_errors = false;

      if (this.new_question.text.length < 5) {
        this.errors.push("Не введен текст вопроса");
      } else {
        switch (this.new_question.type.id) {
          case 1:
            if (this.new_question.single_choice_right == "") {
              this.errors.push("Не выбран правильный вариант ответа");
            }

            for (var i = 0; i < this.new_question.single_choice_answers.length; i++) {
              if (this.new_question.single_choice_answers[i].text == '') {
                this.errors.push("Не введен один из вариантов ответа");
              }
            }

            break;

          case 2:
            if (this.new_question.multi_choice_right.length < 2) {
              this.errors.push("Для данного типа вопроса необходимо выбрать более одного правильного варианта ответа");
            }

            for (var i = 0; i < this.new_question.multi_choice_answers.length; i++) {
              if (this.new_question.multi_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 3:
            for (var i = 0; i < this.new_question.free_choice_answers.length; i++) {
              if (this.new_question.free_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 4:
            for (var i = 0; i < this.new_question.sequence_choice_answers.length; i++) {
              if (this.new_question.sequence_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 5:
            for (var i = 0; i < this.new_question.accordance_choice_answers.length; i++) {
              if (this.new_question.accordance_choice_answers[i].accord1 == '' || this.new_question.accordance_choice_answers[i].accord2 == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;
        }
      }

      if (this.errors.length > 0) {
        this.show_errors = true;
      } else {
        console.log("OK");
        this.$emit('update_question', this.new_question, this.question_to_edit);
      }
    },
    generate_id: function generate_id() {
      return "f".concat((~~(Math.random() * 1e8)).toString(16));
    },
    add_single_choice_answer: function add_single_choice_answer() {
      this.new_question.single_choice_answers.push({
        id: this.generate_id(),
        text: 'Новый ответ',
        is_right: false
      });
    },
    add_multi_choice_answer: function add_multi_choice_answer() {
      this.new_question.multi_choice_answers.push({
        id: this.generate_id(),
        text: 'Новый ответ',
        is_right: false
      });
    },
    add_free_choice_answer: function add_free_choice_answer() {
      this.new_question.free_choice_answers.push({
        id: this.generate_id(),
        text: 'Новый ответ',
        is_right: true
      });
    },
    add_sequence_choice_answer: function add_sequence_choice_answer() {
      this.new_question.sequence_choice_answers.push({
        id: this.generate_id(),
        text: 'Новый ответ',
        is_right: true
      });
    },
    add_accordance_choice_answer: function add_accordance_choice_answer() {
      this.new_question.accordance_choice_answers.push({
        id: this.generate_id(),
        accord1: 'Новый элемент',
        accord2: 'Новый соответствующий элемент',
        is_right: true
      });
    },
    remove_single_choice_answer: function remove_single_choice_answer(item) {
      if (this.new_question.single_choice_right == item.id) {
        this.new_question.single_choice_right = '';
      }

      var new_arr = this.new_question.single_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.single_choice_answers = new_arr;
    },
    remove_multi_choice_answer: function remove_multi_choice_answer(item) {
      var new_arr = this.new_question.multi_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.multi_choice_answers = new_arr;
      new_arr = this.new_question.multi_choice_right.filter(function (answer) {
        return answer != item.id;
      });
      this.new_question.multi_choice_right = new_arr;
    },
    remove_free_choice_answer: function remove_free_choice_answer(item) {
      var new_arr = this.new_question.free_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.free_choice_answers = new_arr;
    },
    remove_sequence_choice_answer: function remove_sequence_choice_answer(item) {
      var new_arr = this.new_question.sequence_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.sequence_choice_answers = new_arr;
    },
    remove_accordance_choice_answer: function remove_accordance_choice_answer(item) {
      var new_arr = this.new_question.accordance_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.accordance_choice_answers = new_arr;
    }
  },
  mounted: function mounted() {
    self = this;
    axios.get('/dpps/get_question_data/' + this.question_to_edit).then(function (response) {
      self.new_question.knowledge = response.data.knowledge;
      self.new_question.type = response.data.type;
      self.new_question.text = response.data.text;
      console.log(response.data.ans_arr);

      switch (response.data.type.id) {
        case 1:
          self.new_question.single_choice_answers = response.data.ans_arr;
          self.new_question.single_choice_right = response.data.single_choice_right;
          break;

        case 2:
          self.new_question.multi_choice_answers = response.data.ans_arr;
          self.new_question.multi_choice_right = response.data.multi_choice_right;
          break;

        case 3:
          self.new_question.free_choice_answers = response.data.ans_arr;
          break;

        case 4:
          self.new_question.sequence_choice_answers = response.data.ans_arr;
          break;

        case 5:
          self.new_question.accordance_choice_answers = response.data.ans_arr;
          break;

        default:
          break;
      }
    }); //.finally (function (response){ 
    //      axios
    //      .get('/dpps/'+self.stage.dpp_id+'/get_knowledges_to_ov/'+ self.stage.om_version_id)
    //      .then(response => (self.knowledges = response.data))
    //  })
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuedraggable_src_vuedraggable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuedraggable/src/vuedraggable */ "./node_modules/vuedraggable/src/vuedraggable.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.common.js");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_search_select__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: "new-question",
  metaInfo: {
    title: "Добавить новый вопрос"
  },
  components: {
    draggable: vuedraggable_src_vuedraggable__WEBPACK_IMPORTED_MODULE_0__["default"],
    ModelSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_1__["ModelSelect"]
  },
  props: {
    knowledges: Array
  },
  data: function data() {
    return {
      show_errors: false,
      errors: [],
      new_question: {
        knowledge: {},
        text: '',
        text_state: '',
        type: '',
        type_state: '',
        single_choice_answers: [{
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }],
        free_choice_answers: [{
          id: this.generate_id(),
          text: '',
          is_right: true
        }],
        multi_choice_answers: [{
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }, {
          id: this.generate_id(),
          text: '',
          is_right: false
        }],
        sequence_choice_answers: [{
          id: this.generate_id(),
          text: '',
          is_right: true
        }, {
          id: this.generate_id(),
          text: '',
          is_right: true
        }, {
          id: this.generate_id(),
          text: '',
          is_right: true
        }, {
          id: this.generate_id(),
          text: '',
          is_right: true
        }],
        accordance_choice_answers: [{
          id: this.generate_id(),
          accord1: '',
          accord2: '',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: '',
          accord2: '',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: '',
          accord2: '',
          is_right: true
        }, {
          id: this.generate_id(),
          accord1: '',
          accord2: '',
          is_right: true
        }],
        single_choice_fields: [{
          key: 'is_right',
          label: 'Правильный?'
        }, {
          key: 'text',
          label: 'Текст ответа'
        }],
        multi_choice_fields: [{
          key: 'is_right',
          label: 'Правильный?'
        }, {
          key: 'text',
          label: 'Текст ответа'
        }],
        free_choice_fields: [{
          key: 'text',
          label: 'Текст правильного ответа'
        }],
        accordance_choice_fields: [{
          key: 'accord1',
          label: 'Элемент'
        }, {
          key: 'accord2',
          label: 'Соответствующий элемент'
        }, {
          key: 'is_right',
          label: 'Удаление'
        }],
        single_choice_right: '',
        multi_choice_right: []
      },
      type_options: [{
        value: {
          id: 1,
          type_name: 'Выбор одного правильного ответа'
        },
        text: 'Выбор одного правильного ответа'
      }, {
        value: {
          id: 2,
          type_name: 'Выбор нескольких правильных ответов'
        },
        text: 'Выбор нескольких правильных ответов'
      }, {
        value: {
          id: 3,
          type_name: 'Вопрос с открытым ответом'
        },
        text: 'Вопрос с открытым ответом'
      }, {
        value: {
          id: 4,
          type_name: 'Установление последовательности'
        },
        text: 'Установление последовательности'
      }, {
        value: {
          id: 5,
          type_name: 'Установление соответствия'
        },
        text: 'Установление соответствия'
      }]
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];
      this.show_errors = false;

      if (!this.new_question.knowledge.value) {
        this.errors.push("Не выбрано знание, для которого формируется вопрос");
      }

      if (this.new_question.text.length < 5) {
        this.errors.push("Не введен текст вопроса");
      }

      if (!this.new_question.type.id) {
        this.errors.push("Не выбран тип вопроса");
      } else {
        switch (this.new_question.type.id) {
          case 1:
            if (this.new_question.single_choice_right == "") {
              this.errors.push("Не выбран правильный вариант ответа");
            }

            for (var i = 0; i < this.new_question.single_choice_answers.length; i++) {
              if (this.new_question.single_choice_answers[i].text == '') {
                this.errors.push("Не введен один из вариантов ответа");
              }
            }

            break;

          case 2:
            if (this.new_question.multi_choice_right.length < 2) {
              this.errors.push("Для данного типа вопроса необходимо выбрать более одного правильного варианта ответа");
            }

            for (var i = 0; i < this.new_question.multi_choice_answers.length; i++) {
              if (this.new_question.multi_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 3:
            for (var i = 0; i < this.new_question.free_choice_answers.length; i++) {
              if (this.new_question.free_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 4:
            for (var i = 0; i < this.new_question.sequence_choice_answers.length; i++) {
              if (this.new_question.sequence_choice_answers[i].text == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;

          case 5:
            for (var i = 0; i < this.new_question.accordance_choice_answers.length; i++) {
              if (this.new_question.accordance_choice_answers[i].accord1 == '' || this.new_question.accordance_choice_answers[i].accord2 == '') {
                this.errors.push("Не введен вариантов ответа");
              }
            }

            break;
        }
      }

      if (this.errors.length > 0) {
        this.show_errors = true;
      } else {
        this.$emit('add_question', this.new_question);
      }
    },
    generate_id: function generate_id() {
      return "f".concat((~~(Math.random() * 1e8)).toString(16));
    },
    add_single_choice_answer: function add_single_choice_answer() {
      this.new_question.single_choice_answers.push({
        id: this.generate_id(),
        text: '',
        is_right: false
      });
    },
    add_multi_choice_answer: function add_multi_choice_answer() {
      this.new_question.multi_choice_answers.push({
        id: this.generate_id(),
        text: '',
        is_right: false
      });
    },
    add_free_choice_answer: function add_free_choice_answer() {
      this.new_question.free_choice_answers.push({
        id: this.generate_id(),
        text: '',
        is_right: true
      });
    },
    add_sequence_choice_answer: function add_sequence_choice_answer() {
      this.new_question.sequence_choice_answers.push({
        id: this.generate_id(),
        text: '',
        is_right: true
      });
    },
    add_accordance_choice_answer: function add_accordance_choice_answer() {
      this.new_question.accordance_choice_answers.push({
        id: this.generate_id(),
        accord1: '',
        accord2: '',
        is_right: true
      });
    },
    remove_single_choice_answer: function remove_single_choice_answer(item) {
      if (this.new_question.single_choice_right == item.id) {
        this.new_question.single_choice_right = '';
      }

      var new_arr = this.new_question.single_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.single_choice_answers = new_arr;
    },
    remove_multi_choice_answer: function remove_multi_choice_answer(item) {
      var new_arr = this.new_question.multi_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.multi_choice_answers = new_arr;
      new_arr = this.new_question.multi_choice_right.filter(function (answer) {
        return answer != item.id;
      });
      this.new_question.multi_choice_right = new_arr;
    },
    remove_free_choice_answer: function remove_free_choice_answer(item) {
      var new_arr = this.new_question.free_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.free_choice_answers = new_arr;
    },
    remove_sequence_choice_answer: function remove_sequence_choice_answer(item) {
      var new_arr = this.new_question.sequence_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.sequence_choice_answers = new_arr;
    },
    remove_accordance_choice_answer: function remove_accordance_choice_answer(item) {
      var new_arr = this.new_question.accordance_choice_answers.filter(function (answer) {
        return answer.id != item.id;
      });
      this.new_question.accordance_choice_answers = new_arr;
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

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".gu-mirror {\n  position: fixed !important;\n  margin: 0 !important;\n  z-index: 9999 !important;\n  opacity: 0.8;\n  -ms-filter: \"progid:DXImageTransform.Microsoft.Alpha(Opacity=80)\";\n  filter: alpha(opacity=80);\n}\n.gu-hide {\n  display: none !important;\n}\n.gu-unselectable {\n  -webkit-user-select: none !important;\n  -moz-user-select: none !important;\n  -ms-user-select: none !important;\n  user-select: none !important;\n}\n.gu-transit {\n  opacity: 0.2;\n  -ms-filter: \"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)\";\n  filter: alpha(opacity=20);\n}\n", ""]);

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--5-1!../../vue-loader/lib/loaders/stylePostLoader.js!../../postcss-loader/src??ref--5-2!./dragula.css?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&":
/*!**************************************************************************************!*\
  !*** ./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css& ***!
  \**************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../style-loader!../../css-loader??ref--5-1!../../vue-loader/lib/loaders/stylePostLoader.js!../../postcss-loader/src??ref--5-2!./dragula.css?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&");
/* harmony import */ var _style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_style_loader_index_js_css_loader_index_js_ref_5_1_vue_loader_lib_loaders_stylePostLoader_js_postcss_loader_src_index_js_ref_5_2_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6& ***!
  \****************************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c(
      "div",
      [
        _c(
          "b-modal",
          {
            attrs: {
              "no-close-on-esc": "",
              "no-close-on-backdrop": "",
              id: "modal-editquestion",
              "ok-title": "Сохранить",
              "cancel-title": "Закрыть",
              size: "xl",
              title: "Редактирование вопроса"
            },
            on: { ok: _vm.handle_ok }
          },
          [
            _c(
              "b-form-group",
              {
                attrs: {
                  state: _vm.new_question.text_state,
                  label: "Выберите знание",
                  "label-for": "knowledge-input",
                  "invalid-feedback": "Знание",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [_c("p", [_vm._v(_vm._s(_vm.new_question.knowledge.name))])]
            ),
            _vm._v(" "),
            _c(
              "b-form-group",
              {
                attrs: {
                  state: _vm.new_question.type_state,
                  label: "Тип вопроса",
                  "label-for": "type-input",
                  "invalid-feedback": "Необходимо выбрать тип вопроса",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [_c("p", [_vm._v(_vm._s(_vm.new_question.type.name))])]
            ),
            _vm._v(" "),
            _c(
              "b-form-group",
              {
                attrs: {
                  state: _vm.new_question.text_state,
                  label: "Текст вопроса",
                  "label-for": "text-input",
                  "invalid-feedback": "Необходимо ввести текст вопроса",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [
                _c("b-form-textarea", {
                  attrs: {
                    id: "text-input",
                    placeholder: "Введите текст вопроса"
                  },
                  model: {
                    value: _vm.new_question.text,
                    callback: function($$v) {
                      _vm.$set(_vm.new_question, "text", $$v)
                    },
                    expression: "new_question.text"
                  }
                })
              ],
              1
            ),
            _vm._v(" "),
            _vm.new_question.type.id == 1
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите варианты ответов и укажите правильный")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.single_choice_answers,
                        fields: _vm.new_question.single_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "90%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .single_choice_answers
                                                  .length <= 4,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_single_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c("b-form-radio", {
                                  attrs: {
                                    "button-variant": "outline-primary",
                                    value: data.item.id,
                                    name: "radio-size",
                                    size: "lg"
                                  },
                                  model: {
                                    value: _vm.new_question.single_choice_right,
                                    callback: function($$v) {
                                      _vm.$set(
                                        _vm.new_question,
                                        "single_choice_right",
                                        $$v
                                      )
                                    },
                                    expression:
                                      "new_question.single_choice_right"
                                  }
                                })
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        2385344954
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_single_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 2
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите варианты ответов и укажите правильные")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.multi_choice_answers,
                        fields: _vm.new_question.multi_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "90%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .multi_choice_answers
                                                  .length <= 4,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_multi_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c("b-form-checkbox", {
                                  attrs: {
                                    "button-variant": "outline-primary",
                                    value: data.item.id,
                                    name: "multi_check",
                                    size: "lg"
                                  },
                                  model: {
                                    value: _vm.new_question.multi_choice_right,
                                    callback: function($$v) {
                                      _vm.$set(
                                        _vm.new_question,
                                        "multi_choice_right",
                                        $$v
                                      )
                                    },
                                    expression:
                                      "new_question.multi_choice_right"
                                  }
                                })
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        449133122
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_multi_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 3
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите допустимые (правильные) варианты ответов")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.free_choice_answers,
                        fields: _vm.new_question.free_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .free_choice_answers.length ==
                                                1,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_free_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
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
                        526813768
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_free_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 4
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v(
                        "Запишите ответы в правильной последовательности. Вы можете поменять последовательность ответов путем перетаскивания элемента (с помощью иконки "
                      ),
                      _c("i", { staticClass: "ion ion-ios-move m-r-1" }),
                      _vm._v(
                        "). Для обучающихся в момент прохождения теста ответы будут перемешаны."
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "draggable",
                      _vm._b(
                        {
                          staticClass: "sortable-example",
                          attrs: { tag: "div" },
                          model: {
                            value: _vm.new_question.sequence_choice_answers,
                            callback: function($$v) {
                              _vm.$set(
                                _vm.new_question,
                                "sequence_choice_answers",
                                $$v
                              )
                            },
                            expression: "new_question.sequence_choice_answers"
                          }
                        },
                        "draggable",
                        { animation: 150, handle: ".ion" },
                        false
                      ),
                      _vm._l(_vm.new_question.sequence_choice_answers, function(
                        item
                      ) {
                        return _c(
                          "div",
                          {
                            key: item.id,
                            staticStyle: { "margin-bottom": "10px" }
                          },
                          [
                            _c(
                              "b-row",
                              [
                                _c("b-col", { attrs: { sm: "1" } }, [
                                  _c("i", {
                                    staticClass: "ion ion-ios-move m-r-1",
                                    staticStyle: { "font-size": "35px" }
                                  }),
                                  _vm._v("  \n                ")
                                ]),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { sm: "10" } },
                                  [
                                    _c("b-form-input", {
                                      attrs: {
                                        placeholder: "Введите текст ответа"
                                      },
                                      model: {
                                        value: item.text,
                                        callback: function($$v) {
                                          _vm.$set(item, "text", $$v)
                                        },
                                        expression: "item.text"
                                      }
                                    }),
                                    _vm._v("  \n                ")
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { sm: "1" } },
                                  [
                                    _c(
                                      "b-btn",
                                      {
                                        staticClass: "btn",
                                        attrs: {
                                          disabled:
                                            _vm.new_question
                                              .sequence_choice_answers.length <=
                                            2,
                                          variant: "outline-danger  icon-btn"
                                        },
                                        on: {
                                          click: function($event) {
                                            return _vm.remove_sequence_choice_answer(
                                              item
                                            )
                                          }
                                        }
                                      },
                                      [_vm._v("X")]
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
                      0
                    ),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_sequence_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 5
              ? _c(
                  "div",
                  [
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.accordance_choice_answers,
                        fields: _vm.new_question.accordance_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "45%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(accord1)",
                            fn: function(data) {
                              return [
                                _c("b-form-input", {
                                  attrs: {
                                    placeholder: "Введите текст ответа"
                                  },
                                  model: {
                                    value: data.item.accord1,
                                    callback: function($$v) {
                                      _vm.$set(data.item, "accord1", $$v)
                                    },
                                    expression: "data.item.accord1"
                                  }
                                }),
                                _vm._v("  \n            ")
                              ]
                            }
                          },
                          {
                            key: "cell(accord2)",
                            fn: function(data) {
                              return [
                                _c("b-form-input", {
                                  attrs: {
                                    placeholder: "Введите текст ответа"
                                  },
                                  model: {
                                    value: data.item.accord2,
                                    callback: function($$v) {
                                      _vm.$set(data.item, "accord2", $$v)
                                    },
                                    expression: "data.item.accord2"
                                  }
                                }),
                                _vm._v("  \n            ")
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn",
                                    attrs: {
                                      disabled:
                                        _vm.new_question
                                          .accordance_choice_answers.length < 2,
                                      variant: "outline-danger  icon-btn"
                                    },
                                    on: {
                                      click: function($event) {
                                        return _vm.remove_accordance_choice_answer(
                                          data.item
                                        )
                                      }
                                    }
                                  },
                                  [_c("i", { staticClass: "ion ion-md-close" })]
                                )
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        69650684
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_accordance_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.show_errors
              ? _c(
                  "div",
                  [
                    _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
                      _c("strong", [_vm._v("Обнаружены ошибки!")]),
                      _vm._v(" "),
                      _c(
                        "ul",
                        _vm._l(_vm.errors, function(error, index) {
                          return _c("li", { key: index }, [
                            _vm._v(_vm._s(error))
                          ])
                        }),
                        0
                      )
                    ])
                  ],
                  1
                )
              : _vm._e()
          ],
          1
        )
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60& ***!
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
  return _c("div", [
    _c(
      "div",
      [
        _c(
          "b-modal",
          {
            attrs: {
              "no-close-on-esc": "",
              "no-close-on-backdrop": "",
              id: "modal-addquest",
              "ok-title": "Добавить вопрос",
              "cancel-title": "Закрыть",
              size: "xl",
              title: "Создание нового вопроса"
            },
            on: { ok: _vm.handle_ok }
          },
          [
            _c(
              "b-form-group",
              {
                attrs: {
                  state: _vm.new_question.text_state,
                  label: "Выберите знание",
                  "label-for": "knowledge-input",
                  "invalid-feedback": "Знание",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [
                _c("model-select", {
                  attrs: {
                    id: "knowledge-input",
                    options: _vm.knowledges,
                    placeholder: "Выберите знание"
                  },
                  model: {
                    value: _vm.new_question.knowledge,
                    callback: function($$v) {
                      _vm.$set(_vm.new_question, "knowledge", $$v)
                    },
                    expression: "new_question.knowledge"
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
                  state: _vm.new_question.type_state,
                  label: "Тип вопроса",
                  "label-for": "type-input",
                  "invalid-feedback": "Необходимо выбрать тип вопроса",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [
                _c("b-form-select", {
                  attrs: { id: "type-input", options: _vm.type_options },
                  on: {
                    change: function($event) {
                      _vm.show_errors = false
                    }
                  },
                  model: {
                    value: _vm.new_question.type,
                    callback: function($$v) {
                      _vm.$set(_vm.new_question, "type", $$v)
                    },
                    expression: "new_question.type"
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
                  state: _vm.new_question.text_state,
                  label: "Текст вопроса",
                  "label-for": "text-input",
                  "invalid-feedback": "Необходимо ввести текст вопроса",
                  "label-cols-lg": "2",
                  "label-size": "lg"
                }
              },
              [
                _c("b-form-textarea", {
                  attrs: {
                    id: "text-input",
                    placeholder: "Введите текст вопроса"
                  },
                  model: {
                    value: _vm.new_question.text,
                    callback: function($$v) {
                      _vm.$set(_vm.new_question, "text", $$v)
                    },
                    expression: "new_question.text"
                  }
                })
              ],
              1
            ),
            _vm._v(" "),
            _vm.new_question.type.id == 1
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите варианты ответов и укажите правильный")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.single_choice_answers,
                        fields: _vm.new_question.single_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "90%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .single_choice_answers
                                                  .length <= 4,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_single_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c("b-form-radio", {
                                  attrs: {
                                    "button-variant": "outline-primary",
                                    value: data.item.id,
                                    name: "radio-size",
                                    size: "lg"
                                  },
                                  model: {
                                    value: _vm.new_question.single_choice_right,
                                    callback: function($$v) {
                                      _vm.$set(
                                        _vm.new_question,
                                        "single_choice_right",
                                        $$v
                                      )
                                    },
                                    expression:
                                      "new_question.single_choice_right"
                                  }
                                })
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        2385344954
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_single_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 2
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите варианты ответов и укажите правильные")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.multi_choice_answers,
                        fields: _vm.new_question.multi_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "90%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .multi_choice_answers
                                                  .length <= 4,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_multi_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                )
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c("b-form-checkbox", {
                                  attrs: {
                                    "button-variant": "outline-primary",
                                    value: data.item.id,
                                    name: "multi_check",
                                    size: "lg"
                                  },
                                  model: {
                                    value: _vm.new_question.multi_choice_right,
                                    callback: function($$v) {
                                      _vm.$set(
                                        _vm.new_question,
                                        "multi_choice_right",
                                        $$v
                                      )
                                    },
                                    expression:
                                      "new_question.multi_choice_right"
                                  }
                                })
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        449133122
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_multi_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 3
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v("Введите допустимые (правильные) варианты ответов")
                    ]),
                    _vm._v(" "),
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.free_choice_answers,
                        fields: _vm.new_question.free_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "cell(text)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "11" } },
                                      [
                                        _c("b-form-input", {
                                          attrs: {
                                            placeholder: "Введите текст ответа"
                                          },
                                          model: {
                                            value: data.item.text,
                                            callback: function($$v) {
                                              _vm.$set(data.item, "text", $$v)
                                            },
                                            expression: "data.item.text"
                                          }
                                        }),
                                        _vm._v("  \n                ")
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-col",
                                      { attrs: { sm: "1" } },
                                      [
                                        _c(
                                          "b-btn",
                                          {
                                            staticClass: "btn",
                                            attrs: {
                                              disabled:
                                                _vm.new_question
                                                  .free_choice_answers.length ==
                                                1,
                                              variant:
                                                "outline-danger  icon-btn"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.remove_free_choice_answer(
                                                  data.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "ion ion-md-close"
                                            })
                                          ]
                                        )
                                      ],
                                      1
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
                        526813768
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_free_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 4
              ? _c(
                  "div",
                  [
                    _c("p", [
                      _vm._v(
                        "Запишите ответы в правильной последовательности. Вы можете поменять последовательность ответов путем перетаскивания элемента (с помощью иконки "
                      ),
                      _c("i", { staticClass: "ion ion-ios-move m-r-1" }),
                      _vm._v(
                        "). Для обучающихся в момент прохождения теста ответы будут перемешаны."
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "draggable",
                      _vm._b(
                        {
                          staticClass: "sortable-example",
                          attrs: { tag: "div" },
                          model: {
                            value: _vm.new_question.sequence_choice_answers,
                            callback: function($$v) {
                              _vm.$set(
                                _vm.new_question,
                                "sequence_choice_answers",
                                $$v
                              )
                            },
                            expression: "new_question.sequence_choice_answers"
                          }
                        },
                        "draggable",
                        { animation: 150, handle: ".ion" },
                        false
                      ),
                      _vm._l(_vm.new_question.sequence_choice_answers, function(
                        item
                      ) {
                        return _c(
                          "div",
                          {
                            key: item.id,
                            staticStyle: { "margin-bottom": "10px" }
                          },
                          [
                            _c(
                              "b-row",
                              [
                                _c("b-col", { attrs: { sm: "1" } }, [
                                  _c("i", {
                                    staticClass: "ion ion-ios-move m-r-1",
                                    staticStyle: { "font-size": "35px" }
                                  }),
                                  _vm._v("  \n                ")
                                ]),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { sm: "10" } },
                                  [
                                    _c("b-form-input", {
                                      attrs: {
                                        placeholder: "Введите текст ответа"
                                      },
                                      model: {
                                        value: item.text,
                                        callback: function($$v) {
                                          _vm.$set(item, "text", $$v)
                                        },
                                        expression: "item.text"
                                      }
                                    }),
                                    _vm._v("  \n                ")
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { sm: "1" } },
                                  [
                                    _c(
                                      "b-btn",
                                      {
                                        staticClass: "btn",
                                        attrs: {
                                          disabled:
                                            _vm.new_question
                                              .sequence_choice_answers.length <=
                                            2,
                                          variant: "outline-danger  icon-btn"
                                        },
                                        on: {
                                          click: function($event) {
                                            return _vm.remove_sequence_choice_answer(
                                              item
                                            )
                                          }
                                        }
                                      },
                                      [_vm._v("X")]
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
                      0
                    ),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_sequence_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.new_question.type.id == 5
              ? _c(
                  "div",
                  [
                    _c("b-table", {
                      attrs: {
                        bordered: "",
                        items: _vm.new_question.accordance_choice_answers,
                        fields: _vm.new_question.accordance_choice_fields
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "table-colgroup",
                            fn: function(scope) {
                              return _vm._l(scope.fields, function(field) {
                                return _c("col", {
                                  key: field.key,
                                  style: {
                                    width:
                                      field.key === "is_right" ? "10%" : "45%"
                                  }
                                })
                              })
                            }
                          },
                          {
                            key: "cell(accord1)",
                            fn: function(data) {
                              return [
                                _c("b-form-input", {
                                  attrs: {
                                    placeholder: "Введите текст ответа"
                                  },
                                  model: {
                                    value: data.item.accord1,
                                    callback: function($$v) {
                                      _vm.$set(data.item, "accord1", $$v)
                                    },
                                    expression: "data.item.accord1"
                                  }
                                }),
                                _vm._v("  \n            ")
                              ]
                            }
                          },
                          {
                            key: "cell(accord2)",
                            fn: function(data) {
                              return [
                                _c("b-form-input", {
                                  attrs: {
                                    placeholder: "Введите текст ответа"
                                  },
                                  model: {
                                    value: data.item.accord2,
                                    callback: function($$v) {
                                      _vm.$set(data.item, "accord2", $$v)
                                    },
                                    expression: "data.item.accord2"
                                  }
                                }),
                                _vm._v("  \n            ")
                              ]
                            }
                          },
                          {
                            key: "cell(is_right)",
                            fn: function(data) {
                              return [
                                _c(
                                  "b-btn",
                                  {
                                    staticClass: "btn",
                                    attrs: {
                                      disabled:
                                        _vm.new_question
                                          .accordance_choice_answers.length < 2,
                                      variant: "outline-danger  icon-btn"
                                    },
                                    on: {
                                      click: function($event) {
                                        return _vm.remove_accordance_choice_answer(
                                          data.item
                                        )
                                      }
                                    }
                                  },
                                  [_c("i", { staticClass: "ion ion-md-close" })]
                                )
                              ]
                            }
                          }
                        ],
                        null,
                        false,
                        69650684
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary" },
                        on: { click: _vm.add_accordance_choice_answer }
                      },
                      [_vm._v("Добавить ответ")]
                    )
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.show_errors
              ? _c(
                  "div",
                  [
                    _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
                      _c("strong", [_vm._v("Обнаружены ошибки!")]),
                      _vm._v(" "),
                      _c(
                        "ul",
                        _vm._l(_vm.errors, function(error, index) {
                          return _c("li", { key: index }, [
                            _vm._v(_vm._s(error))
                          ])
                        }),
                        0
                      )
                    ])
                  ],
                  1
                )
              : _vm._e()
          ],
          1
        )
      ],
      1
    )
  ])
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

/***/ "./resources/assets/src/components/dpps/EditQuestion.vue":
/*!***************************************************************!*\
  !*** ./resources/assets/src/components/dpps/EditQuestion.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditQuestion.vue?vue&type=template&id=7e78fdc6& */ "./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6&");
/* harmony import */ var _EditQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditQuestion.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var vue_dragula_styles_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css& */ "./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _EditQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/EditQuestion.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditQuestion.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6&":
/*!**********************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditQuestion.vue?vue&type=template&id=7e78fdc6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/EditQuestion.vue?vue&type=template&id=7e78fdc6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditQuestion_vue_vue_type_template_id_7e78fdc6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/dpps/NewQuestion.vue":
/*!**************************************************************!*\
  !*** ./resources/assets/src/components/dpps/NewQuestion.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewQuestion.vue?vue&type=template&id=73864e60& */ "./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60&");
/* harmony import */ var _NewQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewQuestion.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var vue_dragula_styles_dragula_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css& */ "./node_modules/vue-dragula/styles/dragula.css?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _NewQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/NewQuestion.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewQuestion.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewQuestion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60&":
/*!*********************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewQuestion.vue?vue&type=template&id=73864e60& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/NewQuestion.vue?vue&type=template&id=73864e60&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewQuestion_vue_vue_type_template_id_73864e60___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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