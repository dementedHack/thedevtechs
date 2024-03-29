<?php

namespace ResponsiveMenuPro\Controllers;
use ResponsiveMenuPro\View\View;
use ResponsiveMenuPro\Management\OptionManager;
use ResponsiveMenuPro\Validation\Validator;
use ResponsiveMenuPro\Tasks\UpdateOptionsTask;
use ResponsiveMenuPro\Collections\OptionsCollection;

/**
* Entry point for all admin functionality.
*
* All routing for the admin comes through the functions below. When any
* button is pressed in the admin view, it will come through here.
*
* @author Peter Featherstone <peter@featherstone.me>
*
* @since 3.0
*/
class AdminController {

    /**
    * Constructor for setting up the AdminController.
    *
    * The constructor allows us to switch implementations for managing options
    * and for rendering views. Useful for switching out mocked or stubbed
    * classes during testing.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param OptionManager  $manager    Instance of a Management options class.
    * @param View           $view       Instance of a View class for rendering.
    */
    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    /**
    * Main GET route for the admin section.
    *
    * This is the default view for the plugin on an initial GET request to the
    * page.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @return string    Output HTML from rendered view.
    */
    public function index() {
        return $this->view->render(
            'admin/main.html.twig', [
                'options' => $this->manager->all()
            ]
        );
    }

