<template>
    <b-modal id="modal-3"  ok-title="���������" size="xl" no-close-on-esc no-close-on-backdrop @ok="add_theme" cancel-title="�������" title="�������� ����">
        <b-form-group label-size="lg" label="�������� ����">
          <b-form-input v-model="new_theme.name"></b-form-input>
        </b-form-group>
        <b-form-group label-size="lg" label="���������� ����� ������">
          <b-form-input v-model="new_theme.lection_hours"></b-form-input>
        </b-form-group>      
        <b-form-group label-size="lg" label="���������� ����� �������">
          <b-form-input v-model="new_theme.practice_hours"></b-form-input>
        </b-form-group>
         <b-form-group label-size="lg" label="���������� ����� ��������������� ������">
          <b-form-input v-model="new_theme.self_hours"></b-form-input>
        </b-form-group>
    </b-modal>
</template>

<script>
export default {
  name: "create-theme",
  metaInfo: {
  title: "�������� ����� ����"
  },
  props: {
    knowledge: Object,
  },
  data (){
  return {
    new_theme: {
        name: "",
        lection_hours: 0,
        practice_hours: 0,
        self_hours: 0
    },
  }
  },  

  methods: {
    add_the,e(bvModalEvt)
    {
      bvModalEvt.preventDefault()
      this.$emit('add_theme', this.new_theme) 
    },
  },
  mounted() {
  }
}
</script>