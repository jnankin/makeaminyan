<Response>
	<Gather numDigits="1" action="/phoneResponse/recordResponse?responseId=<?=$blastResponse->getId();?>" method="GET">
		<Say voice="man">
			This is a call from Make a Minyan.  <?=$minyanName;?> needs a minyan for <?=$eventType;?> <?=$eventTime;?>.
                        Press one if you can come, 2 if you can not, or 3 if you might be able to attend.
		</Say>
	</Gather>
</Response>