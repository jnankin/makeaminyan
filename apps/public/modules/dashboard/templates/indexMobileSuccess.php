<style>
    table.minyanim {
        width: 100%;
        font-size: 16px;
        margin-top: 10px;
    }

    table.minyanim td {
        vertical-align: middle;
    }
</style>

<?=MAMUtils::writeFlashBlock('dashboard'); ?>

<div class="minyanZone" style="margin-bottom: 20px;">
    <? if (count($minyanim) == 0): ?>
        <h3> You are currently not subscribed to any minyan</h3>

    <? else: ?>
        <h3> Your minyanim: </h3>
            <? foreach($minyanim as $minyanUser): ?>
                <h2><?=link_to($minyanUser->getMinyan()->getName(), 'subscriptions/index?minyanId=' . $minyanUser->getMinyan()->getId());?></h2>

                <? if ($minyanUser->isAdmin()): ?>
                    <?=link_to('Blast', 'blast/create?minyanId=' . $minyanUser->getMinyan()->getId(), array('class' => 'action'));?>
                    <?=link_to('View Blasts', 'blast/list?minyanId=' . $minyanUser->getMinyan()->getId(), array('class' => 'fancy'));?>
                <? endif; ?>
                <a href="#" class="fancy" onclick="unsubscribe(<?=$minyanUser->getMinyan()->getId();?>)">Unsubscribe</a><br/><br/>
            <? endforeach; ?>
    <? endif; ?>
<br/><br/>
    <center>
        <a class="whiteButtonFixed" href="/login/logout">Logout</a>
    </center>
</div>