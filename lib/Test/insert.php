<?php

if ($query == 0) {
	$sql = "INSERT INTO `*PREFIX*cards` (`carddata`, `uri`, `lastmodified`, `addressbookid`, `size`, `etag`, `uid`) VALUES(:dcValue1, :dcValue2, :dcValue3, :dcValue4, :dcValue5, :dcValue6, :dcValue7)";
	$from = array ('table' => '`*PREFIX*cards`');
	$values = array ('`carddata`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue1'),'`uri`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue2'),'`lastmodified`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue3'),'`addressbookid`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue4'),'`size`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue5'),'`etag`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue6'),'`uid`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue7'));
	$params = array ('dcValue1' => 'BEGIN:VCARDVERSION:4.0PRODID:-//Nextcloud Contacts v4.1.1UID:0e027bf2-8504-482b-ab31-27dcd4d35034FN:Andrew SummersADR;TYPE=HOME:;;26123 East 14th Street;Catoosa;OK;74015;United StatesEMAIL;TYPE=HOME:TEL;TYPE=HOME,VOICE:ORG:Platypus Innovations\\,LLCREV;VALUE=DATE-AND-OR-TIME:20221019T170853ZEND:VCARD','dcValue2' => '514067A8-3F4A-4D28-98B7-9571EE803E78.vcf','dcValue3' => 1666199333,'dcValue4' => 1,'dcValue5' => 321,'dcValue6' => 'b0d54887f3464a0ab564c69ff8b9533e','dcValue7' => '0e027bf2-8504-482b-ab31-27dcd4d35034');
	$paramTypes = array ('dcValue1' => 3,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2,'dcValue5' => 2,'dcValue6' => 2,'dcValue7' => 2);

}

if ($query == 1) {
	$sql = "INSERT INTO `*PREFIX*cards_properties` (`addressbookid`, `cardid`, `name`, `value`, `preferred`) VALUES(:dcValue1, :dcValue2, :name, :value, :preferred)";
	$from = array ('table' => '`*PREFIX*cards_properties`');
	$values = array ('`addressbookid`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue1'),'`cardid`' =>   new \OC\DB\QueryBuilder\Parameter(':dcValue2'),'`name`' =>   new \OC\DB\QueryBuilder\Parameter(':name'),'`value`' =>   new \OC\DB\QueryBuilder\Parameter(':value'),'`preferred`' =>   new \OC\DB\QueryBuilder\Parameter(':preferred'));
	$params = array ('dcValue1' => 1,'dcValue2' => 9,'UID','value' => '0e027bf2-8504-482b-ab31-27dcd4d35034','preferred' => 0);
	$paramTypes = array ('dcValue1' => 2,'dcValue2' => 2);

}

if ($query == 2) {
	$sql = "INSERT INTO `*PREFIX*filecache` (`mimepart`, `mimetype`, `mtime`, `size`, `etag`, `storage_mtime`, `permissions`, `name`, `parent`, `checksum`, `path_hash`, `path`, `storage`) VALUES(:dcValue1, :dcValue2, :dcValue3, :dcValue4, :dcValue5, :dcValue6, :dcValue7, :dcValue8, :dcValue9, :dcValue10, :dcValue11, :dcValue12, :dcValue13)";
	$from = array ('table' => '`*PREFIX*filecache`');
	$values = array ('`mimepart`' => ':dcValue1','`mimetype`' => ':dcValue2','`mtime`' => ':dcValue3','`size`' => ':dcValue4','`etag`' => ':dcValue5','`storage_mtime`' => ':dcValue6','`permissions`' => ':dcValue7','`name`' => ':dcValue8','`parent`' => ':dcValue9','`checksum`' => ':dcValue10','`path_hash`' => ':dcValue11','`path`' => ':dcValue12','`storage`' => ':dcValue13');
	$params = array ('dcValue1' => 1,'dcValue2' => 2,'dcValue3' => 1666199335,'dcValue4' => -1,'dcValue5' => '63502f271d79c','dcValue6' => 1666199335,'dcValue7' => 31,'dcValue8' => '0a90c7b8194dffacf0b69f1425fbd50f','dcValue9' => 223,'dcValue10' => '','dcValue11' => '4b9bfd3e759a0990b7d6548a2fff5299','dcValue12' => 'appdata_ocu7w419aly6/dav-photocache/0a90c7b8194dffacf0b69f1425fbd50f','dcValue13' => 2);
	$paramTypes = array ('dcValue1' => 2,'dcValue2' => 2,'dcValue3' => 2,'dcValue4' => 2,'dcValue5' => 2,'dcValue6' => 2,'dcValue7' => 2,'dcValue8' => 2,'dcValue9' => 2,'dcValue10' => 2,'dcValue11' => 2,'dcValue12' => 2,'dcValue13' => 2);

}

