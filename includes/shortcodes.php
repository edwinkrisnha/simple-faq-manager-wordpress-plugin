<?php
/**
 * Shortcodes
 *
 * [faq_list]   – All FAQs grouped by category with live search and category filter.
 * [faq_widget] – Widget-enabled FAQs as an accessible accordion.
 *
 * NOTE: FAQ answer content is rendered with sfm_render_faq_content() instead of
 * apply_filters('the_content', ...) to avoid re-triggering the_content hooks from
 * inside a shortcode callback, which causes duplicate output and nested shortcode
 * execution (e.g. [faq_widget] rendering inside [faq_list] answers).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// [faq_list]
// ---------------------------------------------------------------------------

add_shortcode( 'faq_list', 'sfm_shortcode_faq_list' );
function sfm_shortcode_faq_list( $atts ) {
	shortcode_atts( array(), $atts, 'faq_list' );

	$s            = sfm_get_settings();
	$display_mode = $s['list_display_mode']; // 'expanded' | 'accordion'
	$show_search  = '1' === $s['list_show_search'];
	$show_filters = '1' === $s['list_show_cat_filter'];
	$show_expand  = '1' === $s['list_show_expand_all'] && 'accordion' === $display_mode;
	$enable_schema = '1' === $s['enable_schema'];

	$categories = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => true,
		)
	);

	$sort_args = sfm_sort_args( $s['list_sort'] );

	$faqs = get_posts(
		array_merge(
			array(
				'post_type'      => 'faq',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			),
			$sort_args
		)
	);

	if ( empty( $faqs ) ) {
		return '<p class="sfm-no-faqs">' . esc_html__( 'No FAQs found.', 'simple-faq-manager' ) . '</p>';
	}

	// Group FAQs by category; uncategorized go to fallback bucket.
	$grouped       = array();
	$uncategorized = array();

	foreach ( $faqs as $faq ) {
		$faq_terms = get_the_terms( $faq->ID, 'faq_category' );
		if ( $faq_terms && ! is_wp_error( $faq_terms ) ) {
			foreach ( $faq_terms as $term ) {
				$grouped[ $term->term_id ][] = $faq;
			}
		} else {
			$uncategorized[] = $faq;
		}
	}

	// Build schema data (all FAQs flattened).
	$schema_items = array();
	if ( $enable_schema ) {
		foreach ( $faqs as $faq ) {
			$schema_items[] = array(
				'@type' => 'Question',
				'name'  => wp_strip_all_tags( $faq->post_title ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $faq->post_content ),
				),
			);
		}
	}

	$wrap_class = 'sfm-faq-list-wrap sfm-mode-' . esc_attr( $display_mode );

	ob_start();
	?>
	<div class="<?php echo esc_attr( $wrap_class ); ?>">

		<?php if ( $show_search ) : ?>
		<div class="sfm-search-wrap">
			<input type="text" id="sfm-search" class="sfm-search-input"
				placeholder="<?php esc_attr_e( 'Search FAQs…', 'simple-faq-manager' ); ?>"
				aria-label="<?php esc_attr_e( 'Search FAQs', 'simple-faq-manager' ); ?>">
		</div>
		<?php endif; ?>

		<?php if ( $show_filters && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<div class="sfm-category-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'simple-faq-manager' ); ?>">
			<button class="sfm-cat-btn active" data-category="all">
				<?php esc_html_e( 'All', 'simple-faq-manager' ); ?>
			</button>
			<?php foreach ( $categories as $cat ) : ?>
			<button class="sfm-cat-btn" data-category="<?php echo esc_attr( $cat->slug ); ?>">
				<?php echo esc_html( $cat->name ); ?>
			</button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $show_expand ) : ?>
		<div class="sfm-expand-controls">
			<button class="sfm-expand-all-btn" data-state="collapsed">
				<?php esc_html_e( 'Expand All', 'simple-faq-manager' ); ?>
			</button>
		</div>
		<?php endif; ?>

		<div id="sfm-faq-groups" class="sfm-faq-groups">

			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
				foreach ( $categories as $cat ) :
					if ( empty( $grouped[ $cat->term_id ] ) ) {
						continue;
					}
			?>
			<div class="sfm-faq-group" data-category="<?php echo esc_attr( $cat->slug ); ?>">
				<h2 class="sfm-category-title"><?php echo esc_html( $cat->name ); ?></h2>
				<?php foreach ( $grouped[ $cat->term_id ] as $faq ) : ?>
				<?php echo sfm_render_list_item( $faq, $display_mode ); ?>
				<?php endforeach; ?>
			</div>
			<?php endforeach; endif; ?>

			<?php if ( ! empty( $uncategorized ) ) : ?>
			<div class="sfm-faq-group" data-category="uncategorized">
				<h2 class="sfm-category-title"><?php esc_html_e( 'Uncategorized', 'simple-faq-manager' ); ?></h2>
				<?php foreach ( $uncategorized as $faq ) : ?>
				<?php echo sfm_render_list_item( $faq, $display_mode ); ?>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<p class="sfm-no-results" style="display:none;" aria-live="polite">
				<?php esc_html_e( 'No FAQs match your search.', 'simple-faq-manager' ); ?>
			</p>

		</div><!-- .sfm-faq-groups -->

	</div><!-- .sfm-faq-list-wrap -->

	<?php if ( $enable_schema && ! empty( $schema_items ) ) : ?>
	<script type="application/ld+json">
	<?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $schema_items ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
	</script>
	<?php endif; ?>

	<?php
	return ob_get_clean();
}

// ---------------------------------------------------------------------------
// [faq_widget]
// ---------------------------------------------------------------------------

add_shortcode( 'faq_widget', 'sfm_shortcode_faq_widget' );
function sfm_shortcode_faq_widget( $atts ) {
	shortcode_atts( array(), $atts, 'faq_widget' );

	$faqs = get_posts(
		array(
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'sfm_show_on_widget',
					'value'   => '1',
					'compare' => '=',
				),
			),
			'meta_key'       => 'sfm_widget_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		)
	);

	if ( empty( $faqs ) ) {
		return '<p class="sfm-no-faqs">' . esc_html__( 'No FAQs available.', 'simple-faq-manager' ) . '</p>';
	}

	return sfm_render_accordion( $faqs );
}

// ---------------------------------------------------------------------------
// Safe FAQ content renderer
// ---------------------------------------------------------------------------

/**
 * Render FAQ post_content safely inside shortcode callbacks.
 *
 * Uses wpautop + wptexturize instead of apply_filters('the_content', ...)
 * to avoid re-triggering the_content hooks (which causes duplicate shortcode
 * output and nested shortcode execution when called from within a shortcode).
 */
