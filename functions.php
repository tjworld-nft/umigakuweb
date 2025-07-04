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
    
    // Enqueue home test page styles (on home test page or front page)
    if (is_page_template('page-home-test.php') || is_front_page() || is_home()) {
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
        
        // Enqueue books slider script
        wp_enqueue_script(
            'books-slider-js',
            get_template_directory_uri() . '/assets/js/books-slider.js',
            array('jquery'),
            $theme_version,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('home-test-script', 'homeTestAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('home_test_nonce'),
        ));
    }
    
    // Enqueue license page styles (only on license page)
    if (is_page_template('page-diving-license.php')) {
        wp_enqueue_style(
            'license-style',
            get_template_directory_uri() . '/assets/css/license.css',
            array('miura-diving-style'),
            $theme_version
        );
        
        // Enqueue license page scripts
        wp_enqueue_script(
            'license-script',
            get_template_directory_uri() . '/assets/js/license.js',
            array('jquery'),
            $theme_version,
            true
        );
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
// add_action('wp_enqueue_scripts', 'miura_diving_scripts'); // Replaced by miura_diving_assets

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
                                'price' => '53900',
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
                                'price' => '53900',
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

        echo '<script type="application/ld+json">' . json_encode($owd_course_schema, JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($aow_course_schema, JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($license_faq_schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}

// ★License ALL page：Course & FAQ JSON-LD 自動生成
function add_license_all_ldjson(){
    if (!is_page_template('page-diving-license.php')) return;
    
    // 全コースのJSON-LD生成
    if (isset($GLOBALS['courses'])) {
        $courses = $GLOBALS['courses'];
        $graph = array_map(function($c) {
            return [
                '@type' => 'Course',
                'name' => $c['name'],
                'description' => $c['desc'],
                'provider' => [
                    '@type' => 'Organization',
                    'name' => '三浦 海の学校',
                    'url' => 'https://miura-diving.com'
                ],
                'hasCourseInstance' => [
                    '@type' => 'CourseInstance',
                    'courseMode' => 'In-person',
                    'duration' => 'P' . $c['days'] . 'D',
                    'location' => [
                        '@type' => 'Place',
                        'name' => '三浦 海の学校',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => '三浦市',
                            'addressRegion' => '神奈川県',
                            'addressCountry' => 'JP'
                        ]
                    ]
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $c['price'],
                    'priceCurrency' => 'JPY'
                ]
            ];
        }, $courses);
        
        $json = [
            '@context' => 'https://schema.org',
            '@graph' => $graph
        ];
        
        echo '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    // FAQ Schema for License ALL page
    $license_all_faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'どのコースから始めればよいですか？',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => '初心者の方は「PADI オープンウォーターダイバー（OWD）」からスタートすることをおすすめします。泳げない方でも安心して受講でき、世界中で通用するライセンスです。'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'コース料金に含まれるものは何ですか？',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => '各コース料金には、学科講習費、実習費、教材費、認定証発行費が含まれています。レンタル器材費（1日5,500円）は別途必要です。'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => '複数のコースを連続して受講できますか？',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'はい、可能です。例えばOWDとAOWを連続受講すると割引適用もございます。詳しくはお問い合わせください。'
                ]
            ]
        ]
    ];
    
    echo '<script type="application/ld+json">' . json_encode($license_all_faq_schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'add_license_all_ldjson');

