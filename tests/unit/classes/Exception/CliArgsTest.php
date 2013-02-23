<?php

/**
* @packeage Releasr
*/
class Releasr_Exception_CliArgsText extends PHPUnit_Framework_Testcase
{
    public function testUsageMessageIsNullIfNoCommandIsSet()
    {
        $exception = new Releasr_Exception_CliArgs('message');
        
        $message = $exception->getUsageMessage();
        
        $this->assertNull($message);
    }

    public function testGetUsageMessageCallsMethodOnCommand()
    {
        $command = $this->getMock('Releasr_CliCommand_Interface');
        $command->expects($this->once())
            ->method('getUsageMessage');
        
        $exception = new Releasr_exception_CliArgs('message', 0, NULL, $command);
        
        $exception->getUsageMessage();
    }

    public function testGetUsageMessageReturnsMessageFromCommand()
    {
        $command = $this->getMock('Releasr_CliCommand_Interface');
        $command->expects($this->any())
            ->method('getUsageMessage')
            ->will($this->returnValue('EXAMPLE MESSAGE'));

        $exception = new Releasr_exception_CliArgs('message', 0, NULL, $command);

        $message = $exception->getUsageMessage();
        
        $this->assertSame('EXAMPLE MESSAGE', $message);
    }
}