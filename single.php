<?php get_header(); ?>

<article class="post">
    <div class="container">
        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
            <h1 class="post__title"><?php the_title(); ?></h1>
            <?php the_content(); ?>
        <?php endwhile; ?>
    </div>
</article>

<?php get_sidebar(); ?>
<?php get_footer(); ?>