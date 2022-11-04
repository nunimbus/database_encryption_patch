<?php

if ($query == 0) {
	$sql = "DELETE FROM `*PREFIX*authtoken` WHERE `id` = :dcValue1";
	$from = array ('table' => '`*PREFIX*authtoken`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
}

if ($query == 1) {
	$sql = "DELETE FROM `*PREFIX*direct_edit` WHERE `timestamp` < :dcValue1";
	$from = array ('table' => '`*PREFIX*direct_edit`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`timestamp` < :dcValue1'));
}

if ($query == 2) {
	$sql = "DELETE FROM `*PREFIX*cards_properties` WHERE (`cardid` = :dcValue1) AND (`addressbookid` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*cards_properties`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`cardid` = :dcValue1','`addressbookid` = :dcValue2'));
}

if ($query == 3) {
	$sql = "DELETE FROM `*PREFIX*filecache` WHERE `fileid` = :dcValue1";
	$from = array ('table' => '`*PREFIX*filecache`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1'));
}

if ($query == 4) {
	$sql = "DELETE FROM `*PREFIX*filecache_extended` WHERE `fileid` = :dcValue1";
	$from = array ('table' => '`*PREFIX*filecache_extended`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1'));
}

if ($query == 5) {
	$sql = "DELETE FROM `*PREFIX*filecache_extended` WHERE `fileid` IN (:childIds)";
	$from = array ('table' => '`*PREFIX*filecache_extended`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` IN (:childIds)'));
}

if ($query == 6) {
	$sql = "DELETE FROM `*PREFIX*filecache` WHERE `parent` IN (:parentIds)";
	$from = array ('table' => '`*PREFIX*filecache`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`parent` IN (:parentIds)'));
}

if ($query == 7) {
	$sql = "DELETE FROM `*PREFIX*cards` WHERE (`addressbookid` = :dcValue1) AND (`uri` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*cards`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`addressbookid` = :dcValue1','`uri` = :dcValue2'));
}

if ($query == 8) {
	$sql = "DELETE FROM `*PREFIX*calendarobjects_props` WHERE (`calendarid` = :dcValue1) AND (`calendartype` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*calendarobjects_props`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`calendartype` = :dcValue2'));
}

if ($query == 9) {
	$sql = "DELETE FROM `*PREFIX*calendarobjects` WHERE (`calendarid` = :dcValue1) AND (`calendartype` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*calendarobjects`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`calendartype` = :dcValue2'));
}

if ($query == 10) {
	$sql = "DELETE FROM `*PREFIX*calendarchanges` WHERE (`calendarid` = :dcValue1) AND (`calendartype` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*calendarchanges`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendarid` = :dcValue1','`calendartype` = :dcValue2'));
}

if ($query == 11) {
	$sql = "DELETE FROM `*PREFIX*dav_shares` WHERE (`resourceid` = :dcValue1) AND (`type` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*dav_shares`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`resourceid` = :dcValue1','`type` = :dcValue2'));
}

if ($query == 12) {
	$sql = "DELETE FROM `*PREFIX*calendars` WHERE `id` = :dcValue1";
	$from = array ('table' => '`*PREFIX*calendars`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
}

if ($query == 13) {
	$sql = "DELETE FROM `*PREFIX*calendar_reminders` WHERE `calendar_id` = :dcValue1";
	$from = array ('table' => '`*PREFIX*calendar_reminders`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`calendar_id` = :dcValue1'));
}

if ($query == 14) {
	$sql = "DELETE FROM `*PREFIX*jobs` WHERE (`class` = :dcValue1) AND (`argument_hash` = :dcValue2)";
	$from = array ('table' => '`*PREFIX*jobs`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`class` = :dcValue1','`argument_hash` = :dcValue2'));
}

if ($query == 15) {
	$sql = "DELETE FROM `*PREFIX*appconfig` WHERE (`appid` = :app) AND (`configkey` = :configkey)";
	$from = array ('table' => '`*PREFIX*appconfig`','alias' => NULL);
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`appid` = :app','`configkey` = :configkey'));
}

