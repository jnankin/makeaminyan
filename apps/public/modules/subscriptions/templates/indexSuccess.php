<style>

    div.contact_method {
        float: right;
        width: 250px;
        padding: 10px;
        background: #E8E5E5;
        border: 1px solid #777676;
    }

    div.contact_method h2 {
        font-size: 16px;
        margin: 0px;
    }
    div.contact_method p {
        margin-bottom: 20px;
    }

    div.responses {
        float: left;
        width: 640px;
    }
</style>

<h2>Your responses for '<?= $minyan->getName(); ?>'</h2>
<? MAMUtils::writeFlashBlock('subscriptions'); ?>

<br/>

<div class="responses">
    <? if (count($responses) == 0): ?>
        <h3> This minyan has not yet sent you any requests. </h3>
    <? else: ?>
        <table class="responses">
        <? foreach ($this->minyanResponses as $response): ?>
                <tr>

                </tr>
        <? endforeach; ?>
        </table>
    <? endif; ?>
</div>

<div class="contact_method">
    <h2>Contact methods for this minyan: </h2>
    <form action="<?= url_for('subscriptions/updateContactMethod?minyanId=' . $minyan->getId()); ?>" method="post">
        <input name="contact_method[phone]" type="checkbox" <?= FormUtils::writeChecked($minyanUser->getUsePhone()); ?> /> Phone <br/>
        <input name="contact_method[text]" type="checkbox" <?= FormUtils::writeChecked($minyanUser->getUseSms()); ?> /> Text <br/>
        <input name="contact_method[email]" type="checkbox" <?= FormUtils::writeChecked($minyanUser->getUseEmail()); ?> /> Email <br/><br/>

        <button type="submit" class="fancy">Update</button>
    </form>
</div>

<? Utils::clearDiv(); ?>