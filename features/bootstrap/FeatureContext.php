<?php

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $response;
    private $client;

    public function __construct()
    {
        $base = getenv('APP_URL') ?: 'http://host.docker.internal:8000';
        $this->client = new Client(['base_uri' => $base, 'http_errors' => false]);
    }

    /** @Given I have the application URL */
    public function iHaveTheApplicationUrl()
    {
        // noop: constructor resolves APP_URL
    }

    /** @When I request the homepage */
    public function iRequestTheHomepage()
    {
        $this->response = $this->client->get('/');
    }

    /** @Then I should see the text :text */
    public function iShouldSeeTheText($text)
    {
        $body = (string) $this->response->getBody();
        if (strpos($body, $text) === false) {
            throw new \Exception("Text '{$text}' not found in response");
        }
    }
}
