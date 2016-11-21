<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!isset($form->children['channel'])) {
    return;
}

$panels     = [];
$channel    = $form->children['channel']->vars['data'];
$idPrefix   = "message_channels_{$channel}_goals_";
$namePrefix = "message[channels][{$channel}][goals]";

$availablePanels = [];
foreach ($channels[$channel]['goals'] as $key => $label) {
    $availablePanels[] = [
        'label'                         => $label,
        'value'                         => $key,
        'prototypeTemplatePlaceholders' => ['__footer__' => '<i class="fa fa-bullseye"></i> '.$label],
    ];
}

// Convert goals to panels
/** @var \Symfony\Component\Form\Form $goal */
foreach ($form->children['goals'] as $goal) {
    $panels[$goal->vars['name']] = [
        'template'          => 'MauticChannelBundle:FormTheme:goal_row.html.php',
        'templateVariables' => [
            'form'       => $goal,
            'idPrefix'   => $idPrefix,
            'namePrefix' => $namePrefix,
            'goals'      => $channels[$channel]['goals'],
        ],
        'footer' => '<i class="fa fa-bullseye"></i> '.$channels[$channel]['goals'][$goal->vars['data']->getGoalType()],
    ];

    if ($view['form']->containsErrors($goal)) {
        $panels[$goal->vars['name']]['class']                               = 'sortable-has-error';
        $panels[$goal->vars['name']]['templateVariables']['editButtonIcon'] = 'fa-warning text-warning';
    }
}

echo $view['form']->row($form->children['channel']);
?>
    <?php echo $view['form']->errors($form); ?>
    <?php echo $view['form']->row($form->children['isEnabled']); ?>
    <hr/>

    <h4><?php echo $view['translator']->trans('mautic.channel.message.form.channel_header'); ?></h4>
    <?php if (isset($form->children['channelId'])): ?>
    <p class="small"><?php echo $view['translator']->trans('mautic.channel.message.form.channel_descr'); ?></p>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $view['form']->row($form->children['channelId']); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($form->children['properties'])): ?>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $view['form']->row($form->children['properties']); ?>
        </div>
    </div>

    <?php endif; ?>
    <?php if (!empty($channels[$channel]['goals'])): ?>
    <hr/>

    <h4><?php echo $view['translator']->trans('mautic.channel.message.form.goals_header'); ?></h4>
    <p class="small"><?php echo $view['translator']->trans('mautic.channel.message.form.goals_descr'); ?></p>
    <?php
    echo $view->render(
        'MauticCoreBundle:SortablePanels:container.html.php',
        [
            'containerId'              => "$channel-container",
            'availablePanels'          => $availablePanels,
            'appendPanelMessage'       => 'mautic.channel.message.form.add_goal',
            'noPanelMessage'           => 'mautic.channel.message.form.no_goals',
            'panels'                   => $panels,
            'prototypeContainerPrefix' => 'message_prototypes_',
            'prototypeIdPrefix'        => $idPrefix,
            'prototypeNamePrefix'      => $namePrefix,
            'prototypePanelTemplates'  => ['footer' => '__footer__'],
        ]
    );

    if (!empty($channels[$channel]['channelTemplate'])):
        echo $view->render($channels[$channel]['channelTemplate'], ['form' => $form, 'channel' => $channel, 'channelProperties' => $channels[$channel]]);
    endif;
endif;
