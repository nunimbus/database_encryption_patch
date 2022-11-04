<?php

//tail -n +2 nc-query.php | head -n -1 > nc-query.tmp && mv nc-query.tmp nc-query.php
//sed -i 's/  /\t/g' nc-query.php
//sed -zi 's/\n\t\+array /array/g' nc-query.php
//sed -i "s/^\t'\([a-zA-Z]\+\)' =>/\$\1 =/g" nc-query.php
//sed -i 's/[0-9] => //g' nc-query.php
//sed -zi 's/,\n\$/;\n\$/g' nc-query.php
//sed -zi 's/,\n$/;\n/g' nc-query.php
//sed -zi 's/\n\t\+array /array/g' nc-query.php
//sed -i 's/\([A-Z].*\)::__set_state.*/new \\\1(/g' nc-query.php
//sed -zi "s/\n\t\+ '[a-z]\+' => //g" nc-query.php
//sed -i 's/))/)/g' nc-query.php
//sed -i 's/.*compositeExpression.*//g' nc-query.php
//sed -i '/^\s*$/d' nc-query.php
//#sed -zi 's/\n\$/\n\n\$/g' nc-query.php
//sed -zi 's/\n\t\+/ /g' nc-query.php
//sed -i 's/^/\t\t/g' nc-query.php

/* Simple SELECT **
		$select = array( '`addressbookid`', '`carddata`', '`uri`', );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`'), );
		$join = array();
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/
/* SELECT using aliases **/
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array();
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/
/* Simple wildcard SELECT **
		$select = array( '*', );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`'), );
		$join = array();
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/

/* SELECT with JOIN using aliases **
		$sql = "SELECT `c`.`addressbookid`, `cp`.`id`, `c`.`carddata`, `cp`.`name`, `c`.`uri` FROM `*PREFIX*cards` `c` JOIN `*PREFIX*cards_properties` `cp` ON `cp`.`cardid` = `c`.`cardid`";
		$select = array( '`c`.`addressbookid`', '`cp`.`id`', 'CAST(`cp`.`value` AS TEXT)', '`c`.`carddata` AS `carddata`', '`cp`.`name`', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '`cp`', 'joinCondition' => '`cp`.`cardid` = `c`.`cardid`' ) ) );
		$set = array( );
		$where =  new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array( '`c`.`carddata` = :dcValue1', new \OC\DB\QueryBuilder\CompositeExpression( new \Doctrine\DBAL\Query\Expression\CompositeExpression('OR',array( '`cp`.`name` = :dcValue2', '`cp`.`name` = :dcValue3')))));
		$groupBy = array('`c`.`carddata`', '`entity`', 'CAST(`cp`.`value` AS TEXT)');
		$having = NULL;
		$orderBy = array('`c`.`carddata`', 'ASC');
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/
/* SELECT with JOIN using aliases and SELECT table wildcard **
		$sql = "SELECT `c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri` FROM `*PREFIX*cards` `c` JOIN `*PREFIX*cards_properties` `cp` ON `cp`.`cardid` = `c`.`cardid`";
		$select = array( '`c`.*', '`cp`.`value`', '`cp`.`id`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '`cp`', 'joinCondition' => '`cp`.`cardid` = `c`.`cardid`' ) ) );
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/
/* SELECT with JOIN using aliases and JOIN table wildcard **
		$sql = "SELECT `c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri` FROM `*PREFIX*cards` `c` JOIN `*PREFIX*cards_properties` `cp` ON `cp`.`cardid` = `c`.`cardid`";
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`cp`.*', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '`cp`', 'joinCondition' => '`cp`.`cardid` = `c`.`cardid`' ) ) );
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/

/* SELECT alias, but no JOIN alias **
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '', 'joinCondition' => '`c`.`cardid` =`*PREFIX*cards_properties`.`cardid`' ) ) );
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/
/* JOIN alias, but no SELECT alias **
		$select = array( '`addressbookid`', '`cp`.`preferred`', '`carddata`', '`cp`.`value`', '`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => ''), );
		$join = array( '`*PREFIX*cards`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => 'cp', 'joinCondition' => '`cp`.`cardid` = `*PREFIX*cards`.`cardid`' ) ) );
		$set = array( );
		$where =  NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*cards' => ['carddata', 'uri', 'cardid'],
			'*PREFIX*cards_properties' => ['value', 'name', 'cardid'],
		];
/***/










/***
		$sql = "SELECT `o`.*, `s`.`type` AS `scope_type`, `s`.`value` AS `scope_actor_id` FROM `*PREFIX*flow_operations` `o` LEFT JOIN `*PREFIX*flow_operations_scope` `s` ON `o`.`id` = `s`.`operation_id` WHERE (`s`.`type` = :scope) AND (`s`.`value` = :scopeId)";
		$select = array( '`o`.*', '`s`.`type` AS `scope_type`', '`s`.`value` AS `scope_actor_id`', );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*flow_operations`', 'alias' => '`o`', ), );
		$join = array( '`o`' => array( array( 'joinType' => 'left', 'joinTable' => '`*PREFIX*flow_operations_scope`', 'joinAlias' => '`s`', 'joinCondition' => '`o`.`id` = `s`.`operation_id`', ), ), );
		$set = array( );
		$where =  new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array( '`s`.`type` = :scope', '`s`.`value` = :scopeId', ), );
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );

		//$this->encryptMap['`*PREFIX*flow_operations`'] = ['`value`'];
		$this->encryptMap['`*PREFIX*flow_operations_scope`'] = ['`value`'];
/***/


