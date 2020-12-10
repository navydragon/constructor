<template>
    <div>
    <b-button variant="primary" v-b-modal.mto-choose>Добавить МТО к заданию</b-button>
    <b-modal id="mto-choose"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_mto_to_task" cancel-title="Закрыть" title="Добавить МТО к заданию">
    
    </b-modal>
    </div>
</template>

<script>
export default {
    name: 'task-mtos',
    props: {
      dpp_id: String,
    },
    data () {
    return {
      mtos: [],
    }
    },
    methods:{
       add_mto_to_task()
       {

       },
    },
    mounted() {
    }
}
</script>