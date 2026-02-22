<?php
/**
 * Sweet House Theme — Custom Post Type: الوصفات (Recipes).
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * تسجيل نوع المنشور الوصفات.
 */
function sweet_house_register_recipe_post_type() {
	$labels = array(
		'name'               => __( 'الوصفات', 'sweet-house-theme' ),
		'singular_name'      => __( 'وصفة', 'sweet-house-theme' ),
		'menu_name'          => __( 'الوصفات', 'sweet-house-theme' ),
		'add_new'            => __( 'إضافة وصفة', 'sweet-house-theme' ),
		'add_new_item'       => __( 'إضافة وصفة جديدة', 'sweet-house-theme' ),
		'edit_item'          => __( 'تعديل الوصفة', 'sweet-house-theme' ),
		'new_item'           => __( 'وصفة جديدة', 'sweet-house-theme' ),
		'view_item'          => __( 'عرض الوصفة', 'sweet-house-theme' ),
		'search_items'       => __( 'البحث في الوصفات', 'sweet-house-theme' ),
		'not_found'          => __( 'لا توجد وصفات', 'sweet-house-theme' ),
		'not_found_in_trash' => __( 'لا توجد وصفات في المحذوفات', 'sweet-house-theme' ),
		'all_items'          => __( 'جميع الوصفات', 'sweet-house-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-food',
		'menu_position'       => 25,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'recipe' ),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'thumbnail', 'editor' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'recipe', $args );
}
add_action( 'init', 'sweet_house_register_recipe_post_type' );

/**
 * تسجيل تصنيف الوصفات (حلويات، مقبلات، أطباق رئيسية، وصفات مميزة).
 */
function sweet_house_register_recipe_taxonomy() {
	$labels = array(
		'name'               => __( 'تصنيفات الوصفات', 'sweet-house-theme' ),
		'singular_name'      => __( 'تصنيف الوصفة', 'sweet-house-theme' ),
		'search_items'       => __( 'البحث في التصنيفات', 'sweet-house-theme' ),
		'all_items'          => __( 'جميع التصنيفات', 'sweet-house-theme' ),
		'parent_item'        => __( 'التصنيف الأب', 'sweet-house-theme' ),
		'parent_item_colon'  => __( 'التصنيف الأب:', 'sweet-house-theme' ),
		'edit_item'          => __( 'تعديل التصنيف', 'sweet-house-theme' ),
		'update_item'        => __( 'تحديث التصنيف', 'sweet-house-theme' ),
		'add_new_item'       => __( 'إضافة تصنيف جديد', 'sweet-house-theme' ),
		'new_item_name'      => __( 'اسم التصنيف الجديد', 'sweet-house-theme' ),
		'menu_name'          => __( 'تصنيفات الوصفات', 'sweet-house-theme' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'recipe-category' ),
	);

	register_taxonomy( 'recipe_category', array( 'recipe' ), $args );
}
add_action( 'init', 'sweet_house_register_recipe_taxonomy' );

/**
 * إنشاء التصنيفات الافتراضية عند التفعيل.
 */
function sweet_house_create_default_recipe_categories() {
	if ( get_option( 'sweet_house_recipe_categories_created' ) ) {
		return;
	}

	$defaults = array(
		'وصفات مميزة'      => 'featured',
		'حلويات'          => 'sweets',
		'مقبلات'          => 'appetizers',
		'أطباق رئيسية'     => 'main-dishes',
	);

	foreach ( $defaults as $name => $slug ) {
		if ( ! term_exists( $name, 'recipe_category' ) ) {
			wp_insert_term( $name, 'recipe_category', array( 'slug' => $slug ) );
		}
	}

	update_option( 'sweet_house_recipe_categories_created', true );
}
add_action( 'init', 'sweet_house_create_default_recipe_categories', 20 );

/**
 * Meta box: تفاصيل الوصفة (وقت التحضير، وقت الطهي، يخدم).
 */
