<?php
/**
 * Search form template
 *
 * @package MyCarTheme
 */
?>

<form role="search" method="get" class="y-c-header-search-expandable" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" 
           class="y-c-header-search-expandable__input" 
           placeholder="<?php echo esc_attr_x('اكتب للبحث...', 'placeholder', 'my-car-theme'); ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" 
           required>
    <div class="y-c-header-search-expandable__icon">
        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
            <title>Search</title>
            <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none"
                stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></path>
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10"
                stroke-width="32" d="M338.29 338.29L448 448"></path>
        </svg>
    </div>
</form>
