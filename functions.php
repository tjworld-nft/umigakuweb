<?php
/**
 * Theme functions and definitions
 * Miura Diving School WordPress Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function miura_diving_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add custom image sizes
    add_image_size('activity-thumb', 350, 200, true);
    add_image_size('blog-thumb', 300, 200, true);
    add_image_size('hero-image', 1920, 1080, true);
}
add_action('after_setup_theme', 'miura_diving_setup');

// Enqueue styles and scripts
function miura_diving_scripts() {
    // Get theme version for cache busting
    $theme_version = wp_get_theme()->get('Version');
    
    // Enqueue main stylesheet
    wp_enqueue_style('miura-diving-style', get_stylesheet_uri(), array(), $theme_version);
    
    // Enqueue Google Fonts
    wp_enqueue_style(
        'miura-diving-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;600;700&display=swap',
        array(),
        null
    );
    
    // Enqueue home test page styles (only on home test page)
    if (is_page_template('page-home-test.php')) {
        wp_enqueue_style(
            'home-test-style',
            get_template_directory_uri() . '/assets/css/home-test.css',
            array('miura-diving-style'),
            $theme_version
        );
        
        // Enqueue Tiny Slider CSS
        wp_enqueue_style(
            'tiny-slider-css',
            'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css',
            array(),
            '2.9.4'
        );
        
        // Enqueue Tiny Slider JS
        wp_enqueue_script(
            'tiny-slider-js',
            'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js',
            array(),
            '2.9.4',
            true
        );
        
        // Enqueue home test page scripts
        wp_enqueue_script(
            'home-test-script',
            get_template_directory_uri() . '/assets/js/home-test.js',
            array('tiny-slider-js'),
            $theme_version,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('home-test-script', 'homeTestAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('home_test_nonce'),
        ));
    }
    
    // Enqueue main JavaScript
    wp_enqueue_script(
        'miura-diving-script',
        get_template_directory_uri() . '/js/main.js',
        array('jquery'),
        $theme_version,
        true
    );
    
    // Remove jQuery migrate in production
    if (!is_admin() && !WP_DEBUG) {
        wp_deregister_script('jquery-migrate');
    }
}
add_action('wp_enqueue_scripts', 'miura_diving_scripts');

// Register navigation menus
function miura_diving_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'miura-diving'),
        'footer' => __('Footer Menu', 'miura-diving'),
    ));
}
add_action('init', 'miura_diving_menus');

// Register widget areas
function miura_diving_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'miura-diving'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'miura-diving'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 1', 'miura-diving'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here.', 'miura-diving'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 2', 'miura-diving'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here.', 'miura-diving'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 3', 'miura-diving'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here.', 'miura-diving'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'miura_diving_widgets_init');

// Custom excerpt length
function miura_diving_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'miura_diving_excerpt_length');

// Custom excerpt more
function miura_diving_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'miura_diving_excerpt_more');

// Add custom post types (if needed)
function miura_diving_custom_post_types() {
    // Activity post type
    register_post_type('activity', array(
        'labels' => array(
            'name' => __('Activities', 'miura-diving'),
            'singular_name' => __('Activity', 'miura-diving'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-palmtree',
    ));
    
    // Course post type
    register_post_type('course', array(
        'labels' => array(
            'name' => __('Courses', 'miura-diving'),
            'singular_name' => __('Course', 'miura-diving'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-welcome-learn-more',
    ));
}
add_action('init', 'miura_diving_custom_post_types');

// Add custom fields support
function miura_diving_add_meta_boxes() {
    add_meta_box(
        'course_details',
        __('Course Details', 'miura-diving'),
        'miura_diving_course_details_callback',
        'course',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'miura_diving_add_meta_boxes');

function miura_diving_course_details_callback($post) {
    wp_nonce_field('miura_diving_save_course_details', 'miura_diving_course_details_nonce');
    
    $price = get_post_meta($post->ID, '_course_price', true);
    $duration = get_post_meta($post->ID, '_course_duration', true);
    $max_depth = get_post_meta($post->ID, '_course_max_depth', true);
    
    echo '<table class="form-table">';
    echo '<tr><th><label for="course_price">Price</label></th>';
    echo '<td><input type="text" id="course_price" name="course_price" value="' . esc_attr($price) . '" /></td></tr>';
    echo '<tr><th><label for="course_duration">Duration</label></th>';
    echo '<td><input type="text" id="course_duration" name="course_duration" value="' . esc_attr($duration) . '" /></td></tr>';
    echo '<tr><th><label for="course_max_depth">Max Depth</label></th>';
    echo '<td><input type="text" id="course_max_depth" name="course_max_depth" value="' . esc_attr($max_depth) . '" /></td></tr>';
    echo '</table>';
}

function miura_diving_save_course_details($post_id) {
    if (!isset($_POST['miura_diving_course_details_nonce']) || 
        !wp_verify_nonce($_POST['miura_diving_course_details_nonce'], 'miura_diving_save_course_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['course_price'])) {
        update_post_meta($post_id, '_course_price', sanitize_text_field($_POST['course_price']));
    }
    
    if (isset($_POST['course_duration'])) {
        update_post_meta($post_id, '_course_duration', sanitize_text_field($_POST['course_duration']));
    }
    
    if (isset($_POST['course_max_depth'])) {
        update_post_meta($post_id, '_course_max_depth', sanitize_text_field($_POST['course_max_depth']));
    }
}
add_action('save_post', 'miura_diving_save_course_details');

// Security enhancements
function miura_diving_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'miura_diving_remove_wp_version');

// Remove unnecessary WordPress features
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Optimize WordPress
function miura_diving_optimize_wp() {
    // Disable emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Disable embeds
    wp_deregister_script('wp-embed');
    
    // Clean up wp_head
    remove_action('wp_head', 'wp_resource_hints', 2);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
}
add_action('init', 'miura_diving_optimize_wp');

// Add performance optimizations
function miura_diving_performance_optimizations() {
    // Preload critical resources
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/home-test.css" as="style">';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'miura_diving_performance_optimizations', 1);

// Add enhanced structured data for SEO v2
function miura_diving_structured_data() {
    if (is_page_template('page-home-test.php')) {
        // Main Business Schema
        $business_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => 'https://miura-diving.com/#business',
            'name' => '三浦 海の学校',
            'alternateName' => 'Miura Umigaku',
            'description' => '一般の方なら誰でも利用できるPADI 5スター IDC ダイビングセンター。都心から60分、三浦半島で安全・安心のダイビング指導。',
            'url' => 'https://miura-diving.com',
            'telephone' => '046-123-4567',
            'email' => 'info@miura-diving.com',
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => '神奈川県三浦市南下浦町上宮田',
                'addressLocality' => '三浦市',
                'addressRegion' => '神奈川県',
                'postalCode' => '238-0111',
                'addressCountry' => 'JP'
            ),
            'geo' => array(
                '@type' => 'GeoCoordinates',
                'latitude' => 35.1441,
                'longitude' => 139.6197
            ),
            'openingHours' => array(
                'Mo-Su 08:00-18:00'
            ),
            'priceRange' => '¥45,000-¥65,000',
            'image' => array(
                'https://miura-diving.com/assets/images/hero.jpg',
                'https://miura-diving.com/assets/images/instructor.jpg',
                'https://miura-diving.com/assets/images/facility.jpg'
            ),
            'logo' => 'https://miura-diving.com/assets/images/logo.png',
            'sameAs' => array(
                'https://www.facebook.com/miuraumigaku',
                'https://www.instagram.com/miuraumigaku',
                'https://line.me/ti/p/miuraumigaku'
            ),
            'areaServed' => array(
                array(
                    '@type' => 'AdministrativeArea',
                    'name' => '関東地方'
                ),
                array(
                    '@type' => 'AdministrativeArea', 
                    'name' => '東京都'
                ),
                array(
                    '@type' => 'AdministrativeArea',
                    'name' => '神奈川県'
                )
            ),
            'serviceArea' => array(
                array(
                    '@type' => 'GeoCircle',
                    'geoMidpoint' => array(
                        '@type' => 'GeoCoordinates',
                        'latitude' => 35.1441,
                        'longitude' => 139.6197
                    ),
                    'geoRadius' => '100000'
                )
            ),
            'hasOfferCatalog' => array(
                '@type' => 'OfferCatalog',
                'name' => 'ダイビングコース',
                'itemListElement' => array(
                    array(
                        '@type' => 'OfferCatalog',
                        'name' => 'オープンウォーターコース',
                        'itemListElement' => array(
                            array(
                                '@type' => 'Offer',
                                'itemOffered' => array(
                                    '@type' => 'Service',
                                    'name' => 'PADI オープンウォーターダイバー',
                                    'description' => '初心者向けダイビングライセンス取得コース'
                                ),
                                'price' => '65000',
                                'priceCurrency' => 'JPY'
                            )
                        )
                    ),
                    array(
                        '@type' => 'OfferCatalog',
                        'name' => 'アドバンスコース',
                        'itemListElement' => array(
                            array(
                                '@type' => 'Offer',
                                'itemOffered' => array(
                                    '@type' => 'Service',
                                    'name' => 'PADI アドバンスドオープンウォーターダイバー',
                                    'description' => '一般ダイバー向けスキルアップコース'
                                ),
                                'price' => '45000',
                                'priceCurrency' => 'JPY'
                            )
                        )
                    )
                )
            ),
            'amenityFeature' => array(
                array(
                    '@type' => 'LocationFeatureSpecification',
                    'name' => '専用プール',
                    'value' => true
                ),
                array(
                    '@type' => 'LocationFeatureSpecification',
                    'name' => '器材レンタル',
                    'value' => true
                ),
                array(
                    '@type' => 'LocationFeatureSpecification',
                    'name' => '無料駐車場',
                    'value' => true
                )
            )
        );

        // Organization Schema
        $organization_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => 'https://miura-diving.com/#organization',
            'name' => '三浦 海の学校',
            'url' => 'https://miura-diving.com',
            'logo' => 'https://miura-diving.com/assets/images/logo.png',
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => '046-123-4567',
                'contactType' => 'customer service',
                'areaServed' => 'JP',
                'availableLanguage' => 'Japanese'
            ),
            'founder' => array(
                '@type' => 'Person',
                'name' => '田中 海太郎',
                'jobTitle' => 'チーフインストラクター',
                'worksFor' => array(
                    '@id' => 'https://miura-diving.com/#organization'
                )
            )
        );

        // FAQ Schema
        $faq_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array(
                array(
                    '@type' => 'Question',
                    'name' => '一般の方でも参加できますか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'はい。一般の方なら誰でもご参加いただけます。年齢や経験を問わず、10歳から70歳まで幅広い年齢層の方が楽しくダイビングを学んでいます。'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => '泳げなくても大丈夫ですか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => '大丈夫です。専用プールでしっかりと練習してから海に入るので、泳ぎが苦手な方でも安心してダイビングを始められます。'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => 'アクセスはどうですか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => '都心から車で約60分の好立地です。平日・土日問わず、お仕事帰りや休日に手軽にお越しいただけます。'
                    )
                )
            )
        );

        // Website Schema
        $website_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => 'https://miura-diving.com/#website',
            'url' => 'https://miura-diving.com',
            'name' => '三浦 海の学校 - PADI 5スター IDC ダイビングセンター',
            'description' => '一般の方なら誰でも利用できるダイビングスクール。都心から60分、三浦半島で本格ダイビング。',
            'publisher' => array(
                '@id' => 'https://miura-diving.com/#organization'
            ),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => 'https://miura-diving.com/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            )
        );

        echo '<script type="application/ld+json">' . json_encode($business_schema, JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($organization_schema, JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($website_schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
add_action('wp_head', 'miura_diving_structured_data');

// Add custom CSS for admin
function miura_diving_admin_styles() {
    echo '<style>
        .post-type-course .form-table th,
        .post-type-activity .form-table th {
            width: 150px;
        }
    </style>';
}
add_action('admin_head', 'miura_diving_admin_styles');

// AJAX handler for contact form (if needed)
function miura_diving_handle_contact_form() {
    check_ajax_referer('home_test_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Process contact form submission
    // Add your contact form processing logic here
    
    wp_send_json_success(array('message' => 'お問い合わせありがとうございます。'));
}
add_action('wp_ajax_contact_form', 'miura_diving_handle_contact_form');
add_action('wp_ajax_nopriv_contact_form', 'miura_diving_handle_contact_form');

// Theme customizer
function miura_diving_customize_register($wp_customize) {
    // Add contact section
    $wp_customize->add_section('contact_info', array(
        'title' => __('Contact Information', 'miura-diving'),
        'priority' => 30,
    ));
    
    // Phone number
    $wp_customize->add_setting('phone_number', array(
        'default' => '046-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('phone_number', array(
        'label' => __('Phone Number', 'miura-diving'),
        'section' => 'contact_info',
        'type' => 'text',
    ));
    
    // Email address
    $wp_customize->add_setting('email_address', array(
        'default' => 'info@miura-diving.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('email_address', array(
        'label' => __('Email Address', 'miura-diving'),
        'section' => 'contact_info',
        'type' => 'email',
    ));
}
add_action('customize_register', 'miura_diving_customize_register');

// Helper function to get customizer values
function miura_diving_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

?>