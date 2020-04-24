<template>
    <b-modal id="modal-tasknsi"  ok-title="Сохранить" size="xl" no-close-on-esc no-close-on-backdrop @ok="update_tasknsi" cancel-title="Закрыть" title="Выбор НСИ для задания">
    <nsi-choose @change_nsi="change_nsi" :mode="'work'" :selected="task_nsis" :ish_version_id="ish_version_id"></nsi-choose>
    </b-modal>
</template>

<script>
import NsiChoose from '@/components/nsis/NsiChoose'
export default {
  name: "task-nsi",
  metaInfo: {
  title: "Выбор НСИ для задания"
  },
  props: {
      ish_version_id: Number,
      task_nsis: Array,
  },
  components: { NsiChoose},
  data(){
   return {
       nsis: [],
   }
  },
  methods:{
      change_nsi (){

      },
      update_tasknsi()
      {

      }
  }
}
</script>