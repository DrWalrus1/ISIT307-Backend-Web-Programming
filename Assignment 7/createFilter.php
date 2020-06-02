<?php

require_once "dbinteraction.php";

// TODO: add aggregate counter of currently showing games matching this checkbox
function createLabel ($inputID, $displayName) {
    return "<label for=\"$inputID\">$displayName</label>";
}

function createButtons(){
    return "<input class=\"button\" type=\"submit\" value=\"Search\"/>
    <button type=\"button\" class=\"button\" onclick=\"clearForm(this.form.id);\">Clear</button>";
}

function createTextInput($displayName, $inputID, $inputName, $placeholder = NULL) {
    $string =
    createLabel($inputID, $displayName) .
        "\n<input id=\"$inputID\" type=\"text\" name=\"$inputName\"";
    if (!is_null($placeholder)) {
        $string .= " placeholder=\"$placeholder\"";
    }
    $string .= "/>\n<br>\n";
    return $string;
}

function createNumberInput($displayName, $inputID, $inputName, $minNum = NULL, $maxNum = NULL, $placeholder = NULL) {
    $string =
    createLabel($inputID, $displayName) . 
        "\n<input id =\"$inputID\" type=\"number\" name=\"$inputName\"";
    if (!is_null($placeholder)) {
        $string .= " placeholder=\"$placeholder\"";
    }
    if(!is_null($minNum)) {
        $string .= " min=\"$minNum\"";
    }
    if(!is_null($maxNum)) {
        $string .= " max=\"$maxNum\"";
    }
    $string .= "/>\n<br>\n";
    return $string;
}

//Create individual checkbox with label
function createCheckboxInput($labelName, $displayName, $inputID, $inputName, $isChecked) {
    $string =
    "<input type=\"checkbox\" id=\"$inputID\" name=\"$inputName\" value=\"$displayName\"";
    if ($isChecked) {
        $string .= " checked";
    }
    $string .= ">" .
    createLabel($inputID, $labelName) .
    "<br>\n";
    return $string;
}

function initialiseCheckboxGroup($groupName, $category, array $stickyValues = NULL) {
    $string = "<div id=\"$groupName\">\n";
    if (!is_null($stickyValues) && isset($stickyValues[$groupName])) {
        if ($stickyValues[$groupName][0] == $groupName) {
            $string .= "<input type=\"checkbox\" id=\"" . $groupName . "Checkbox\" onchange=\"selectAll(this.value, this.checked)\" name=\"" . $category . "[" . $groupName . "][]\" value=\"$groupName\" checked>";
        } else {
            $string .= "<input type=\"checkbox\" id=\"" . $groupName . "Checkbox\" onchange=\"selectAll(this.value, this.checked)\" name=\"" . $category . "[" . $groupName . "][]\" value=\"$groupName\">";
        }
    } else {
        $string .= "<input type=\"checkbox\" id=\"" . $groupName . "Checkbox\" onchange=\"selectAll(this.value, this.checked)\" name=\"" . $category . "[" . $groupName . "][]\" value=\"$groupName\">";
    }
    return $string;
}

//Create grouped checkboxes e.g. manufacturer
//TODO: anonymize function
function createGroupedCheckboxes(array $groupNames, $category, $inputName, array $filterCount, array $stickyValues = NULL) {
    $platforms = dbGetPlatforms();
    $string = "";
    foreach ($groupNames as $groupName) {
        $string .= initialiseCheckboxGroup($groupName, $category, $stickyValues);
        $string .= createLabel(($groupName . "Checkbox"), $groupName . " (" . $filterCount[$groupName] . ")") . "<br>" .
            "<div id=\"" . $groupName . "Selection\" style=\"padding-left:0.75em\">";
        foreach ($platforms as $platform) {
            $count = (array_key_exists($platform["name"], $filterCount) ? " (" . $filterCount[$platform["name"]] . ")" : " (0)");
            if ($platform["name"] != $platform["manufacturer"]) {
                if ($platform["manufacturer"] == $groupName) {
                    $found = false;
                    
                    if (!is_null($stickyValues) && isset($stickyValues[$groupName])) {
                        for ($i=0; $i < count($stickyValues[$groupName]); $i++) {
                            if ($stickyValues[$groupName][$i] == $platform["name"]) {
                                $string .= createCheckboxInput($platform["name"] . $count, $platform["name"], $platform["initial"], str_replace("[]", "[" . $groupName . "][]", $inputName), true);
                                $found = true;
                                break;
                            }
                        }
                    }
                    if (!$found) {
                    $string .= createCheckboxInput($platform["name"] . $count, $platform["name"], $platform["initial"], str_replace("[]", "[" . $groupName . "][]", $inputName), false);
                    }
                    
                }
            }
        }
        $string .= "</div></div>";
    }
    return $string;
}

?>