    /**
    * Rebuild database POST route for the admin section.
    *
    * This route is called when the Rebuild Database button is clicked from
    * inside the admin section. The intention is to set the version back to
    * a pre 3.0 version, which will then force a rebuild.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @return string    Output HTML from rendered view.
    */
    public function rebuild() {
        update_option('responsive_menu_pro_version', '2.8.9');

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $this->manager->all(),
                'alert' => ['success' => 'Responsive Menu Pro Database Rebuilt Successfully.']
            ]
        );
    }

    /**
    * Apply a specific theme options and commit to the database.
    *
    * This route is called when the Apply Theme button is pressed inside the
    * admin area. It loads in the options file for the theme and updates the
    * options accordingly.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.1.16
    *
    * @param string  $theme     The theme name to apply
    *
    * @return string            Output HTML from rendered view.
    */
    public function apply_theme($theme) {
        $options = $this->manager->all();

        $upload_folder = wp_upload_dir()['basedir'];
        $theme_folder = $upload_folder . '/responsive-menu-themes/';
        $options_file_location = $theme_folder . $theme . '/options.json';

        $theme_options_file = file_get_contents($options_file_location);
        $theme_options = json_decode($theme_options_file, true);

        foreach($theme_options as $key => $value)
            $options[$key] = $value;

        $options['menu_theme'] = $theme;

        $this->manager->updateOptions($options->toArray());

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => ['success' => 'Responsive Menu Pro Theme Applied Successfully.']
            ]
        );
    }

    /**
    * Import a theme from a zip file.
    *
    * This route is called when the Upload Theme button is pressed inside the
    * admin area. It loads in the selected zip file for the theme and unpacks
    * it in the uploads directory.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.1.16
    *
    * @param string  $theme     The theme file location to unzip
    *
    * @return string            Output HTML from rendered view.
    */
    public function import_theme($theme) {
        if($theme):
            WP_Filesystem();
            $upload_folder = wp_upload_dir()['basedir'] . '/responsive-menu-themes';

            $unzipfile = unzip_file($theme, $upload_folder);

            if(is_wp_error($unzipfile)) {
                $alert = ['danger' => $unzipfile->get_error_message()];
            } else {
                $alert = ['success' => 'Responsive Menu Pro Theme Imported Successfully.'];
            }

        else:
            $alert = ['danger' => 'No import file selected'];

        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $this->manager->all(),
                'alert' => $alert
            ]
        );
    }

    /**
    * Update the options based on submitted admin form.
    *
    * This route is called whenever the Update Options button is pressed and is
    * the most commonly called route. Takes the submitted options and runs the
    * update options task.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param bool   $valid_nonce    Is the form nonce valid or not
    * @param array  $new_options    An array of the submitted options.
    *
    * @return string                Output HTML from rendered view.
    */
    public function update($valid_nonce, $new_options) {
        $validator = new Validator();
        $errors = [];

        if(!$valid_nonce):
            $alert = ['danger' => 'CSRF token not valid'];
            $options = new OptionsCollection($new_options);

        elseif($validator->validate($new_options)):
            try {
                $options = $this->manager->updateOptions($new_options);
                $task = new UpdateOptionsTask;
                $task->run($options, $this->view);
                $alert = ['success' => 'Responsive Menu Pro Options Updated Successfully.'];
            } catch (\Exception $e) {
                $alert = ['danger' => $e->getMessage()];
            }

        else:
            $options = new OptionsCollection($new_options);
            $errors = $validator->getErrors();
            $alert = ['danger' => $errors];

        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert,
                'errors' => $errors
            ]
        );
    }

    /**
    * Reset the options back to the defaults.
    *
    * This route is called whenever the Reset Options button is pressed. Resets
    * all options back to their default states and runs the update options
    * task.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param array  $default_options    An array of the default options.
    *
    * @return string                    Output HTML from rendered view.
    */
    public function reset($default_options) {
        try {
            $options = $this->manager->updateOptions($default_options);
            $task = new UpdateOptionsTask;
            $task->run($options, $this->view);
            $alert = ['success' => 'Responsive Menu Pro Options Reset Successfully'];

        } catch(\Exception $e) {
            $options = new OptionsCollection($default_options);
            $alert = ['danger' => $e->getMessage()];
        }

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert
            ]
        );
    }

    /**
    * Import options from a file.
    *
    * This route is called when the Import Options button is pressed inside the
    * admin area. It loads in the selected json file and updates the options.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param array  $imported_options   An array of the imported options.
    *
    * @return string                    Output HTML from rendered view.
    */
    public function import($imported_options) {
        $errors = [];
        if(!empty($imported_options)):
            $validator = new Validator();
            if($validator->validate($imported_options)):
                try {
                    unset($imported_options['button_click_trigger']);
                    $options = $this->manager->updateOptions($imported_options);
                    $task = new UpdateOptionsTask;
                    $task->run($options, $this->view);
                    $alert = ['success' => 'Responsive Menu Pro Options Imported Successfully.'];

                } catch(\Exception $e) {
                    $options = $this->manager->all();
                    $alert = ['danger' => $e->getMessage()];
                }

            else:
                $options = new OptionsCollection($imported_options);
                $errors = $validator->getErrors();
                $alert = ['danger' => $errors];

            endif;

        else:
            $options = $this->manager->all();
            $alert = ['danger' => 'No import file selected'];

        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert,
                'errors' => $errors
            ]
        );
    }

    /**
    * Check an entered license key and apply it.
    *
    * This route is called when the Add License Key button is pressed inside
    * the admin area. It checks the provided license key for validity and
    * updates the license status for the account.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param string     $license_key   Provided license key.
    *
    * @return string                    Output HTML from rendered view.
    */
    public function license($license_key) {
        $license_key = trim($license_key);
        $alert = [];
        if(!$license_key):
            $alert = ['danger' => 'No license key added'];
            update_option('responsive_menu_pro_license_type', '');
            update_option('responsive_menu_pro_license_key', '');

        else:
            /*
            First Check The Single License */
            $response = wp_remote_get('https://responsive.menu/?' . http_build_query(
                    [
                        'edd_action'=> 'activate_license',
                        'license' 	=> $license_key,
                        'item_name' => urlencode('Responsive Menu Pro - Single License'),
                        'url'       => home_url()
                    ]
                ), ['decompress' => false]);
            $license_type = 'Single License';

            if(is_wp_error($response))
                $alert = ['danger' => $response->get_error_message() . ' - Please <a href="https://responsive.menu/faq/license-activation-issues" target="_blank"> click here</a> for more information.'];
            else
                $response = json_decode($response['body']);

            /* Parse Result */
            if(!isset($response->success) || !$response->success):
                /* Now Check The Multi License */
                $response = wp_remote_get('https://responsive.menu/?' . http_build_query(
                        [
                            'edd_action'=> 'activate_license',
                            'license' 	=> $license_key,
                            'item_name' => urlencode('Responsive Menu Pro - Multi License'),
                            'url'       => home_url()
                        ]
                    ), ['decompress' => false]);
                $license_type = 'Multi License';
                if(is_wp_error($response))
                    $alert = ['danger' => $response->get_error_message() . ' - Please <a href="https://responsive.menu/faq/license-activation-issues" target="_blank"> click here</a> for more information.'];
                else
                    $response = json_decode($response['body']);
            endif;

            if(isset($response->success) && $response->success):
                update_option('responsive_menu_pro_license_type', $license_type);
                $alert = ['success' => 'License key updated'];
            else:
                update_option('responsive_menu_pro_license_type', '');
                if(!is_wp_error($response))
                    $alert = ['danger' => 'License key invalid' . ' - Please <a href="https://responsive.menu/knowledgebase/license-activation-issues/" target="_blank"> click here</a> for more information.'];
            endif;
            update_option('responsive_menu_pro_license_key', $license_key);
        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'alert' => $alert,
                'options' => $this->manager->all()
            ]
        );
    }

    /**
    * Export all current options to a json file.
    *
    * This route is called when the Export Options button is pressed inside
    * the admin area. It returns a json file of all the current options as a
    * download attachment to the browser.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @return attachment    json file attachment of plugin options.
    */
    public function export() {
        return json_encode(
            $this->manager->all()->toArray()
        );
    }

}
