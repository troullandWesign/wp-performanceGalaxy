<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use Timber\Timber;
use Timber\Site AS TimberSite;
use Timber\Menu AS TimberMenu;
use Timber\Twig_Filter as TwigFilter;
use Timber\Loader as TimberLoader;

use WS_2020\inc\Taxonomies;
use WS_2020\inc\CustomPostTypes;
use WS_2020\inc\AjaxRequests;
use WS_2020\inc\CustomFields;

class WS_Starter extends TimberSite {
   private $stylesheet_directory_uri;
   private $stylesheet_directory;

   public function __construct()
   {
      $this->stylesheet_directory_uri = get_stylesheet_directory_uri();
      $this->stylesheet_directory = get_stylesheet_directory();

      $this->custom_fields = new CustomFields();

      $this->configure();
      $this->manage_actions();
      $this->manage_filters();

      parent::__construct();
   }


   /**
    * Configure theme by setting up multiple WP options
    */
   public function configure()
   {
      define('DISALLOW_FILE_EDIT', true);
      define('ENFORCE_GZIP', true);

      show_admin_bar(false);

      add_theme_support('post-formats', 
         array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
         )
      );
      add_theme_support('post-thumbnails');
      add_theme_support('menus');
      add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
      add_theme_support('block-templates');

      // Init Timber
      new Timber();
      Timber::$dirname = array('templates', 'templates/partials', 'templates/layouts', 'templates/components');
      Timber::$cache = self::isLocalhost() ? false : true;

      // Init Taxonomies & Custom post types
      new Taxonomies();
      new CustomPostTypes();