// ★License 5 fixed - JSON-LD for 5 courses
function add_license_5_ldjson() {
    if (!is_page_template('page-diving-license.php')) return;
    
    // 5コース用JSON-LD生成
    if (isset($GLOBALS['license_courses'])) {
        $courses = $GLOBALS['license_courses'];
        $graph = array_map(function($c) {
            return [
                '@type' => 'Course',
                'name' => $c['name'],
                'description' => $c['desc'],
                'provider' => [
                    '@type' => 'Organization',
                    'name' => '三浦 海の学校',
                    'url' => 'https://miura-diving.com'
                ],
                'hasCourseInstance' => [
                    '@type' => 'CourseInstance',
                    'courseMode' => 'In-person',
                    'duration' => $c['duration'],
                    'location' => [
                        '@type' => 'Place',
                        'name' => '三浦 海の学校',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => '三浦市',
                            'addressRegion' => '神奈川県',
                            'addressCountry' => 'JP'
                        ]
                    ]
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $c['price'],
                    'priceCurrency' => 'JPY'
                ]
            ];
        }, $courses);
        
        $json = [
            '@context' => 'https://schema.org',
            '@graph' => $graph
        ];
        
        echo '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
add_action('wp_head', 'add_license_5_ldjson');
    
    // ★License page JSON-LD
    if (is_page_template('page-diving-license.php')) {
        // Course Schema for OWD
        $owd_course_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => 'PADI オープンウォーターダイバー講習',
            'description' => '初心者向けダイビングライセンス講習。泳げない方でも安心して参加できる3日間のコース。',
            'provider' => array(
                '@type' => 'Organization',
                'name' => '三浦 海の学校',
                'url' => 'https://miura-diving.com'
            ),
            'hasCourseInstance' => array(
                '@type' => 'CourseInstance',
                'courseMode' => 'In-person',
                'duration' => 'P3D',
                'location' => array(
                    '@type' => 'Place',
                    'name' => '三浦 海の学校',
                    'address' => array(
                        '@type' => 'PostalAddress',
                        'addressLocality' => '三浦市',
                        'addressRegion' => '神奈川県',
                        'addressCountry' => 'JP'
                    )
                )
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => '53900',
                'priceCurrency' => 'JPY'
            )
        );

        // Course Schema for AOW
        $aow_course_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => 'PADI アドバンスドオープンウォーターダイバー講習',
            'description' => 'OWD取得者向けスキルアップ講習。より深い海を楽しめるようになる2日間のコース。',
            'provider' => array(
                '@type' => 'Organization',
                'name' => '三浦 海の学校',
                'url' => 'https://miura-diving.com'
            ),
            'hasCourseInstance' => array(
                '@type' => 'CourseInstance',
                'courseMode' => 'In-person',
                'duration' => 'P2D',
                'location' => array(
                    '@type' => 'Place',
                    'name' => '三浦 海の学校',
                    'address' => array(
                        '@type' => 'PostalAddress',
                        'addressLocality' => '三浦市',
                        'addressRegion' => '神奈川県',
                        'addressCountry' => 'JP'
                    )
                )
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => '53900',
                'priceCurrency' => 'JPY'
            )
        );

        // FAQ Schema for License page
        $license_faq_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array(
                array(
                    '@type' => 'Question',
                    'name' => '泳げませんが、ライセンス取得は可能ですか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'はい、可能です。ダイビングは泳力よりも正しい呼吸法と器材の使い方が重要です。専用プールでゆっくり練習してから海に入るので、泳げない方でも安心してライセンスを取得できます。'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => '年齢制限はありますか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'OWDコースは10歳から参加可能です。上限はありませんが、健康状態に問題がないことが条件となります。事前に医師の診断書が必要な場合もございます。'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => '講習は何日間で完了しますか？',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'OWDコースは3日間、AOWコースは2日間で完了します。平日・土日問わず開催しており、お仕事の都合に合わせてスケジュール調整も可能です。'
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

// ★TOP polish v3 - SEO & OGP
function miura_diving_seo_ogp() {
    if (is_page_template('page-home-test.php') || is_front_page()) {
        ?>
        <!-- ★SEO & OGP -->
        <meta name="description" content="三浦海の学校は一般の方でも参加できるPADI5スターIDCセンター。都心から60分、初心者向けライセンス講習やSUP・シュノーケリングも開催。専用プール完備で安心。">
        <link rel="canonical" href="https://miura-diving.com/">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="ja_JP">
        <meta property="og:site_name" content="三浦 海の学校">
        <meta property="og:title" content="ダイビングライセンス取得なら | 三浦 海の学校">
        <meta property="og:description" content="一般の方でも参加できるPADI5スターIDCセンター。都心から60分、初心者向け講習も充実。">
        <meta property="og:url" content="https://miura-diving.com/">
        <meta property="og:image" content="https://miura-diving.com/ogp.jpg">
        <meta name="twitter:card" content="summary_large_image">

        <!-- ★LCP 画像プリロード -->
        <link rel="preload" as="image" href="<?php echo get_stylesheet_directory_uri(); ?>/image/owd-hero.png" imagesrcset="<?php echo get_stylesheet_directory_uri(); ?>/image/owd-hero.png 1x" type="image/png">
        <?php
    }
    
    // ★License page SEO & OGP
    if (is_page_template('page-diving-license.php')) {
        ?>
        <!-- ★License page SEO & OGP -->
        <title>PADIダイビングライセンス講習 | 三浦 海の学校</title>
        <meta name="description" content="三浦海の学校のPADIダイビングライセンス講習。OWD・AOW取得可能。都心から60分、一般の方でも泳げない方でも安心の指導体制。専用プール完備で段階的にスキルアップ。">
        <link rel="canonical" href="https://miura-diving.com/diving-license/">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="ja_JP">
        <meta property="og:site_name" content="三浦 海の学校">
        <meta property="og:title" content="PADIダイビングライセンス講習 | 三浦 海の学校">
        <meta property="og:description" content="一般の方でも安心してライセンス取得。OWD・AOW講習を都心から60分の三浦で開催。泳げない方もOK。">
        <meta property="og:url" content="https://miura-diving.com/diving-license/">
        <meta property="og:image" content="https://miura-diving.com/assets/img/license-ogp.jpg">
        <meta name="twitter:card" content="summary_large_image">

        <!-- ★LCP 画像プリロード -->
        <link rel="preload" as="image" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/license-hero.webp" type="image/webp">
        <?php
    }
}
add_action('wp_head', 'miura_diving_seo_ogp');

// ★CSS/JS 条件読み込み
function miura_diving_assets() {
    if (is_page_template('page-home-test.php') || is_front_page()) {
        wp_enqueue_style('home-test', get_stylesheet_directory_uri() . '/assets/css/home-test.css', [], '3.0');
        wp_enqueue_script('home-test', get_stylesheet_directory_uri() . '/assets/js/home-test.js', [], '3.0', true);
        wp_script_add_data('home-test', 'defer', true);
        
        // Books slider script
        wp_enqueue_script('books-slider-js', get_stylesheet_directory_uri() . '/assets/js/books-slider.js', array('jquery'), '3.0', true);
    }
    
    // ★License page assets
    if (is_page_template('page-diving-license.php')) {
        wp_enqueue_style('license', get_stylesheet_directory_uri() . '/assets/css/license.css', [], '1.0');
        wp_enqueue_script('license', get_stylesheet_directory_uri() . '/assets/js/license.js', [], '1.0', true);
        wp_script_add_data('license', 'defer', true);
    }
}
add_action('wp_enqueue_scripts', 'miura_diving_assets');

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

// Add JSON-LD structured data for license 5 courses page
function add_license_5_ldjson() {
    if (!is_page_template('page-diving-license.php')) return;
    
    if (isset($GLOBALS['license_courses'])) {
        $courses = $GLOBALS['license_courses'];
        
        $graph = array_map(function($c) {
            return [
                '@type' => 'Course',
                'name' => $c['name'],
                'description' => $c['desc'],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $c['price'],
                    'priceCurrency' => 'JPY'
                ],
                'courseMode' => 'blended',
                'provider' => [
                    '@type' => 'Organization',
                    'name' => '三浦海の学校',
                    'url' => 'https://miura-diving.com'
                ]
            ];
        }, $courses);
        
        $json_ld = [
            '@context' => 'https://schema.org',
            '@graph' => array_merge([
                [
                    '@type' => 'WebPage',
                    '@id' => home_url('/diving-license/'),
                    'url' => home_url('/diving-license/'),
                    'name' => 'PADIダイビングライセンス講習 | 三浦海の学校',
                    'description' => '体験ダイビングからレスキューダイバーまで5コース開講。PADI認定ダイビングスクール。',
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => home_url('/#website'),
                        'url' => home_url('/'),
                        'name' => '三浦海の学校'
                    ]
                ]
            ], $graph)
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'add_license_5_ldjson');

// Books array for homepage slider
$BOOKS = [
    [
        'title' => '「私にはムリ・・」から「潜りたい」に変わるダイビングのはじめ方',
        'desc'  => '初心者の不安や疑問をプロが忖度なしで解消。安全ルールや器材選びまで網羅した超実践ガイド。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/start-diving.png',
        'url'   => 'https://amzn.to/40BBpqn',
        'author' => '吉田哲司',
        'kindle_unlimited' => true
    ],
    [
        'title' => 'むずかしく考えなくて大丈夫！ はじめてのセルフダイビング',
        'desc'  => 'セルフ・バディ・ソロの違いから海外ポイントまで、自由に潜るコツを学べるセルフ派入門書。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/self-diving.png',
        'url'   => 'https://amzn.to/4003YO7',
        'author' => '吉田哲司',
        'kindle_unlimited' => true
    ],
    [
        'title' => 'ぷかぷか浮かんで水中散歩！ 今日から始めるごきげんスノーケリング',
        'desc'  => '泳ぎが苦手な人でも安心。安全テクニックと国内外おすすめスポットをプロ目線でやさしく案内。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/snorkel.png',
        'url'   => 'https://amzn.to/44ToUbh',
        'author' => '吉田哲司'
    ],
    [
        'title' => '海に一歩、人生にひと花: 「年だから」と言わない60代からのダイビング',
        'desc'  => '60歳からでも遅くない！ 健康維持と生きがいを広げるシニア向けダイビング応援ブック。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/senior-diver.png',
        'url'   => 'https://amzn.to/40z9qaN',
        'author' => '吉田哲司'
    ],
    [
        'title' => 'AIは、あなたの「魔法の杖」: 知識ゼロ・パソコン苦手でも大丈夫！',
        'desc'  => 'ChatGPT ほか最新AIを今日から使いこなす方法を、テック好きダイバーがやさしく解説する超入門。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/ai.png',
        'url'   => 'https://amzn.to/45TGvBY',
        'author' => '吉田哲司',
        'kindle_unlimited' => true
    ],
    [
        'title' => 'はじめてのSUP: ドキドキの初体験からワンちゃんとの水上散歩まで',
        'desc'  => 'ボード選び・乗り方・安全のコツを写真とQ&Aで解説。愛犬と楽しむSUP情報も満載の初心者ガイド。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/sup.png',
        'url'   => 'https://amzn.to/3TSlwYL',
        'author' => '吉田哲司'
    ],
    [
        'title' => '大切な人と、海の上で過ごす時間: 家族・仲間と楽しむシーカヤック入門',
        'desc'  => 'シーカヤックの魅力と始め方を、必要装備やおすすめスポットと合わせて紹介するやさしい入門書。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/kayac.png',
        'url'   => 'https://amzn.to/4nxWYCa',
        'author' => '吉田哲司'
    ],
    [
        'title' => '親子で楽しむ！マリンアクティビティ完全ガイド',
        'desc'  => 'SUP・カヤック・スノーケリング・体験ダイビングまで、親子で海を満喫する方法を一冊に凝縮。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/marine.png',
        'url'   => 'https://amzn.to/44LzvpB',
        'author' => '吉田哲司'
    ],
    [
        'title' => 'ブランクダイバー復活ガイド: "ReActivate" 完全ロードマップ',
        'desc'  => '半年以上潜っていないダイバーが自信を取り戻すためのリフレッシュ手順と安全チェックを徹底解説。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/brank-diver.png',
        'url'   => 'https://amzn.to/44eH3kD',
        'author' => '吉田哲司'
    ],
    [
        'title' => '水中で学ぶマインドフルネス: ダイビングがもたらす心の平穏',
        'desc'  => '呼吸と意識を整え、海の癒しを体験。ダイビングで実践するマインドフルネスのメソッドを紹介。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/maindfulness.png',
        'url'   => 'https://amzn.to/3I9PdlI',
        'author' => '吉田哲司'
    ],
    [
        'title' => 'おかしだいすき みーちゃん: 10歳の女の子が乳幼児に書いた絵本',
        'desc'  => 'お菓子が大好きなみーちゃんの甘くてかわいい冒険を10歳作者が描いた心温まるストーリー絵本。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/mi-chan.png',
        'url'   => 'https://amzn.to/3TmIyqI',
        'author' => '吉田哲司（編集）'
    ],
    [
        'title' => 'うみがめになったぜんくんの大冒険: 海を守る小さな勇者の物語',
        'desc'  => '海ガメに変身したぜんくんがゴミ問題に立ち向かう、友情と環境保護を描いた感動のエコ絵本。',
        'img'   => get_stylesheet_directory_uri().'/assets/img/books/zenkun.png',
        'url'   => 'https://amzn.to/4ny1P6k',
        'author' => '吉田哲司（編集）'
    ],
];

// Function to display books slider
function display_books_slider() {
    global $BOOKS;
    
    if (empty($BOOKS)) {
        return '';
    }
    
    $html = '<section class="books-slider-section" aria-labelledby="books-title">';
    $html .= '<div class="container">';
    $html .= '<h2 id="books-title" class="section-title">おすすめ</h2>';
    $html .= '<div class="books-slider-container">';
    $html .= '<div class="swiper books-swiper">';
    $html .= '<div class="swiper-wrapper">';
    
    foreach ($BOOKS as $book) {
        $html .= '<div class="swiper-slide">';
        $html .= '<div class="book-card">';
        $html .= '<a href="' . esc_url($book['url']) . '" target="_blank" rel="noopener" class="book-link">';
        $html .= '<img src="' . esc_url($book['img']) . '" alt="' . esc_attr($book['title']) . '" class="book-image">';
        $html .= '<div class="book-content">';
        $html .= '<h3 class="book-title">' . esc_html($book['title']) . '</h3>';
        $html .= '<p class="book-desc">' . esc_html($book['desc']) . '</p>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '<div class="swiper-pagination"></div>';
    $html .= '<div class="swiper-button-next"></div>';
    $html .= '<div class="swiper-button-prev"></div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
}

/* ── 最小版 AI ミュージック Hero ── */
function tj_music_shortcode(){
    $hero = get_stylesheet_directory_uri().'/assets/img/albums/tj-music-umigaku-hero.png';
    ob_start(); ?>
    <section id="tj-music"
             style="background-image:url('<?= esc_url($hero); ?>');"
             onclick="window.open('https://tj-music.com','_blank')" role="link">
        <div class="music-hero-inner">
            <h2 class="music-heading">吉田プロデュース&nbsp;AI&nbsp;ミュージック</h2>
            <p class="music-sub">潜って聴く？ 聴いて潜る？ ――1st &amp; 2nd Album 好評配信中！</p>
            <a class="music-cta" href="https://tj-music.com" target="_blank" rel="noopener">LISTEN</a>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('tj_music','tj_music_shortcode');


// Function to enqueue Swiper assets
function enqueue_swiper_assets() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
}

// Hook to enqueue Swiper assets on homepage
add_action('wp_enqueue_scripts', function() {
    if (is_front_page() || is_home() || is_page_template('page-home-test.php')) {
        enqueue_swiper_assets();
    }
});

?>