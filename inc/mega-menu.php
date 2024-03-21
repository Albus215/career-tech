<?php

// registration header sidebar
function header_sidebar()
{
	register_sidebar(array(
		'name'          => __('Header', 'text_domain'),
		'id'            => 'header_sidebar',
		'description'   => __('Header sidebar', 'text_domain'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}
add_action('widgets_init', 'header_sidebar');


// mega menu render fucntion
function render_mega_menu($widget_id_prefixed)
{
	if (have_rows('content_', $widget_id_prefixed)) :
		while (have_rows('content_', $widget_id_prefixed)) : the_row();
			if (get_row_layout() == 'link_block') :
				$link = get_sub_field('link');
				if ($link) :
					echo '<div class="mega-menu__item link"><a href="' . esc_url($link['url']) . '" target="' . esc_attr($link['target']) . '"><span>' . esc_html($link['title']) . '</span></a></div>';
				endif;
			elseif (get_row_layout() == 'submenu') :
				$number_of_columns = get_sub_field('number_of_columns');
				$grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($number_of_columns) . ', 1fr);';
				echo '<div class="mega-menu__item submenu">';
				$menu_title = get_sub_field('menu_title');
				if ($menu_title) :
					echo '<div class="mega-menu__wrapper">';
					echo '<span class="link-item__title">' . $menu_title . '</span>';
					echo '<div class="mega-menu__block" style="' . esc_attr($grid_style) . '">';
					if (have_rows('select')) :
						while (have_rows('select')) : the_row();
							if (get_row_layout() == 'menu_item') :
								echo '<div class="mega-menu__submenu">';
								echo '<div class="mega-menu__submenu-item">';
								$image = get_sub_field('image');
								if ($image) :
									echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '" />';
								endif;
								echo '</div>';
								echo '<div class="mega-menu__submenu-item">';
								$link = get_sub_field('link');
								if ($link) :
									echo '<a href="' . esc_url($link['url']) . '" target="' . esc_attr($link['target']) . ' ">' . esc_html($link['title']) . '</a>';
								endif;
								echo '</div>';
								echo '<div class="mega-menu__submenu-item">';
								$desc = get_sub_field('description');
								if ($desc) :
									echo '<span>' . $desc . '</span>';
								endif;
								echo '</div>';
								echo '</div>';
							elseif (get_row_layout() == 'all_products') :
								echo '<div class="mega-menu__submenu products">';
								$link = get_sub_field('link');
								if ($link) :
									echo '<a href="' . esc_url($link['url']) . '" target="' . esc_attr($link['target']) . '">' . esc_html($link['title']) . '</a>';
								endif;
								echo '</div>';
							endif;
						endwhile;
					endif;
					echo '</div>';
					echo '</div>';
				endif;
				echo '</div>';
			endif;
		endwhile;
	endif;
}

add_action('widgets_init', function () {
	register_widget('ACF_Mega_Menu_Widget');
});

class ACF_Mega_Menu_Widget extends WP_Widget
{

	function __construct()
	{
		parent::__construct(
			'acf_mega_menu_widget', // Base ID
			'ACF Mega Menu', // Name
			array('description' => 'A widget that displays a mega menu with ACF fields') // Args
		);
	}

	// widget front end
	public function widget($args, $instance)
	{
		$widget_id = $this->id; // Ідентифікатор віджета
		$widget_acf_prefix = 'widget_';
		$widget_id_prefixed = $widget_acf_prefix . $widget_id;
		render_mega_menu($widget_id_prefixed);
	}

	// Form in admin panel
	public function form($instance)
	{
		echo '<p>Використовуйте ACF поля для керування цим віджетом.</p>';
	}

	// Update widget options
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		// save fields
		$instance['number_of_columns'] = !empty($new_instance['number_of_columns']) ? sanitize_text_field($new_instance['number_of_columns']) : '';

		return $new_instance;
	}
}