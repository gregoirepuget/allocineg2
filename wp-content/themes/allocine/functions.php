<?php
add_action( 'wp_enqueue_scripts', 'ajout_scripts' );

function ajout_scripts() {

// enregistrement d'un nouveau script
wp_register_script('bootstrap_script', get_template_directory_uri() . '/assets/scripts/bootstrap.min.js', array('jquery'),'1.1', true);
wp_enqueue_script('bootstrap_script');

wp_register_script('main_js', get_template_directory_uri() . '/assets/scripts/main.js', array('jquery'),'1.1', true);
wp_enqueue_script('main_js');

// enregistrement des styles
wp_register_style( 'bootstrap_style', get_template_directory_uri() . '/assets/styles/bootstrap.min.css' );
wp_enqueue_style( 'bootstrap_style' );

// enregistrement des styles
wp_register_style( 'main_style', get_template_directory_uri() . '/assets/styles/main.css' );
wp_enqueue_style( 'main_style' );
}



 ?>
