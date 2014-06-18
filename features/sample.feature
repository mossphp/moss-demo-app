Feature: Sample app
  In order to show off
  As a user
  I need to be able to demonstrate capabilities

  Scenario:
    Given I am on "/"
    When I follow "it looks like this"
    Then the response status code should be 403

  Scenario:
    Given I am on "/login/"
    And I fill in the following:
      | login | foo |
      | password | bar |
    And I press "Submit"
    Then the response status code should be 401
    And I should be on "/login/"
    And I should see "Unable to tokenize, invalid login"

  Scenario:
    Given I am on "/login/"
    And I fill in the following:
      | login | login |
      | password | password |
    And I press "Submit"
    Then the response status code should be 200
    And I should be on "/source/"
    And I should see "Moss\Sample\Controller\SampleController::source"

  Scenario:
    Given I am authenticated with "login:password"
    And I am on "/source/"
    Given I follow "here"
    Then the response status code should be 403
    And I should be on "/logout/"