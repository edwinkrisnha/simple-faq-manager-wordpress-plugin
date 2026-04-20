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

	$categories = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => true,
		)
	);

	$faqs = get_posts(
		array(
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	if ( empty( $faqs ) ) {
		return '<p class="sfm-no-faqs">' . esc_html__( 'No FAQs found.', 'simple-faq-manager' ) . '</p>';
	}

	// Group FAQs by their first category; uncategorized FAQs go to a fallback bucket.
	$grouped       = array(); // term_id => WP_Post[]
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

	ob_start();
	?>
	<div class="sfm-faq-list-wrap">

		<div class="sfm-search-wrap">
			<input type="text" id="sfm-search" class="sfm-search-input"
				placeholder="<?php esc_attr_e( 'Search FAQs…', 'simple-faq-manager' ); ?>"
				aria-label="<?php esc_attr_e( 'Search FAQs', 'simple-faq-manager' ); ?>">
		</div>

		<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
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
				<div class="sfm-faq-item">
					<h3 class="sfm-faq-question"><?php echo esc_html( $faq->post_title ); ?></h3>
					<div class="sfm-faq-answer">
						<?php echo sfm_render_faq_content( $faq->post_content ); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; endif; ?>

			<?php if ( ! empty( $uncategorized ) ) : ?>
			<div class="sfm-faq-group" data-category="uncategorized">
				<h2 class="sfm-category-title"><?php esc_html_e( 'Uncategorized', 'simple-faq-manager' ); ?></h2>
				<?php foreach ( $uncategorized as $faq ) : ?>
				<div class="sfm-faq-item">
					<h3 class="sfm-faq-question"><?php echo esc_html( $faq->post_title ); ?></h3>
					<div class="sfm-faq-answer">
						<?php echo sfm_render_faq_content( $faq->post_content ); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<p class="sfm-no-results" style="display:none;" aria-live="polite">
				<?php esc_html_e( 'No FAQs match your search.', 'simple-faq-manager' ); ?>
			</p>
		</div>

	</div>
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
// Shared accordion renderer (used by shortcode and Elementor widget)
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
