<?php
function theme_slug_widgets_init() {
$args = array(
'name' => __('Sidebar Footer %d'),
'id'   => 'sidebar-footer',
'description' => '',
'class' => '',
'before_widget' => '<li id="%1$s" class="widget %2$s">',
'after_widget' => '</li>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2>' );

register_sidebars(4, $args);
}
add_action( 'widgets_init', 'theme_slug_widgets_init' );
