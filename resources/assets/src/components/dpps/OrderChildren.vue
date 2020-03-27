<template>
  <div>
    <b-modal
      no-close-on-esc
      no-close-on-backdrop
      @ok="handle_ok"
      id="modal-orderchildren"
      ok-title="Сохранить"
      cancel-title="Закрыть"
      size="xl"
      title="Упорядочивание дочерних компонентов"
    >
      <h5>Родительский компонент: {{edit_elem.name}}</h5>
      <h5>Дочерние компоненты:</h5>
      <b-alert show  variant="info">
      <span>Установите правильную последовательность отображения компонентов. Вы можете поменять последовательность путем перетаскивания компонента (с помощью иконки <i class="ion ion-ios-move m-r-1"></i>).</span>
    </b-alert>
      <draggable
        v-model="children"
        v-bind="{animation: 150, handle: '.ion'}"
        tag="div"
        class="sortable-example"
      >
        <div
          v-for="item in children"
          :key="item.id"
          style="margin-bottom:10px"
        >
          <b-row>
            <b-col sm="1">
              <i class="ion ion-ios-move m-r-1" style="font-size: 35px;"></i> &nbsp;
            </b-col>
            <b-col sm="11">
              {{item.name}}
            </b-col>
          </b-row>
          <hr>
        </div>
      </draggable>
    </b-modal>
  </div>
</template>

<script>
import draggable from "vuedraggable/src/vuedraggable";
export default {
  name: "order-children",
  props: {
    edit_elem: Object,
    zun_version: Number
  },
  components: {
    draggable
  },
  data() {
    return {
      elem: {},
      children: []
    };
  },
  methods: {
    handle_ok(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$emit('update_order', this.children)
    }
  },
  mounted() {
    axios
      .post("/dpps/"+this.zun_version+"/get_children", {
        elem_id: this.edit_elem.id,
        elem_type: this.edit_elem.tags[0]
      })
      .then(response => (this.children = response.data));
  }
};
</script>