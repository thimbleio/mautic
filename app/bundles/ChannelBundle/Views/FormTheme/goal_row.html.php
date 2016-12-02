<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!$goalType = $form->vars['data']->getGoalType()) {
    return;
}

$label = $form->vars['data']->getName();
if (empty($label) && isset($defaultLabel)) {
    $label = $defaultLabel;
}
?>

<span class="sortable-panel-label"><?php echo $label; ?></span>
<?php
echo $view->render('MauticCoreBundle:FormTheme:entity_properties.html.php',
    [
        'properties'    => $form,
        'idPrefix'      => $idPrefix.$form->vars['name'].'_',
        'namePrefix'    => $namePrefix.'['.$form->vars['name'].']',
        'appendAsPanel' => true,
        'update'        => true,
        'header'        => $goals[$form->vars['data']->getGoalType()],
    ]
);
