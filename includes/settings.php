<?php
/**
 * Plugin Settings
 *
 * Single settings page at FAQs > Settings.
 * All options stored in one wp_option key: sfm_settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// Defaults & getter
// ---------------------------------------------------------------------------

function sfm_default_settings() {
	return array(
		// [faq_list]
		'list_display_mode'    => 'expanded',  // 'expanded' | 'accordion'
		'list_show_search'     => '1',
		'list_show_cat_filter' => '1',
		'list_show_all_btn'    => '1',         // show "All" button inside category filter row
		'list_show_expand_all' => '1',         // expand/collapse all btn (accordion mode only)
		'list_exclusive'       => '1',         // close sibling items when one opens (accordion mode)
		'list_sort'            => 'title_asc', // title_asc|title_desc|date_desc|date_asc|menu_order
		// [faq_widget] & Elementor widget
		'widget_open_first'    => '0',
		'widget_exclusive'     => '1',         // close sibling items when one opens
		// SEO
		'enable_schema'        => '1',         // output JSON-LD FAQPage schema
	);
}

function sfm_get_settings() {
	return wp_parse_args( get_option( 'sfm_settings', array() ), sfm_default_settings() );
}

// ---------------------------------------------------------------------------
// Admin menu
// ---------------------------------------------------------------------------

add_action( 'admin_menu', 'sfm_settings_menu' );
function sfm_settings_menu() {
	add_submenu_page(
		'edit.php?post_type=faq',
		__( 'FAQ Settings', 'simple-faq-manager' ),
		__( 'Settings', 'simple-faq-manager' ),
		'manage_options',
		'sfm-settings',
		'sfm_render_settings_page'
	);
}

// ---------------------------------------------------------------------------
// Settings API registration
// ---------------------------------------------------------------------------

add_action( 'admin_init', 'sfm_register_settings' );
function sfm_register_settings() {
	register_setting(
		'sfm_settings_group',
		'sfm_settings',
		array( 'sanitize_callback' => 'sfm_sanitize_settings' )
	);

	// --- Section: FAQ List ---
	add_settings_section(
		'sfm_section_list',
		__( 'FAQ List — [faq_list]', 'simple-faq-manager' ),
		function () {
			echo '<p class="description">' . esc_html__( 'Controls the appearance and behaviour of the [faq_list] shortcode.', 'simple-faq-manager' ) . '</p>';
		},
		'sfm-settings'
	);

	add_settings_field( 'sfm_list_display_mode',    __( 'Display Mode', 'simple-faq-manager' ),            'sfm_field_list_display_mode',    'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_show_search',     __( 'Search Bar', 'simple-faq-manager' ),              'sfm_field_list_show_search',     'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_show_cat_filter', __( 'Category Filter', 'simple-faq-manager' ),         'sfm_field_list_show_cat_filter', 'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_show_all_btn',    __( '"All" Button', 'simple-faq-manager' ),            'sfm_field_list_show_all_btn',    'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_show_expand_all', __( 'Expand / Collapse All', 'simple-faq-manager' ),   'sfm_field_list_show_expand_all', 'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_exclusive',       __( 'Exclusive Accordion', 'simple-faq-manager' ),     'sfm_field_list_exclusive',       'sfm-settings', 'sfm_section_list' );
	add_settings_field( 'sfm_list_sort',            __( 'Sort Order', 'simple-faq-manager' ),              'sfm_field_list_sort',            'sfm-settings', 'sfm_section_list' );

	// --- Section: FAQ Widget ---
	add_settings_section(
		'sfm_section_widget',
		__( 'FAQ Widget — [faq_widget] & Elementor Widget', 'simple-faq-manager' ),
		function () {
			echo '<p class="description">' . esc_html__( 'Controls the accordion behaviour of the FAQ widget.', 'simple-faq-manager' ) . '</p>';
		},
		'sfm-settings'
	);

	add_settings_field( 'sfm_widget_open_first', __( 'Open First Item', 'simple-faq-manager' ),      'sfm_field_widget_open_first', 'sfm-settings', 'sfm_section_widget' );
	add_settings_field( 'sfm_widget_exclusive',  __( 'Exclusive Accordion', 'simple-faq-manager' ),  'sfm_field_widget_exclusive',  'sfm-settings', 'sfm_section_widget' );

	// --- Section: SEO ---
	add_settings_section(
		'sfm_section_seo',
		__( 'SEO', 'simple-faq-manager' ),
		function () {
			echo '<p class="description">' . esc_html__( 'Structured data helps search engines display FAQ rich results.', 'simple-faq-manager' ) . '</p>';
		},
		'sfm-settings'
	);

	add_settings_field( 'sfm_enable_schema', __( 'FAQ Schema Markup', 'simple-faq-manager' ), 'sfm_field_enable_schema', 'sfm-settings', 'sfm_section_seo' );
}

// ---------------------------------------------------------------------------
// Field callbacks
// ---------------------------------------------------------------------------

function sfm_field_list_display_mode() {
	$v = sfm_get_settings()['list_display_mode'];
	?>
	<fieldset>
		<label style="display:block;margin-bottom:6px;">
			<input type="radio" name="sfm_settings[list_display_mode]" value="expanded" <?php checked( $v, 'expanded' ); ?>>
			<strong><?php esc_html_e( 'All Expanded', 'simple-faq-manager' ); ?></strong>
			&mdash; <?php esc_html_e( 'All answers are always visible (best for SEO)', 'simple-faq-manager' ); ?>
		</label>
		<label style="display:block;">
			<input type="radio" name="sfm_settings[list_display_mode]" value="accordion" <?php checked( $v, 'accordion' ); ?>>
			<strong><?php esc_html_e( 'Accordion', 'simple-faq-manager' ); ?></strong>
			&mdash; <?php esc_html_e( 'Click a question to reveal its answer', 'simple-faq-manager' ); ?>
		</label>
	</fieldset>
	<?php
}

function sfm_field_list_show_search() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[list_show_search]" value="1" <?php checked( $s['list_show_search'], '1' ); ?>>
		<?php esc_html_e( 'Show the live search bar above the FAQ list', 'simple-faq-manager' ); ?>
	</label>
	<?php
}

function sfm_field_list_show_cat_filter() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[list_show_cat_filter]" value="1" <?php checked( $s['list_show_cat_filter'], '1' ); ?>>
		<?php esc_html_e( 'Show category filter buttons above the FAQ list', 'simple-faq-manager' ); ?>
	</label>
	<?php
}

function sfm_field_list_show_all_btn() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[list_show_all_btn]" value="1" <?php checked( $s['list_show_all_btn'], '1' ); ?>>
		<?php esc_html_e( 'Show the "All" button that resets the category filter', 'simple-faq-manager' ); ?>
	</label>
	<p class="description"><?php esc_html_e( 'Only relevant when Category Filter is enabled.', 'simple-faq-manager' ); ?></p>
	<?php
}

function sfm_field_list_exclusive() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[list_exclusive]" value="1" <?php checked( $s['list_exclusive'], '1' ); ?>>
		<?php esc_html_e( 'Only one answer open at a time — closes others when a new one is opened', 'simple-faq-manager' ); ?>
	</label>
	<p class="description"><?php esc_html_e( 'Only applies in Accordion mode.', 'simple-faq-manager' ); ?></p>
	<?php
}

function sfm_field_list_show_expand_all() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[list_show_expand_all]" value="1" <?php checked( $s['list_show_expand_all'], '1' ); ?>>
		<?php esc_html_e( 'Show "Expand All / Collapse All" button (accordion mode only)', 'simple-faq-manager' ); ?>
	</label>
	<?php
}

function sfm_field_list_sort() {
	$s       = sfm_get_settings();
	$current = $s['list_sort'];
	$options = array(
		'title_asc'  => __( 'Title A → Z', 'simple-faq-manager' ),
		'title_desc' => __( 'Title Z → A', 'simple-faq-manager' ),
		'date_desc'  => __( 'Newest first', 'simple-faq-manager' ),
		'date_asc'   => __( 'Oldest first', 'simple-faq-manager' ),
		'menu_order' => __( 'Custom order (drag-and-drop order from Widget FAQs page)', 'simple-faq-manager' ),
	);
	?>
	<select name="sfm_settings[list_sort]">
		<?php foreach ( $options as $value => $label ) : ?>
		<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>>
			<?php echo esc_html( $label ); ?>
		</option>
		<?php endforeach; ?>
	</select>
	<?php
}

function sfm_field_widget_open_first() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[widget_open_first]" value="1" <?php checked( $s['widget_open_first'], '1' ); ?>>
		<?php esc_html_e( 'Automatically expand the first FAQ when the page loads', 'simple-faq-manager' ); ?>
	</label>
	<?php
}

function sfm_field_widget_exclusive() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[widget_exclusive]" value="1" <?php checked( $s['widget_exclusive'], '1' ); ?>>
		<?php esc_html_e( 'Only one item open at a time — closing others when a new one is opened', 'simple-faq-manager' ); ?>
	</label>
	<?php
}

function sfm_field_enable_schema() {
	$s = sfm_get_settings();
	?>
	<label>
		<input type="checkbox" name="sfm_settings[enable_schema]" value="1" <?php checked( $s['enable_schema'], '1' ); ?>>
		<?php esc_html_e( 'Output JSON-LD FAQPage schema on pages that use [faq_list]', 'simple-faq-manager' ); ?>
	</label>
	<p class="description">
		<?php esc_html_e( 'Enables Google FAQ rich results in search. Only valid when all answers are visible (All Expanded mode).', 'simple-faq-manager' ); ?>
	</p>
	<?php
}

// ---------------------------------------------------------------------------
// Sanitize
// ---------------------------------------------------------------------------

function sfm_sanitize_settings( $input ) {
	$defaults = sfm_default_settings();
	$clean    = array();

	$clean['list_display_mode'] = in_array( $input['list_display_mode'] ?? '', array( 'expanded', 'accordion' ), true )
		? $input['list_display_mode']
		: $defaults['list_display_mode'];

	$clean['list_show_search']     = empty( $input['list_show_search'] )     ? '0' : '1';
	$clean['list_show_cat_filter'] = empty( $input['list_show_cat_filter'] ) ? '0' : '1';
	$clean['list_show_all_btn']    = empty( $input['list_show_all_btn'] )    ? '0' : '1';
	$clean['list_show_expand_all'] = empty( $input['list_show_expand_all'] ) ? '0' : '1';
	$clean['list_exclusive']       = empty( $input['list_exclusive'] )       ? '0' : '1';

	$valid_sorts        = array( 'title_asc', 'title_desc', 'date_desc', 'date_asc', 'menu_order' );
	$clean['list_sort'] = in_array( $input['list_sort'] ?? '', $valid_sorts, true )
		? $input['list_sort']
		: $defaults['list_sort'];

	$clean['widget_open_first'] = empty( $input['widget_open_first'] ) ? '0' : '1';
	$clean['widget_exclusive']  = empty( $input['widget_exclusive'] )  ? '0' : '1';
	$clean['enable_schema']     = empty( $input['enable_schema'] )     ? '0' : '1';

	return $clean;
}

// ---------------------------------------------------------------------------
// Settings page render
// ---------------------------------------------------------------------------

function sfm_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'FAQ Settings', 'simple-faq-manager' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sfm_settings_group' );
			do_settings_sections( 'sfm-settings' );
			submit_button( __( 'Save Settings', 'simple-faq-manager' ) );
			?>
		</form>
	</div>
	<?php
}
