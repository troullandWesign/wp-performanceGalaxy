<?php
/*
* Template Name: Actus - Category
*/

$context = Timber::get_context();
$page = new Timber\Post();
$current_term = get_queried_object();

/**
 * On récupére tous les articles
 * pour la navigation entre les
 * catégories
 */
$allPosts = new Timber\PostQuery(array(
    'post_type'      => 'post',
    'taxonomy' => 'category',
    'posts_per_page' => -1,
    'post_status'  => 'publish',
));

//Récupération des catégories
$categories = [];
foreach ($allPosts as $key => $post) {
    $post_terms = wp_get_post_terms($post->id, 'category');
    if($post_terms){
        foreach ($post_terms as $key => $post_term) {
            $post_term->link = get_term_link($post_term);
            if(!in_array($post_term, $categories)){
                array_push($categories, $post_term);
            }
        } 
    }
}

//Récupération des posts de la cat
$posts = new Timber\PostQuery(array(
    'post_type'      => 'post',
    'taxonomy' => 'category',
    'posts_per_page' => 9,
    'post_status'  => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $current_term->term_id,
        )
    ),
    'paged' => $paged
));
foreach ($posts as $key => $post) {
    $post->thumbnail = get_the_post_thumbnail_url($post->ID);
    $post->permalink = get_permalink($post->ID);
    $post->terms = get_the_terms($post->ID, 'category');
}

/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['posts'] = $posts;
$context['category'] = $current_term;
$context['categories'] = $categories;
$context['pagination'] = $posts->pagination();

Timber::render( 'actus-category.twig', $context );