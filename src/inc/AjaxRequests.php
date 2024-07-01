<?php 

namespace WS_2020\inc;

use WS_2020\inc\core\AbstractController;
use Timber;

class AjaxRequests extends AbstractController
{
    public function __construct()
    {
        $this->handle_request($this, 'test');
        $this->handle_request($this, 'handle_form_contact');
        date_default_timezone_set('Europe/Paris');
    }

    public function test()
    {
        $output = 'Hello world 👋';
        wp_send_json($output);
        wp_die();
    }

    public function handle_form_contact()
    {
        if (!isset($_POST['contact']) || empty($_POST['contact'])) wp_die();

        //ACF contact adress
        /*$options = get_fields('option');
        $email = $options['contact_support_devis']['email_de_contact'];*/
        $email = 'test@test.fr';

        //$this->verifyCaptcha();

        //Send to website owner
        /*$html = Timber::compile('mails/contact.twig', ['form' => $_POST['contact'], 'to' => 'owner']);
        if (wp_mail([$email], "Demande de contact depuis votre site web", $html)) {
            //Send confirmation to user
            $html = Timber::compile('mails/contact.twig', ['form' => $_POST['contact'], 'to' => 'user']);
            wp_mail($_POST['contact']['email'], "SITENAME - Votre demande de contact a bien été transmise", $html);

            wp_send_json(['status' => 'success', 'message' => __('Votre demande a bien été transmise', 'ws-starter')]);
            wp_die();
        }*/

        //Falback if mail() failed
        wp_send_json(['status' => 'error', 'message' => __('Impossible de transmettre votre demande, réessayez plus tard.', 'ws-starter')]);
        wp_die();
    }
}