<div id="modal_addSubscriberForm" style="width: 530px; height: 400px; "class="modal" style="display: none">
    <strong>
        This will only work for new subscribers who currently do not have a Make a Minyan account.
        If there already is an account associated with the user, the user should log in and subscribe that way.</strong>


    <form action="<?php echo url_for('minyanim/addNewSubscriber?minyanId=' . $minyan->getId()); ?>" method="post" class="tableless">
    <div class="fieldset">
        <div class="fields">
            <div class="shortField">
                <?= $form['first_name']->render(); ?>
                <label>First Name</label>
                <?=$form['first_name']->renderError();?>
            </div>
            <div class="shortField">
                <?= $form['last_name']->render(); ?>
                <label>Last Name</label>
                <?=$form['last_name']->renderError();?>
            </div>

            <div class="field">
                <?= $form['email']->render(); ?>
                <label>Email</label>
                <?=$form['email']->renderError();?>
            </div>

            <div class="field">
                <?= $form['phone']->render(); ?>
                <label>Phone</label>
                <?=$form['phone']->renderError();?>
            </div>
            
            
            <div class="field">
                <h4 style="margin: 0px;">Contact Methods:</h4>
                <input name="contact_method[phone]" type="checkbox" /> Phone <br/>
                <input name="contact_method[text]" type="checkbox" /> Text <br/>
                <input name="contact_method[email]" type="checkbox" /> Email 
            </div>

            <? FormUtils::writeCSRFToken($form); ?>
            <div class="field" style="margin-top: 20px;">
                <button class="bigGreen" type="submit">Add Subscriber</button>
            </div>
        </div>
    </div>
    </form>
    <?=Utils::clearDiv(); ?>
</div>