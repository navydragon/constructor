(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'edit-nsi',
  props: {
    types: Array,
    nsi_id: Number
  },
  data: function data() {
    return {
      new_nsi: {
        type_id: '',
        name: '',
        fullname: '',
        old_name: '',
        start_date: '',
        accept_date: '',
        accept_number: '',
        accept_odm: '',
        odm_number: '',
        npa_type: '',
        city: '',
        year: '',
        pages: '',
        authors: '',
        assistants: '',
        note: '',
        output: '',
        url: ''
      },
      errors: []
    };
  },
  methods: {
    handleOk: function handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_nsi.type_id.length == 0) {
        this.errors.push("Не выбран тип источника");
      }

      if (this.new_nsi.name.length == 0) {
        this.errors.push("Не введено название");
      }

      if (this.errors.length == 0) {
        this.$emit('update_nsi', {
          nsi_data: this.new_nsi
        });
      }
    }
  },
  mounted: function mounted() {
    var _this = this;

    axios.get('/nsis/get_nsi/' + this.nsi_id).then(function (response) {
      return _this.new_nsi = response.data;
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'new-nsi',
  props: {
    types: Array
  },
  data: function data() {
    return {
      new_nsi: {
        type: '',
        name: '',
        fullname: '',
        old_name: '',
        start_date: '',
        accept_date: '',
        accept_number: '',
        accept_odm: '',
        odm_number: '',
        npa_type: '',
        city: '',
        year: '',
        pages: '',
        authors: '',
        assistants: '',
        note: '',
        output: '',
        url: ''
      },
      errors: []
    };
  },
  methods: {
    handleOk: function handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.errors = [];

      if (this.new_nsi.type.length == 0) {
        this.errors.push("Не выбран тип источника");
      }

      if (this.new_nsi.name.length == 0) {
        this.errors.push("Не введено название");
      }

      if (this.errors.length == 0) {
        this.$emit('add_nsi', {
          nsi_data: this.new_nsi
        });
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
        "b-modal",
        {
          attrs: {
            size: "xl",
            id: "modal-editnsi",
            title: "Редактирование источника НСИ",
            "ok-title": "Сохранить",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handleOk }
        },
        [
          _c(
            "div",
            [
              _c(
                "b-form-group",
                {
                  attrs: {
                    "label-size": "lg",
                    "label-cols": "2",
                    description: "Выберите тип источника",
                    label: "Тип источника *",
                    "label-for": "nsi_type"
                  }
                },
                [
                  _c("b-form-select", {
                    attrs: {
                      id: "nsi_type",
                      options: _vm.types,
                      "value-field": "id",
                      "text-field": "name"
                    },
                    model: {
                      value: _vm.new_nsi.type_id,
                      callback: function($$v) {
                        _vm.$set(_vm.new_nsi, "type_id", $$v)
                      },
                      expression: "new_nsi.type_id"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _vm.new_nsi.type_id == 1
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: ГОСТ 7.53 – 2001. Издания. Международная стандартная нумерация книг",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Взамен ГОСТ 7.53 – 86",
                            label: "Ранее действовавший документ, если есть",
                            "label-for": "old_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "old_name" },
                            model: {
                              value: _vm.new_nsi.old_name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "old_name", $$v)
                              },
                              expression: "new_nsi.old_name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Введ. 2002 – 07 – 01",
                            label: "Когда введен документ",
                            "label-for": "start_date"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "start_date" },
                            model: {
                              value: _vm.new_nsi.start_date,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "start_date", $$v)
                              },
                              expression: "new_nsi.start_date"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3",
                            label: "Кол-во страниц",
                            "label-for": "pages"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "pages" },
                            model: {
                              value: _vm.new_nsi.pages,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "pages", $$v)
                              },
                              expression: "new_nsi.pages"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 2
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Основы надежности строительных машин",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3-е изд., перераб. и доп.",
                            label: "Издание",
                            "label-for": "edition"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "edition" },
                            model: {
                              value: _vm.new_nsi.edition,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "edition", $$v)
                              },
                              expression: "new_nsi.edition"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др.",
                            label: "Автор(-ы)",
                            "label-for": "nsi_authors"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_authors" },
                            model: {
                              value: _vm.new_nsi.authors,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "authors", $$v)
                              },
                              expression: "new_nsi.authors"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: пер. с анг. И.Ю.Багровой и Р.З. Пановой, науч. ред. Л.М. Иньковой",
                            label: "Редакторы, составители, переводчики",
                            "label-for": "assistants"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "assistants" },
                            model: {
                              value: _vm.new_nsi.assistants,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "assistants", $$v)
                              },
                              expression: "new_nsi.assistants"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3",
                            label: "Кол-во страниц",
                            "label-for": "pages"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "pages" },
                            model: {
                              value: _vm.new_nsi.pages,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "pages", $$v)
                              },
                              expression: "new_nsi.pages"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 4
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Лань",
                            label: "Название библиотечной системы *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "",
                            label: "Адрес сайта, URL",
                            "label-for": "nsi_url"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_url" },
                            model: {
                              value: _vm.new_nsi.url,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "url", $$v)
                              },
                              expression: "new_nsi.url"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 5
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: О воинской обязанности и военной службе",
                            label: "Полное название нормативного акта *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: федеральный закон",
                            label: "Тип нормативного документа",
                            "label-for": "npa_type"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "npa_type" },
                            model: {
                              value: _vm.new_nsi.npa_type,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "npa_type", $$v)
                              },
                              expression: "new_nsi.npa_type"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2872-1",
                            label: "Номер нормативного акта",
                            "label-for": "accept_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_number" },
                            model: {
                              value: _vm.new_nsi.accept_number,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_number", $$v)
                              },
                              expression: "new_nsi.accept_number"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 29.05.1992",
                            label: "Дата принятия/утверждения",
                            "label-for": "accept_date"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_date" },
                            model: {
                              value: _vm.new_nsi.accept_date,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_date", $$v)
                              },
                              expression: "new_nsi.accept_date"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label:
                              "Где опубликован документ (или издательство)",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город публикации (издания)",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год публикации (издания)",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 6
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Рекомендации по разработке и применению документов технического регулирования в сфере дорожного хозяйства",
                            label: "Название ОДМ *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 218.1.001-2010",
                            label: "Номер документа",
                            "label-for": "odm_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "odm_number" },
                            model: {
                              value: _vm.new_nsi.odm_number,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "odm_number", $$v)
                              },
                              expression: "new_nsi.odm_number"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: утв. распоряжением Федерального дорожного агентства от 9 июня 2010 г. N 384-р",
                            label: "Утверждение документа",
                            "label-for": "accept_odm"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_odm" },
                            model: {
                              value: _vm.new_nsi.accept_odm,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_odm", $$v)
                              },
                              expression: "new_nsi.accept_odm"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label:
                              "Где опубликован документ (или издательство)",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город публикации (издания)",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год публикации (издания)",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 3
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Название книги, статьи и тд.",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "",
                            label: "Адрес сайта, URL",
                            "label-for": "nsi_url"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_url" },
                            model: {
                              value: _vm.new_nsi.url,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "url", $$v)
                              },
                              expression: "new_nsi.url"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др.",
                            label: "Автор(-ы)",
                            "label-for": "nsi_authors"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_authors" },
                            model: {
                              value: _vm.new_nsi.authors,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "authors", $$v)
                              },
                              expression: "new_nsi.authors"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Электрон. версия печ. публикации",
                            label: "Примечание",
                            "label-for": "note"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "note" },
                            model: {
                              value: _vm.new_nsi.note,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "note", $$v)
                              },
                              expression: "new_nsi.note"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type_id == 8
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Введите название источника",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Введите полное описание в соответствии с ГОСТ 7.1-2003",
                            label: "Полное библиографическое описание",
                            "label-for": "fullname"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "fullname" },
                            model: {
                              value: _vm.new_nsi.fullname,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "fullname", $$v)
                              },
                              expression: "new_nsi.fullname"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.errors.length > 0
                ? _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
                    _vm._v("Обнаружены ошибки:\n            "),
                    _c(
                      "ul",
                      _vm._l(_vm.errors, function(error) {
                        return _c("li", { key: error }, [_vm._v(_vm._s(error))])
                      }),
                      0
                    )
                  ])
                : _vm._e()
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
          directives: [
            {
              name: "b-modal",
              rawName: "v-b-modal.modal-1",
              modifiers: { "modal-1": true }
            }
          ],
          attrs: { variant: "primary" }
        },
        [_vm._v("Добавить источник")]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          attrs: {
            size: "xl",
            id: "modal-1",
            title: "Добавить новый источник НСИ",
            "ok-title": "Добавить",
            "cancel-title": "Закрыть"
          },
          on: { ok: _vm.handleOk }
        },
        [
          _c(
            "div",
            [
              _c(
                "b-form-group",
                {
                  attrs: {
                    "label-size": "lg",
                    "label-cols": "2",
                    description: "Выберите тип источника",
                    label: "Тип источника *",
                    "label-for": "nsi_type"
                  }
                },
                [
                  _c("b-form-select", {
                    attrs: {
                      id: "nsi_type",
                      options: _vm.types,
                      "value-field": "id",
                      "text-field": "name"
                    },
                    model: {
                      value: _vm.new_nsi.type,
                      callback: function($$v) {
                        _vm.$set(_vm.new_nsi, "type", $$v)
                      },
                      expression: "new_nsi.type"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _vm.new_nsi.type == 1
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: ГОСТ 7.53 – 2001. Издания. Международная стандартная нумерация книг",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Взамен ГОСТ 7.53 – 86",
                            label: "Ранее действовавший документ, если есть",
                            "label-for": "old_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "old_name" },
                            model: {
                              value: _vm.new_nsi.old_name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "old_name", $$v)
                              },
                              expression: "new_nsi.old_name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Введ. 2002 – 07 – 01",
                            label: "Когда введен документ",
                            "label-for": "start_date"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "start_date" },
                            model: {
                              value: _vm.new_nsi.start_date,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "start_date", $$v)
                              },
                              expression: "new_nsi.start_date"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3",
                            label: "Кол-во страниц",
                            "label-for": "pages"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "pages" },
                            model: {
                              value: _vm.new_nsi.pages,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "pages", $$v)
                              },
                              expression: "new_nsi.pages"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 2
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Основы надежности строительных машин",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3-е изд., перераб. и доп.",
                            label: "Издание",
                            "label-for": "edition"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "edition" },
                            model: {
                              value: _vm.new_nsi.edition,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "edition", $$v)
                              },
                              expression: "new_nsi.edition"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др.",
                            label: "Автор(-ы)",
                            "label-for": "nsi_authors"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_authors" },
                            model: {
                              value: _vm.new_nsi.authors,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "authors", $$v)
                              },
                              expression: "new_nsi.authors"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: пер. с анг. И.Ю.Багровой и Р.З. Пановой, науч. ред. Л.М. Иньковой",
                            label: "Редакторы, составители, переводчики",
                            "label-for": "assistants"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "assistants" },
                            model: {
                              value: _vm.new_nsi.assistants,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "assistants", $$v)
                              },
                              expression: "new_nsi.assistants"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 3",
                            label: "Кол-во страниц",
                            "label-for": "pages"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "pages" },
                            model: {
                              value: _vm.new_nsi.pages,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "pages", $$v)
                              },
                              expression: "new_nsi.pages"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 4
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Лань",
                            label: "Название библиотечной системы *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "",
                            label: "Адрес сайта, URL",
                            "label-for": "nsi_url"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_url" },
                            model: {
                              value: _vm.new_nsi.url,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "url", $$v)
                              },
                              expression: "new_nsi.url"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 5
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: О воинской обязанности и военной службе",
                            label: "Полное название нормативного акта *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: федеральный закон",
                            label: "Тип нормативного документа",
                            "label-for": "npa_type"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "npa_type" },
                            model: {
                              value: _vm.new_nsi.npa_type,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "npa_type", $$v)
                              },
                              expression: "new_nsi.npa_type"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2872-1",
                            label: "Номер нормативного акта",
                            "label-for": "accept_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_number" },
                            model: {
                              value: _vm.new_nsi.accept_number,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_number", $$v)
                              },
                              expression: "new_nsi.accept_number"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 29.05.1992",
                            label: "Дата принятия/утверждения",
                            "label-for": "accept_date"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_date" },
                            model: {
                              value: _vm.new_nsi.accept_date,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_date", $$v)
                              },
                              expression: "new_nsi.accept_date"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label:
                              "Где опубликован документ (или издательство)",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город публикации (издания)",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год публикации (издания)",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 6
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Рекомендации по разработке и применению документов технического регулирования в сфере дорожного хозяйства",
                            label: "Название ОДМ *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 218.1.001-2010",
                            label: "Номер документа",
                            "label-for": "odm_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "odm_number" },
                            model: {
                              value: _vm.new_nsi.odm_number,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "odm_number", $$v)
                              },
                              expression: "new_nsi.odm_number"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: утв. распоряжением Федерального дорожного агентства от 9 июня 2010 г. N 384-р",
                            label: "Утверждение документа",
                            "label-for": "accept_odm"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "accept_odm" },
                            model: {
                              value: _vm.new_nsi.accept_odm,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "accept_odm", $$v)
                              },
                              expression: "new_nsi.accept_odm"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label:
                              "Где опубликован документ (или издательство)",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город публикации (издания)",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год публикации (издания)",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 3
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Название книги, статьи и тд.",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "",
                            label: "Адрес сайта, URL",
                            "label-for": "nsi_url"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_url" },
                            model: {
                              value: _vm.new_nsi.url,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "url", $$v)
                              },
                              expression: "new_nsi.url"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример (если авторов более 4, добавьте «и др.»): Л.П. Краснова, Н.Т. Шалашова, Н.М. Ярцева, Н.П. Гордина, и др.",
                            label: "Автор(-ы)",
                            "label-for": "nsi_authors"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_authors" },
                            model: {
                              value: _vm.new_nsi.authors,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "authors", $$v)
                              },
                              expression: "new_nsi.authors"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Пример: Электрон. версия печ. публикации",
                            label: "Примечание",
                            "label-for": "note"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "note" },
                            model: {
                              value: _vm.new_nsi.note,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "note", $$v)
                              },
                              expression: "new_nsi.note"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Эксмо",
                            label: "Издательство",
                            "label-for": "publishing"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "publishing" },
                            model: {
                              value: _vm.new_nsi.publishing,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "publishing", $$v)
                              },
                              expression: "new_nsi.publishing"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: Москва",
                            label: "Город издания",
                            "label-for": "city"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "city" },
                            model: {
                              value: _vm.new_nsi.city,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "city", $$v)
                              },
                              expression: "new_nsi.city"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Пример: 2002",
                            label: "Год издания",
                            "label-for": "year"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "year" },
                            model: {
                              value: _vm.new_nsi.year,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "year", $$v)
                              },
                              expression: "new_nsi.year"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.new_nsi.type == 8
                ? _c(
                    "div",
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            "label-size": "lg",
                            "label-cols": "2",
                            description: "Введите название источника",
                            label: "Название *",
                            "label-for": "nsi_name"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "nsi_name" },
                            model: {
                              value: _vm.new_nsi.name,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "name", $$v)
                              },
                              expression: "new_nsi.name"
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
                            "label-size": "lg",
                            "label-cols": "2",
                            description:
                              "Введите полное описание в соответствии с ГОСТ 7.1-2003",
                            label: "Полное библиографическое описание",
                            "label-for": "fullname"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: { id: "fullname" },
                            model: {
                              value: _vm.new_nsi.fullname,
                              callback: function($$v) {
                                _vm.$set(_vm.new_nsi, "fullname", $$v)
                              },
                              expression: "new_nsi.fullname"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.errors.length > 0
                ? _c("b-alert", { attrs: { show: "", variant: "danger" } }, [
                    _vm._v("Обнаружены ошибки:\n            "),
                    _c(
                      "ul",
                      _vm._l(_vm.errors, function(error) {
                        return _c("li", { key: error }, [_vm._v(_vm._s(error))])
                      }),
                      0
                    )
                  ])
                : _vm._e()
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

/***/ "./resources/assets/src/components/nsis/EditNsi.vue":
/*!**********************************************************!*\
  !*** ./resources/assets/src/components/nsis/EditNsi.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditNsi.vue?vue&type=template&id=d2167a98& */ "./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98&");
/* harmony import */ var _EditNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditNsi.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EditNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__["render"],
  _EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/nsis/EditNsi.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditNsi.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98&":
/*!*****************************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditNsi.vue?vue&type=template&id=d2167a98& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/EditNsi.vue?vue&type=template&id=d2167a98&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditNsi_vue_vue_type_template_id_d2167a98___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/assets/src/components/nsis/NewNsi.vue":
/*!*********************************************************!*\
  !*** ./resources/assets/src/components/nsis/NewNsi.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewNsi.vue?vue&type=template&id=10cbe99a& */ "./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a&");
/* harmony import */ var _NewNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewNsi.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NewNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/nsis/NewNsi.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewNsi.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewNsi_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a&":
/*!****************************************************************************************!*\
  !*** ./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a& ***!
  \****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./NewNsi.vue?vue&type=template&id=10cbe99a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/nsis/NewNsi.vue?vue&type=template&id=10cbe99a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewNsi_vue_vue_type_template_id_10cbe99a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);