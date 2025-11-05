Feature: Homepage
  In order to ensure the app is reachable
  As a developer
  I want to check that the homepage returns 200

  Scenario: Homepage returns 200
    Given I have the application URL
    When I request the homepage
    Then I should see the text "Laravel"
