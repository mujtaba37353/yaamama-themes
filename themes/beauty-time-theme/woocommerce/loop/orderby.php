<?php
/**
 * Order By — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<?php
$orderby_labels = array(
	'menu_order' => __( 'الترتيب الافتراضي', 'beauty-time-theme' ),
	'popularity' => __( 'الأكثر شعبية', 'beauty-time-theme' ),
	'rating'     => __( 'الأعلى تقييماً', 'beauty-time-theme' ),
	'date'       => __( 'الأحدث', 'beauty-time-theme' ),
	'price'      => __( 'السعر: من الأقل إلى الأعلى', 'beauty-time-theme' ),
	'price-desc' => __( 'السعر: من الأعلى إلى الأقل', 'beauty-time-theme' ),
);
?>
<form class="woocommerce-ordering beauty-ordering" method="get">
	<select name="orderby" class="orderby beauty-orderby" aria-label="<?php esc_attr_e( 'ترتيب المتجر', 'beauty-time-theme' ); ?>">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<?php $label = isset( $orderby_labels[ $id ] ) ? $orderby_labels[ $id ] : $name; ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
