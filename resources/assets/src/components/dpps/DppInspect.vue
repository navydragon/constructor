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
                    <h4>ОБЩИЕ СВЕДЕНИЯ</h4>
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
                    <h4>ИСХОДНЫЕ ДАННЫЕ</h4>
                    <div v-if="!isBusy">
                        <h5>ТРЕБОВАНИЯ К УРОВНЮ ПРОФЕССИОНАЛЬНОГО ОБРАЗОВАНИЯ</h5>
                        <div>
                            <div v-if="ish_data.prof_levels.length > 0">
                            <ul v-for="(elem,index) in ish_data.prof_levels" :key="index">
                                <li>{{elem.name}}</li>
                            </ul>
                            </div>
                            
                            <p v-if="ish_data.prof_levels.length == 0">
                                {{ish_data.req_user_edulevel}}
                            </p>
                        </div>
                        <hr>
                        <h5>ТРЕБОВАНИЯ К КВАЛИФИКАЦИИ</h5>
                        <p>{{ish_data.req_user_kval}}</p>
                        <hr>
                        <h5>ТИПОВАЯ СТРУКТУРА ДПП</h5>
                        <b-alert show >Отредактируйте (при необходимости) типовую структуру ДПП</b-alert>
                        <new-dtp @add_dtp="add_dtp" :key="'ds'"></new-dtp>
                        <b-list-group>
                            <b-list-group-item v-for="dpp_part in ish_data.dpp_parts" :key="dpp_part.id">
                                <b-btn variant="outline-primary icon-btn btn-xs" class="btn"><i class="ion ion-md-create"></i></b-btn>
                                <b-btn variant="outline-danger icon-btn btn-xs" class="btn" @click="remove_dtp(dpp_part.id,dpp_part.name)">X</b-btn>
                               {{dpp_part.position}}. {{dpp_part.name}}
                            </b-list-group-item>
                        </b-list-group>
                        <hr>
                        <h5>НОРМАТИВНО-СПРАВОЧНАЯ ИНФОРМАЦИЯ</h5>
                        <nsis v-if="!isBusy" :mode="'view'" :ish_version_id="ish_data.id"></nsis>
                    </div>
                </b-tab>
                <b-tab title="Проектирование результатов">
                    <div v-if="!isBusy">
                        <h4>ПРОЕКТИРОВАНИЕ РЕЗУЛЬТАТОВ</h4>
                        <h5>Сформированные компетенции</h5>
                        <div v-for="comp in nodes.filter(el => el.type=='Компетенция')" :key="comp.id">
                            <h5><i class="ion ion-ios-radio-button-on text-primary"></i> Компетенция: {{comp.name}}</h5>
                            <ul style="padding-left:20px" type="none">
                                <li v-for="skil in nodes.filter(el => el.type=='Навык'&&el.pid==comp.id)" :key="skil.id"><i class="ion ion-ios-radio-button-on text-secondary"></i> Навык: {{skil.name}}
                                <ul style="padding-left:20px" type="none">
                                    <li v-for="abil in nodes.filter(el => el.type=='Умение'&&el.pid==skil.id)" :key="abil.id">
                                        <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                        <ul style="padding-left:20px" type="none">
                                            <li v-for="know in nodes.filter(el => el.type=='Знание'&&el.pid==abil.id)" :key="know.id">
                                                <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                </li>
                            </ul>
                        </div>
                        <hr>
                        <h5>ЗУН, не прикрепленные к компетенциям</h5>
                        <ul style="padding-left:20px" type="none">
                                <li v-for="skil in nodes.filter(el => el.type=='Навык'&&el.pid=='c')" :key="skil.id"><i class="ion ion-ios-radio-button-on text-secondary"></i> Навык: {{skil.name}}
                                <ul style="padding-left:20px" type="none">
                                    <li v-for="abil in nodes.filter(el => el.type=='Умение'&&el.pid==skil.id)" :key="abil.id">
                                        <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                        <ul style="padding-left:20px" type="none">
                                            <li v-for="know in nodes.filter(el => el.type=='Знание'&&el.pid==abil.id)" :key="know.id">
                                                <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                </li>
                        </ul>
                        <ul style="padding-left:20px" type="none">
                            <li v-for="abil in nodes.filter(el => el.type=='Умение'&&el.pid=='s')" :key="abil.id">
                                        <i class="ion ion-ios-radio-button-on text-success"></i> Умение: {{abil.name}}
                                        <ul style="padding-left:20px" type="none">
                                            <li v-for="know in nodes.filter(el => el.type=='Знание'&&el.pid==abil.id)" :key="know.id">
                                                <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                                            </li>
                                        </ul>
                            </li>
                        </ul>
                        <hr>
                        <h5>Сквозные знания:</h5>
                        <ul style="padding-left:20px" type="none">
                            <li v-for="know in nodes.filter(el => el.type=='Знание'&&el.pid==0)" :key="know.id">
                                <i class="ion ion-ios-radio-button-on text-warning"></i> Знание: {{know.name}}
                            </li>
                        </ul>
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
import Nsis from '@/components/nsis/Nsis'
import NewDtp from '@/components/typologies/NewDppTypologyPart'
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
    VJstree, Nsis, NewDtp
    },
    data () {
    return {
        isBusy: true,
        item: {},
        ish_data: {},
        nodes: [],
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
            //.get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ self.item.zun_version_id+'/unattached')
            //.then(response => (this.treeData[0].children = response.data))
            .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data2/'+ self.item.zun_version_id)
            .then(response => (this.nodes = response.data))
            .finally(() => (this.isBusy = false) )
            this.$nextTick(() => {
            var self = this
            axios
            .get('/dpps/'+this.$route.params.dpp+'/get_zun_version_data/'+ self.item.zun_version_id+'/attached')
            .then (function (response){
                self.attachedTreeData[0].children = response.data
            })
        })
        },
    add_dtp(data) {
        axios
        .post('/typologies/add_dtp',{
            dtp_name: data,
            dpp_id: this.$route.params.dpp,
            ish_version_id: this.ish_data.id,
            typology_id: this.ish_data.typology_id,
            })
        .then((response) => (this.ish_data.dpp_parts.push(response.data)))
        .finally(()=>(this.$bvModal.hide("modal-newdtp")))
    },
    remove_dtp (dtp,name) {
        this.$bvModal.msgBoxConfirm('Действительно хотите удалить раздел «'+name+'»? Это также удалит привязки к этому разделу знаний (если они имеются).')
        .then(value => {
        if (value == true)
        {
        axios
       .post('/typologies/remove_dtp',dtp)
        .then((response) => (this.ish_data.dpp_parts = this.ish_data.dpp_parts.filter(part => part.id != dtp))) 
        }})
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