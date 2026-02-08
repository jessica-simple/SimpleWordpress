<?php
/**
 * Plugin Name: BeCustom
 * Plugin URI: https://muffingroup.com/betheme/features/be-custom/
 * Description: Rebrand Be & WordPress Admin And Take Your Business To The Next Level. Be like PRO!
 * Version: 1.2.3
 * Author: Muffin Group
 * Author URI: https://muffingroup.com
 *
 * @Changelog
 *
 * 1.0.7
 * - PHP 8.2 Deprecated: Creation of dynamic property
 * - PHP 8.2 Deprecated: strip_tags(): Passing null to parameter #1
 *
 * 1.0.8
 * - Branding: Include parent theme image, name, description and author
 *
 * 1.0.9
 * - WP Login: WP 6.7 logo
 *
 * 1.1
 * - WP 6.7 compatibility (load_textdomain action changed)
 *
 * 1.2
 * - PHP Notice: Translation loading for the becustom domain was triggered too early
 *
 * 1.2.1
 * - PHP error: Reference to a removed function
 *
 * 1.2.2
 * - PHP Notice: $get_attributes is deprecated
 *
 * 1.2.3
 * - PHP Notice: Translation loading for the becustom domain was triggered too early
 * - Option to disable single page import (requires Betheme version 28.0.6+)
 */


class Be_custom {

  public $becustom_subpages = array('branding', 'built_in_features', 'wp_login', 'be_dashboard', 'advanced'); //always must be in proper setup, LTR in tabs
  public $betheme_fields;

  /* Subpages above fields used */
  public $branding = array();

  public $built_in_features = array();

  public $wp_login = array();

  public $be_dashboard = array();

  public $advanced = array();

  /**
   * Register the templates, required to tabs work
   */

  public function get_template() {
    include_once( plugin_dir_path( __DIR__ ) . 'becustom/templates/general.php' );
  }

  public function load_subpages() {

    load_plugin_textdomain( 'becstm' );

    if( defined('MFN_THEME_VERSION') ){
      foreach($this->becustom_subpages as $page){
        include_once( plugin_dir_path( __DIR__ ) . 'becustom/becustom_'.$page.'.php');

        //to clear all records
        //delete_option('be_custom_'.$page);
      }
    }
  }

  public function add_menu() {
    $default_theme_slug = 'be';
    $default_theme_label = 'betheme';

    $page = add_submenu_page(
        apply_filters('betheme_dynamic_slug', 'betheme'),
        __( 'BeCustom', 'mfn-translate' ),
        'BeCustom',
        'edit_theme_options',
        'be_custom',
        array( $this, 'get_template')
    );

    add_action('admin_print_styles-'. $page, array( $this, 'enqueue' ));
  }


  /**
   * Register or display tables
   * It will iterate and check the form values and names
   */
  public function iterate_merge_array($page_name) {
    $user_settings = get_option( 'be_custom_'.$page_name );

    $db_schema = $this->$page_name;
    $merge_first_dimension = shortcode_atts( $db_schema, $user_settings);

    $merged_array = array();

    foreach($db_schema as $tableName => $tableValue){
      $merged_array[$tableName] = shortcode_atts($db_schema[$tableName], $merge_first_dimension[$tableName]);
    }

    return $merged_array;
  }

  /*
    Provide popup html
  */

  public function popup($attribute_name){
    $roll_uid = uniqid();

    if( empty($this->get_attributes[$attribute_name]['popup_content']) ){
      return;
    }

    return '<a href="#popup-'. $roll_uid .'" class="popup-link mfn-option-btn"><span class="mfn-icon mfn-icon-information"></span></a>
      <div id="popup-'. $roll_uid .'" class="popup-content">
        <div class="popup-inner" style="padding:10px;">
          <div class="becustom-popup-image">
            <img src="'. $this->get_attributes[$attribute_name]['popup_content']['image'] .'" />
          </div>
          <div class="becustom-popup-text">
            '. __($this->get_attributes[$attribute_name]['popup_content']['text'], "becustom") .'
          </div>
        </div>
      </div>';
  }

  /*
    Pass values straight from the array
  */

  public function get_page_attributes($page_name){
    return $this->$page_name;
  }

  /**
   * Enqueue styles and scripts
  */

	public function enqueue()
	{
		wp_enqueue_script( 'be_custom_js', '/wp-content/plugins/becustom/assets/script.js', array('jquery'), MFN_THEME_VERSION, true );
    wp_enqueue_style( 'be_custom_css', '/wp-content/plugins/becustom/assets/style.css', false, MFN_THEME_VERSION, 'all' );

    $fields_JS = [
      'color',
      'switch',
      'textarea',
      'upload',
      'visual',
    ];

    foreach ( $fields_JS as $type ){
      require_once( get_template_directory() .'/muffin-options/fields/'. $type .'/field_'. $type .'.php' );
      $field_class = 'MFN_Options_'. $type;
      $field_object = new $field_class();
      $field_object->enqueue();
    }
	}

  /**
   *	Registration | Is registered
  */

