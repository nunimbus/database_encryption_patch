<?php

if ($query == 0) {
	$sql = "SELECT `uid`, `displayname`, `password` FROM `*PREFIX*users` WHERE `uid_lower` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*users`','alias' => NULL));
	$distinct = false;
	$select = array ('`uid`','`displayname`','`password`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid_lower` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 1) {
	$sql = "SELECT * FROM `*PREFIX*authtoken` WHERE (`token` = :dcValue1) AND (`version` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*authtoken`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`token` = :dcValue1','`version` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 2) {
	$sql = "SELECT `class`, `entity`, CAST(`events` AS TEXT) AS `events` FROM `*PREFIX*flow_operations` WHERE `events` <> :dcValue1 GROUP BY `class`, `entity`, CAST(`events` AS TEXT)";
	$from = array (array ('table' => '`*PREFIX*flow_operations`','alias' => NULL));
	$distinct = false;
	$select = array ('`class`','`entity`','CAST(`events` AS TEXT) AS `events`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`events` <> :dcValue1'));
	$groupBy = array ('`class`','`entity`','CAST(`events` AS TEXT)');
	$having = NULL;
	$orderBy = array ();
}

if ($query == 3) {
	$sql = "SELECT `gu`.`gid`, `g`.`displayname` FROM `*PREFIX*group_user` `gu` LEFT JOIN `*PREFIX*groups` `g` ON `gu`.`gid` = `g`.`gid` WHERE `uid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*group_user`','alias' => '`gu`'));
	$distinct = false;
	$select = array ('`gu`.`gid`','`g`.`displayname`');
	$join = array ('`gu`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*groups`','joinAlias' => '`g`','joinCondition' => '`gu`.`gid` = `g`.`gid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 4) {
	$sql = "SELECT `provider_id`, `enabled` FROM `*PREFIX*twofactor_providers` WHERE `uid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*twofactor_providers`','alias' => NULL));
	$distinct = false;
	$select = array ('`provider_id`','`enabled`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 5) {
	$sql = "SELECT `id`, `numeric_id`, `available`, `last_checked` FROM `*PREFIX*storages` WHERE `id` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*storages`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`numeric_id`','`available`','`last_checked`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 6) {
	$sql = "SELECT `fileid` FROM `*PREFIX*filecache` WHERE (`storage` = :dcValue1) AND (`path_hash` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => NULL));
	$distinct = false;
	$select = array ('`fileid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`storage` = :dcValue1','`path_hash` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 7) {
	$sql = "SELECT `storage_id`, `root_id`, `user_id`, `mount_point`, `mount_id`, `f`.`path`, `mount_provider_class` FROM `*PREFIX*mounts` `m` INNER JOIN `*PREFIX*filecache` `f` ON `m`.`root_id` = `f`.`fileid` WHERE `user_id` = ?";
	$from = array (array ('table' => '`*PREFIX*mounts`','alias' => '`m`'));
	$distinct = false;
	$select = array ('`storage_id`','`root_id`','`user_id`','`mount_point`','`mount_id`','`f`.`path`','`mount_provider_class`');
	$join = array ('`m`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*filecache`','joinAlias' => '`f`','joinCondition' => '`m`.`root_id` = `f`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`user_id` = ?'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 8) {
	$sql = "SELECT `filecache`.`fileid`, `storage`, `path`, `path_hash`, `filecache`.`parent`, `name`, `mimetype`, `mimepart`, `size`, `mtime`, `storage_mtime`, `encrypted`, `etag`, `permissions`, `checksum`, `metadata_etag`, `creation_time`, `upload_time`, `unencrypted_size` FROM `*PREFIX*filecache` `filecache` LEFT JOIN `*PREFIX*filecache_extended` `fe` ON `filecache`.`fileid` = `fe`.`fileid` WHERE (`storage` = :dcValue1) AND (`path_hash` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`filecache`'));
	$distinct = false;
	$select = array ('`filecache`.`fileid`','`storage`','`path`','`path_hash`','`filecache`.`parent`','`name`','`mimetype`','`mimepart`','`size`','`mtime`','`storage_mtime`','`encrypted`','`etag`','`permissions`','`checksum`','`metadata_etag`','`creation_time`','`upload_time`','`unencrypted_size`');
	$join = array ('`filecache`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache_extended`','joinAlias' => '`fe`','joinCondition' => '`filecache`.`fileid` = `fe`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`storage` = :dcValue1','`path_hash` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 9) {
	$sql = "SELECT `id`, `mimetype` FROM `*PREFIX*mimetypes`";
	$from = array (array ('table' => '`*PREFIX*mimetypes`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`mimetype`');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 10) {
	$sql = "SELECT * FROM `*PREFIX*jobs` WHERE (`reserved_at` <= :dcValue1) AND (`last_checked` <= :dcValue2) ORDER BY `last_checked` ASC LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*jobs`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`reserved_at` <= :dcValue1','`last_checked` <= :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`last_checked` ASC');
}

if ($query == 11) {
	$sql = "SELECT `file`.`fileid`, `storage`, `path`, `path_hash`, `file`.`parent`, `name`, `mimetype`, `mimepart`, `size`, `mtime`, `storage_mtime`, `encrypted`, `etag`, `permissions`, `checksum`, `metadata_etag`, `creation_time`, `upload_time`, `unencrypted_size` FROM `*PREFIX*filecache` `file` LEFT JOIN `*PREFIX*filecache_extended` `fe` ON `file`.`fileid` = `fe`.`fileid` WHERE ((`mimetype` <> :dcValue1) OR (`size` = :dcValue2)) AND ((`storage` = :dcValue3) AND ((`path` = :dcValue4) OR (`path` LIKE :dcValue5))) ORDER BY `mtime` + :dcValue6 desc LIMIT 100";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`file`'));
	$distinct = false;
	$select = array ('`file`.`fileid`','`storage`','`path`','`path_hash`','`file`.`parent`','`name`','`mimetype`','`mimepart`','`size`','`mtime`','`storage_mtime`','`encrypted`','`etag`','`permissions`','`checksum`','`metadata_etag`','`creation_time`','`upload_time`','`unencrypted_size`');
	$join = array ('`file`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache_extended`','joinAlias' => '`fe`','joinCondition' => '`file`.`fileid` = `fe`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`mimetype` <> :dcValue1','`size` = :dcValue2'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`storage` = :dcValue3',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`path` = :dcValue4','`path` LIKE :dcValue5'))))))))))))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`mtime` + :dcValue6 desc');
}

