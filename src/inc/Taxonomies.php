<?php

namespace WS_2020\inc;

use WS_2020\inc\core\AbstractController;

class Taxonomies extends AbstractController
{
   public function __construct()
   {
      // $this->register_taxonomy('type', ['badges'], [$this, 'badges_type']);
   }

   public static function badges_type()
   {
      return [
         'labels'             => array(
            'name'               => _x( 'Catégories', 'taxonomy general name' ),
            'singular_name'      => _x( 'Catégorie', 'taxonomy singular name' ),
            'search_items'       => __( 'Chercher une catégorie' ),
            'all_items'          => __( 'Toutes les catégories' ),
            'parent_item'        => __( 'Catégorie parente' ),
            'parent_item_colon'  => __( 'Catégorie parente:' ),
            'edit_item'          => __( 'Modifier la catégorie' ),
            'update_item'        => __( 'Mettre la catégorie' ),
            'add_new_item'       => __( 'Ajouter une catégorie' ),
            'new_item_name'      => __( 'Nouvelle catégorie' ),
            'menu_name'          => __( 'Catégories' ),
         ),
         'hierarchical'       => true,
         'public'					=> true,
         'publicly_queryable' => false,
         'show_ui'            => true,
         'show_admin_column'  => true,
         'show_in_nav_menus'  => false,
         'query_var'          => true,
         'rewrite'            => array(
            'slug'       => 'la-carte',
            'with_front' => false,
         ),
      ];
   }
}