function sweet_house_recipe_meta_box_add() {
	add_meta_box(
		'sweet_house_recipe_details',
		__( 'تفاصيل الوصفة', 'sweet-house-theme' ),
		'sweet_house_recipe_meta_box_render',
		'recipe',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sweet_house_recipe_meta_box_add' );

/**
 * عرض حقول meta box.
 */
function sweet_house_recipe_meta_box_render( $post ) {
	wp_nonce_field( 'sweet_house_save_recipe_meta', 'sweet_house_recipe_meta_nonce' );

	$prep_time  = get_post_meta( $post->ID, '_recipe_prep_time', true );
	$cook_time  = get_post_meta( $post->ID, '_recipe_cook_time', true );
	$serves     = get_post_meta( $post->ID, '_recipe_serves', true );
	$ingredients = get_post_meta( $post->ID, '_recipe_ingredients', true );
	$instructions = get_post_meta( $post->ID, '_recipe_instructions', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="recipe_prep_time"><?php esc_html_e( 'وقت التحضير (دقائق)', 'sweet-house-theme' ); ?></label></th>
			<td><input type="number" id="recipe_prep_time" name="recipe_prep_time" value="<?php echo esc_attr( $prep_time ); ?>" min="0" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="recipe_cook_time"><?php esc_html_e( 'وقت الطهي (دقائق)', 'sweet-house-theme' ); ?></label></th>
			<td><input type="number" id="recipe_cook_time" name="recipe_cook_time" value="<?php echo esc_attr( $cook_time ); ?>" min="0" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="recipe_serves"><?php esc_html_e( 'يخدم (عدد الأشخاص)', 'sweet-house-theme' ); ?></label></th>
			<td><input type="number" id="recipe_serves" name="recipe_serves" value="<?php echo esc_attr( $serves ); ?>" min="1" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="recipe_ingredients"><?php esc_html_e( 'المكونات', 'sweet-house-theme' ); ?></label></th>
			<td>
				<p class="description"><?php esc_html_e( 'سطر واحد لكل مكون (مثال: لتر حليب)', 'sweet-house-theme' ); ?></p>
				<textarea id="recipe_ingredients" name="recipe_ingredients" rows="12" class="large-text"><?php echo esc_textarea( $ingredients ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="recipe_instructions"><?php esc_html_e( 'التوجيهات', 'sweet-house-theme' ); ?></label></th>
			<td>
				<p class="description"><?php esc_html_e( 'سطر واحد لكل خطوة (مثال: في وعاء كبير، اخلطي الحليب مع النشا)', 'sweet-house-theme' ); ?></p>
				<textarea id="recipe_instructions" name="recipe_instructions" rows="15" class="large-text"><?php echo esc_textarea( $instructions ); ?></textarea>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * حفظ meta box.
 */
function sweet_house_recipe_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['sweet_house_recipe_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sweet_house_recipe_meta_nonce'] ) ), 'sweet_house_save_recipe_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( get_post_type( $post_id ) !== 'recipe' ) {
		return;
	}

	$fields = array(
		'recipe_prep_time'    => 'sanitize_text_field',
		'recipe_cook_time'    => 'sanitize_text_field',
		'recipe_serves'       => 'sanitize_text_field',
		'recipe_ingredients'  => 'sanitize_textarea_field',
		'recipe_instructions' => 'sanitize_textarea_field',
	);
	foreach ( $fields as $field => $sanitize ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = call_user_func( $sanitize, wp_unslash( $_POST[ $field ] ) );
			update_post_meta( $post_id, '_' . $field, $value );
		}
	}
}
add_action( 'save_post_recipe', 'sweet_house_recipe_meta_box_save' );

/**
 * إعادة بناء قواعد الروابط عند التبديل للثيم.
 * ملاحظة: لو كانت الوصفات لا تظهر بعد التحديث، اذهب إلى إعدادات > الروابط الدائمة واضغط حفظ.
 */
function sweet_house_recipe_rewrite_flush() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'sweet_house_recipe_rewrite_flush' );
