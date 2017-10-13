<?php

add_action( 'wp_enqueue_scripts', 'ajout_scripts' );

function ajout_scripts() {

// enregistrement d'un nouveau script
wp_register_script('bootstrap_script', JS_URL.'/bootstrap.min.js', array('jquery'),'1.1', true);
wp_enqueue_script('bootstrap_script');

wp_register_script('main_js', JS_URL.'/main.js', array('jquery'),'1.1', true);
wp_enqueue_script('main_js');


// enregistrement des styles
wp_register_style( 'google_font', 'https://fonts.googleapis.com/css?family=Anton|Oxygen' );
wp_enqueue_style( 'google_font' );

wp_register_style( 'bootstrap_style', CSS_URL.'/bootstrap.min.css' );
wp_enqueue_style( 'bootstrap_style' );

// enregistrement des styles
wp_register_style( 'main_style', CSS_URL.'/main.css' );
wp_enqueue_style( 'main_style' );
}
