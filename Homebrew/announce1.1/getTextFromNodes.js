

// Returns the text value stored in the xml tag "tag"
function getTextFromNodes( xml, tag ) {

	var nodes = xml.getElementsByTagName( tag );
	var textValues = new Array();


	for( var i=0; i<nodes.length; i++ ) {
		
		textValues[i] = nodes[i].firstChild.nodeValue;
	}

	return( textValues );

}