  function mfn_is_registered()
  {
    if ( $this->mfn_get_purchase_code() ) {
      return strlen( $this->mfn_get_purchase_code() );
    }

    return false;
  }

  /**
   *	Registration | Get purchase code
  */

  function mfn_get_purchase_code()
  {
    $code = get_site_option( 'envato_purchase_code_7758048' );

    if( ! $code ){
      // BeTheme < 21.0.8 backward compatibility
      $code = get_site_option( 'betheme_purchase_code' );
      if( $code ){
        update_site_option( 'envato_purchase_code_7758048', $code );
        delete_site_option( 'betheme_purchase_code' );
        delete_site_option( 'betheme_registered' );
      }
    }

    return $code;
  }

  /*
    This function is used only when theme is not
    registered, to check, if label is changed (proper redirect after registering)
  */
  public function becustom_check_without_register($default){
    $theme_slug = $this->iterate_merge_array( 'branding' )['betheme_url_slug']['value'];

    if(($theme_slug)){
      $default = preg_replace('/betheme/', $theme_slug, $default);
    }

    return $default;
  }

  public function __construct() {

    $this->branding = array(
      'betheme_label' => array(
        'type' => 'text',
        'filter_name' => 'betheme_label',
        'title' => __('Default BeTheme text', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option changes default <strong>Betheme</strong> text located in main WP dashboard and just after the Welcome text in <i>Betheme > Dashboard</i> section.', 'becstm')
        )
      ),
      'replaced_logo_url' => array(
        'type' => 'upload',
        'filter_name' => 'betheme_logo',
        'title' => __('Betheme & Muffin group logo', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option changes any Betheme & Muffin group logo (some located in <i>Betheme > Dashboard</i> section, other in Muffin Builder.)', 'becstm')
        )
      ),
      'replaced_theme_image' => array(
        'type' => 'upload',
        'filter_name' => 'betheme_image',
        'title' => __('Default Betheme theme image', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default Betheme image in <i>Appearance > Themes</i> section.', 'becstm')
        )
      ),
      'replaced_theme_desc' => array(
        'type' => 'text',
        'filter_name' => 'betheme_desc',
        'title' => __('Theme description WP', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default Betheme description in <i>Appearance > Themes</i> section.', 'becstm')
        )
      ),
      'replaced_theme_author' => array(
        'type' => 'text',
        'filter_name' => 'betheme_author',
        'title' => __('Default Betheme author', 'becstm'),
        'value' => 'Muffin Group',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default Betheme author in <i>Appearance > Themes</i> section.', 'becstm')
        )
      ),
      'betheme_url_slug' => array(
        'type' => 'text',
        'filter_name' => 'betheme_slug',
        'title' => __('be friendly URL', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces each occurance of default <b>be</b> in URL.', 'becstm')
        )
      ),

      //FIELDS WHERE USERS CANNOT CHANGE ANYTHING
      'betheme_dynamic_slug' => array(
        'type' => 'text',
        'filter_name' => 'betheme_dynamic_slug',
      ),
      'betheme_input_options' => array( //theme options input value
        'type' => 'text',
        'filter_name' => 'betheme_options_filed_options',
      ),
      'betheme_input_title' => array( //theme options title value
        'type' => 'text',
        'filter_name' => 'betheme_options_filed_title',
      ),
      'betheme_input_desc' => array( //theme options desc value
        'type' => 'text',
        'filter_name' => 'betheme_options_filed_desc',
      ),
      'betheme_replaced_logo_nohtml' => array(
        'type' => 'upload',
        'filter_name' => 'betheme_logo_nohtml',
      ),
    );

