<?php
function validation($valeur, $regex) {
    return preg_match($regex, $valeur);
}

?>