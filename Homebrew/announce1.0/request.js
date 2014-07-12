function createRequest() {

    var activexmodes = ["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]
    var newRequest = null;

    if (window.ActiveXObject) {
        for (var i = 0; i < activexmodes.length; i++) {
            try {
                newRequest = new ActiveXObject(activexmodes[i]);
                break;
            }
            catch (e) { }
        }
    } else if (window.XMLHttpRequest) {
        newRequest = new XMLHttpRequest();
    }
/*
    if (newRequest != null) {
        newRequest.setRequestHeader("User-Agent", "XMLHttpRequest");
    }
*/
    return newRequest;
}
