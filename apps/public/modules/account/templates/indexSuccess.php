<script type="text/javascript">
    $().ready(function(){
        createPhoneMask("#account_phone");
    });
</script>

<style>
    div.fieldsetLabel {
        width: 130px;
    }
</style>

<h2>My Account</h2>

<? MAMUtils::writeFlashBlock('account'); ?>

<form action="<?php echo url_for('account/index'); ?>" method="post" class="form">
    <div class="fieldset">
        <div class="fieldsetLabel">
            Name <span class="required">*</span>
        </div>
        <div class="fields">
            <div class="shortField <? if ($form['first_name']->hasError()) echo 'error';?>">
                <?=$form['first_name']->render(); ?>
                <label>First Name</label>
                <?=$form['first_name']->renderError();?>
            </div>
            <div class="shortField <? if ($form['last_name']->hasError()) echo 'error';?>">
                <?=$form['last_name']->render(); ?>
                <label>Last Name</label>
                <?=$form['last_name']->renderError();?>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>


    <div class="fieldset">
        <div class="fieldsetLabel">
            Email <span class="required">*</span>
        </div>
        <div class="fields">
            <div class="field  <? if ($form['email']->hasError()) echo 'error';?>">
                <?=$form['email']->render(); ?>
                <label class="help">We hate spam. We promise to keep this safe.</label>
                 <?=$form['email']->renderError();?>
            </div>

        </div>
    </div>
    <div style="clear: both; "></div>

    <div class="fieldset">
        <div class="fieldsetLabel">
            Phone <span class="required">*</span>
        </div>
        <div class="fields">
            <div class="field  <? if ($form['phone']->hasError()) echo 'error';?>">
                <?=$form['phone']->render(); ?>
                <label class="help">Include area code - e.g. (414) 123-4567</label>
                 <?=$form['email']->renderError();?>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>

    <? FormUtils::writeCSRFToken($form);?>
    <div class="fieldset">
        <div class="fieldsetLabel">&nbsp;</div>
        <div class="fields">
            <div class="field">
                <button type="submit" class="action">Update</button>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>
</form>
<hr />

<h2>Password</h2>
<strong><? echo link_to('Click here to change your password', 'account/changePassword'); ?></strong>
