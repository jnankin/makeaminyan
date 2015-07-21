<Response>
    <? if ($choice == '1'): ?>
        <Gather numDigits="1" action="/phoneResponse/acceptAdditional?responseId=<?=$blastResponse->getId();?>" method="GET">
            <Say voice="man">
    			If you are bringing others besides yourself,
    			please enter how many you are bringing now.
            </Say>
        </Gather>
    <? elseif ($choice == '2' || $choice == '3'): ?>
            <Say voice="man">
        		Your response has been recorded.  Thank you.  Goodbye.
            </Say>
            <Hangup/>
    <? else: ?>
                <Gather numDigits="1" action="/phoneResponse/recordResponse?responseId=<?=$blastResponse->getId();?>" method="GET">
                    <Say voice="man">
            		I didn't understand your response.
            		Press one if you can come, 2 if you can not,
            		or 3 if you might be able to attend.
                    </Say>
                </Gather>
    <? endif; ?>
</Response>