<?php
require_once dirname(__FILE__).'/TestConfig.php';

class OmiseAccountTest extends TestConfig
{
    /**
     * @test
     * OmiseAccount class must be contain some method below.
     */
    public function method_exists()
    {
        $this->assertTrue(method_exists('OmiseAccount', 'retrieve'));
        $this->assertTrue(method_exists('OmiseAccount', 'reload'));
        $this->assertTrue(method_exists('OmiseAccount', 'getUrl'));
    }

    /**
     * @test
     * Assert that an account object is returned after a successful retrieve.
     */
    public function retrieve_omise_account_object()
    {
        $account = OmiseAccount::retrieve();

        $this->assertArrayHasKey('object', $account);
        $this->assertEquals('account', $account['object']);
    }

    /**
     * @test
     * Assert that an account object is returned after a successful reload.
     */
    public function reload()
    {
        $account = OmiseAccount::retrieve();
        $account->reload();

        $this->assertArrayHasKey('object', $account);
        $this->assertEquals('account', $account['object']);
    }
}
