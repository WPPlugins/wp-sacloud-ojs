<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Aws\Tests\Common\Credentials;

use Aws\Common\Credentials\Credentials;

/**
 * @covers Aws\Common\Credentials\AbstractRefreshableCredentials
 */
class AbstractRefreshableCredentialsTest extends \Guzzle\Tests\GuzzleTestCase
{
    public function testCallsRefreshWhenExpired()
    {
        $c = new Credentials('a', 'b', 'c', 10);

        $mock = $this->getMockBuilder('Aws\\Common\\Credentials\\AbstractRefreshableCredentials')
            ->setConstructorArgs(array($c))
            ->setMethods(array('refresh'))
            ->getMock();

        $mock->expects($this->exactly(4))
            ->method('refresh');

        /** @var $mock \Aws\Common\Credentials\AbstractRefreshableCredentials */
        $mock->getAccessKeyId();
        $mock->getSecretKey();
        $mock->getSecurityToken();
        $mock->serialize();
    }

    public function testCanReturnRefreshedCredentialsInSingleTransaction()
    {
        $c = new Credentials('a', 'b', 'c', 10);

        $mock = $this->getMockBuilder('Aws\\Common\\Credentials\\AbstractRefreshableCredentials')
            ->setConstructorArgs(array($c))
            ->setMethods(array('refresh'))
            ->getMock();

        $mock->expects($this->once())
            ->method('refresh');

        /** @var $mock \Aws\Common\Credentials\AbstractRefreshableCredentials */
        $newCreds = $mock->getCredentials();
        $this->assertInstanceOf('\Aws\Common\Credentials\CredentialsInterface', $newCreds);
        $this->assertSame($c->getAccessKeyId(), $newCreds->getAccessKeyId());
        $this->assertSame($c->getSecretKey(), $newCreds->getSecretKey());
        $this->assertSame($c->getSecurityToken(), $newCreds->getSecurityToken());
    }
}
