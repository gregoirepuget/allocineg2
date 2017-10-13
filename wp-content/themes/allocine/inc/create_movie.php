<?php
function ajout_custom_type_init() {
$post_type = "film";
$labels = array(
      'name'               => 'Films',
      'singular_name'      => 'Film',
      'all_items'          => 'Tous les films',
      'add_new'            => 'Ajouter un film',
      'add_new_item'       => 'Ajouter un nouveau film',
      'edit_item'          => "Editer le film",
      'new_item'           => 'Nouveau film',
      'view_item'          => "Voir le film",
      'search_items'       => 'Chercher un film',
      'not_found'          => 'Pas de résultat',
      'not_found_in_trash' => 'Pas de résultat',
      'parent_item_colon'  => 'Film parent :',
      'menu_name'          => 'Films',
  );

  $args = array(
      'labels'              => $labels,
      'hierarchical'        => false,
      'supports'            => array( 'title','thumbnail','editor', 'excerpt', 'revisions'),
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'menu_position'       => 0,
      'menu_icon'           => 'dashicons-video-alt2',
      'show_in_nav_menus'   => true,
      'publicly_queryable'  => true,
      'exclude_from_search' => false,
      'has_archive'         => false,
      'query_var'           => true,
      'can_export'          => true,
      'rewrite'             => array( 'slug' => $post_type )
  );

  register_post_type($post_type, $args );
}
add_action( 'init', 'ajout_custom_type_init' );
