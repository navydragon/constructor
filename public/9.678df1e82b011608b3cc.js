(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[9],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-tables-2 */ "./node_modules/vue-tables-2/compiled/index.js");
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_tables_2__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _NewDolgkval__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NewDolgkval */ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue");
/* harmony import */ var _EditDolgkval__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./EditDolgkval */ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'choose-dolgkval',
  metaInfo: {
    title: 'Выбор квалификационных требований по должностям'
  },
  components: {
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_1__["ClientTable"],
    NewDolgkval: _NewDolgkval__WEBPACK_IMPORTED_MODULE_2__["default"],
    EditDolgkval: _EditDolgkval__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  props: {
    dolgkvals: Array
  },
  data: function data() {
    return {
      isBusy: true,
      n_ps: 'dk',
      edit_item: {},
      items: [],
      selected: this.dolgkvals.map(function (x) {
        return x["id"];
      }),
      columns: ['choose', 'name', 'actions'],
      options: {
        // pagination: { chunk: 5 },
        sortable: ['name', 'code'],
        sortIcon: {
          is: 'fa-sort',
          base: 'fas',
          up: 'fa-sort-up',
          down: 'fa-sort-down'
        },
        headings: {
          choose: 'Выбрать',
          name: 'Профессия',
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
          filterBy: "Filter by {column}",
          loading: 'Загрузка...',
          defaultOption: 'Select {column}',
          columns: 'Columns'
        }
      }
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$emit('select_dolgkvals', this.selected);
    },
    create_dolgkval: function create_dolgkval() {
      this.$bvModal.show("modal-new-dolgkval");
    },
    add_dolgkval: function add_dolgkval(data) {
      self = this;
      axios.post('/dolgkvals/add_dolgkval', data).then(function (response) {
        self.items.push(response.data);
        self.n_ps += 1;
      });
    },
    remove_dolgkval: function remove_dolgkval(elem) {
      var self = this;
      this.$bvModal.msgBoxConfirm("Действительно хотите удалить профессию «" + elem.name + "»?").then(function (value) {
        if (value === true) {
          axios.post('/dolgkvals/remove_dolgkval', elem).then(function (response) {
            self.items = self.items.filter(function (item) {
              return item.id != response.data;
            });
          });
        }
      });
    },
    edit_dolgkval: function edit_dolgkval(elem) {
      var _this = this;

      this.edit_item = elem;
      this.$nextTick(function () {
        _this.$bvModal.show("modal-edit-dolgkval");
      });
    },
    update_dolgkval: function update_dolgkval(data) {
      self = this;
      axios.post('/dolgkvals/update_dolgkval', data).then(function (response) {
        var upd_item = self.items.find(function (item) {
          return item.id == response.data.id;
        });
        upd_item.code = response.data.code;
        upd_item.name = response.data.name;
        self.$bvModal.hide("modal-edit-dolgkval");
      })["finally"](function () {
        return self.edit_item = {};
      });
    },
    get_data: function get_data() {
      self = this;
      axios.post('/dolgkvals/get_dolgkvals').then(function (response) {
        self.items = response.data;
      })["finally"](function (response) {
        self.isBusy = false;
        self.$bvModal.show("modal-choose-dk");
      });
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "edit-dolgkval",
  props: {
    edit_item: Object
  },
  data: function data() {
    return {
      new_item: {
        code: '',
        name: ''
      },
      errors: []
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_item.code == '') {
        this.errors.push("Не заполнено поле «Код».");
      }

      if (this.new_item.name == '') {
        this.errors.push("Не заполнено поле «Название профессии».");
      }

      if (this.errors.length == 0) {
        this.$emit('update_dolgkval', this.new_item);
        this.new_item = '';
      }
    }
  },
  mounted: function mounted() {
    self = this;
    axios.post('/dolgkvals/get_dolgkval', this.edit_item).then(function (response) {
      self.new_item = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js& ***!
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "new-dolgkval",
  data: function data() {
    return {
      new_item: {
        code: '',
        name: ''
      },
      errors: []
    };
  },
  methods: {
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_item.name == '') {
        this.errors.push("Не заполнено поле «Название профессии».");
      }

      if (this.errors.length == 0) {
        this.$emit('add_dolgkval', this.new_item);
        this.new_item = '';
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_nsis_Nsis__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/components/nsis/Nsis */ "./resources/assets/src/components/nsis/Nsis.vue");
/* harmony import */ var _components_profstandarts_ChooseProfstandart__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/components/profstandarts/ChooseProfstandart */ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue");
/* harmony import */ var _components_dolgkvals_ChooseDolgkval__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/components/dolgkvals/ChooseDolgkval */ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue");
/* harmony import */ var _components_fgoses_ChooseFgos__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/components/fgoses/ChooseFgos */ "./resources/assets/src/components/fgoses/ChooseFgos.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: "dpp_stage_work_ish",
  components: {
    Nsis: _components_nsis_Nsis__WEBPACK_IMPORTED_MODULE_0__["default"],
    ChooseProfstandart: _components_profstandarts_ChooseProfstandart__WEBPACK_IMPORTED_MODULE_1__["default"],
    ChooseDolgkval: _components_dolgkvals_ChooseDolgkval__WEBPACK_IMPORTED_MODULE_2__["default"],
    ChooseFgos: _components_fgoses_ChooseFgos__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  metaInfo: {
    title: "Разработка ДПП - Исходные данные"
  },
  data: function data() {
    return {
      stage: {},
      show_errors: false,
      errors: [],
      isBusy: true,
      ish_data: {
        req_user_edulevel: '',
        req_user_kval: '',
        target: '',
        tasks: '',
        pl: [],
        nsi_types: [],
        nsis: [],
        typologies: [],
        prof_standarts: [],
        dolg_kvals: [],
        fgoses: [],
        typology: {},
        make_new_competence: ''
      },
      prof_levels_arr: []
    };
  },
  computed: {
    header: function header() {
      return "Разработка ДПП / " + this.stage.dpp_name + " / " + this.stage.type_name;
    }
  },
  methods: {
    get_ish_versions_data: function get_ish_versions_data() {
      var _this = this;

      axios.get('/dpps/' + this.$route.params.dpp + '/get_ish_version_data/' + this.stage.ish_version_id).then(function (response) {
        return _this.ish_data = response.data;
      })["finally"](function () {
        return _this.isBusy = false;
      });
    },
    select_profstandarts: function select_profstandarts(data) {
      var _this2 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/ish_version_data/' + this.stage.ish_version_id + '/select_profstandarts', {
        data: data
      }).then(function (response) {
        return _this2.ish_data.prof_standarts = response.data;
      })["finally"](function () {
        return _this2.$bvModal.hide("modal-choose-ps");
      });
    },
    select_dolgkvals: function select_dolgkvals(data) {
      var _this3 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/ish_version_data/' + this.stage.ish_version_id + '/select_dolgkvals', {
        data: data
      }).then(function (response) {
        return _this3.ish_data.dolg_kvals = response.data;
      })["finally"](function () {
        return _this3.$bvModal.hide("modal-choose-dk");
      });
    },
    select_fgoses: function select_fgoses(data) {
      var _this4 = this;

      axios.post('/dpps/' + this.$route.params.dpp + '/ish_version_data/' + this.stage.ish_version_id + '/select_fgoses', {
        data: data
      }).then(function (response) {
        return _this4.ish_data.fgoses = response.data;
      })["finally"](function () {
        return _this4.$bvModal.hide("modal-choose-fg");
      });
    },
    save: function save() {
      axios.post('/dpps/' + this.$route.params.dpp + '/update_ish_version_data/' + this.stage.ish_version_id, {
        'ish_data': this.ish_data
      });
    },
    go_forward: function go_forward() {
      var _this5 = this;

      this.show_errors = false;
      this.errors = [];

      if (this.ish_data.req_user_kval.length < 10) {
        this.errors.push("Некорректно введены Требования к квалификации");
      }

      if (this.ish_data.pl.length == 0) {
        this.errors.push("Не выбраны требования к уровню профессионального образования");
      }

      if (this.ish_data.typology == null) {
        this.errors.push("Не выбрано типовое содержание ДПП");
      }

      if (this.errors.length > 0) {
        this.show_errors = true;
      } else {
        this.show_errors = false;
        this.save();
        this.$nextTick(function () {
          var self = _this5;
          axios.post('/dpps/' + _this5.$route.params.dpp + '/' + _this5.stage.id + '/go_next').then(function () {
            return _this5.$router.push('/my_dpps/' + _this5.$route.params.dpp + '/overview/1');
          });
        });
      }
    },
    typology: function typology() {
      var _this6 = this;

      var t = this.ish_data.typologies.find(function (el) {
        return el.id == _this6.ish_data.typology;
      });
    }
  },
  mounted: function mounted() {
    var _this7 = this;

    axios.get('/dpps/get_prof_levels').then(function (response) {
      return _this7.prof_levels_arr = response.data;
    });
    axios.get('/dpps/' + this.$route.params.dpp + '/get_stage_data/' + this.$route.params.stage).then(function (response) {
      return _this7.stage = response.data;
    })["finally"](function () {
      return _this7.get_ish_versions_data();
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-tables-2 */ "./node_modules/vue-tables-2/compiled/index.js");
/* harmony import */ var vue_tables_2__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_tables_2__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _NewFgos__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NewFgos */ "./resources/assets/src/components/fgoses/NewFgos.vue");
/* harmony import */ var _EditFgos__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./EditFgos */ "./resources/assets/src/components/fgoses/EditFgos.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'choose-fgos',
  metaInfo: {
    title: 'Выбор ФГОСов'
  },
  props: {
    fgoses: Array
  },
  components: {
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_1__["ClientTable"],
    NewFgos: _NewFgos__WEBPACK_IMPORTED_MODULE_2__["default"],
    EditFgos: _EditFgos__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  data: function data() {
    return {
      isBusy: true,
      n_ps: 'fg',
      edit_item: {},
      selected: this.fgoses.map(function (x) {
        return x["id"];
      }),
      items: [],
      fgos_levels: [],
      columns: ['choose', 'code', 'name', 'level', 'actions'],
      options: {
        // pagination: { chunk: 5 },
        sortable: ['name', 'code', 'level'],
        filterable: ['name', 'code', 'level'],
        filterByColumn: true,
        sortIcon: {
          is: 'fa-sort',
          base: 'fas',
          up: 'fa-sort-up',
          down: 'fa-sort-down'
        },
        headings: {
          choose: 'Выбрать',
          name: 'Направление',
          code: 'Код',
          level: 'Уровень',
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
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$emit('select_fgoses', this.selected);
    },
    create_fgos: function create_fgos() {
      this.$bvModal.show("modal-new-fgos");
    },
    add_fgos: function add_fgos(data) {
      self = this;
      axios.post('/fgoses/add_fgos', data).then(function (response) {
        self.items.push(response.data);
        self.n_ps += 1;
      });
    },
    remove_fgos: function remove_fgos(elem) {
      var self = this;
      this.$bvModal.msgBoxConfirm("Действительно хотите удалить ФГОС «" + elem.name + "»?").then(function (value) {
        if (value === true) {
          axios.post('/fgoses/remove_fgos', elem).then(function (response) {
            self.items = self.items.filter(function (item) {
              return item.id != response.data;
            });
          });
        }
      });
    },
    edit_fgos: function edit_fgos(elem) {
      var _this = this;

      this.edit_item = elem;
      this.$nextTick(function () {
        _this.$bvModal.show("modal-edit-fgos");
      });
    },
    update_fgos: function update_fgos(data) {
      self = this;
      axios.post('/fgoses/update_fgos', data).then(function (response) {
        var upd_item = self.items.find(function (item) {
          return item.id == response.data.id;
        });
        upd_item.code = response.data.code;
        upd_item.name = response.data.name;
        upd_item.level = response.data.level;
        self.$bvModal.hide("modal-edit-fgos");
      })["finally"](function () {
        return self.edit_item = {};
      });
    },
    get_data: function get_data() {
      self = this;
      axios.post('/fgoses/get_fgos_levels').then(function (response) {
        self.fgos_levels = response.data;
      });
      this.$nextTick(function () {
        axios.post('/fgoses/get_fgoses').then(function (response) {
          self.items = response.data;
        })["finally"](function (response) {
          self.isBusy = false;
          self.$bvModal.show("modal-choose-fg");
        });
      });
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewNsi__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewNsi */ "./resources/assets/src/components/nsis/NewNsi.vue");
/* harmony import */ var _EditNsi__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditNsi */ "./resources/assets/src/components/nsis/EditNsi.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'nsis',
  props: {
    ish_version_id: Number,
    mode: String
  },
  components: {
    NewNsi: _NewNsi__WEBPACK_IMPORTED_MODULE_0__["default"],
    EditNsi: _EditNsi__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      nn: 'n',
      types: [],
      nsis: [],
      isBusy: true,
      show_edit_window: false,
      nsi_to_edit: 0
    };
  },
  computed: {
    npas: function npas() {
      return this.nsis.filter(function (nsi) {
        return nsi.type_id == 1;
      });
    },
    ychs: function ychs() {
      return this.nsis.filter(function (nsi) {
        return nsi.type_id == 2;
      });
    },
    irs: function irs() {
      return this.nsis.filter(function (nsi) {
        return nsi.type_id == 3;
      });
    },
    ebss: function ebss() {
      return this.nsis.filter(function (nsi) {
        return nsi.type_id == 4;
      });
    }
  },
  methods: {
    add_nsi: function add_nsi(data) {
      self = this;
      axios.post('/nsis/add_nsi', {
        'nsi_data': data.nsi_data,
        'ish_version_id': this.ish_version_id
      }).then(function (response) {
        alert('Источник добавлен!');
        self.nn++;
        self.nsis.push(response.data);
      });
    },
    update_nsi: function update_nsi(data) {
      axios.post('/nsis/update_nsi', {
        'nsi_data': data.nsi_data
      }).then(function (response) {
        alert('Источник обновлен!');
        self.nsis = self.nsis.filter(function (nsi) {
          return nsi.id != data.nsi_data.id;
        });
        self.nsis.push(response.data);
        self.$bvModal.hide("modal-editnsi");
      });
    },
    remove_nsi: function remove_nsi(id) {
      var _this = this;

      this.$bvModal.msgBoxConfirm('Действительно хотите источник?').then(function (value) {
        if (value == true) {
          axios.post('/nsis/remove_nsi', {
            'nsi_id': id
          }).then(function (response) {
            return _this.nsis = _this.nsis.filter(function (nsi) {
              return nsi.id != response.data;
            });
          });
        }
      });
    },
    edit_nsi: function edit_nsi(id) {
      var _this2 = this;

      this.nsi_to_edit = id;
      this.show_edit_window = true;
      this.$nextTick(function () {
        _this2.$bvModal.show("modal-editnsi");
      });
    }
  },
  mounted: function mounted() {
    var _this3 = this;

    self = this;
    axios.get('/nsis/nsi_types').then(function (response) {
      return _this3.types = response.data;
    });
    axios.get('/nsis/' + self.ish_version_id).then(function (response) {
      return _this3.nsis = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
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
  name: 'choose-profstandart',
  metaInfo: {
    title: 'Выбор профессиональных стандартов'
  },
  components: {
    ClientTable: vue_tables_2__WEBPACK_IMPORTED_MODULE_1__["ClientTable"],
    NewProfstandart: _NewProfstandart__WEBPACK_IMPORTED_MODULE_2__["default"],
    EditProfstandart: _EditProfstandart__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  props: {
    profstandarts: Array
  },
  data: function data() {
    return {
      isBusy: true,
      n_ps: 'ps',
      edit_item: {},
      selected: this.profstandarts.map(function (x) {
        return x["id"];
      }),
      items: [],
      columns: ['choose', 'code', 'name', 'actions'],
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
          choose: 'Выбрать',
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
    handle_ok: function handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$emit('select_profstandarts', this.selected);
    },
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
    },
    get_data: function get_data() {
      self = this;
      axios.post('/profstandarts/get_profstandarts').then(function (response) {
        self.items = response.data;
      })["finally"](function (response) {
        self.isBusy = false;
        self.$bvModal.show("modal-choose-ps");
      });
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6& ***!
  \***********************************************************************************************************************************************************************************************************************************/
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
          on: {
            click: function($event) {
              return _vm.get_data()
            }
          }
        },
        [_vm._v("Выбрать...")]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          attrs: {
            size: "lg",
            id: "modal-choose-dk",
            title: "Выбор профессиональных стандартов",
            "no-close-on-esc": "",
            "no-close-on-backdrop": "",
            "ok-title": "Сохранить",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handle_ok }
        },
        [
          _c(
            "b-button",
            {
              attrs: { variant: "primary" },
              on: {
                click: function($event) {
                  return _vm.create_dolgkval()
                }
              }
            },
            [_vm._v("Добавить профессию ")]
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
                      key: "choose",
                      fn: function(props) {
                        return [
                          _c("b-form-checkbox", {
                            attrs: {
                              "button-variant": "outline-primary",
                              value: props.row.id,
                              name: "multi_check",
                              size: "lg"
                            },
                            model: {
                              value: _vm.selected,
                              callback: function($$v) {
                                _vm.selected = $$v
                              },
                              expression: "selected"
                            }
                          })
                        ]
                      }
                    },
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
                                      return _vm.edit_dolgkval(props.row)
                                    }
                                  }
                                },
                                [_c("i", { staticClass: "ion ion-md-create" })]
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
                  1948454314
                )
              })
            : _vm._e(),
          _vm._v(" "),
          _c("new-dolgkval", {
            key: _vm.n_ps,
            on: { add_dolgkval: _vm.add_dolgkval }
          }),
          _vm._v(" "),
          _c("edit-dolgkval", {
            key: _vm.edit_item.id,
            attrs: { edit_item: _vm.edit_item },
            on: { update_dolgkval: _vm.update_dolgkval }
          })
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
        id: "modal-edit-dolgkval",
        title: "Редактирование параметров",
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
            id: "fieldset-2",
            description: "Пример: Машинист асфальтоукладчика",
            label: "Название профессии",
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc& ***!
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
    "b-modal",
    {
      attrs: {
        size: "lg",
        id: "modal-new-dolgkval",
        title: "Добавить новую профессию",
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
            id: "fieldset-2",
            description: "Пример: Машинист асфальтоукладчика",
            label: "Название профессии",
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
          _c("b-alert", { attrs: { show: "", variant: "info" } }, [
            _vm._v(
              'Заполните предолженные поля формы. Для сохранения данных, нажмите кнопку "Сохранить". Для перехода к следующему этапу нажмите кнопку "Перейти к следующему этапу".\n                '
            ),
            _c("br"),
            _vm._v(" "),
            _c("em", [
              _vm._v(
                "* так как система работает в тестовом режиме, предусматривается возможность заполнения полей формы ЛЮБЫМИ (более 10 символов) данными, без дополнительной проверки"
              )
            ])
          ]),
          _vm._v(" "),
          _c("h4", [_vm._v("ОБЩИЕ ПОЛОЖЕНИЯ")]),
          _vm._v(" "),
          _c("h5", [_vm._v("Нормативные правовые основания разработки")]),
          _vm._v(" "),
          _c("b-alert", { attrs: { show: "", variant: "info" } }, [
            _vm._v(
              "Выберите нормативные документы (ПрофСтандарты, КвалТребования, ФГОСы), на основе которых разрабатывается ДПП."
            )
          ]),
          _vm._v(" "),
          _c("h5", [_vm._v("Программа разработана на основе:")]),
          _vm._v(" "),
          _c(
            "p",
            [
              _c("strong", [
                _vm._v(
                  "Профессиональных стандартов (" +
                    _vm._s(_vm.ish_data.prof_standarts.length) +
                    ")"
                )
              ]),
              _vm._v(" "),
              !_vm.isBusy
                ? _c("choose-profstandart", {
                    attrs: { profstandarts: _vm.ish_data.prof_standarts },
                    on: { select_profstandarts: _vm.select_profstandarts }
                  })
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "ul",
            _vm._l(_vm.ish_data.prof_standarts, function(elem) {
              return _c("li", { key: "ps_" + elem.id }, [
                _vm._v(_vm._s(elem.code) + " - " + _vm._s(elem.name))
              ])
            }),
            0
          ),
          _vm._v(" "),
          _c(
            "p",
            [
              _c("strong", [
                _vm._v(
                  "Установленных квалификационных требований по должностям (" +
                    _vm._s(_vm.ish_data.dolg_kvals.length) +
                    ")"
                )
              ]),
              _vm._v(" "),
              !_vm.isBusy
                ? _c("choose-dolgkval", {
                    attrs: { dolgkvals: _vm.ish_data.dolg_kvals },
                    on: { select_dolgkvals: _vm.select_dolgkvals }
                  })
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "ul",
            _vm._l(_vm.ish_data.dolg_kvals, function(elem) {
              return _c("li", { key: "dk_" + elem.id }, [
                _vm._v(_vm._s(elem.name))
              ])
            }),
            0
          ),
          _vm._v(" "),
          _c(
            "p",
            [
              _c("strong", [
                _vm._v(
                  "Требований федеральных государственных образовательных стандартов (" +
                    _vm._s(_vm.ish_data.fgoses.length) +
                    ")"
                )
              ]),
              _vm._v(" "),
              !_vm.isBusy
                ? _c("choose-fgos", {
                    attrs: { fgoses: _vm.ish_data.fgoses },
                    on: { select_fgoses: _vm.select_fgoses }
                  })
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "ul",
            _vm._l(_vm.ish_data.fgoses, function(elem) {
              return _c("li", { key: "fg_" + elem.id }, [
                _vm._v(_vm._s(elem.code) + " - " + _vm._s(elem.name))
              ])
            }),
            0
          ),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _c("h5", [_vm._v("Требования к обучающимся")]),
          _vm._v(" "),
          _c("h5", [
            _vm._v("Требования к уровню профессионального образования ")
          ]),
          _vm._v(" "),
          _c(
            "b-form-row",
            { staticClass: "m-0" },
            [
              _c(
                "b-form-group",
                [
                  _c("b-form-checkbox-group", {
                    attrs: {
                      state: _vm.ish_data.pl.length > 0,
                      id: "checkbox-group",
                      options: _vm.prof_levels_arr,
                      stacked: "",
                      name: "selectedItems"
                    },
                    model: {
                      value: _vm.ish_data.pl,
                      callback: function($$v) {
                        _vm.$set(_vm.ish_data, "pl", $$v)
                      },
                      expression: "ish_data.pl"
                    }
                  })
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c("h5", [_vm._v("Требования к квалификации")]),
          _vm._v(" "),
          _c(
            "div",
            [
              _c("b-form-textarea", {
                attrs: {
                  id: "req_user_kval",
                  state: _vm.ish_data.req_user_kval.length >= 10,
                  placeholder:
                    "Квалификация – это совокупность профессиональных компетенций и опыта работника, необходимых для осуществления им профессиональной деятельности.",
                  rows: "3"
                },
                model: {
                  value: _vm.ish_data.req_user_kval,
                  callback: function($$v) {
                    _vm.$set(_vm.ish_data, "req_user_kval", $$v)
                  },
                  expression: "ish_data.req_user_kval"
                }
              }),
              _vm._v(" "),
              _c("br")
            ],
            1
          ),
          _vm._v(" "),
          _c("h4", [_vm._v("ЦЕЛЬ И ЗАДАЧИ ОСВОЕНИЯ")]),
          _vm._v(" "),
          _c(
            "div",
            [
              _c("b-alert", { attrs: { show: "" } }, [
                _vm._v(
                  "Укажите, формирует ли ДПП новую компетенцию или совершенствует имеющуюся. Цель и задачи ДПП . "
                )
              ])
            ],
            1
          ),
          _vm._v(" "),
          _c("h5", [_vm._v("Цель освоения")]),
          _vm._v(" "),
          _c("p", { staticClass: "text-justify" }, [
            _vm._v(
              "\n                Целью освоения программы являются совершенствование и (или) получение новой компетенции, необходимой для профессиональной деятельности, и (или) повышение профессионального уровня в рамках имеющейся квалификации в области профессиональной деятельности.\n            "
            )
          ]),
          _vm._v(" "),
          _c("h5", [_vm._v("Задачи освоения")]),
          _vm._v(" "),
          _c("div", [
            _c("p", { staticClass: "text-justify" }, [
              _vm._v("Задачами освоения программы являются:\n                "),
              _c("ul", [
                _c("li", [
                  _vm._v(
                    "приобретение обучающимися знаний, умений и навыков в соответствии с учебным планом и календарным графиком учебного процесса;"
                  )
                ]),
                _vm._v(" "),
                _c("li", [
                  _vm._v(
                    "оценка достижений обучающимися планируемых результатов обучения."
                  )
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c(
            "div",
            [
              _c("h5", [_vm._v("Планируемые результаты освоения")]),
              _vm._v(" "),
              _c("p", { staticClass: "text-justify" }, [
                _vm._v(
                  "\n                    Программа направлена на:\n                "
                )
              ]),
              _vm._v(" "),
              _c(
                "b-form-radio",
                {
                  attrs: { name: "pres-radio", value: "1" },
                  model: {
                    value: _vm.ish_data.make_new_competence,
                    callback: function($$v) {
                      _vm.$set(_vm.ish_data, "make_new_competence", $$v)
                    },
                    expression: "ish_data.make_new_competence"
                  }
                },
                [
                  _vm._v(
                    "Получение у обучающихся новой компетенции, необходимой для профессиональной деятельности"
                  )
                ]
              ),
              _vm._v(" "),
              _c(
                "b-form-radio",
                {
                  attrs: { name: "pres-radio", value: "0" },
                  model: {
                    value: _vm.ish_data.make_new_competence,
                    callback: function($$v) {
                      _vm.$set(_vm.ish_data, "make_new_competence", $$v)
                    },
                    expression: "ish_data.make_new_competence"
                  }
                },
                [
                  _vm._v(
                    "Совершенствование компетенции, необходимой для профессиональной деятельности и (или) повышение профессионального уровня в рамках имеющейся квалификации"
                  )
                ]
              )
            ],
            1
          ),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _c("h4", [_vm._v("ТИПОВАЯ СТРУКТУРА ДПП")]),
          _vm._v(" "),
          _c(
            "div",
            [
              _c("b-alert", { attrs: { show: "" } }, [
                _vm._v(
                  "Выберите наиболее подходящую типовую структуру ДПП. Типовая структура состоит из разделов, которых слудеует придерживаться во время разработки ДПП."
                )
              ])
            ],
            1
          ),
          _vm._v(" "),
          !_vm.isBusy
            ? _c("div", { staticClass: "row" }, [
                _c(
                  "div",
                  { staticClass: "col-md-6" },
                  [
                    _c("h5", [_vm._v("Виды типовых структур")]),
                    _vm._v(" "),
                    _vm._l(_vm.ish_data.typologies, function(elem) {
                      return _c(
                        "b-form-radio",
                        {
                          key: elem.id,
                          attrs: { name: "some-radios", value: elem.id },
                          model: {
                            value: _vm.ish_data.typology,
                            callback: function($$v) {
                              _vm.$set(_vm.ish_data, "typology", $$v)
                            },
                            expression: "ish_data.typology"
                          }
                        },
                        [_vm._v(_vm._s(elem.name))]
                      )
                    })
                  ],
                  2
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-md-6" },
                  _vm._l(_vm.ish_data.typologies, function(elem) {
                    return _c("div", { key: elem.id }, [
                      _vm.ish_data.typology == elem.id
                        ? _c("div", [
                            _c("h5", [
                              _vm._v(
                                "Типовые разделы ДПП (" +
                                  _vm._s(elem.name) +
                                  ")"
                              )
                            ]),
                            _vm._v(" "),
                            _c(
                              "ul",
                              _vm._l(elem.parts, function(part, index) {
                                return _c("li", { key: index }, [
                                  _vm._v(_vm._s(part.name))
                                ])
                              }),
                              0
                            )
                          ])
                        : _vm._e()
                    ])
                  }),
                  0
                )
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _c("h4", [_vm._v("НОРМАТИВНО-СПРАВОЧНАЯ ИНФОРМАЦИЯ ")]),
          _vm._v(" "),
          _c(
            "div",
            [
              _c("b-alert", { attrs: { show: "" } }, [
                _vm._v(
                  "Добавьте названия источников НСИ, которые будут использованы в ДПП. Вы также сможете дополнить данный список на последующих этапах разработки ДПП."
                )
              ])
            ],
            1
          ),
          _vm._v(" "),
          !_vm.isBusy
            ? _c("nsis", {
                attrs: { ish_version_id: _vm.stage.ish_version_id }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("hr"),
          _vm._v(" "),
          _vm.show_errors
            ? _c(
                "b-alert",
                { attrs: { show: "", variant: "danger" } },
                [
                  _c("strong", [_vm._v("Обнаружены ошибки!")]),
                  _vm._v(" "),
                  _vm._l(_vm.errors, function(error, index) {
                    return _c("ul", { key: index }, [
                      _c("li", [_vm._v(_vm._s(error))])
                    ])
                  })
                ],
                2
              )
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-button",
                  {
                    attrs: { block: "", variant: "primary" },
                    on: {
                      click: function($event) {
                        return _vm.save()
                      }
                    }
                  },
                  [_vm._v("Сохранить")]
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6" },
              [
                _c(
                  "b-button",
                  {
                    attrs: { block: "", variant: "success" },
                    on: {
                      click: function($event) {
                        return _vm.go_forward()
                      }
                    }
                  },
                  [_vm._v("Перейти к следующему этапу")]
                )
              ],
              1
            )
          ])
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e& ***!
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
  return _c(
    "div",
    [
      _c(
        "b-button",
        {
          on: {
            click: function($event) {
              return _vm.get_data()
            }
          }
        },
        [_vm._v("Выбрать...")]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          attrs: {
            size: "lg",
            id: "modal-choose-fg",
            title: "Выбор ФГОСов",
            "no-close-on-esc": "",
            "no-close-on-backdrop": "",
            "ok-title": "Сохранить",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handle_ok }
        },
        [
          _c(
            "b-button",
            {
              attrs: { variant: "primary" },
              on: {
                click: function($event) {
                  return _vm.create_fgos()
                }
              }
            },
            [_vm._v("Добавить ФГОС ")]
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
                      key: "choose",
                      fn: function(props) {
                        return [
                          _c("b-form-checkbox", {
                            attrs: {
                              "button-variant": "outline-primary",
                              value: props.row.id,
                              name: "multi_check",
                              size: "lg"
                            },
                            model: {
                              value: _vm.selected,
                              callback: function($$v) {
                                _vm.selected = $$v
                              },
                              expression: "selected"
                            }
                          })
                        ]
                      }
                    },
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
                                      return _vm.edit_fgos(props.row)
                                    }
                                  }
                                },
                                [_c("i", { staticClass: "ion ion-md-create" })]
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
                  2282853831
                )
              })
            : _vm._e(),
          _vm._v(" "),
          _c("new-fgos", {
            key: _vm.n_ps,
            attrs: { fgos_levels: _vm.fgos_levels },
            on: { add_fgos: _vm.add_fgos }
          }),
          _vm._v(" "),
          _c("edit-fgos", {
            key: _vm.edit_item.id,
            attrs: { fgos_levels: _vm.fgos_levels, edit_item: _vm.edit_item },
            on: { update_fgos: _vm.update_fgos }
          })
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876& ***!
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
    { staticClass: "row" },
    [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          !_vm.mode
            ? _c(
                "div",
                [
                  _c("new-nsi", {
                    key: _vm.nn,
                    attrs: { types: _vm.types },
                    on: { add_nsi: _vm.add_nsi }
                  }),
                  _vm._v(" "),
                  _c("hr")
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c("h5", [
            _vm._v("Текущие источники (" + _vm._s(_vm.nsis.length) + ")")
          ]),
          _vm._v(" "),
          _vm._l(_vm.types, function(type) {
            return _c(
              "div",
              { key: "t" + type.id },
              [
                _c("h5", [
                  _vm._v(
                    _vm._s(type.name) +
                      " (" +
                      _vm._s(
                        _vm.nsis.filter(function(nsi) {
                          return nsi.type_id == type.id
                        }).length
                      ) +
                      ")"
                  )
                ]),
                _vm._v(" "),
                _c(
                  "b-list-group",
                  _vm._l(
                    _vm.nsis.filter(function(nsi) {
                      return nsi.type_id == type.id
                    }),
                    function(nsi) {
                      return _c(
                        "b-list-group-item",
                        { key: nsi.id },
                        [
                          _c(
                            "b-btn",
                            {
                              staticClass: "btn",
                              attrs: {
                                variant: "outline-primary icon-btn btn-xs"
                              },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  return _vm.edit_nsi(nsi.id)
                                }
                              }
                            },
                            [_c("i", { staticClass: "ion ion-md-create" })]
                          ),
                          _vm._v(" "),
                          !_vm.mode
                            ? _c(
                                "b-btn",
                                {
                                  staticClass: "btn",
                                  attrs: {
                                    variant: "outline-danger icon-btn btn-xs"
                                  },
                                  on: {
                                    click: function($event) {
                                      return _vm.remove_nsi(nsi.id)
                                    }
                                  }
                                },
                                [_vm._v("X")]
                              )
                            : _vm._e(),
                          _vm._v(
                            "\n               " +
                              _vm._s(nsi.name) +
                              "\n            "
                          )
                        ],
                        1
                      )
                    }
                  ),
                  1
                ),
                _vm._v(" "),
                _c("br")
              ],
              1
            )
          })
        ],
        2
      ),
      _vm._v(" "),
      _vm.show_edit_window
        ? _c("edit-nsi", {
            key: _vm.nsi_to_edit,
            attrs: { nsi_id: _vm.nsi_to_edit, types: _vm.types },
            on: { update_nsi: _vm.update_nsi }
          })
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
          on: {
            click: function($event) {
              return _vm.get_data()
            }
          }
        },
        [_vm._v("Выбрать...")]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          attrs: {
            size: "lg",
            id: "modal-choose-ps",
            title: "Выбор профессиональных стандартов",
            "no-close-on-esc": "",
            "no-close-on-backdrop": "",
            "ok-title": "Сохранить",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handle_ok }
        },
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
          _c("v-client-table", {
            attrs: {
              data: _vm.items,
              columns: _vm.columns,
              options: _vm.options
            },
            scopedSlots: _vm._u([
              {
                key: "choose",
                fn: function(props) {
                  return [
                    _c("b-form-checkbox", {
                      attrs: {
                        "button-variant": "outline-primary",
                        value: props.row.id,
                        name: "multi_check",
                        size: "lg"
                      },
                      model: {
                        value: _vm.selected,
                        callback: function($$v) {
                          _vm.selected = $$v
                        },
                        expression: "selected"
                      }
                    })
                  ]
                }
              },
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
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue":
/*!**********************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChooseDolgkval.vue?vue&type=template&id=00bdb4f6& */ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6&");
/* harmony import */ var _ChooseDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChooseDolgkval.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vendor_libs_vue_data_tables_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ChooseDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dolgkvals/ChooseDolgkval.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseDolgkval.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6&":
/*!*****************************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6& ***!
  \*****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseDolgkval.vue?vue&type=template&id=00bdb4f6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/ChooseDolgkval.vue?vue&type=template&id=00bdb4f6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseDolgkval_vue_vue_type_template_id_00bdb4f6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue":
/*!********************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/EditDolgkval.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditDolgkval.vue?vue&type=template&id=55168778& */ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778&");
/* harmony import */ var _EditDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditDolgkval.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EditDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__["render"],
  _EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dolgkvals/EditDolgkval.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditDolgkval.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778&":
/*!***************************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditDolgkval.vue?vue&type=template&id=55168778& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/EditDolgkval.vue?vue&type=template&id=55168778&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDolgkval_vue_vue_type_template_id_55168778___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue":
/*!*******************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/NewDolgkval.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewDolgkval.vue?vue&type=template&id=8c04e4cc& */ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc&");
/* harmony import */ var _NewDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewDolgkval.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NewDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dolgkvals/NewDolgkval.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewDolgkval.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewDolgkval_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc&":
/*!**************************************************************************************************!*\
  !*** ./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewDolgkval.vue?vue&type=template&id=8c04e4cc& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dolgkvals/NewDolgkval.vue?vue&type=template&id=8c04e4cc&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewDolgkval_vue_vue_type_template_id_8c04e4cc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkIsh.vue":
/*!******************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkIsh.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DppStageWorkIsh.vue?vue&type=template&id=5037d78d& */ "./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d&");
/* harmony import */ var _DppStageWorkIsh_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DppStageWorkIsh.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DppStageWorkIsh_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/dpps/DppStageWorkIsh.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkIsh_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkIsh.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkIsh_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d&":
/*!*************************************************************************************************!*\
  !*** ./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./DppStageWorkIsh.vue?vue&type=template&id=5037d78d& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/dpps/DppStageWorkIsh.vue?vue&type=template&id=5037d78d&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DppStageWorkIsh_vue_vue_type_template_id_5037d78d___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/fgoses/ChooseFgos.vue":
/*!***************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/ChooseFgos.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChooseFgos.vue?vue&type=template&id=779cb20e& */ "./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e&");
/* harmony import */ var _ChooseFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChooseFgos.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vendor_libs_vue_data_tables_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ChooseFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/fgoses/ChooseFgos.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseFgos.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseFgos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e&":
/*!**********************************************************************************************!*\
  !*** ./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseFgos.vue?vue&type=template&id=779cb20e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/fgoses/ChooseFgos.vue?vue&type=template&id=779cb20e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseFgos_vue_vue_type_template_id_779cb20e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/nsis/Nsis.vue":
/*!*******************************************************!*\
  !*** ./resources/assets/src/components/nsis/Nsis.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Nsis.vue?vue&type=template&id=7c4e8876& */ "./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876&");
/* harmony import */ var _Nsis_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Nsis.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Nsis_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/nsis/Nsis.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Nsis_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Nsis.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/Nsis.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Nsis_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876&":
/*!**************************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Nsis.vue?vue&type=template&id=7c4e8876& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/Nsis.vue?vue&type=template&id=7c4e8876&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nsis_vue_vue_type_template_id_7c4e8876___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue":
/*!******************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/ChooseProfstandart.vue ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChooseProfstandart.vue?vue&type=template&id=c28f65ce& */ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce&");
/* harmony import */ var _ChooseProfstandart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChooseProfstandart.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vendor_libs_vue_data_tables_vue_data_tables_scss_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss& */ "./resources/assets/src/vendor/libs/vue-data-tables/vue-data-tables.scss?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ChooseProfstandart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/profstandarts/ChooseProfstandart.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseProfstandart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseProfstandart.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseProfstandart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce&":
/*!*************************************************************************************************************!*\
  !*** ./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChooseProfstandart.vue?vue&type=template&id=c28f65ce& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/profstandarts/ChooseProfstandart.vue?vue&type=template&id=c28f65ce&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChooseProfstandart_vue_vue_type_template_id_c28f65ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);