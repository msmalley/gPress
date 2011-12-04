/*
 *
 *  SIMPLY UPLOAD A FILE CALLED "custom.php" into "wp-content/gpress/"
 *  If gPress finds that file, it will automatically include it at run-time
 *  This allows you to further customise gPress without modifying core files
 *
 *  The "wp-content/gpress" folder can also contain custom.css and custom.js files
 *
 *  PLEASE FIND SEVERAL USAGE EXAMPLES BELOW WHICH MAY LATER BECOME GUI
 *  CONTROLLABLE COMPONENTS BUT ARE FOR NOW TO SHOWCASE LIVE EXAMPLES
 *
 */

function checkString(string, txt) {
    if(string.indexOf(txt) != -1) {
        return true;
    }else{
        return false;
    }
}
function gpress_tab_toggle(key, id){
    var tabLI = $('li#gpress-tabbed-content-tab-'+key+'-'+id);
    var tabLIclass = $(tabLI).attr('class');
    if(!checkString(tabLIclass,'selected')){
        $('ul#gpress-tabbed-content-list-'+id).find('li.gpress-tabbed-tab').removeClass('selected');
        $(tabLI).addClass('selected');
    }
}