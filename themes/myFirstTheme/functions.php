<?php 

function pageBanner($args = NULL) {
    
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }
    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!isset($args['photo'])) {
        if(get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
            $args['photo'] = get_field('page_banner_background_image');
        } else {
            $args['photo'] = get_theme_file_uri('images/ocean.jpg');
        }
    }

    ?>
    <div class="page-banner">
		<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>)"></div>
		<div class="page-banner__content container container--narrow">
			<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
			<div class="page-banner__intro">
				<p><?php echo $args['subtitle'] ?></p>
			</div>
		</div>
	</div>
<?php }

function university_files() {
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_script('main_university_js', get_theme_file_uri('/build/index.js'), array('jquery'), 1.0, true);
    wp_localize_script('main_university_js', 'universityData', array(
        'root_url' => get_site_url()
    ));
}
add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260);
    add_image_size('professorPortrait', 480, 650);
    add_image_size('pageBanner', 1500, 650);
    register_nav_menu('headerMenuLocation', 'Header Menu');
    register_nav_menu('footerMenuLocationOne', 'Footer Menu One');
    register_nav_menu('footerMenuLocationTwo', 'Footer Menu Two');
}
add_action('after_setup_theme', 'university_features');

function university_adjust_query($query) {
    // for Program
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'DSC');
        $query->set('posts_per_page', -1);
    }

    // for Campus
    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }

    // for Event
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_query');