<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="game-canvas">

    <h1 class="main-title"><?php echo __( 'Football stars Top Trumps', 'tsg_top_trumps' ); ?></h1>

    <button class="start-game"><?php echo __( 'Play game', 'tsg_top_trumps' ); ?></button>

    <div class="response"></div>

    <div class="cards-holder">
        <div class="card">
            <div class="card-inner">
                <div class="card-face card-face--back">
                    <img class="top-trumps-logo" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/Top_Trumps.svg'; ?>">
                </div>
                <div class="card-face card-face--front">
                    <h2 class="card-name"></h2>
                    <img class="card-image">
                    <ul class="card-list">
                        <li class="card-prop">
                            <span></span>
                            <span></span>
                        </li>
                    </ul>
                    <div class="card-description"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>