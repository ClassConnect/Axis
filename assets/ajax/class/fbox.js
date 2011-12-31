function changeDir(dirID) {
	var url = 'index.php?dir=' + dirID;
	changePage(currentApp, url);
	parent.location.hash = currentApp + '_' + url;
}