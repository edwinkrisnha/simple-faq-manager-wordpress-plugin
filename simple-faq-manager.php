<?php
/**
 * Plugin Name: 			Simple FAQ Manager
 * Plugin URI:  			https://github.com/edwinkrisnha/simple-faq-manager-wordpress-plugin
 * Description: 			Manage FAQs with categories, drag-and-drop widget ordering, shortcodes, and an Elementor widget.
 * Version:     			1.0.3
 * Author:            Edwin Krisnha
 * Author URI:        https://github.com/edwinkrisnha
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 			simple-faq-manager
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Tested up to:      6.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SFM_VERSION', '1.0.3' );
define( 'SFM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SFM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SFM_PLUGIN_DIR . 'includes/settings.php';
require_once SFM_PLUGIN_DIR . 'includes/admin-widget-faqs.php';
require_once SFM_PLUGIN_DIR . 'includes/shortcodes.php';

// ---------------------------------------------------------------------------
// Custom Post Type & Taxonomy
// ---------------------------------------------------------------------------

add_action( 'init', 'sfm_register_cpt' );
function sfm_register_cpt() {
	register_post_type(
		'faq',
		array(
			'labels'        => array(
				'name'               => __( 'FAQs', 'simple-faq-manager' ),
				'singular_name'      => __( 'FAQ', 'simple-faq-manager' ),
				'add_new_item'       => __( 'Add New FAQ', 'simple-faq-manager' ),
				'edit_item'          => __( 'Edit FAQ', 'simple-faq-manager' ),
				'new_item'           => __( 'New FAQ', 'simple-faq-manager' ),
				'view_item'          => __( 'View FAQ', 'simple-faq-manager' ),
				'search_items'       => __( 'Search FAQs', 'simple-faq-manager' ),
				'not_found'          => __( 'No FAQs found', 'simple-faq-manager' ),
				'not_found_in_trash' => __( 'No FAQs found in Trash', 'simple-faq-manager' ),
				'menu_name'          => __( 'FAQs', 'simple-faq-manager' ),
			),
			'public'        => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-editor-help',
			'supports'      => array( 'title', 'editor' ),
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'faq' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'faq_category',
		'faq',
		array(
			'labels'            => array(
				'name'          => __( 'FAQ Categories', 'simple-faq-manager' ),
				'singular_name' => __( 'FAQ Category', 'simple-faq-manager' ),
				'search_items'  => __( 'Search FAQ Categories', 'simple-faq-manager' ),
				'all_items'     => __( 'All FAQ Categories', 'simple-faq-manager' ),
				'edit_item'     => __( 'Edit FAQ Category', 'simple-faq-manager' ),
				'update_item'   => __( 'Update FAQ Category', 'simple-faq-manager' ),
				'add_new_item'  => __( 'Add New FAQ Category', 'simple-faq-manager' ),
				'new_item_name' => __( 'New FAQ Category Name', 'simple-faq-manager' ),
				'menu_name'     => __( 'Categories', 'simple-faq-manager' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'faq-category' ),
			'show_in_rest'      => true,
		)
	);
}

// ---------------------------------------------------------------------------
// Meta Fields
// ---------------------------------------------------------------------------

add_action( 'init', 'sfm_register_meta' );
function sfm_register_meta() {
	register_post_meta(
		'faq',
		'sfm_show_on_widget',
		array(
			'type'         => 'boolean',
			'default'      => false,
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_post_meta(
		'faq',
		'sfm_widget_order',
		array(
			'type'         => 'integer',
			'default'      => 0,
			'single'       => true,
			'show_in_rest' => true,
		)
	);
}

// Meta box on the individual FAQ edit screen
add_action( 'add_meta_boxes', 'sfm_add_meta_boxes' );
function sfm_add_meta_boxes() {
	add_meta_box(
		'sfm_faq_options',
		__( 'FAQ Widget Options', 'simple-faq-manager' ),
		'sfm_render_meta_box',
		'faq',
		'side'
	);
}

function sfm_render_meta_box( $post ) {
	wp_nonce_field( 'sfm_save_meta', 'sfm_meta_nonce' );
	$show_on_widget = (bool) get_post_meta( $post->ID, 'sfm_show_on_widget', true );
	$widget_order   = (int) get_post_meta( $post->ID, 'sfm_widget_order', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="sfm_show_on_widget" value="1" <?php checked( $show_on_widget ); ?>>
			<?php esc_html_e( 'Show on Widget', 'simple-faq-manager' ); ?>
		</label>
	</p>
	<p>
		<label for="sfm_widget_order"><?php esc_html_e( 'Widget Order', 'simple-faq-manager' ); ?></label><br>
		<input type="number" id="sfm_widget_order" name="sfm_widget_order"
			value="<?php echo esc_attr( $widget_order ); ?>" style="width:100%">
	</p>
	<?php
}

add_action( 'save_post_faq', 'sfm_save_meta_box' );
function sfm_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['sfm_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sfm_meta_nonce'] ) ), 'sfm_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, 'sfm_show_on_widget', isset( $_POST['sfm_show_on_widget'] ) );
	update_post_meta( $post_id, 'sfm_widget_order', isset( $_POST['sfm_widget_order'] ) ? intval( $_POST['sfm_widget_order'] ) : 0 );
}

// ---------------------------------------------------------------------------
// Asset Registration & Conditional Enqueueing
// ---------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'sfm_register_frontend_assets' );
function sfm_register_frontend_assets() {
	// Register so Elementor widget's get_style/script_depends() can declare them.
	wp_register_style(
		'sfm-frontend',
		SFM_PLUGIN_URL . 'assets/css/frontend.css',
		array(),
		SFM_VERSION
	);
	wp_register_script(
		'sfm-frontend-search',
		SFM_PLUGIN_URL . 'assets/js/frontend-search.js',
		array( 'jquery' ),
		SFM_VERSION,
		true
	);

	// Pass plugin settings to JS (works on both shortcode pages and Elementor pages).
	$s = sfm_get_settings();
	wp_localize_script(
		'sfm-frontend-search',
		'sfmSettings',
		array(
			'listDisplayMode'   => $s['list_display_mode'],
			'listShowExpandAll' => '1' === $s['list_show_expand_all'],
			'listExclusive'     => '1' === $s['list_exclusive'],
			'widgetOpenFirst'   => '1' === $s['widget_open_first'],
			'widgetExclusive'   => '1' === $s['widget_exclusive'],
			'i18n'              => array(
				'expandAll'   => __( 'Expand All', 'simple-faq-manager' ),
				'collapseAll' => __( 'Collapse All', 'simple-faq-manager' ),
			),
		)
	);

	// Enqueue only on pages that actually use the shortcodes.
	global $post;
	if ( is_a( $post, 'WP_Post' ) &&
		( has_shortcode( $post->post_content, 'faq_list' ) || has_shortcode( $post->post_content, 'faq_widget' ) )
	) {
		wp_enqueue_style( 'sfm-frontend' );
		wp_enqueue_script( 'sfm-frontend-search' );
	}
}

add_action( 'admin_enqueue_scripts', 'sfm_enqueue_admin_assets' );
function sfm_enqueue_admin_assets( $hook ) {
	if ( 'faq_page_sfm-widget-faqs' !== $hook ) {
		return;
	}
	wp_enqueue_style(
		'sfm-admin',
		SFM_PLUGIN_URL . 'assets/css/admin.css',
		array(),
		SFM_VERSION
	);
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script(
		'sfm-admin-sortable',
		SFM_PLUGIN_URL . 'assets/js/admin-sortable.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SFM_VERSION,
		true
	);
	wp_localize_script(
		'sfm-admin-sortable',
		'sfmAdmin',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'sfm_admin_nonce' ),
			'i18n'    => array(
				'orderSaved'   => __( 'Order saved.', 'simple-faq-manager' ),
				'orderFailed'  => __( 'Failed to save order.', 'simple-faq-manager' ),
				'toggleSaved'  => __( 'Widget setting saved.', 'simple-faq-manager' ),
				'toggleFailed' => __( 'Failed to save setting.', 'simple-faq-manager' ),
			),
		)
	);
}

// ---------------------------------------------------------------------------
// Elementor Integration
// ---------------------------------------------------------------------------

// Register the custom "FAQ" category in Elementor's panel.
add_action( 'elementor/elements/categories_registered', 'sfm_register_elementor_category' );
function sfm_register_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'sfm-faq',
		array(
			'title' => __( 'FAQ', 'simple-faq-manager' ),
			'icon'  => 'fa fa-question-circle',
		)
	);
}

// Register the widget itself (Elementor 3.x API).
add_action( 'elementor/widgets/register', 'sfm_register_elementor_widget' );
function sfm_register_elementor_widget( $widgets_manager ) {
	require_once SFM_PLUGIN_DIR . 'includes/elementor-widget.php';
	$widgets_manager->register( new SFM_Elementor_Widget() );
}

// ---------------------------------------------------------------------------
// Activation / Deactivation
// ---------------------------------------------------------------------------

register_activation_hook( __FILE__, 'sfm_activate' );
function sfm_activate() {
	sfm_register_cpt();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'sfm_deactivate' );
function sfm_deactivate() {
	flush_rewrite_rules();
}
