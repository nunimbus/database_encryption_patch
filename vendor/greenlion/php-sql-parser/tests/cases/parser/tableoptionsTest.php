<?php
/**
 * tableoptionsTest.php
 *
 * Test case for PHPSQLParser.
 *
 * PHP version 5
 *
 * LICENSE:
 * Copyright (c) 2010-2014 Justin Swanhart and André Rothe
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author    André Rothe <andre.rothe@phosco.info>
 * @copyright 2010-2014 Justin Swanhart and André Rothe
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @version   SVN: $Id$
 *
 */
namespace PHPSQLParser\Test\Parser;
use PHPSQLParser\PHPSQLParser;
use PHPSQLParser\PHPSQLCreator;

class TableOptionsTest extends \PHPUnit\Framework\TestCase {

    public function testTableOptions1() {
        $parser = new PHPSQLParser();
        $sql = "CREATE TABLE hohoho () AUTO_INCREMENT = 1 DEFAULT CHARACTER SET _utf8 PASSWORD 'test123'";
        $parser->parse($sql);
        $p = $parser->parsed;
        $expected = getExpectedValue(dirname(__FILE__), 'tableoptions1.serialized');
        $this->assertEquals($expected, $p, 'CREATE TABLE statement with table options');
    }

    public function testTableOptions2() {
        // TODO: the union statement within the CREATE TABLE has not been parsed
        $parser = new PHPSQLParser();
        $sql = "CREATE TABLE hohoho () UNION (tableA, tableB,tableC)";
        $parser->parse($sql);
        $p = $parser->parsed;
        $expected = getExpectedValue(dirname(__FILE__), 'tableoptions2.serialized');
        $this->assertEquals($expected, $p, 'CREATE TABLE statement with UNION table option');
    }
}
?>
