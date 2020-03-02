<template>
    <div>
        <router-link icon="ion ion-md-person" :to="{name: 'dpps'}" :exact="true">
                <b-button  variant="info">
                   <i class="ion ion-ios-arrow-back" style="font-size:20px;"></i> Назад
                </b-button>
        </router-link>
        <b-card :title="header">
        <div>
            <b-card no-body>
                <b-tabs pills card vertical>
                <b-tab title="Общие сведения" active>
                    <h4>Общие сведения</h4>
                    <h5>Название ДПП</h5>
                    <p>{{item.name}}</p>
                    <h5>Тип ДПП</h5>
                    <p>{{item.type_name}}</p>
                    <h5>Участники</h5>
                    <ul v-for="(elem,index) in item.participants" :key="index">
                        <li>{{elem.fullname}} ({{elem.rolename}})</li>
                    </ul>
                </b-tab>
                <b-tab title="Исходные данные">
                    <h4>Исходные данные</h4>
                    <div v-if="!isBusy">
                        <h5>Требования к уровню профессионального образования</h5>
                        <div v-if="ish_data.prof_levels.length > 0">
                        <ul v-for="(elem,index) in ish_data.prof_levels" :key="index">
                            <li>{{elem.name}}</li>
                        </ul>
                        </div>
                        <p v-if="ish_data.prof_levels.length == 0">
                            {{ish_data.req_user_edulevel}}
                        </p>
                        <h5>Требования к квалификации</h5>
                        <p>{{ish_data.req_user_kval}}</p>
                    </div>
                </b-tab>
                <b-tab title="Проектирование результатов">
                    <div v-if="!isBusy">
                        <h4>Проектирование результатов</h4>
                        <h5>Сформированные компетенции</h5>
                        <v-jstree
                            :data="attachedTreeData"
                            :class="{ 'tree-rtl': isRTL }"
                            multiple
                            allow-batch
                            whole-row
                            >
                            <template slot-scope="_">
                                <div style="display: inherit; width: 100px">
                                <i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
                                <span v-html="_.model.text"></span>
                                </div>
                            </template>
                        </v-jstree>
                        <h5>ЗУН, не прикрепленные к компетенциям</h5>
                        <v-jstree
                            :data="treeData"
                            :class="{ 'tree-rtl': isRTL }"
                            multiple
                            allow-batch
                            whole-row
                            >
                            <template slot-scope="_">
                                <div style="display: inherit; width: 100px">
                                <i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
                                <span v-html="_.model.text"></span>
                                </div>
                            </template>
                        </v-jstree>
                    </div>
                </b-tab>
                </b-tabs>
            </b-card>
        </div>
        </b-card>
    </div>
</template>

<script>
import VJstree from 'vue-jstree'
export default {
    name: 'dpp_inspect',
    metaInfo: {
        title: 'Просмотр ДПП'
    },
    computed: {
        header() {
            return 'Управление ДПП / '+this.item.name + ' / Просмотр'
        }
    },
    components: {
    VJstree
    },
    data () {
    return {
        isBusy: true,
        item: {},
        ish_data: {},
        treeData: [{
        'text': 'Новая компетенция',
        'id': 'nc',
        'opened': true,
        'type': 'folder',
        'color': 'btn-outline-dark',
        'children': []
        }],
        attachedTreeData: [{
        'text': 'Программа ДПП',
        'id': 'pr',
        'opened': true,
        'type': 'folder',
        'color': 'btn-outline-dark',
        'children': []
        }],
    }
    },
    methods:{
        get_data() {
            axios
            .get('/dpps/'+this.$route.params.dpp+'/get_ish_version_data/'+ self.item.ish_version_id)
            .then(response => (this.ish_data = response.data))

            axios
            .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ self.item.zun_version_id+'/unattached')
            .then(response => (this.treeData[0].children = response.data))
            .finally(() => (this.isBusy = false) )
            this.$nextTick(() => {
            var self = this
            axios
            .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ self.item.zun_version_id+'/attached')
            .then (function (response){
                self.attachedTreeData[0].children = response.data
            })
        })
        }
    },
    mounted() {
        self = this
        axios
        .get('/dpps/'+ this.$route.params.dpp+'/config')
        .then(response => (this.item = response.data))
        .finally ( () => (this.get_data()));
    }
}
</script>