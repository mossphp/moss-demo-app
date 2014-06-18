<?php
use Behat\MinkExtension\Context\MinkContext;


/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * @Given /^I am authenticated with "([^:]+):([^"]+)"$/
     */
    public function authenticateWithCredentials($login, $password)
    {
        $this->visit('/login');

        $table = new \Behat\Gherkin\Node\TableNode();
        $table->addRow(array('login', $login));
        $table->addRow(array('password', $password));
        $this->fillFields($table);

        $this->pressButton('Submit');
    }
} 