    $this->built_in_features = array(
      'disable_theme_version' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_theme_version',
        'title' => __('Theme version', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes theme version visible or hidden in <i>Betheme > Theme Options</i>.', 'becstm')
        )
      ),
      'disable_single_import' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_single',
        'title' => __('Single page import', 'becstm'),
        'value' => false,
      ),
      'disable_support_link' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_support',
        'title' => __('Manual & Support tab', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes Betheme <b>Manual & Support</b> tab visible or hidden in <i>Betheme > Dashboard</i>.', 'becstm')
        )
      ),
      'disable_changelog_link' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_changelog',
        'title' => __('Changelog tab', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes Betheme <b>Changelog</b> tab visible or hidden in <i>Betheme > Dashboard</i>.', 'becstm')
        )
      ),
      'disable_theme_update' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_theme_update',
        'title' => __('Theme Update', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option disable/enable <b>Betheme Update</b> button in <i>Betheme > Dashboard</i>.', 'becstm')
        )
      ),
      'disable_advanced_tab' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_advanced',
        'title' => __('Advanced tab', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes <b>Advanced</b> tab visible or hidden in <i>Betheme > Theme Options</i>.', 'becstm')
        )
      ),
      'disable_hooks_tab' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_hooks',
        'title' => __('Hooks tab', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes <b>Hooks</b> tab visible or hidden in <i>Betheme > Theme Options</i>.', 'becstm')
        )
      ),
      'disable_footer_copy' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_footer',
        'title' => __('Footer copyright', 'becstm'),
        'value' => false,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option makes <b>Footer copyright</b> visible or hidden in front-end.', 'becstm')
        )
      ),
    );

    $this->wp_login = array(
      'enable_custom_login' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_custom_login',
        'title' => __('Enable the custom WP-Login page', 'becstm'),
        'value' => false
      ),
      'custom_wplogin_logo' => array(
        'type' => 'upload',
        'filter_name' => 'betheme_wplogin_logo',
        'title' => __('Custom WP-Login logo', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('this option replaces default WP logo on WP-Login page with your own.', 'becstm')
        )
      ),
      'custom_background_color' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_bg_color',
        'title' => __('WP-Login page background color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default background color on WP-Login page.', 'becstm')
        )
      ),
      'custom_font_color' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_font_color',
        'title' => __('WP-Login page font color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default font color on WP-Login page for texts like <i>Lost your password?</i> or <i><- Go to XYZ</i>.', 'becstm')
        )
      ),
      'custom_background_image' => array(
        'type' => 'upload',
        'filter_name' => 'betheme_wplogin_bg_image',
        'title' => __('WP-Login page background image', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default background color on WP-Login page with custom background image.', 'becstm')
        )
      ),
      'custom_background_size' => array(
        'type' => 'select',
        'filter_name' => 'betheme_wplogin_bg_size',
        'title' => __('WP-Login page background image size', 'becstm'),
        'value' => 'Auto',
        'popup_content' => array(
          'image' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Example_image.png',
          'text' => 'Oh boy',
        )
      ),
      'custom_background_position' => array(
        'type' => 'select',
        'filter_name' => 'betheme_wplogin_bg_position',
        'title' => __('WP-Login page background image position', 'becstm'),
        'value' => 'no-repeat;left top;;',
        'popup_content' => array(
          'image' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Example_image.png',
          'text' => 'Oh boy',
        )
      ),
      'custom_login_container_position' => array(
        'type' => 'select',
        'filter_name' => 'betheme_wplogin_container_position',
        'title' => __('WP-Login Container position', 'becstm'),
        'value' => 'unset',
        'std' => 'unset',
        'popup_content' => array(
          'image' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Example_image.png',
          'text' => 'Oh boy',
        )
      ),
      'custom_login_container_background' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_container_bg',
        'title' => __('WP-Login container background color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default background color on WP-Login page container with custom background color.', 'becstm')
        )
      ),
      'custom_login_container_font_color' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_container_font_color',
        'title' => __('WP-Login container font color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default font color inside container on WP-Login page.', 'becstm')
        )
      ),
      'custom_login_container_input_background' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_container_input_bg',
        'title' => __('WP-Login container input background color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default input background color inside container on WP-Login page.', 'becstm')
        )
      ),
      'custom_login_container_input_font_color' => array(
        'type' => 'text',
        'filter_name' => 'betheme_wplogin_container_input_color',
        'title' => __('WP-Login container input font color', 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces default input font color inside container on WP-Login page.', 'becstm')
        )
      ),
      'enable_forgot_password' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_wplogin_enable_forgot_password',
        'title' => __('Forgot Password', 'becstm'),
        'value' => true,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option enable/disable <b>Forgot Password</b> feature located under container on WP-Login page.', 'becstm')
        )
      ),
      'enable_goto_link' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_wplogin_enable_gotolink',
        'title' => __('"Go TO XYZ"', 'becstm'),
        'value' => true,
        'popup_content' => array(
          'image' => '',
          'text' => __('This option enable/disable <b>Go to XYZ</b> feature located under container on WP-Login page.', 'becstm')
        )
      )
    );

    $this->be_dashboard = array(
      'disable_survey' => array(
        'type' => 'switch',
        'filter_name' => 'betheme_disable_survey',
        'title' => __('Survey banner', 'becstm'),
        'value' => false,
      ),
      'subheader' => array(
        'type'  => 'text',
        'filter_name' => 'betheme_dashboard_subheader',
        'title' => __("Change content of dashboard subheader", 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces subheader content in  <i>Betheme > Dashboard</i>.','becstm')
        )
      ),
      'content' => array(
        'type'  => 'text',
        'filter_name' => 'betheme_dashboard_content',
        'title' => __("Change content in dashboard.", 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces content located in <i>Betheme > Dashboard</i>.','becstm')
        )
      ),
      'footer' => array(
        'type'  => 'text',
        'filter_name' => 'betheme_dashboard_footer',
        'title' => __("Change content on footer of dashboard", 'becstm'),
        'value' => '',
        'popup_content' => array(
          'image' => '',
          'text' => __('This option replaces content just underneath the <i>Content</i>.','becstm')
        )
      )
    );

    if( $this->mfn_is_registered() ) {
      //menu & subpages
      add_action( 'admin_menu', array( $this, 'add_menu'), 20);
      add_action( 'init', array( $this, 'load_subpages' ), 1 );
    }else{
      add_filter( 'becustom_check_without_register',  array( $this, 'becustom_check_without_register' ), 1, 3);
    }
  }
}

new Be_custom();
