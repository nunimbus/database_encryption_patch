<?php

if ($query == 0) {
	$sql = "UPDATE `*PREFIX*authtoken` SET `last_check` = :dcValue1 WHERE `id` = :dcValue2";
	$from = array ('table' => '`*PREFIX*authtoken`','alias' => NULL);
	$set = array ('`last_check` = :dcValue1');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue2'));
	$params = array ('dcValue1' => 1666199286,'dcValue2' => 14);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 1);
}

if ($query == 1) {
	$sql = "UPDATE `*PREFIX*jobs` SET `reserved_at` = :dcValue1, `last_checked` = :dcValue2 WHERE (`id` = :jobid) AND (`reserved_at` = :reserved_at) AND (`last_checked` = :last_checked)";
	$from = array ('table' => '`*PREFIX*jobs`','alias' => NULL);
	$set = array ('`reserved_at` = :dcValue1','`last_checked` = :dcValue2');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :jobid','`reserved_at` = :reserved_at','`last_checked` = :last_checked'));
	$params = array ('dcValue1' => 1666199286,'dcValue2' => 1666199286,'jobid' => 30,'reserved_at' => 0,'last_checked' => 1666198628);
	$paramTypes = array ('dcValue1' => 2,'dcValue2' => 2);
}

if ($query == 2) {
	$sql = "UPDATE `*PREFIX*jobs` SET `last_run` = :dcValue1 WHERE `id` = :dcValue2";
	$from = array ('table' => '`*PREFIX*jobs`','alias' => NULL);
	$set = array ('`last_run` = :dcValue1');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue2'));
	$params = array ('dcValue1' => 1666199286,'dcValue2' => 30);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 1);
}

if ($query == 3) {
	$sql = "UPDATE `*PREFIX*jobs` SET `execution_duration` = :dcValue1 WHERE `id` = :dcValue2";
	$from = array ('table' => '`*PREFIX*jobs`','alias' => NULL);
	$set = array ('`execution_duration` = :dcValue1');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue2'));
	$params = array ('dcValue1' => 0,'dcValue2' => 30);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 1);
}

if ($query == 4) {
	$sql = "UPDATE `*PREFIX*jobs` SET `reserved_at` = '0' WHERE `id` = :dcValue1";
	$from = array ('table' => '`*PREFIX*jobs`','alias' => NULL);
	$set = array ('`reserved_at` = \'0\'');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue1'));
	$params = array ('dcValue1' => 30);
	$paramTypes = array ('dcValue1' => 1);
}

if ($query == 5) {
	$sql = "UPDATE `*PREFIX*appconfig` SET `configvalue` = :dcValue1 WHERE (`appid` = :dcValue2) AND (`configkey` = :dcValue3) AND ((`configvalue` IS NULL) OR (`configvalue` <> :dcValue4))";
	$from = array ('table' => '`*PREFIX*appconfig`','alias' => NULL);
	$set = array ('`configvalue` = :dcValue1');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`appid` = :dcValue2','`configkey` = :dcValue3',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`configvalue` IS NULL','`configvalue` <> :dcValue4')))));
	$params = array ('dcValue1' => 30,'dcValue2' => 'backgroundjob','dcValue3' => 'lastjob','dcValue4' => 30);
	$paramTypes = array ('dcValue1' => 2,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2);
}

if ($query == 6) {
	$sql = "UPDATE `*PREFIX*authtoken` SET `last_activity` = :dcValue1 WHERE (`id` = :dcValue2) AND (`last_activity` < :dcValue3)";
	$from = array ('table' => '`*PREFIX*authtoken`','alias' => NULL);
	$set = array ('`last_activity` = :dcValue1');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`id` = :dcValue2','`last_activity` < :dcValue3'));
	$params = array ('dcValue1' => 1666199333,'dcValue2' => 14,'dcValue3' => 1666199318);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 1,'dcValue3' => 1);
}

if ($query == 7) {
	$sql = "UPDATE `*PREFIX*filecache` SET `size` = :dcValue3 WHERE (`fileid` = :dcValue1) AND ((`size` <> :dcValue2) OR (`size` IS NULL))";
	$from = array ('table' => '`*PREFIX*filecache`','alias' => NULL);
	$set = array ('`size` = :dcValue3');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`size` <> :dcValue2','`size` IS NULL'))))))));
	$params = array ('dcValue1' => 254,'dcValue2' => 0,'dcValue3' => 0);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 2,'dcValue3' => 2);
}

if ($query == 8) {
	$sql = "UPDATE `*PREFIX*filecache` SET `mtime` = :dcValue4, `storage_mtime` = :dcValue5 WHERE (`fileid` = :dcValue1) AND (((`mtime` <> :dcValue2) OR (`mtime` IS NULL)) OR ((`storage_mtime` <> :dcValue3) OR (`storage_mtime` IS NULL)))";
	$from = array ('table' => '`*PREFIX*filecache`','alias' => NULL);
	$set = array ('`mtime` = :dcValue4','`storage_mtime` = :dcValue5');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`mtime` <> :dcValue2','`mtime` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`storage_mtime` <> :dcValue3','`storage_mtime` IS NULL'))))))));
	$params = array ('dcValue1' => 223,'dcValue2' => 1666199335,'dcValue3' => 1666199335,'dcValue4' => 1666199335,'dcValue5' => 1666199335);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2,'dcValue5' => 2);
}

