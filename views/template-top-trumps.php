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

    <h1 class="main-title"><?php echo __( 'Football stars Top Trumps', 'ftbll_top_trumps' ); ?></h1>

    <button data-bind="click: startGame"
            class="start-game"><?php echo __( 'Play game', 'ftbll_top_trumps' ); ?></button>

    <div class="response"></div>

    <div class="cards-holder" data-bind="css: { active: gameOn }, foreach: cards">
        <div data-bind="css: type" class="card">
            <div data-bind="css: { flipped: isVisible }" class="card-inner">
                <div class="card-face card-face--back">
                    <img class="top-trumps-logo" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/Top_Trumps.svg'; ?>">
                </div>
                <div class="card-face card-face--front">
                    <h2 data-bind="text: name" class="card-name"></h2>
                    <img data-bind="attr: { src: image }" class="card-image">
                    <ul data-bind="foreach: attributes" class="card-list">
                        <li class="card-prop">
                            <span data-bind="text: label"></span>
                            <span data-bind="text: value"></span>
                        </li>
                    </ul>
                    <div data-bind="html: description" class="card-description"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>