<h1><?=$minyan->getName();?> - (Minyan ID: <?=$minyan->getIdentifier();?>)</h1>
A minyan's identifier cannot be changed.  If this is a problem, please contact <a href="mailto:makeaminyan1@gmail.com">makeaminyan1@gmail.com</a>. <br/><br/>

<?=link_to('Add/Remove Subscribers', 'minyanim/subscribers?minyanId=' . $minyan->getId(), array('class' => 'fancy important')); ?><br/>

<form class="tableless" action="<?php echo url_for('minyanim/manage?id=' . $minyan->getId()); ?>" method="post" class="form">

    <div class="fieldset">
        <div class="fieldsetLabel">
            Name <span class="required">*</span>
        </div>
        <div class="fields">
            <div class="field <? if ($form['name']->hasError()) echo 'error';?>">
                <?=$form['name']->render(); ?>
                <label>Name</label>
                <?=$form['name']->renderError();?>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>

    <div class="fieldset">
        <div class="fieldsetLabel">
            Address <span class="required">*</span>
        </div>
        <div class="fields">
            <div class="field <? if ($form['address1']->hasError()) echo 'error';?>">
                <?=$form['address1']->render(); ?>
                <label>Street Address</label>
                <?=$form['address1']->renderError();?>
            </div>
            <div class="field <? if ($form['address2']->hasError()) echo 'error';?>">
                <?=$form['address2']->render(); ?>
                <label>Address Line 2</label>
                <?=$form['address2']->renderError();?>
            </div>

            <div class="shortField <? if ($form['city']->hasError()) echo 'error';?>">
                <?=$form['city']->render(); ?>
                <label>City</label>
                <?=$form['city']->renderError();?>
            </div>


            <div class="superShortField <? if ($form['state']->hasError()) echo 'error';?>" style="margin-right: 10px; width: 65px;">
                <?=$form['state']->render(array('style' => 'height: 30px; width: 55px;')); ?>
                <label>State</label>
                <?=$form['state']->renderError();?>
            </div>

            <div class="superShortField <? if ($form['zip']->hasError()) echo 'error';?>">
                <?=$form['zip']->render(); ?>
                <label>Zip</label>
                <?=$form['zip']->renderError();?>
            </div>

        </div>
    </div>
    <div style="clear: both; "></div>

    <? FormUtils::writeCSRFToken($form); ?>

    <div class="fieldset">
        <div class="fieldsetLabel">&nbsp;</div>
        <div class="fields">
            <div class="field">
                <button type="submit" class="action">Save</button>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>

</form>
