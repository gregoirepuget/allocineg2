<?php
function create_new_role(){
add_role(
      'nouveau_role',
      'Nouveau rôle',
      array(
      'read' => true, // true allows this capability
      'edit_posts' => true,
      'delete_posts' => false, // Use false to explicitly deny
      )
);
}
add_action('after_switch_theme', 'create_new_role');
 ?>