function sfm_render_faq_content( $content ) {
	return wp_kses_post( wpautop( wptexturize( $content ) ) );
}

// ---------------------------------------------------------------------------
// List item renderer — outputs different HTML based on display mode
// ---------------------------------------------------------------------------

function sfm_render_list_item( WP_Post $faq, $display_mode ) {
	ob_start();

	if ( 'accordion' === $display_mode ) :
		?>
		<div class="sfm-faq-item sfm-list-item-accordion">
			<h3 class="sfm-faq-question">
				<button class="sfm-list-toggle" aria-expanded="false">
					<span><?php echo esc_html( $faq->post_title ); ?></span>
					<span class="sfm-list-icon" aria-hidden="true">+</span>
				</button>
			</h3>
			<div class="sfm-faq-answer" hidden>
				<?php echo sfm_render_faq_content( $faq->post_content ); ?>
			</div>
		</div>
		<?php
	else :
		?>
		<div class="sfm-faq-item">
			<h3 class="sfm-faq-question"><?php echo esc_html( $faq->post_title ); ?></h3>
			<div class="sfm-faq-answer">
				<?php echo sfm_render_faq_content( $faq->post_content ); ?>
			</div>
		</div>
		<?php
	endif;

	return ob_get_clean();
}

// ---------------------------------------------------------------------------
// Shared accordion renderer (widget + Elementor widget)
// ---------------------------------------------------------------------------

function sfm_render_accordion( array $faqs ) {
	ob_start();
	?>
	<div class="sfm-faq-widget-wrap">
		<div class="sfm-accordion">
			<?php foreach ( $faqs as $faq ) : ?>
			<div class="sfm-accordion-item">
				<button class="sfm-accordion-header" aria-expanded="false">
					<span class="sfm-accordion-question"><?php echo esc_html( $faq->post_title ); ?></span>
					<span class="sfm-accordion-icon" aria-hidden="true">+</span>
				</button>
				<div class="sfm-accordion-body" hidden>
					<?php echo sfm_render_faq_content( $faq->post_content ); ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

// ---------------------------------------------------------------------------
// Sort args helper
// ---------------------------------------------------------------------------

function sfm_sort_args( $sort ) {
	switch ( $sort ) {
		case 'title_desc':
			return array( 'orderby' => 'title', 'order' => 'DESC' );
		case 'date_desc':
			return array( 'orderby' => 'date', 'order' => 'DESC' );
		case 'date_asc':
			return array( 'orderby' => 'date', 'order' => 'ASC' );
		case 'menu_order':
			return array( 'orderby' => 'menu_order', 'order' => 'ASC' );
		default: // title_asc
			return array( 'orderby' => 'title', 'order' => 'ASC' );
	}
}
