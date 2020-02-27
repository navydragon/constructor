<template>
<div>
    <b-button @click="$router.go(-1)" variant="primary">Назад</b-button>
    <b-card :title="header">
            <b-tabs content-class="mt-3" pills fill>
            <b-tab title="Знания" active>
                <h5>Выберите знание</h5>
                <model-select id="user-input" :options="knowledges"
                    v-model="knowledge"
                    placeholder="Выберите знание">
                </model-select>
                <hr class="container-m-nx border-light mt-2 mb-2">
                <v-client-table :data="knowledge.questions" :columns="kn_columns" :options="options">
                    <template slot="edit" slot-scope="props">
                        <div>
                        <b-btn variant="outline-success borderless icon-btn" class="btn-xs" @click.prevent="edit(props.row)"><i class="ion ion-md-create"></i></b-btn>
                        <b-btn variant="outline-danger borderless icon-btn" class="btn-xs" @click.prevent="remove(props.row)"><i class="ion ion-md-close"></i></b-btn>
                        </div>
                    </template>
                </v-client-table>
                <new-question></new-question>
            </b-tab>
            <b-tab title="Умения">
                <p>I'm the second tab</p>
            </b-tab>
            <b-tab title="Навыки">
                <p>I'm the tab with the very, very long title</p>
            </b-tab>
        </b-tabs>
    </b-card>
</div>
</template>

<style src="@/vendor/libs/vue-data-tables/vue-data-tables.scss" lang="scss"></style>
<script>
import { ModelSelect } from 'vue-search-select'
import Vue from 'vue'
import { ClientTable } from 'vue-tables-2'
import NewQuestion from './NewQuestion'
Vue.use(ClientTable)


export default {
    name: "dpp_stage_work_om",
    metaInfo: {
        title: "Разработка ДПП - Оценочные материалы"
  },
  components: {
      ModelSelect, ClientTable,NewQuestion
  },
  computed: {
      header() {
          return "Разработка ДПП / "+this.stage.dpp_name+" / "+this.stage.type_name
      },
  },
  data () {
    return {
        stage: {},
        knowledge: {
            questions: []
        },
        knowledges: [
            {
                text: 'Знание 1',
                value: 1,
                questions: [
                    {
                        text: 'Вопрос 1',
                        type_name: 'Выбор вариантов ответа'
                    }
                ]
            },
            {
                text: 'Знание 2',
                value: 2,
                questions: []
            },
        ],
        kn_columns: ['text', 'type_name', 'edit'],
        options: {
            pagination: { chunk: 5 },
            sortIcon: {
                is: 'fa-sort',
                base: 'fas',
                up: 'fa-sort-up',
                down: 'fa-sort-down'
            },
            headings: {
                text: 'Текст вопроса',
                type_name: 'Тип вопроса',
                edit: 'Управление'
            }
        }
    }
  },
  mounted() {
      axios
        .get('/dpps/'+this.$route.params.dpp+'/get_stage_data/'+ this.$route.params.stage)
        .then(response => (this.stage = response.data))
        //.finally( () =>( this.get_ish_versions_data() ) )  
  }
}
</script>