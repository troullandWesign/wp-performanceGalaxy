<?php
/*
* Template Name: Single Abonnement Template
*/
$context = Timber::context();
$page = new Timber\Post();


/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['test'] = 'home';

Timber::render('single-abonnement.twig', $context);