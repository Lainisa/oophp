<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Init the game and redirect to the game
 */
$app->router->get("guess/init", function () use ($app) {
    //init the game
    $game = new Ligl\Guess\Guess();
    //fetch the number thats initiated
    $_SESSION["number"] = $game->number();
    //check nr of tries left, default 6
    $_SESSION["tries"] = $game->tries();
    // init the session for the gamestart
    return $app->response->redirect("guess/play");
});

/**
* show game status
*/
$app->router->get("guess/play", function () use ($app) {
    $title = "Play the game";

    $res = $_SESSION["res"] ?? null;

    $tries = $_SESSION["tries"] ?? null;
    $guess = $_SESSION["guess"] ?? null;
    $cheat = $_SESSION["doCheat"] ?? null;
    $number = $_SESSION["number"] ?? null;

    $_SESSION["res"] = null;
    $_SESSION["guess"] = null;
    //$_SESSION["doCheat"] = null;
    //send this into the view page so it can pick it up theree
    $data = [
        "res" => $res,
        "number" => $number ?? null,
        "tries" => $tries,
        "cheat" => $cheat
    ];

    $app->page->add("guess/play", $data);
    //$app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
* make a guess
*/
$app->router->post("guess/play", function () use ($app) {
    $title = "Play the game";

    $tries = $_SESSION["tries"] ?? null;
    $number = $_SESSION["number"] ?? null;

    $doGuess = $_POST["doGuess"] ?? null;
    $guess = $_POST["guess"] ?? null;

    $doInit = $_POST["doInit"] ?? null;
    $_SESSION["doCheat"] = $_POST["doCheat"] ?? null;

    if ($doInit) {
        return $app->response->redirect("guess/init");
    }

    //if you guessed a number between 1-100
    if ($doGuess) {
        $game = new Ligl\Guess\Guess($number, $tries);
        //if its correct, then what?
        try {
            $makeGuess = $game->makeGuess((int)$guess);
            $_SESSION["res"] = "You guessed for: " . $guess . "<br>" . "<b>"
            . $makeGuess
            . "</b>!";

            $_SESSION["tries"] = $game->tries();
            $_SESSION["guess"] = $guess;

            if ($makeGuess === "No more tries") {
                $_SESSION["res"] = "Out of tries, started over. The number you were looking for was "
                . $_SESSION["number"];

                return $app->response->redirect("guess/init");
            }

            if ($makeGuess === "Correct!") {
                $_SESSION["res"] = "Well done! The correct number was your guess, nr "
                . $_SESSION["number"];

                return $app->response->redirect("guess/init");
            }
        } catch (Ligl\Guess\GuessException $e) {
            $_SESSION["tries"] = $game->tries();
            $_SESSION["res"] = $e->getMessage();
        }
    }
    return $app->response->redirect("guess/play");
});
