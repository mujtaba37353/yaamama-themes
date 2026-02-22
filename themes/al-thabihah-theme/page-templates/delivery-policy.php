<?php
/*
Template Name: Delivery Policy
*/
get_header();
?>

<main>
    <div class="y-u-container y-c-breadcrumbs" data-y="breadcrumbs">
        <p>
            <?php the_title(); ?>
        </p>
    </div>

    <div class="y-l-privacy-content" data-y="deliver-content">
        <?php the_content(); ?>
    </div>
</main>

<?php
get_footer();
