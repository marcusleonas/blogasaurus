<?php get_header(); ?>

<section class="blog-content">
    <div class="container">

        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
            <div class="post">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
            </div>
        <?php endwhile; ?>

        <?php get_sidebar(); ?>

        <?php posts_nav_link(); ?>
    </div>
</section>

<?php get_footer(); ?>