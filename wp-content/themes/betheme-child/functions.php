<?php
function betheme_child_enqueue_styles_scripts() {
    // Load parent style
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // Load child style
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );

    // Load Remix Icon (CDN)
    wp_enqueue_style( 'remixicon', 'https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css', array(), null );
}
add_action( 'wp_enqueue_scripts', 'betheme_child_enqueue_styles_scripts' );

// Footer Widget Shortcode
function mfn_footer_widget_shortcode() {
    ob_start();
    ?>
    <?php if (mfn_opts_get('footer-hide') != 1 && ! apply_filters( 'betheme_disable_footer', false ) ): ?>
        <?php
            if (has_nav_menu('social-menu-bottom')) {
              mfn_wp_social_menu_bottom();
            } else {
              get_template_part('includes/include', 'social');
            }
          ?>

          <div class="copyright">
            <?php
              if (mfn_opts_get('footer-copy')) {
                echo do_shortcode(mfn_opts_get('footer-copy'));
              } else {
                echo '&copy; '. esc_html(date('Y')) .' Betheme by <a href="https://muffingroup.com" target="_blank">Muffin group</a> | All Rights Reserved | Powered by <a href="https://wordpress.org" target="_blank">WordPress</a>';
              }
            ?>
          </div>

    <?php endif;
    
    return ob_get_clean();
}

add_shortcode('footer_widget', 'mfn_footer_widget_shortcode');

