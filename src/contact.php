<?php
/*
* Template Name: Contact Template
*/
$context = Timber::context();
$page = new Timber\Post();


/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['test'] = 'contact';

Timber::render('contact.twig', $context);