/***
		$sql = "SELECT * FROM `*PREFIX*jobs` WHERE (`reserved_at` <= :dcValue1) AND (`last_checked` <= :dcValue2) ORDER BY `last_checked` ASC LIMIT 1";
		$select = array( '*', );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*jobs`', 'alias' => NULL, ), );
		$join = array( );
		$set = array( );
		$where =  new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array( '`reserved_at` <= :dcValue1', '`last_checked` <= :dcValue2', ), );
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( '`last_checked` ASC', );
		$values = array( );

		$this->encryptMap  = [
			'*PREFIX*jobs' => ['value'],
		];
/***/

/* UPDATE **
		$select = array( );
		$distinct = false;
		$from = array( 'table' => '`*PREFIX*cards`', 'alias' => NULL, );
		$join = array( );
		$set = array( '`carddata` = :dcValue1', '`lastmodified` = :dcValue2', '`size` = :dcValue3', '`etag` = :dcValue4', '`uid` = :dcValue5', );
		$where =  new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array( '`uri` = :dcValue6', '`addressbookid` = :dcValue7', ), );
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );
		$params = array(
			'dcValue1' => "BEGIN:VCARD
VERSION:4.0
PRODID:-//Nextcloud Contacts v4.1.1
UID:ad136ccd-3433-4353-afd1-f31dbe9d1850
FN:Andrew Summers
ADR;TYPE=HOME:;;26123 East 14th Street;Catoosa;OK;74015;United States
EMAIL;TYPE=HOME:
TEL;TYPE=HOME,VOICE:281433790
ORG:Platypus Innovations\, LLC
REV;VALUE=DATE-AND-OR-TIME:20221014T151213Z
END:VCARD",
			'dcValue2' => 1665760334,
			'dcValue3' => 330,
			'dcValue4' => "92dfbdf5bf29f39e0f68f4aa919ab536",
			'dcValue5' => "ad136ccd-3433-4353-afd1-f31dbe9d1850",
			'dcValue6' => "87136798-F7AD-43CA-9C1B-4102F83302E3.vcf",
			'dcValue7' => 1,
		);
		$paramTypes = array(
			'dcValue1' => 3,
			'dcValue2' => 2,
			'dcValue3' => 2,
			'dcValue4' => 2,
			'dcValue5' => 2,
			'dcValue6' => 2,
			'dcValue7' => 2,
		);
/***/

/* INSERT **
		$select = array( );
		$distinct = false;
		$from = array( 'table' => '`*PREFIX*cards`', );
		$join = array( );
		$set = array( );
		$where = NULL;
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( '`carddata`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue1', ), '`uri`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue2', ), '`lastmodified`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue3', ), '`addressbookid`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue4', ), '`size`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue5', ), '`etag`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue6', ), '`uid`' =>  new \OC\DB\QueryBuilder\Parameter(':dcValue7', ), );
		$params = [
			'dcValue1' => "BEGIN:VCARD
VERSION:4.0
PRODID:-//Nextcloud Contacts v4.1.1
UID:6fd3e46a-fb12-438f-acf6-98a82ca36257
FN:Andrew Summers
ADR;TYPE=HOME:;;26123 East 14th Street;Catoosa;OK;74015;United States
EMAIL;TYPE=HOME:
TEL;TYPE=HOME,VOICE:
ORG:Platypus Innovations\, LLC
REV;VALUE=DATE-AND-OR-TIME:20221014T152803Z
END:VCARD",
			'dcValue2' => "132076DD-D81A-4ECE-9E21-0869EB6D0989.vcf",
			'dcValue3' => 1665761293,
			'dcValue4' => 1,
			'dcValue5' => 321,
			'dcValue6' => "d8f56c4d536b8b4fa7b593ca05eb97d1",
			'dcValue7' => "6fd3e46a-fb12-438f-acf6-98a82ca36257",
		];
		$paramTypes = [
			'dcValue1' => 3,
			'dcValue2' => 2,
			'dcValue3' => 2,
			'dcValue4' => 2,
			'dcValue5' => 2,
			'dcValue6' => 2,
			'dcValue7' => 2,
		];

/***/

/* DELETE **
		$select = array( );
		$distinct = false;
		$from = array( 'table' => '`*PREFIX*cards`', 'alias' => NULL, );
		$join = array( );
		$set = array( );
		$where =  new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND',array( '`addressbookid` = :dcValue1', '`uri` = :dcValue2', ), );
		$groupBy = array( );
		$having = NULL;
		$orderBy = array( );
		$values = array( );
		$params = [
			'dcValue1' => 1,
			'dcValue2' => "132076DD-D81A-4ECE-9E21-0869EB6D0989.vcf",
		];
		$paramTypes = [
			'dcValue1' => 2,
			'dcValue2' => 2,
		];
/***/