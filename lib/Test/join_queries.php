<?php

/* SELECT with JOIN using aliases **
		$sql = "SELECT `c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri` FROM `*PREFIX*cards` `c` JOIN `*PREFIX*cards_properties` `cp` ON `cp`.`cardid` = `c`.`cardid`";
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri`' );
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
/* Same as above, but JOIN condition is switched **/
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '`cp`', 'joinCondition' => '`c`.`cardid` =`cp`.`cardid`' ) ) );
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
/* Same as above, but no SELECT alias **
		$select = array( '`addressbookid`', '`carddata`', '`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => ''), );
		$join = array( "" => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '`cp`', 'joinCondition' => '`*PREFIX*cards`.`cardid` =`cp`.`cardid`' ) ) );
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
/* SELECT alias, but no JOIN alias **/
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
/* SELECT alias, but no JOIN alias **
		$select = array( '`c`.`addressbookid`', '`c`.`carddata`', '`c`.`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => '`c`'), );
		$join = array( '`c`' => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => '', 'joinCondition' => '`*PREFIX*cards_properties`.`cardid` = `c`.`cardid`' ) ) );
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
/* No aliases - this WILL NOT work **
		$select = array( '`addressbookid`', '`carddata`', '`uri`' );
		$distinct = false;
		$from = array( array( 'table' => '`*PREFIX*cards`', 'alias' => ''), );
		$join = array( "" => array( array( 'joinType' => 'inner', 'joinTable' => '`*PREFIX*cards_properties`', 'joinAlias' => "", 'joinCondition' => '`*PREFIX*cards`.`cardid` = `*PREFIX*cards_properties`.`cardid`' ) ) );
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
