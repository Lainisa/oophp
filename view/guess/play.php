<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

?><h1> Guess the number</h1>
<p> Guess a number between <b>1</b> and <b>100</b>, you got<b> <?= (int)$tries; ?></b> tries. </p>

<form method="post">
    <input type="number" name="guess">
    <input type="hidden" name="number" value="<?= (int)$number ?>">
    <input type="hidden" name="tries" value="<?= (int)$tries ?>">
    <input type="submit" value="Make a guess" name="doGuess">
    <input type="submit" value="Start from beginning" name="doInit">
    <input type="submit" value="Cheat" name="doCheat">
</form>
<br>
<p> <?= $res ?> </p>
<?php if ($cheat) :?>
    <p> the number your loking for is <?= $number ?> </p>
<?php endif; ?>
<br>
