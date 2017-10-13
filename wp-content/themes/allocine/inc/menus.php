<?php

add_action( 'after_setup_theme', 'menus_du_themes' );
function menus_du_themes() {
  register_nav_menu( 'header', 'Menu entête' );
  register_nav_menu( 'footer', 'Menu bas de page' );
  register_nav_menu( 'topBar', 'Menu top Bar' );
}
