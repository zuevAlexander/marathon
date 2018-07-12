<?php

namespace CoreBundle\Tests\Service\OauthProvider;

use RestBundle\Service\OauthProvider\FacebookProvider;
use PHPUnit\Framework\TestCase;

class FacebookProviderTest extends TestCase
{
    /**
     * @var FacebookProvider
     */
    private $provider;

    protected function setUp()
    {
        parent::setUp();
        $this->provider = new FacebookProvider();
    }

    public function testAuthenticate()
    {
        $result = $this->provider->authenticate(
            'EAACEdEose0cBANuhE7ZBEcksVJmFKt26sONsTV5i8GJYAYBAzxZCEGteDcSvGS1LqntaFj2CnU8wledyMNbAZCPRraXgTPc3ugwTSkBNQsW3b0AjfcasnMhQwnfXN4ZCYIZBhBQQ23lF7CjG6yyY6ziLaBwxxlNZBsyBM2qVIMxQZDZD',
   $errorMessage);
    }
}
