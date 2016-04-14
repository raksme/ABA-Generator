<?php

namespace AbaFileGenerator\Tests\Generator;

use \PHPUnit_Framework_TestCase;
use AbaFileGenerator\Generator\AbaFileGenerator;
use AbaFileGenerator\Tests\Fixtures\TransactionFixtures;

class AbaFileGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testRecordTypes()
    {
        $abaString = $this->generate();
        $abaLines = explode("\r\n", $abaString);

        $this->assertCount(6, $abaLines);
        $this->assertEquals('0', $abaLines[0][0]);
        $this->assertEquals('1', $abaLines[1][0]);
        $this->assertEquals('1', $abaLines[4][0]);
        $this->assertEquals('7', $abaLines[5][0]);

        return $abaLines;
    }

    public function testDescriptiveRecordEntry($abaLines)
    {
        $record = $abaLines[0];
        $ad = $this->getAccountDetails();

        $this->assertEquals(120, strlen($record));
        $this->assertEquals($ad['bsb'], substr($record, 1, 7));
        $this->assertEquals($ad['accountNumber'], substr($record, 8, 9));
        $this->assertEquals($ad['bankName'], substr($record, 20, 3));
        $this->assertStringStartsWith($ad['userName'], substr($record, 30, 26));
        $this->assertEquals($ad['directEntryUserId'], substr($record, 56, 6));
        $this->assertStringStartsWith($ad['description'], substr($record, 62, 12));
    }

    public function testDetailRecordEntry($abaLines)
    {
        $record = $abaLines[0];
        $ad = $this->getAccountDetails();


        return $abaLines;
    }

    public function testBatchControlRecord($abaLines)
    {
        $abaString = $this->generate();
        $abaLines = explode("\r\n", $abaString);
        $record = $abaLines[0];
        $ad = $this->getAccountDetails();


        return $abaLines;
    }

    private function generate()
    {
        $ad = $this->getAccountDetails();
        $generator = new AbaFileGenerator($ad['bsb'], $ad['accountNumber'], $ad['bankName'], $ad['userName'], $ad['remitterName'], $ad['directEntryUserId'], $ad['description']);
        $fixtures = new TransactionFixtures();

        return $generator->generate($fixtures->getTransactions());
    }

    private function getAccountDetails()
    {
        return array(
            'bsb' => '123-123',
            'accountNumber' => '12345678',
            'bankName' => 'CBA',
            'userName' => 'Some name',
            'remitterName' => 'From some guy',
            'directEntryUserId' => '999999',
            'description' => 'Payroll'
        );
    }
}
