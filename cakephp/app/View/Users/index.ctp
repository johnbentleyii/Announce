<script type='text/javascript'>
//![CDATA[
	function openAnnouncements() {
	
		var jsonRequest = new Object();
		jsonRequest.user_id = $( "#UserSelect" ).val();
		jQuery.post( "/Users/select_user",
					JSON.stringify( jsonRequest ),
					function ( response ) {
						if( response.error ) {
							alert( response.error );
							console.log( response.error );
						} else {
							window.open( "/Doctors/", "_self" );
						}
					}
		);
	}
//]]>
</script>
<div id="UserDiv">
    <select id="UserSelect"> <!-- onchange="selectUser();"> -->
<?php
	foreach( $users as $user ) {
		echo '<option value="' . $user['User']['id'] . '">';
		echo $user['User']['username'];
		echo '</option>';
	}
?>
	</select>
</div>

<div id="ToolsDiv">
    <input id="OKBtn" type="button" value="OK" onclick="openAnnouncements();"/>
</div>