if ($query == 12) {
	$sql = "SELECT `filecache`.`fileid`, `storage`, `path`, `path_hash`, `filecache`.`parent`, `name`, `mimetype`, `mimepart`, `size`, `mtime`, `storage_mtime`, `encrypted`, `etag`, `permissions`, `checksum`, `metadata_etag`, `creation_time`, `upload_time`, `unencrypted_size` FROM `*PREFIX*filecache` `filecache` LEFT JOIN `*PREFIX*filecache_extended` `fe` ON `filecache`.`fileid` = `fe`.`fileid` WHERE `filecache`.`parent` = :dcValue1 ORDER BY `name` ASC";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`filecache`'));
	$distinct = false;
	$select = array ('`filecache`.`fileid`','`storage`','`path`','`path_hash`','`filecache`.`parent`','`name`','`mimetype`','`mimepart`','`size`','`mtime`','`storage_mtime`','`encrypted`','`etag`','`permissions`','`checksum`','`metadata_etag`','`creation_time`','`upload_time`','`unencrypted_size`');
	$join = array ('`filecache`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache_extended`','joinAlias' => '`fe`','joinCondition' => '`filecache`.`fileid` = `fe`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`filecache`.`parent` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`name` ASC');
}

if ($query == 13) {
	$sql = "SELECT * FROM `*PREFIX*dav_cal_proxy` WHERE `proxy_id` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*dav_cal_proxy`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`proxy_id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 14) {
	$sql = "SELECT `id`, `uri`, `displayname`, `principaluri`, `description`, `synctoken` FROM `*PREFIX*addressbooks` WHERE `principaluri` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*addressbooks`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`uri`','`displayname`','`principaluri`','`description`','`synctoken`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`principaluri` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 15) {
	$sql = "SELECT `a`.`id`, `a`.`uri`, `a`.`displayname`, `a`.`principaluri`, `a`.`description`, `a`.`synctoken`, `s`.`access` FROM `*PREFIX*dav_shares` `s` INNER JOIN `*PREFIX*addressbooks` `a` ON `s`.`resourceid` = `a`.`id` WHERE (`s`.`principaluri` IN (:principaluri)) AND (`s`.`type` = :type)";
	$from = array (array ('table' => '`*PREFIX*dav_shares`','alias' => '`s`'));
	$distinct = false;
	$select = array ('`a`.`id`','`a`.`uri`','`a`.`displayname`','`a`.`principaluri`','`a`.`description`','`a`.`synctoken`','`s`.`access`');
	$join = array ('`s`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*addressbooks`','joinAlias' => '`a`','joinCondition' => '`s`.`resourceid` = `a`.`id`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`s`.`principaluri` IN (:principaluri)','`s`.`type` = :type'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 16) {
	$sql = "SELECT `principaluri`, `access` FROM `*PREFIX*dav_shares` WHERE (`resourceid` = :dcValue1) AND (`type` = :dcValue2) GROUP BY `principaluri`, `access`";
	$from = array (array ('table' => '`*PREFIX*dav_shares`','alias' => NULL));
	$distinct = false;
	$select = array ('`principaluri`','`access`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`resourceid` = :dcValue1','`type` = :dcValue2'));
	$groupBy = array ('`principaluri`','`access`');
	$having = NULL;
	$orderBy = array ();
}

