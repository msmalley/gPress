<?php

function gpress_install_db($forced_install=false){
    global $wpdb;
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    if(!empty($wpdb->charset)){
	$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }if(!empty($wpdb->collate)){
	$charset_collate .= " COLLATE $wpdb->collate";
    }
    $gpress_db_version = get_site_option('gpress_db_version', 0);
    //echo '$forced_install = '.$forced_install.'<br />';
    //echo '$gpress_db_version = '.$gpress_db_version; exit;
    if($gpress_db_version<1){
        $sql = "CREATE TABLE ".GPRESS_DB_TABLE." (
            gp_id bigint(32) unsigned NOT NULL auto_increment,
            meta_type enum('post','comment','user','blog','bp_group','bp_activity','bp_forum','custom') NOT NULL default 'custom',
            meta_key varchar(32) NOT NULL default 'custom',
            meta_id bigint(32) NOT NULL default '0',
            lat float(18,15) NOT NULL,
            lng float(18,15) NOT NULL,
            point point NOT NULL,
            icon varchar(255) default '',
            shadow varchar(255) default '',
            title varchar(255) NOT NULL,
            address varchar(255) NOT NULL default '".__('Address Unknown','gpress')."',
            url varchar(255) default '',
            map_height int(4) NOT NULL default '450',
            map_type enum('ROADMAP','SATELLITE','TERRAIN','HYBRID') NOT NULL default 'ROADMAP',
            map_zoom int(2) NOT NULL default '13',
            added datetime NOT NULL default '0000-00-00 00:00:00',
            updated timestamp NOT NULL,
            PRIMARY KEY (gp_id),
            KEY meta_type (meta_type),
            KEY meta_key (meta_key),
            KEY meta_id (meta_id),
            KEY lat (lat),
            KEY lng (lng),
            KEY address (address),
            KEY map_height (map_height),
            KEY map_type (map_type),
            KEY map_zoom (map_zoom),
            SPATIAL INDEX point (point)
        ) $charset_collate;";
        /* SPATIAL INDEX point (point) - REQUIRES point point NOT NULL */
        dbDelta($sql);
        add_option('gpress_db_version', GPRESS_DB_VERSION);
    }elseif($forced_install){
        /* USE THIS TO TEST SCHEMA FOR NEXT VERSION */
        $sql = "CREATE TABLE ".GPRESS_DB_TABLE." (
            gp_id bigint(32) unsigned NOT NULL auto_increment,
            meta_type enum('post','comment','user','blog','bp_group','bp_activity','bp_forum','custom') NOT NULL default 'custom',
            meta_key varchar(32) NOT NULL default 'custom',
            meta_id bigint(32) NOT NULL default '0',
            lat float(18,15) NOT NULL,
            lng float(18,15) NOT NULL,
            point point NOT NULL,
            icon varchar(255) default '',
            shadow varchar(255) default '',
            title varchar(255) NOT NULL,
            address varchar(255) NOT NULL default '".__('Address Unknown','gpress')."',
            url varchar(255) default '',
            map_height int(4) NOT NULL default '450',
            map_type enum('ROADMAP','SATELLITE','TERRAIN','HYBRID') NOT NULL default 'ROADMAP',
            map_zoom int(2) NOT NULL default '13',
            added datetime NOT NULL default '0000-00-00 00:00:00',
            updated timestamp NOT NULL,
            PRIMARY KEY (gp_id),
            KEY meta_type (meta_type),
            KEY meta_key (meta_key),
            KEY meta_id (meta_id),
            KEY lat (lat),
            KEY lng (lng),
            KEY address (address),
            KEY map_height (map_height),
            KEY map_type (map_type),
            KEY map_zoom (map_zoom),
            SPATIAL INDEX point (point)
        ) $charset_collate;";
        dbDelta($sql);
    }
    //echo $sql; exit;
}

?>