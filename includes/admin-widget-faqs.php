<?php
/**
 * Admin page: Widget FAQs
 *
 * Lists all published FAQs with a toggle for show_on_widget and drag-and-drop
 * reordering. Both actions save via AJAX without a page reload.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// Admin Menu
// ---------------------------------------------------------------------------

add_action( 'admin_menu', 'sfm_admin_menu' );
function sfm_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=faq',
		__( 'Widget FAQs', 'simple-faq-manager' ),
		__( 'Widget FAQs', 'simple-faq-manager' ),
		'manage_options',
		'sfm-widget-faqs',
		'sfm_render_widget_faqs_page'
	);
}

// ---------------------------------------------------------------------------
// Page Render
// ---------------------------------------------------------------------------

function sfm_render_widget_faqs_page() {
	$faqs = get_posts(
		array(
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => 'sfm_widget_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		)
	);
	?>
	<div class="wrap sfm-admin-wrap">
		<h1><?php esc_html_e( 'Widget FAQs', 'simple-faq-manager' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Drag rows to reorder. Toggle the switch to show or hide a FAQ in the widget.', 'simple-faq-manager' ); ?>
		</p>

		<div class="sfm-save-notice" id="sfm-save-notice" style="display:none;"></div>

		<?php if ( empty( $faqs ) ) : ?>
			<p><?php esc_html_e( 'No published FAQs found. Create some under FAQs > Add New.', 'simple-faq-manager' ); ?></p>
		<?php else : ?>
		<table class="wp-list-table widefat fixed striped sfm-faq-table">
			<thead>
				<tr>
					<th style="width:36px;"></th>
					<th><?php esc_html_e( 'Question', 'simple-faq-manager' ); ?></th>
					<th style="width:200px;"><?php esc_html_e( 'Category', 'simple-faq-manager' ); ?></th>
					<th style="width:140px;"><?php esc_html_e( 'Show on Widget', 'simple-faq-manager' ); ?></th>
					<th style="width:70px;"><?php esc_html_e( 'Edit', 'simple-faq-manager' ); ?></th>
				</tr>
			</thead>
			<tbody id="sfm-sortable-list">
				<?php foreach ( $faqs as $faq ) :
					$show_on_widget = (bool) get_post_meta( $faq->ID, 'sfm_show_on_widget', true );
					$terms          = get_the_terms( $faq->ID, 'faq_category' );
					$cat_names      = ( $terms && ! is_wp_error( $terms ) ) ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '—';
				?>
				<tr class="sfm-faq-row" data-id="<?php echo esc_attr( $faq->ID ); ?>">
					<td class="sfm-drag-handle"><span class="dashicons dashicons-move"></span></td>
					<td><?php echo esc_html( $faq->post_title ); ?></td>
					<td><?php echo esc_html( $cat_names ); ?></td>
					<td>
						<label class="sfm-toggle" title="<?php esc_attr_e( 'Toggle widget visibility', 'simple-faq-manager' ); ?>">
							<input type="checkbox"
								class="sfm-widget-toggle"
								data-id="<?php echo esc_attr( $faq->ID ); ?>"
								<?php checked( $show_on_widget ); ?>>
							<span class="sfm-toggle-slider"></span>
						</label>
					</td>
					<td>
						<a href="<?php echo esc_url( get_edit_post_link( $faq->ID ) ); ?>" class="button button-small">
							<?php esc_html_e( 'Edit', 'simple-faq-manager' ); ?>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>
	<?php
}

// ---------------------------------------------------------------------------
// AJAX: Save drag-and-drop order
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_sfm_save_order', 'sfm_ajax_save_order' );
function sfm_ajax_save_order() {
	check_ajax_referer( 'sfm_admin_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'Permission denied.', 'simple-faq-manager' ) );
	}

	$order = isset( $_POST['order'] ) ? array_map( 'intval', (array) $_POST['order'] ) : array();

	foreach ( $order as $index => $post_id ) {
		if ( $post_id > 0 ) {
			update_post_meta( $post_id, 'sfm_widget_order', $index );
		}
	}

	wp_send_json_success();
}

// ---------------------------------------------------------------------------
// AJAX: Toggle show_on_widget
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_sfm_toggle_widget', 'sfm_ajax_toggle_widget' );
function sfm_ajax_toggle_widget() {
	check_ajax_referer( 'sfm_admin_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'Permission denied.', 'simple-faq-manager' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

	if ( ! $post_id || 'faq' !== get_post_type( $post_id ) ) {
		wp_send_json_error( __( 'Invalid FAQ.', 'simple-faq-manager' ) );
	}

	// Value sent from checkbox: "1" when checked, "0" when unchecked.
	$value = ! empty( $_POST['value'] ) && '1' === $_POST['value'];

	update_post_meta( $post_id, 'sfm_show_on_widget', $value );

	wp_send_json_success( array( 'show_on_widget' => $value ) );
}