if ($query == 17) {
	$sql = "SELECT `id`, `uri`, `lastmodified`, `etag`, `size`, `carddata`, `uid` FROM `*PREFIX*cards` WHERE `addressbookid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*cards`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`uri`','`lastmodified`','`etag`','`size`','`carddata`','`uid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`carddata` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 18) {
	$sql = "SELECT `id`, `uri`, `lastmodified`, `etag`, `size`, `carddata`, `uid` FROM `*PREFIX*cards` WHERE (`addressbookid` = :dcValue1) AND (`uri` = :dcValue2) LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*cards`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`uri`','`lastmodified`','`etag`','`size`','`carddata`','`uid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`addressbookid` = :dcValue1','`uri` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 19) {
	$sql = "SELECT `path` FROM `*PREFIX*filecache` WHERE (`storage` = :dcValue1) AND (`fileid` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => NULL));
	$distinct = false;
	$select = array ('`path`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`storage` = :dcValue1','`fileid` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 20) {
	$sql = "SELECT `uid` FROM `*PREFIX*cards` WHERE (`addressbookid` = :dcValue1) AND (`uid` = :dcValue2) LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*cards`','alias' => NULL));
	$distinct = false;
	$select = array ('`uid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`addressbookid` = :dcValue1','`uid` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 21) {
	$sql = "SELECT `id` FROM `*PREFIX*cards` WHERE (`uri` = :dcValue1) AND (`addressbookid` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*cards`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uri` = :dcValue1','`addressbookid` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 22) {
	$sql = "SELECT `id`, `uri`, `displayname`, `principaluri`, `description`, `synctoken` FROM `*PREFIX*addressbooks` WHERE `id` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*addressbooks`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`uri`','`displayname`','`principaluri`','`description`','`synctoken`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 23) {
	$sql = "SELECT `displayname`, `description`, `timezone`, `calendarorder`, `calendarcolor`, `deleted_at`, `id`, `uri`, `synctoken`, `components`, `principaluri`, `transparent` FROM `*PREFIX*calendars` WHERE (`uri` = :dcValue1) AND (`principaluri` = :dcValue2) LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*calendars`','alias' => NULL));
	$distinct = false;
	$select = array ('`displayname`','`description`','`timezone`','`calendarorder`','`calendarcolor`','`deleted_at`','`id`','`uri`','`synctoken`','`components`','`principaluri`','`transparent`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uri` = :dcValue1','`principaluri` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 24) {
	$sql = "SELECT `id`, `uri`, `lastmodified`, `etag`, `calendarid`, `size`, `calendardata`, `componenttype`, `classification`, `deleted_at` FROM `*PREFIX*calendarobjects` WHERE (`calendarid` = :dcValue1) AND (`uri` = :dcValue2) AND (`calendartype` = :dcValue3)";
	$from = array (array ('table' => '`*PREFIX*calendarobjects`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`uri`','`lastmodified`','`etag`','`calendarid`','`size`','`calendardata`','`componenttype`','`classification`','`deleted_at`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`uri` = :dcValue2','`calendartype` = :dcValue3'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 25) {
	$sql = "SELECT * FROM `*PREFIX*calendar_appt_configs` WHERE `user_id` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*calendar_appt_configs`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`user_id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 26) {
	$sql = "SELECT * FROM `*PREFIX*properties` WHERE `propertypath` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*properties`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`propertypath` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 27) {
	$sql = "SELECT `displayname`, `description`, `timezone`, `calendarorder`, `calendarcolor`, `deleted_at`, `id`, `uri`, `synctoken`, `components`, `principaluri`, `transparent` FROM `*PREFIX*calendars` WHERE `principaluri` = :dcValue1 ORDER BY `calendarorder` ASC";
	$from = array (array ('table' => '`*PREFIX*calendars`','alias' => NULL));
	$distinct = false;
	$select = array ('`displayname`','`description`','`timezone`','`calendarorder`','`calendarcolor`','`deleted_at`','`id`','`uri`','`synctoken`','`components`','`principaluri`','`transparent`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`principaluri` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`calendarorder` ASC');
}

if ($query == 28) {
	$sql = "SELECT `displayname`, `description`, `timezone`, `calendarorder`, `calendarcolor`, `deleted_at`, `a`.`id`, `a`.`uri`, `a`.`synctoken`, `a`.`components`, `a`.`principaluri`, `a`.`transparent`, `s`.`access` FROM `*PREFIX*dav_shares` `s` INNER JOIN `*PREFIX*calendars` `a` ON `s`.`resourceid` = `a`.`id` WHERE (`s`.`principaluri` IN (:principaluri)) AND (`s`.`type` = :type)";
	$from = array (array ('table' => '`*PREFIX*dav_shares`','alias' => '`s`'));
	$distinct = false;
	$select = array ('`displayname`','`description`','`timezone`','`calendarorder`','`calendarcolor`','`deleted_at`','`a`.`id`','`a`.`uri`','`a`.`synctoken`','`a`.`components`','`a`.`principaluri`','`a`.`transparent`','`s`.`access`');
	$join = array ('`s`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*calendars`','joinAlias' => '`a`','joinCondition' => '`s`.`resourceid` = `a`.`id`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`s`.`principaluri` IN (:principaluri)','`s`.`type` = :type'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 29) {
	$sql = "SELECT `displayname`, `refreshrate`, `calendarorder`, `calendarcolor`, `striptodos`, `stripalarms`, `stripattachments`, `id`, `uri`, `source`, `principaluri`, `lastmodified`, `synctoken` FROM `*PREFIX*calendarsubscriptions` WHERE `principaluri` = :dcValue1 ORDER BY `calendarorder` asc";
	$from = array (array ('table' => '`*PREFIX*calendarsubscriptions`','alias' => NULL));
	$distinct = false;
	$select = array ('`displayname`','`refreshrate`','`calendarorder`','`calendarcolor`','`striptodos`','`stripalarms`','`stripattachments`','`id`','`uri`','`source`','`principaluri`','`lastmodified`','`synctoken`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`principaluri` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`calendarorder` asc');
}

if ($query == 30) {
	$sql = "SELECT `publicuri` FROM `*PREFIX*dav_shares` WHERE (`resourceid` = :dcValue1) AND (`access` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*dav_shares`','alias' => NULL));
	$distinct = false;
	$select = array ('`publicuri`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`resourceid` = :dcValue1','`access` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 31) {
	$sql = "SELECT `uri`, `calendardata` FROM `*PREFIX*calendarobjects` WHERE (`calendarid` = :dcValue1) AND (`calendartype` = :dcValue2) AND (`deleted_at` IS NULL) AND (`componenttype` = :dcValue3) AND (`lastoccurence` > :dcValue4) AND (`firstoccurence` < :dcValue5)";
	$from = array (array ('table' => '`*PREFIX*calendarobjects`','alias' => NULL));
	$distinct = false;
	$select = array ('`uri`','`calendardata`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`calendartype` = :dcValue2','`deleted_at` IS NULL','`componenttype` = :dcValue3','`lastoccurence` > :dcValue4','`firstoccurence` < :dcValue5'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 32) {
	$sql = "SELECT `uri`, `calendardata` FROM `*PREFIX*calendarobjects` WHERE (`calendarid` = :dcValue1) AND (`calendartype` = :dcValue2) AND (`deleted_at` IS NULL) AND (`componenttype` = :dcValue3)";
	$from = array (array ('table' => '`*PREFIX*calendarobjects`','alias' => NULL));
	$distinct = false;
	$select = array ('`uri`','`calendardata`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`calendartype` = :dcValue2','`deleted_at` IS NULL','`componenttype` = :dcValue3'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 33) {
	$sql = "SELECT DISTINCT `cp`.`cardid` FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => '`cp`'));
	$distinct = false;
	$select = array ('DISTINCT `cp`.`cardid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`addressbookid` = :dcValue1'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`name` = :dcValue2','`cp`.`name` = :dcValue3')))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 34) {
	$sql = "SELECT `c`.`addressbookid`, `c`.`carddata`, `c`.`uri` FROM `*PREFIX*cards` `c` WHERE `c`.`id` IN (:matches)";
	$from = array (array ('table' => '`*PREFIX*cards`','alias' => '`c`'));
	$distinct = false;
	$select = array ('`c`.`addressbookid`','`c`.`carddata`','`c`.`uri`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`c`.`id` IN (:matches)'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 35) {
	$sql = "SELECT `displayname`, `description`, `timezone`, `calendarorder`, `calendarcolor`, `deleted_at`, `id`, `uri`, `synctoken`, `components`, `principaluri`, `transparent` FROM `*PREFIX*calendars` WHERE `id` = :dcValue1 LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*calendars`','alias' => NULL));
	$distinct = false;
	$select = array ('`displayname`','`description`','`timezone`','`calendarorder`','`calendarcolor`','`deleted_at`','`id`','`uri`','`synctoken`','`components`','`principaluri`','`transparent`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 36) {
	$sql = "SELECT `data` FROM `*PREFIX*accounts` WHERE `uid` = :uid";
	$from = array (array ('table' => '`*PREFIX*accounts`','alias' => NULL));
	$distinct = false;
	$select = array ('`data`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` = :uid'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 37) {
	$sql = "SELECT COUNT(*) FROM `*PREFIX*preferences` WHERE (`appid` = :dcValue1) AND (`configkey` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*preferences`','alias' => NULL));
	$distinct = false;
	$select = array ('COUNT(*)');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`appid` = :dcValue1','`configkey` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 38) {
	$sql = "SELECT * FROM `*PREFIX*calendar_appt_configs` WHERE (`user_id` = :dcValue1) AND (`visibility` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*calendar_appt_configs`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`user_id` = :dcValue1','`visibility` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 39) {
	$sql = "SELECT * FROM `*PREFIX*profile_config` WHERE `user_id` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*profile_config`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`user_id` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 40) {
	$sql = "SELECT `id` FROM `*PREFIX*jobs` WHERE (`class` = :dcValue1) AND (`argument_hash` = :dcValue2) LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*jobs`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`class` = :dcValue1','`argument_hash` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 41) {
	$sql = "SELECT * FROM `*PREFIX*authtoken` WHERE (`uid` = :dcValue1) AND (`version` = :dcValue2) LIMIT 1000";
	$from = array (array ('table' => '`*PREFIX*authtoken`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` = :dcValue1','`version` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 42) {
	$sql = "SELECT `a`.`name` FROM `*PREFIX*filecache` `a` LEFT JOIN `*PREFIX*filecache` `b` ON CAST(`a`.`name` AS INT) = `b`.`fileid` WHERE (`b`.`fileid` IS NULL) AND (`a`.`parent` = :dcValue1) AND (`a`.`name` LIKE :dcValue2) LIMIT 10";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`a`'));
	$distinct = false;
	$select = array ('`a`.`name`');
	$join = array ('`a`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache`','joinAlias' => '`b`','joinCondition' => 'CAST(`a`.`name` AS INT) = `b`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`b`.`fileid` IS NULL','`a`.`parent` = :dcValue1','`a`.`name` LIKE :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 43) {
	$sql = "SELECT `path`, `mimetype` FROM `*PREFIX*filecache` WHERE `fileid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => NULL));
	$distinct = false;
	$select = array ('`path`','`mimetype`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 44) {
	$sql = "SELECT `a`.`name` FROM `*PREFIX*filecache` `a` LEFT JOIN `*PREFIX*filecache` `b` ON CAST(`a`.`name` AS INT) = `b`.`fileid` WHERE (`a`.`storage` = :dcValue1) AND (`b`.`fileid` IS NULL) AND (`a`.`path` LIKE :dcValue2) AND (`a`.`mimetype` = :dcValue3) LIMIT 10";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`a`'));
	$distinct = false;
	$select = array ('`a`.`name`');
	$join = array ('`a`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache`','joinAlias' => '`b`','joinCondition' => 'CAST(`a`.`name` AS INT) = `b`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`a`.`storage` = :dcValue1','`b`.`fileid` IS NULL','`a`.`path` LIKE :dcValue2','`a`.`mimetype` = :dcValue3')))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 45) {
	$sql = "SELECT `uri`, `calendardata`, `lastmodified`, `etag`, `size` FROM `*PREFIX*schedulingobjects` WHERE `principaluri` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*schedulingobjects`','alias' => NULL));
	$distinct = false;
	$select = array ('`uri`','`calendardata`','`lastmodified`','`etag`','`size`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`principaluri` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 46) {
	$sql = "SELECT `o`.*, `s`.`type` AS `scope_type`, `s`.`value` AS `scope_actor_id` FROM `*PREFIX*flow_operations` `o` LEFT JOIN `*PREFIX*flow_operations_scope` `s` ON `o`.`id` = `s`.`operation_id` WHERE (`s`.`type` = :scope) AND (`s`.`value` = :scopeId)";
	$from = array (array ('table' => '`*PREFIX*flow_operations`','alias' => '`o`'));
	$distinct = false;
	$select = array ('`o`.*','`s`.`type` AS `scope_type`','`s`.`value` AS `scope_actor_id`');
	$join = array ('`o`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*flow_operations_scope`','joinAlias' => '`s`','joinCondition' => '`o`.`id` = `s`.`operation_id`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`s`.`type` = :scope','`s`.`value` = :scopeId'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 47) {
	$sql = "SELECT `cr`.*, `co`.`calendardata`, `c`.`displayname`, `c`.`principaluri` FROM `*PREFIX*calendar_reminders` `cr` INNER JOIN `*PREFIX*calendarobjects` `co` ON `cr`.`object_id` = `co`.`id` INNER JOIN `*PREFIX*calendars` `c` ON `cr`.`calendar_id` = `c`.`id` WHERE `cr`.`notification_date` <= :dcValue1";
	$from = array (array ('table' => '`*PREFIX*calendar_reminders`','alias' => '`cr`'));
	$distinct = false;
	$select = array ('`cr`.*','`co`.`calendardata`','`c`.`displayname`','`c`.`principaluri`');
	$join = array ('`cr`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*calendarobjects`','joinAlias' => '`co`','joinCondition' => '`cr`.`object_id` = `co`.`id`'),array ('joinType' => 'inner','joinTable' => '`*PREFIX*calendars`','joinAlias' => '`c`','joinCondition' => '`cr`.`calendar_id` = `c`.`id`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cr`.`notification_date` <= :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 48) {
	$sql = "SELECT `last_checked` FROM `*PREFIX*jobs` ORDER BY `last_checked` ASC LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*jobs`','alias' => NULL));
	$distinct = false;
	$select = array ('`last_checked`');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`last_checked` ASC');
}

if ($query == 49) {
	$sql = "SELECT * FROM `*PREFIX*oauth2_clients`";
	$from = array (array ('table' => '`*PREFIX*oauth2_clients`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 50) {
	$sql = "SELECT `gid` FROM `*PREFIX*groups` ORDER BY `gid` ASC LIMIT 20";
	$from = array (array ('table' => '`*PREFIX*groups`','alias' => NULL));
	$distinct = false;
	$select = array ('`gid`');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`gid` ASC');
}

if ($query == 51) {
	$sql = "SELECT `id`, `deleted_at` FROM `*PREFIX*calendars` WHERE (`deleted_at` IS NOT NULL) AND (`deleted_at` < :dcValue1)";
	$from = array (array ('table' => '`*PREFIX*calendars`','alias' => NULL));
	$distinct = false;
	$select = array ('`id`','`deleted_at`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`deleted_at` IS NOT NULL','`deleted_at` < :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 52) {
	$sql = "SELECT `co`.`id`, `co`.`uri`, `co`.`lastmodified`, `co`.`etag`, `co`.`calendarid`, `co`.`calendartype`, `co`.`size`, `co`.`componenttype`, `co`.`classification`, `co`.`deleted_at` FROM `*PREFIX*calendarobjects` `co` INNER JOIN `*PREFIX*calendars` `c` ON `c`.`id` = `co`.`calendarid` WHERE (`co`.`deleted_at` IS NOT NULL) AND (`co`.`deleted_at` < :dcValue1)";
	$from = array (array ('table' => '`*PREFIX*calendarobjects`','alias' => '`co`'));
	$distinct = false;
	$select = array ('`co`.`id`','`co`.`uri`','`co`.`lastmodified`','`co`.`etag`','`co`.`calendarid`','`co`.`calendartype`','`co`.`size`','`co`.`componenttype`','`co`.`classification`','`co`.`deleted_at`');
	$join = array ('`co`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*calendars`','joinAlias' => '`c`','joinCondition' => '`c`.`id` = `co`.`calendarid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`co`.`deleted_at` IS NOT NULL','`co`.`deleted_at` < :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 53) {
	$sql = "SELECT `gid` FROM `*PREFIX*groups` ORDER BY `gid` ASC";
	$from = array (array ('table' => '`*PREFIX*groups`','alias' => NULL));
	$distinct = false;
	$select = array ('`gid`');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`gid` ASC');
}

if ($query == 54) {
	$sql = "SELECT * FROM `*PREFIX*authorized_groups`";
	$from = array (array ('table' => '`*PREFIX*authorized_groups`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 55) {
	$sql = "SELECT `user_id` FROM `*PREFIX*filecache` `f` INNER JOIN `*PREFIX*mounts` `m` ON `storage_id` = `storage` WHERE (`size` < :dcValue1) AND (`parent` > :dcValue2) LIMIT 1";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`f`'));
	$distinct = false;
	$select = array ('`user_id`');
	$join = array ('`f`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*mounts`','joinAlias' => '`m`','joinCondition' => '`storage_id` = `storage`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`size` < :dcValue1','`parent` > :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 56) {
	$sql = "SELECT `o`.*, `s`.`type` AS `scope_type`, `s`.`value` AS `scope_actor_id` FROM `*PREFIX*flow_operations` `o` LEFT JOIN `*PREFIX*flow_operations_scope` `s` ON `o`.`id` = `s`.`operation_id` WHERE `s`.`type` = :scope";
	$from = array (array ('table' => '`*PREFIX*flow_operations`','alias' => '`o`'));
	$distinct = false;
	$select = array ('`o`.*','`s`.`type` AS `scope_type`','`s`.`value` AS `scope_actor_id`');
	$join = array ('`o`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*flow_operations_scope`','joinAlias' => '`s`','joinCondition' => '`o`.`id` = `s`.`operation_id`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`s`.`type` = :scope'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 57) {
	$sql = "SELECT DISTINCT `cob`.`objectid` FROM `*PREFIX*calendarobjects_props` `cob` LEFT JOIN `*PREFIX*calendarobjects` `co` ON `co`.`id` = `cob`.`objectid` WHERE (`co`.`componenttype` IN (:dcValue13)) AND ((`cob`.`calendarid` = :dcValue1) AND (`cob`.`calendartype` = :dcValue2)) AND (((`cob`.`name` = :dcValue3) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue4) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue5) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue6) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue7) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue8) AND (`cob`.`parameter` IS NULL)) OR ((`cob`.`name` = :dcValue9) AND (`cob`.`parameter` = :dcValue10)) OR ((`cob`.`name` = :dcValue11) AND (`cob`.`parameter` = :dcValue12))) AND (`deleted_at` IS NULL) AND (`cob`.`value` ILIKE :dcValue14) LIMIT 5";
	$from = array (array ('table' => '`*PREFIX*calendarobjects_props`','alias' => '`cob`'));
	$distinct = false;
	$select = array ('DISTINCT `cob`.`objectid`');
	$join = array ('`cob`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*calendarobjects`','joinAlias' => '`co`','joinCondition' => '`co`.`id` = `cob`.`objectid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`co`.`componenttype` IN (:dcValue13)',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`calendarid` = :dcValue1','`cob`.`calendartype` = :dcValue2')))))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue3','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue4','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue5','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue6','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue7','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue8','`cob`.`parameter` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue9','`cob`.`parameter` = :dcValue10'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cob`.`name` = :dcValue11','`cob`.`parameter` = :dcValue12')))))),'`deleted_at` IS NULL','`cob`.`value` ILIKE :dcValue14'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 58) {
	$sql = "SELECT `calendardata`, `uri`, `calendarid`, `calendartype` FROM `*PREFIX*calendarobjects` WHERE `id` IN (:dcValue1)";
	$from = array (array ('table' => '`*PREFIX*calendarobjects`','alias' => NULL));
	$distinct = false;
	$select = array ('`calendardata`','`uri`','`calendarid`','`calendartype`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` IN (:dcValue1)'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 59) {
	$sql = "SELECT `file`.`fileid`, `storage`, `path`, `path_hash`, `file`.`parent`, `name`, `mimetype`, `mimepart`, `size`, `mtime`, `storage_mtime`, `encrypted`, `etag`, `permissions`, `checksum`, `metadata_etag`, `creation_time`, `upload_time`, `unencrypted_size` FROM `*PREFIX*filecache` `file` LEFT JOIN `*PREFIX*filecache_extended` `fe` ON `file`.`fileid` = `fe`.`fileid` WHERE (`name` ILIKE :dcValue1) AND ((`storage` = :dcValue2) AND ((`path` = :dcValue3) OR (`path` LIKE :dcValue4))) ORDER BY `mtime` + :dcValue5 desc LIMIT 5";
	$from = array (array ('table' => '`*PREFIX*filecache`','alias' => '`file`'));
	$distinct = false;
	$select = array ('`file`.`fileid`','`storage`','`path`','`path_hash`','`file`.`parent`','`name`','`mimetype`','`mimepart`','`size`','`mtime`','`storage_mtime`','`encrypted`','`etag`','`permissions`','`checksum`','`metadata_etag`','`creation_time`','`upload_time`','`unencrypted_size`');
	$join = array ('`file`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*filecache_extended`','joinAlias' => '`fe`','joinCondition' => '`file`.`fileid` = `fe`.`fileid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`name` ILIKE :dcValue1',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`storage` = :dcValue2',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`path` = :dcValue3','`path` LIKE :dcValue4'))))))))))))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`mtime` + :dcValue5 desc');
}

if ($query == 60) {
	$sql = "SELECT DISTINCT `cp`.`cardid` FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3) OR (`cp`.`name` = :dcValue4) OR (`cp`.`name` = :dcValue5) OR (`cp`.`name` = :dcValue6) OR (`cp`.`name` = :dcValue7) OR (`cp`.`name` = :dcValue8) OR (`cp`.`name` = :dcValue9) OR (`cp`.`name` = :dcValue10)) AND (`cp`.`value` ILIKE :dcValue11) LIMIT 5";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => '`cp`'));
	$distinct = false;
	$select = array ('DISTINCT `cp`.`cardid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`addressbookid` = :dcValue1'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`name` = :dcValue2','`cp`.`name` = :dcValue3','`cp`.`name` = :dcValue4','`cp`.`name` = :dcValue5','`cp`.`name` = :dcValue6','`cp`.`name` = :dcValue7','`cp`.`name` = :dcValue8','`cp`.`name` = :dcValue9','`cp`.`name` = :dcValue10'))),'`cp`.`value` ILIKE :dcValue11'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 61) {
	$sql = "SELECT `displayname` FROM `*PREFIX*groups` WHERE `gid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*groups`','alias' => NULL));
	$distinct = false;
	$select = array ('`displayname`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`gid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 62) {
	$sql = "SELECT `uid` FROM `*PREFIX*group_user` WHERE (`gid` = :dcValue1) AND (`uid` = :dcValue2)";
	$from = array (array ('table' => '`*PREFIX*group_user`','alias' => NULL));
	$distinct = false;
	$select = array ('`uid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`gid` = :dcValue1','`uid` = :dcValue2'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 63) {
	$sql = "SELECT COUNT(*) AS `num_users` FROM `*PREFIX*group_user` WHERE `gid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*group_user`','alias' => NULL));
	$distinct = false;
	$select = array ('COUNT(*) AS `num_users`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`gid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 64) {
	$sql = "SELECT COUNT(DISTINCT `uid`) FROM `*PREFIX*preferences` `p` INNER JOIN `*PREFIX*group_user` `g` ON `p`.`userid` = `g`.`uid` WHERE (`appid` = :dcValue1) AND (`configkey` = :dcValue2) AND (`configvalue` = :dcValue3) AND (`gid` = :dcValue4)";
	$from = array (array ('table' => '`*PREFIX*preferences`','alias' => '`p`'));
	$distinct = false;
	$select = array ('COUNT(DISTINCT `uid`)');
	$join = array ('`p`' =>   array (array ('joinType' => 'inner','joinTable' => '`*PREFIX*group_user`','joinAlias' => '`g`','joinCondition' => '`p`.`userid` = `g`.`uid`')));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`appid` = :dcValue1','`configkey` = :dcValue2','`configvalue` = :dcValue3','`gid` = :dcValue4'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 65) {
	$sql = "SELECT COUNT(*) FROM `*PREFIX*preferences` WHERE (`appid` = :dcValue1) AND (`configkey` = :dcValue2) AND (`configvalue` = :dcValue3)";
	$from = array (array ('table' => '`*PREFIX*preferences`','alias' => NULL));
	$distinct = false;
	$select = array ('COUNT(*)');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`appid` = :dcValue1','`configkey` = :dcValue2','`configvalue` = :dcValue3'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 66) {
	$sql = "SELECT COUNT(`uid`) FROM `*PREFIX*users`";
	$from = array (array ('table' => '`*PREFIX*users`','alias' => NULL));
	$distinct = false;
	$select = array ('COUNT(`uid`)');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 67) {
	$sql = "SELECT `uid`, `displayname` FROM `*PREFIX*users` `u` LEFT JOIN `*PREFIX*preferences` `p` ON (`userid` = `uid`) AND (`appid` = 'settings') AND (`configkey` = 'email') WHERE (`uid` ILIKE ?) OR (`displayname` ILIKE ?) OR (`configvalue` ILIKE ?) ORDER BY LOWER(`displayname`) ASC, `uid_lower` ASC LIMIT 25";
	$from = array (array ('table' => '`*PREFIX*users`','alias' => '`u`'));
	$distinct = false;
	$select = array ('`uid`','`displayname`');
	$join = array ('`u`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*preferences`','joinAlias' => '`p`','joinCondition' =>       new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`userid` = `uid`','`appid` = \'settings\'','`configkey` = \'email\''))))));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` ILIKE ?')),'`displayname` ILIKE ?','`configvalue` ILIKE ?'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('LOWER(`displayname`) ASC','`uid_lower` ASC');
}

if ($query == 68) {
	$sql = "SELECT `gid` FROM `*PREFIX*group_admin` WHERE `uid` = :dcValue1";
	$from = array (array ('table' => '`*PREFIX*group_admin`','alias' => NULL));
	$distinct = false;
	$select = array ('`gid`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` = :dcValue1'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 69) {
	$sql = "SELECT `uid`, `displayname` FROM `*PREFIX*users` `u` LEFT JOIN `*PREFIX*preferences` `p` ON (`userid` = `uid`) AND (`appid` = 'settings') AND (`configkey` = 'email') WHERE (`uid` ILIKE ?) OR (`displayname` ILIKE ?) OR (`configvalue` ILIKE ?) ORDER BY LOWER(`displayname`) ASC, `uid_lower` ASC LIMIT 25 OFFSET 25";
	$from = array (array ('table' => '`*PREFIX*users`','alias' => '`u`'));
	$distinct = false;
	$select = array ('`uid`','`displayname`');
	$join = array ('`u`' =>   array (array ('joinType' => 'left','joinTable' => '`*PREFIX*preferences`','joinAlias' => '`p`','joinCondition' =>       new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`userid` = `uid`','`appid` = \'settings\'','`configkey` = \'email\''))))));
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uid` ILIKE ?')),'`displayname` ILIKE ?','`configvalue` ILIKE ?'));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('LOWER(`displayname`) ASC','`uid_lower` ASC');
}

if ($query == 70) {
	$sql = "SELECT `gid` FROM `*PREFIX*groups` ORDER BY `gid` ASC LIMIT 5";
	$from = array (array ('table' => '`*PREFIX*groups`','alias' => NULL));
	$distinct = false;
	$select = array ('`gid`');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ('`gid` ASC');
}

if ($query == 71) {
	$sql = "SELECT COUNT(DISTINCT `cp`.`cardid`) FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => '`cp`'));
	$distinct = false;
	$select = array ('COUNT(DISTINCT `cp`.`cardid`)');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`addressbookid` = :dcValue1'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`name` = :dcValue2','`cp`.`name` = :dcValue3')))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 72) {
	$sql = "SELECT COUNT(*) FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => '`cp`'));
	$distinct = false;
	$select = array ('COUNT(*)');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`addressbookid` = :dcValue1'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`name` = :dcValue2','`cp`.`name` = :dcValue3')))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 73) {
	$sql = "SELECT COUNT(*) AS `num_cards` FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => '`cp`'));
	$distinct = false;
	$select = array ('COUNT(*) AS `num_cards`');
	$join = array ();
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`addressbookid` = :dcValue1'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`cp`.`name` = :dcValue2','`cp`.`name` = :dcValue3')))));
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}

if ($query == 74) {
	$sql = "SELECT * FROM `*PREFIX*cards_properties`";
	$from = array (array ('table' => '`*PREFIX*cards_properties`','alias' => NULL));
	$distinct = false;
	$select = array ('*');
	$join = array ();
	$where = NULL;
	$groupBy = array ();
	$having = NULL;
	$orderBy = array ();
}
