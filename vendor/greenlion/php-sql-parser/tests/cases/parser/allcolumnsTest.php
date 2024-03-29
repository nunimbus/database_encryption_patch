<?php
/**
 * allcolumnsTest.php
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

class AllColumnsTest extends \PHPUnit\Framework\TestCase {

	protected $parser;

	/**
	 * @before
	 * Executed before each test
	 */
	protected function setup(): void {
		$this->parser = new PHPSQLParser();
	}

    public function testAllColumns1() {
        $sql="SELECT * FROM FAILED_LOGIN_ATTEMPTS WHERE ip='192.168.50.5'";
        $p = $this->parser->parse($sql);
        $expected = getExpectedValue(dirname(__FILE__), 'allcolumns1.serialized');
        $this->assertEquals($expected, $p, 'single all column alias');
    }

    public function testAllColumns2() {
        $sql="SELECT a * b FROM tests";
        $p = $this->parser->parse($sql);
        $expected = getExpectedValue(dirname(__FILE__), 'allcolumns2.serialized');
        $this->assertEquals($expected, $p, 'multiply two columns');
    }

    public function testAllColumns3() {
        $sql="SELECT count(*) FROM tests";
        $p = $this->parser->parse($sql);
        $expected = getExpectedValue(dirname(__FILE__), 'allcolumns3.serialized');
        $this->assertEquals($expected, $p, 'special function count(*)');
    }

    public function testAllColumns4() {
        $sql="SELECT a.* FROM FAILED_LOGIN_ATTEMPTS a";
        $p = $this->parser->parse($sql);
        $expected = getExpectedValue(dirname(__FILE__), 'allcolumns4.serialized');
        $this->assertEquals($expected, $p, 'single all column alias with table alias');
    }

    public function testAllColumns5() {
        $sql="SELECT a, * FROM tests";
        $p = $this->parser->parse($sql);
        $expected = getExpectedValue(dirname(__FILE__), 'allcolumns5.serialized');
        $this->assertEquals($expected, $p, 'column reference and a single all column alias');
    }
}
?>
