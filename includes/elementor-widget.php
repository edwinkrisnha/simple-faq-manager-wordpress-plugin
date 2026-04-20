<?php
/**
 * Elementor Widget: FAQ Widget
 *
 * Renders widget-enabled FAQs as an accordion inside any Elementor page.
 * Category: "FAQ" (sfm-faq).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SFM_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'sfm_faq_widget';
	}

	public function get_title() {
		return __( 'FAQ Widget', 'simple-faq-manager' );
	}

	public function get_icon() {
		return 'eicon-help-o';
	}

	public function get_categories() {
		return array( 'sfm-faq' );
	}

	public function get_keywords() {
		return array( 'faq', 'accordion', 'question', 'answer' );
	}

	/**
	 * Declare stylesheet dependency so Elementor enqueues it automatically.
	 */
	public function get_style_depends() {
		return array( 'sfm-frontend' );
	}

	/**
	 * Declare script dependency so Elementor enqueues it automatically.
	 */
	public function get_script_depends() {
		return array( 'sfm-frontend-search' );
	}

	// -----------------------------------------------------------------------
	// Controls (Elementor panel options)
	// -----------------------------------------------------------------------

	protected function register_controls() {
		$this->start_controls_section(
			'sfm_content_section',
			array(
				'label' => __( 'Content', 'simple-faq-manager' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'widget_title',
			array(
				'label'       => __( 'Title', 'simple-faq-manager' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Frequently Asked Questions', 'simple-faq-manager' ),
				'placeholder' => __( 'Enter a title or leave blank', 'simple-faq-manager' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'simple-faq-manager' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'p'  => 'p',
				),
				'default' => 'h2',
			)
		);

		$this->end_controls_section();

		// Style tab: title typography
		$this->start_controls_section(
			'sfm_style_section',
			array(
				'label' => __( 'Title', 'simple-faq-manager' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'simple-faq-manager' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sfm-widget-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .sfm-widget-title',
			)
		);

		$this->end_controls_section();
	}

	// -----------------------------------------------------------------------
	// Render
	// -----------------------------------------------------------------------

	protected function render() {
		$settings = $this->get_settings_for_display();
		$title    = $settings['widget_title'];
		$tag      = in_array( $settings['title_tag'], array( 'h2', 'h3', 'h4', 'p' ), true )
			? $settings['title_tag']
			: 'h2';

		if ( $title ) {
			echo '<' . esc_attr( $tag ) . ' class="sfm-widget-title">' . esc_html( $title ) . '</' . esc_attr( $tag ) . '>';
		}

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
			echo '<p class="sfm-no-faqs">' . esc_html__( 'No FAQs available. Enable some via FAQs > Widget FAQs.', 'simple-faq-manager' ) . '</p>';
			return;
		}

		// sfm_render_accordion() is defined in shortcodes.php (already loaded).
		echo sfm_render_accordion( $faqs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	// Render plain content for Elementor's "content" mode (no HTML).
	protected function content_template() {
		// Dynamic data — not renderable in JS template context.
	}
}