      // Init Ajax requests
      new AjaxRequests();
   }

   private static function isLocalhost()
   {
      return in_array($_SERVER['REMOTE_ADDR'], ['localhost', '127.0.0.1', '::1']);
   }

   /**
    * Manage add_action && remove_action
    */
   public function manage_actions()
   {
      remove_action('template_redirect', 'rest_output_link_header', 11, 0);
      remove_action('admin_print_scripts', 'print_emoji_detection_script');
      remove_action('wp_print_styles', 'print_emoji_styles');
      remove_action('admin_print_styles', 'print_emoji_styles');
      remove_action('wp_head', 'rest_output_link_wp_head');
      remove_action('wp_head', 'wp_oembed_add_discovery_links');
      remove_action('wp_head', 'print_emoji_detection_script', 7);
      remove_action('wp_head', 'wlwmanifest_link');
      remove_action('wp_head', 'index_rel_link');
      remove_action('wp_head', 'rsd_link');
      remove_action('wp_head', 'wp_generator');

      add_action('init', [$this, 'custom_menus']);
      //add_action('init', [$this, 'define_permalink_structure']);
      add_action('acf/init', [$this, 'custom_options_page']);
      add_action('acf/init', [$this->custom_fields, 'load']);
      add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
      add_action('wp_print_styles', [$this, 'deregister_styles']);
      add_action('wp_footer', [$this, 'deregister_scripts']);
      add_action('admin_head', [$this, 'admin_dashboard_favicons']);
      add_action('rt_nginx_helper_after_purge_all', [$this, 'clear_twig_cache']);
   }

   /**
    * Manage add_filter && remove_filter
    */
   public function manage_filters()
   {
      remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
      remove_filter('the_content_feed', 'wp_staticize_emoji');
      remove_filter('comment_text_rss', 'wp_staticize_emoji');
      
      add_filter('xmlrpc_enabled', '__return_false');
      add_filter('timber_context', [$this, 'add_to_context']);
      add_filter('get_twig', [$this, 'add_to_twig']);
      add_filter('wp_mail_from', [$this, 'wp_mail_sender_email']);
      add_filter('wp_mail_from_name', [$this, 'wp_mail_sender_name']);
      add_filter('wp_mail_content_type',[$this, 'set_mail_content_type']);
      add_filter('upload_mimes', [$this, 'allow_svg_upload']);
   }

   // Make sure we send HTML
   function set_mail_content_type(){
      return "text/html";
   }

   // Accept SVG media upload
   function allow_svg_upload($mimes) {
      $mimes['svg'] = 'image/svg+xml';
      return $mimes;
   }

   /**
    * Configure PHPMailer just before sending email
    */
   public function add_dkim_to_email($phpmailer)
   {
      // $dir = __DIR__;
      // $phpmailer->DKIM_domain = "nom-de-domaine.fr";
      // $phpmailer->DKIM_private = "{$dir}/../dkim/dkim.private.key";
      // $phpmailer->DKIM_selector = "mail";
      // $phpmailer->DKIM_passphrase = '';
      // $phpmailer->DKIM_identity = $phpmailer->From;
      $phpmailer->IsHTML(true);
   }

   /**
    * Change wp_mail sender email header
    */
   public function wp_mail_sender_email($original_email)
   {
      return 'nepasrepondre@nom-de-domaine.fr';
   }

   /**
    * Change wp_mail sender name header
    */
   public function wp_mail_sender_name($original_name)
   {
      return 'Nom du site';
   }

   /**
    * Load CSS & JS
    */
    public function register_scripts()
    {
        $js_files = glob($this->stylesheet_directory . '/js/*.js');
        $css_files = glob($this->stylesheet_directory . '/css/*.css');
    
        if (isset($js_files[1])) {
            wp_register_script('ws-vendor-js', $this->stylesheet_directory_uri.'/js/'.basename($js_files[1]), null, null, true);
            wp_enqueue_script('ws-vendor-js');
        }
    
        if (isset($js_files[0])) {
            wp_register_script('ws-app-js', $this->stylesheet_directory_uri.'/js/'.basename($js_files[0]), isset($js_files[1]) ? ['ws-vendor-js'] : null, null, true);
            wp_enqueue_script('ws-app-js');
        }
    
        if (isset($css_files[0])) {
            wp_register_style('ws-app', $this->stylesheet_directory_uri.'/css/'.basename($css_files[0]), [], null);
            wp_enqueue_style('ws-app');
        }
    }
    

   /**
    * Remove useless Wordpress styles
    */
   public function deregister_styles()
   {
      wp_dequeue_style('wp-block-library');
   }

   /**
    * Remove useless Wordpress scripts
    */
   public function deregister_scripts()
   {
      wp_deregister_script('wp-embed');
      wp_deregister_script('jquery');
   }

   /**
    * Automaticaly define permalink structure
    */
   public function define_permalink_structure()
   {
      global $wp_rewrite;
      $wp_rewrite->set_permalink_structure('/%postname%/');
      $wp_rewrite->flush_rules();
   }

   /**
    * Load favicon for admin dashboard
    */
   public function admin_dashboard_favicons()
   {
      $html = "
            <link rel='apple-touch-icon' sizes='57x57' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-57x57.png'>
            <link rel='apple-touch-icon' sizes='60x60' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-60x60.png'>
            <link rel='apple-touch-icon' sizes='72x72' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-72x72.png'>
            <link rel='apple-touch-icon' sizes='76x76' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-76x76.png'>
            <link rel='apple-touch-icon' sizes='114x114' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-114x114.png'>
            <link rel='apple-touch-icon' sizes='120x120' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-120x120.png'>
            <link rel='apple-touch-icon' sizes='144x144' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-144x144.png'>
            <link rel='apple-touch-icon' sizes='152x152' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-152x152.png'>
            <link rel='apple-touch-icon' sizes='180x180' href='{$this->stylesheet_directory_uri}/images/favicon/apple-icon-180x180.png'>
            <link rel='icon' type='image/png' sizes='192x192' href='{$this->stylesheet_directory_uri}/images/favicon/android-icon-192x192.png'>
            <link rel='icon' type='image/png' sizes='32x32' href='{$this->stylesheet_directory_uri}/images/favicon/favicon-32x32.png'>
            <link rel='icon' type='image/png' sizes='96x96' href='{$this->stylesheet_directory_uri}/images/favicon/favicon-96x96.png'>
            <link rel='icon' type='image/png' sizes='16x16' href='{$this->stylesheet_directory_uri}/images/favicon/favicon-16x16.png'>
            <link rel='manifest' href='{$this->stylesheet_directory_uri}/images/favicon/manifest.json'>
        ";
        echo $html;
   }
   
   public function custom_options_page()
   {
      // Site options page
      $parent = acf_add_options_page(array(
         'page_title' => __('Configuration'),
         'menu_title' => __('Configuration'),
         'menu_slug'  => 'ws-options',
         'position'	 => 80,
         'redirect'   => true,
      ));

      // Global options
      $globalOptions = acf_add_options_page(array(
         'page_title'  => __('Contenus globaux'),
         'menu_title'  => __('Contenus globaux'),
         'menu_slug'   => 'ws-options-contents',
         'parent_slug' => $parent['menu_slug']
      ));

      // Recursive sections
      $globalOptions = acf_add_options_page(array(
         'page_title'  => __('Sections récurrentes'),
         'menu_title'  => __('Sections récurrentes'),
         'menu_slug'   => 'ws-options-sections',
         'parent_slug' => $parent['menu_slug']
      ));

      //Header & footer
      $globalOptions = acf_add_options_page(array(
         'page_title'  => __('Header & Footer'),
         'menu_title'  => __('Header & Footer'),
         'menu_slug'   => 'ws-options-header_footer',
         'parent_slug' => $parent['menu_slug']
      ));
   }

   /**
    * Manage WP menus
    * (Don't forget to call them in add_to_context)
    */
   public function custom_menus() {
      register_nav_menu('menu-main', __('Menu Principal'));
      /*register_nav_menu('menu-open', __('Menu Open'));*/
      register_nav_menu('menu-footer', __('Menu Footer'));
      register_nav_menu('menu-footer2', __('Menu Footer 2'));
   }


   /* --------------------- */ 
   /* --- TIMBER METHOD --- */
   /* --------------------- */

   public function add_to_context($context) {
      $context['site'] = $this;
      $context['menu_main'] = new TimberMenu('menu-main');
      $context['menu_footer'] = new TimberMenu('menu-footer');
      $context['menu_footer_two'] = new TimberMenu('menu-footer2');
      /*$context['menu_open'] = new TimberMenu('menu-open');
      $context['menu_footer'] = new TimberMenu('menu-footer');*/
      $context['options'] = get_fields('option');
      
      return $context;
   }

   public function add_to_twig($twig) {
      $twig->addExtension(new Twig\Extension\StringLoaderExtension());
      $twig->addFilter(new TwigFilter('tagtohtml', function($text) {
         $tags = [
            '[b]'  => '<strong>',
            '[/b]' => '</strong>',
            '[br]' => '<br/>',
         ];
           return str_replace(array_keys($tags), array_values($tags), $text);
      }));

      $twig->addFilter(new TwigFilter('notags', function($text) {
         $tags = [
            '[b]'  => '',
            '[/b]' => '',
            '[br]' => '',
         ];
           return str_replace(array_keys($tags), array_values($tags), $text);
      }));

      $twig->addFilter(new TwigFilter('nop', function($text) {
         $tags = [
            '<p>' => '',
            '</p>' => '',
         ];
           return str_replace(array_keys($tags), array_values($tags), $text);
      }));

      return $twig;
   }

   /**
    * CLear twig cache on nginx-helper purge_all
    */
   private function clear_twig_cache()
   {
      $loader = new TimberLoader();
      $loader->clear_cache_twig();
   }
}

new WS_Starter();