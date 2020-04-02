<template>
  <sidenav :orientation="orientation" :class="curClasses">

    <!-- Inner -->
    <div class="sidenav-inner" :class="{ 'py-1': orientation !== 'horizontal' }">

      <sidenav-router-link icon="ion ion-md-home" to="/" :exact="true">Главная</sidenav-router-link>
      <div class="sidenav-item" v-if="!$auth.check()">
        
        <sidenav-router-link  icon="ion ion-md-person-add" to="/register" :exact="true">Регистрация</sidenav-router-link>
        
        <sidenav-router-link  icon="ion ion-ios-contact" to="/login" :exact="true">Авторизация</sidenav-router-link>
      </div>
      <div class="sidenav-item" v-if="$auth.check()">
        <div v-if="$auth.user().right_id!=3">
          <sidenav-menu :active="isMenuActive('/adm')" :open="isMenuOpen('/adm')">
            <template slot="link-text">Управление</template>
            <sidenav-router-link  icon="ion ion-md-person" to="/adm/users" :exact="true">Пользователи</sidenav-router-link>
            <sidenav-router-link  icon="ion ion-md-paper" to="/adm/dpps" :exact="true">ДПП</sidenav-router-link>      
            <sidenav-router-link  icon="ion ion-md-paper" to="/adm/typologies" :exact="true">Типовые структуры ДПП</sidenav-router-link> 
          </sidenav-menu>
        </div>
        <div class="sidenav-item" v-if="$auth.check()">
          <sidenav-router-link  icon="ion ion-md-paper" to="/my_dpps" :exact="true">Разработка ДПП</sidenav-router-link>      
        </div>
        <div class="sidenav-item"><a href="#" @click.prevent="$auth.logout()" class="sidenav-link"><i class="sidenav-icon ion ion-ios-log-out"></i> <div>Выход</div> <!----></a></div>
      </div>
      

    </div>
  </sidenav>
</template>

<script>
import { Sidenav, SidenavLink, SidenavRouterLink, SidenavMenu, SidenavHeader, SidenavBlock, SidenavDivider } from '@/vendor/libs/sidenav'

export default {
  name: 'app-layout-sidenav',
  components: {
    Sidenav,
    SidenavLink,
    SidenavRouterLink,
    SidenavMenu,
    SidenavHeader,
    SidenavBlock,
    SidenavDivider
  },

  props: {
    orientation: {
      type: String,
      default: 'vertical'
    }
  },

  computed: {
    curClasses () {
      let bg = this.layoutSidenavBg

      if (this.orientation === 'horizontal' && (bg.indexOf(' sidenav-dark') !== -1 || bg.indexOf(' sidenav-light') !== -1)) {
        bg = bg
          .replace(' sidenav-dark', '')
          .replace(' sidenav-light', '')
          .replace('-darker', '')
          .replace('-dark', '')
      }

      return `bg-${bg} ` + (
        this.orientation !== 'horizontal'
          ? 'layout-sidenav'
          : 'layout-sidenav-horizontal container-p-x flex-grow-0'
      )
    }   
  },

  methods: {
    isMenuActive (url) {
      return this.$route.path.indexOf(url) === 0
    },

    isMenuOpen (url) {
      return this.$route.path.indexOf(url) === 0 && this.orientation !== 'horizontal'
    },

    toggleSidenav () {
      this.layoutHelpers.toggleCollapsed()
    }
  }
}
</script>
