var $ = jQuery;

/**
 * Knockout ViewModel
 */
function TopTrumpsViewModel() {
    var self = this;

    /** Check if game is on */
    self.gameOn = ko.observable(false);

    /** Check if game has ended */
    self.gameEnd = ko.observable(false);

    /** Store response text */
    self.response = ko.observable();

    /** Store card attribute to play with */
    self.propChosen = ko.observable();

    /** Store all the cards */
    self.cards = ko.observableArray([]);

    /** Setup player and opponent variables */
    self.player = "";
    self.opponent = "";

    /**
     * With this function we set-up all the properties for the cards
     * and assign one card to the player and one to the opponent.
     */
    self.setCards = function(cards) {
        // Push each card into our observableArray
        cards.forEach(function(card, index) {
            card.type = (index === 0) ? 'card--player' : 'card--computer';
            card.isVisible = ko.observable((index === 0) ? true : false)
            self.cards.push(card);
        });

        // Setup player and opponent variables
        self.player = self.cards()[0];
        self.opponent = self.cards()[1];
    };

    /**
     * With this function we retrieve the cards via the WP REST API
     * and we start the game.
     */
    self.getCards = function() {
        $.ajax({
            url: 'http://pragmatic.dev/wp-json/top_trumps/cards',
            data: {
                per_page: 2,
                _embed: true
            },
            success: function (data) {
                self.setCards(data);
            },
            error: function(data) {
                alert("Error, you cannot play this game right now!");
            }
        });
    };

    /**
     * Start the game.
     */
    self.startGame = function() {
        self.getCards();
        self.gameOn(true);
    };

    /**
     * Give the user the possibility to restart the game with new cards
     */
    self.restartGame = function() {
        // Reset all values to default
        self.propChosen("");
        self.opponent.isVisible(false);
        self.gameEnd(false);
        self.response("");
        self.cards([]);

        // Restart the game
        self.startGame();
    };

    /**
     * Compare the chosen value to declare the winner
     */
    self.compareValue = function(parent_ID, currentItem, event) {
        if(self.gameEnd())
            return;

        // Get opponent attributes array
        var opponentAttributes = self.opponent.attributes;

        // Store the chosen attribute
        self.propChosen(currentItem.key);

        // Show the opponent character
        self.opponent.isVisible(true);

        /**
         * Iterate attributes array to find the chosen attribute value
         * and compare it to declare the winner.
         */
        for(var i = 0, l = opponentAttributes.length; i < l; i++) {
            if(opponentAttributes[i].key === currentItem.key){
                if(currentItem.value > opponentAttributes[i].value) {
                    self.response(self.player.name + ' win!');
                } else {
                    self.response(self.player.name + ' lose!');
                }
            }
        }

        // End the game
        self.gameEnd(true);
    };
};

ko.applyBindings(TopTrumpsViewModel);