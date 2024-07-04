<?php
/*
* Template Name: Home Template
*/
$context = Timber::context();
$page = new Timber\Post();

/**
 * GET de tous les posts pour récupérer
 * les catégories contenant + 1 article
*/
$allPosts = new Timber\PostQuery(array(
    'post_type'      => 'post',
    'taxonomy' => 'category',
    'posts_per_page' => -1,
    'post_status'  => 'publish',
));


/**
 * Catégories contenant + 1 article
*/
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


/**
 * Récupération des posts
 * -> si pas de pagination, commenter la requete
 *    et dé-commenter la ligne du dessous
 */
$posts = new Timber\PostQuery(array(
    'post_type'      => 'post',
    'taxonomy' => 'category',
    'posts_per_page' => 9,
    'post_status'  => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'paged' => $paged
));
//$posts = $allPosts;


/**
 * Pour chaque post, on récup
 * des infos supplémentaires
 */
foreach ($posts as $key => $post) {
    $post->thumbnail = get_the_post_thumbnail_url($post->ID);
    $post->permalink = get_permalink($post->ID);
    $post->terms = get_the_terms($post->ID, 'category');
}


/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['test'] = 'home';
$context['posts'] = $posts;

Timber::render('home.twig', $context);