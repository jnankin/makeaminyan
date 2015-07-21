<style>
    table.minyanim {
        width: 100%;
        font-size: 16px;
        margin-top: 10px;
    }

    table.minyanim td {
        vertical-align: top;
    }
    </style>

<?=MAMUtils::writeFlashBlock('dashboard'); ?>

<div class="minyanZone" style="margin-bottom: 20px;">
    <? if (count($minyanim) == 0): ?>
        <h3> You are currently not subscribed to any minyan</h3>

    <? else: ?>

        <h3>Minyanim you are subscribed to: </h3>
        <table class="minyanim">
            <? foreach($minyanim as $minyanUser): ?>
            <tr>
                <td width="5%"><? if ($minyanUser->isAdmin()) echo image_tag('star.png', array('title' => 'You are an admin for this minyan.')); ?></td>
                <td width="45%"><?=link_to($minyanUser->getMinyan()->getName(), 'subscriptions/index?minyanId=' . $minyanUser->getMinyan()->getId());?></td>
                <td width="10%">
                    <? if ($minyanUser->getUsePhone()) echo image_tag('phone.gif', array('title' => 'Phone')); ?>
                    <? if ($minyanUser->getUseSms()) echo image_tag('text.png', array('title' => 'Text')); ?>
                    <? if ($minyanUser->getUseEmail()) echo image_tag('email.png', array('title' => 'Email')); ?>
                </td>
                <td style="width: 60px;">
                    <? if ($minyanUser->isAdmin()): ?>
                        <?=link_to('Blast', 'blast/create?minyanId=' . $minyanUser->getMinyan()->getId(), array('class' => 'action'));?>
                    <? endif; ?>
                </td>
                <td style="width: 85px;">
                    <? if ($minyanUser->isAdmin()): ?>
                        <?=link_to('View Blasts', 'blast/list?minyanId=' . $minyanUser->getMinyan()->getId(), array('class' => 'fancy'));?>
                    <? endif; ?>
                </td>
                <td style="width: 60px;">
                    <? if ($minyanUser->isAdmin()): ?>
                        <?=link_to('Manage', 'minyanim/manage?id=' . $minyanUser->getMinyan()->getId(), array('class' => 'fancy'));?>
                    <? endif; ?>
                </td>
                <td style="width: 60px;">
                    <a href="#" class="fancy" onclick="unsubscribe(<?=$minyanUser->getMinyan()->getId();?>)">
                        Unsubscribe
                    </a>
                </td>
            </tr>

            <? endforeach; ?>
        </table>
    <? endif; ?>
</div>

<button class="fancy important" onclick="showEnterMinyanIdModal()">
    Subscribe to a minyan
</button>


<div id="modal_enterMinyanId" class="modal" style="display: none">
    <h4>Enter a minyan id to subscribe:</h4>

    <div style="margin: 10px">
        <input class="subscribeMinyanId" type="text" style="width: 440px; font-size: 16px; padding: 5px; margin-bottom: 10px;" /> <br/>
        <button onclick="findMinyanByIdentifier($(this).parent().find('.subscribeMinyanId').val())" class="action">Subscribe</button>
    </div>
</div>

<div id="modal_subscribe" class="modal" style="display: none">
    <strong>Select contact method(s) for subscription to '<span class="minyanName"></span>':</strong>

    <div style="margin: 10px">

        <input class="subscribe_phone" type="checkbox" /> Phone <br/>
        <input class="subscribe_text" type="checkbox" /> Text <br/>
        <input class="subscribe_email" type="checkbox" /> Email <br/>
        <input class="minyanId" type="hidden" /><br/>

        <button type="button" onclick="subscribe(this)" class="action">Subscribe</button>
    </div>
</div>

<script type="text/javascript">
    function showEnterMinyanIdModal(){
        MAMUI.modal('#modal_enterMinyanId', 'Subscribe', 500, 200);
    }

    function findMinyanByIdentifier(minyanIdentifier){
        $.post('/subscriptions/findMinyanByIdentifier', {
            'minyanIdentifier' : minyanIdentifier
        },
        function(data){
            if (data){
                if (data.success) {
                     $('#modal_subscribe .minyanId').val(data.data.id);
                     $('#modal_subscribe .minyanName').text(data.data.name);
                     MAMUI.modal('#modal_subscribe', 'Subscribe', 500, 200, {modal: false});
                }
                else {
                    Boxy.alert(data.message, null, { 'title' : 'Failure' });
                }
            }
        }, 'json');
    }

    function subscribe(context){
        var options = {};
        options['id'] = $(context).parent().find('.minyanId').val();
        options['use_phone'] = $(context).parent().find('.subscribe_phone').attr('checked');
        options['use_text'] = $(context).parent().find('.subscribe_text').attr('checked');
        options['use_email'] = $(context).parent().find('.subscribe_email').attr('checked');
        
        $.post('/subscriptions/subscribe', options,
        function(data){
            if (data){
                if (data.success) location.reload();
                else { Boxy.alert(data.message, null,  { 'title' : 'Failure' }); }
            }
        }, 'json');
    }

</script>