if ($query == 9) {
	$sql = "UPDATE `*PREFIX*file_locks` SET `lock` = `lock` - '1' WHERE (`key` IN (:dcValue1)) AND (`lock` > 0)";
	$from = array ('table' => '`*PREFIX*file_locks`','alias' => NULL);
	$set = array ('`lock` = `lock` - \'1\'');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`key` IN (:dcValue1)','`lock` > 0'));
	$params = array ('dcValue1' =>   array ('files/53ddd538a7e9bfed4fb1b19106cd8e2d'));
	$paramTypes = array ('dcValue1' => 102);
}

if ($query == 10) {
	$sql = "UPDATE `*PREFIX*cards` SET `carddata` = :dcValue1, `lastmodified` = :dcValue2, `size` = :dcValue3, `etag` = :dcValue4, `uid` = :dcValue5 WHERE (`uri` = :dcValue6) AND (`addressbookid` = :dcValue7)";
	$from = array ('table' => '`*PREFIX*cards`','alias' => NULL);
	$set = array ('`carddata` = :dcValue1','`lastmodified` = :dcValue2','`size` = :dcValue3','`etag` = :dcValue4','`uid` = :dcValue5');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`uri` = :dcValue6','`addressbookid` = :dcValue7'));
	$params = array ('dcValue1' => 'BEGIN:VCARDVERSION:4.0PRODID:-//Nextcloud Contacts v4.1.1UID:0e027bf2-8504-482b-ab31-27dcd4d35034FN:Andrew SummersADR;TYPE=HOME:;;26123 East 14th Street;Catoosa;OK;74015;United StatesEMAIL;TYPE=HOME:TEL;TYPE=HOME,VOICE:ORG:Platypus Innovations\\,LLCREV;VALUE=DATE-AND-OR-TIME:20221019T170854ZEND:VCARD','dcValue2' => 1666199335,'dcValue3' => 321,'dcValue4' => 'cb4246c46d670c81b734a6de5ec6e671','dcValue5' => '0e027bf2-8504-482b-ab31-27dcd4d35034','dcValue6' => '514067A8-3F4A-4D28-98B7-9571EE803E78.vcf','dcValue7' => 1);
	$paramTypes = array ('dcValue1' => 3,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2,'dcValue5' => 2,'dcValue6' => 2,'dcValue7' => 2);
}

if ($query == 11) {
	$sql = "UPDATE `*PREFIX*filecache` SET `mtime` = :dcValue7, `etag` = :dcValue8, `storage_mtime` = :dcValue9, `checksum` = :dcValue10, `parent` = :dcValue11 WHERE (`fileid` = :dcValue1) AND (((`mtime` <> :dcValue2) OR (`mtime` IS NULL)) OR ((`etag` <> :dcValue3) OR (`etag` IS NULL)) OR ((`storage_mtime` <> :dcValue4) OR (`storage_mtime` IS NULL)) OR ((`checksum` <> :dcValue5) OR (`checksum` IS NULL)) OR ((`parent` <> :dcValue6) OR (`parent` IS NULL)))";
	$from = array ('table' => '`*PREFIX*filecache`','alias' => NULL);
	$set = array ('`mtime` = :dcValue7','`etag` = :dcValue8','`storage_mtime` = :dcValue9','`checksum` = :dcValue10','`parent` = :dcValue11');
	$where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array ('`fileid` = :dcValue1',new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array (new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`mtime` <> :dcValue2','`mtime` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`etag` <> :dcValue3','`etag` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`storage_mtime` <> :dcValue4','`storage_mtime` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`checksum` <> :dcValue5','`checksum` IS NULL'))),new \OC\DB\QueryBuilder\CompositeExpression(new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array ('`parent` <> :dcValue6','`parent` IS NULL'))))))));
	$params = array ('dcValue1' => 186,'dcValue2' => 1666199488,'dcValue3' => 'e0aef40a9f1496c2d8fe2ea2fbb88113','dcValue4' => 1666199488,'dcValue5' => '','dcValue6' => 56,'dcValue7' => 1666199488,'dcValue8' => 'e0aef40a9f1496c2d8fe2ea2fbb88113','dcValue9' => 1666199488,'dcValue10' => '','dcValue11' => 56);
	$paramTypes = array ('dcValue1' => 1,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2,'dcValue5' => 2,'dcValue6' => 2,'dcValue7' => 2,'dcValue8' => 2,'dcValue9' => 2,'dcValue10' => 2,'dcValue11' => 2);
}

