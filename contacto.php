<?php

/*
Plugin Name: contacto
Plugin URI:  https://trecedenueve.com
Description: custom contact form
Author:      trecedenueve
Author URI:  
Version:     1.0
License:     GPL
*/

function agregar_formulario_contacto() {
    ob_start(); // Iniciar el almacenamiento en búfer de salida
    include(plugin_dir_path(__FILE__) . 'contacto-form.php'); // Incluir el formulario
    return ob_get_clean(); // Devolver el formulario como una cadena
}

function agregar_formulario_a_pagina() {
    add_shortcode('formulario_contacto', 'agregar_formulario_contacto');
}

add_action('init', 'agregar_formulario_a_pagina');


?>