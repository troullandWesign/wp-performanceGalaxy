<?php 

namespace WS_2020\inc;

use WS_2020\inc\core\AbstractController;

class CustomPostTypes extends AbstractController
{
    public function __construct()
    {
        $this->register_post_type('abonnements', [$this, 'abonnements']);
    }

	public static function abonnements()
	{
        return [
            'labels'             => array(
                'name'               => _x('Offre', 'post type general name'),
                'singular_name'      => _x('Offre', 'post type singular name'),
                'add_new'            => _x('Ajouter une Offre', 'book'),
                'add_new_item'       => __('Ajouter une nouveau Offre'),
                'edit_item'          => __('Editer une Offre'),
                'new_item'           => __('Nouveau Offre'),
                'all_items'          => __('Tous les Offres'),
                'view_item'          => __('Voir les offres'),
                'search_items'       => __('Rechercher une Offre'),
                'not_found'          => __('Aucun Offre trouvée'),
                'not_found_in_trash' => __('Aucun Offre trouvée dans la corbeille'),
                'menu_name'          => __('Offres')
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_in_rest'       => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-products',
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'rewrite'            => array( 'slug' => 'offre' )
        ];
    }
}