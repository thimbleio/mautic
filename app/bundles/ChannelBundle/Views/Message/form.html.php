<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$view->extend('MauticCoreBundle:FormTheme:form_tabbed.html.php');

// active, id, name, content
$tabs   = [];
$active = true;
foreach ($channels as $channel => $config) {
    if (!isset($form['channels'][$channel])) {
        continue;
    }

    $tab = [
        'active'  => $active,
        'id'      => 'channel_'.$channel,
        'name'    => $config['label'],
        'content' => $view['form']->row($form['channels'][$channel]),
    ];

    if ($view['form']->containsErrors($form['channels'][$channel])) {
        $tab['class'] = 'text-danger';
        $tab['icon']  = 'fa-warning';
    } elseif ($form['channels'][$channel]['isEnabled']->vars['data']) {
        $tab['published'] = true;
    }

    if ($active) {
        $tab['content'] .= $view->render(
            'MauticCoreBundle:FormTheme:entity_properties.html.php',
            [
                'properties'        => $form['prototypes']->children,
                'idPrefix'          => 'message_prototypes_',
                'namePrefix'        => 'message[prototypes]',
                'appendAsPanel'     => true,
                'clearFormOnCancel' => true,
            ]
        );
    }

    $tabs[] = $tab;

    $active = false;
}

// @todo - remove me
$count = 0;
while ($count < 30) {
    $tabs[] = [
        'id'      => 'tab_'.$count,
        'name'    => "Tab $count",
        'content' => "Tab $count",
    ];
    ++$count;
}
$view['slots']->set('formTabs', $tabs);

$view['slots']->start('rightFormContent');
echo $view['form']->errors($form);
echo $view['form']->row($form['name']);
echo $view['form']->row($form['description']);
echo $view['form']->row($form['category']);
echo $view['form']->row($form['isPublished']);
echo $view['form']->row($form['publishUp']);
echo $view['form']->row($form['publishDown']);
$view['slots']->stop();
