Feature: Import opportunity feature
  In order to simplify work with opportunities
  As crm user
  I want to import/export opportunities data

  Scenario: Data Template for Opportunity
    Given I login as administrator
    And "First Sales Channel" is a channel with enabled Business Customer entities
    And I go to Opportunity Index page
    And there is no records in grid
    When I download Data Template file
    Then I don't see Business Customer Name column
    And I see Account Customer name column

  Scenario: Import Opportunity with Account and Customer
    Given crm has Acme Account with Charlie and Samantha customers
    And I fill template with data:
      | Account Customer name | Channel Name        | Opportunity name | Status Id   |
      | Charlie               | First Sales Channel | Opportunity one  | in_progress |
      | Samantha              | First Sales Channel | Opportunity two  | in_progress |
    When I import file
    Then Charlie customer has Opportunity one opportunity
    And Samantha customer has Opportunity two opportunity
    And go to Opportunity Index page
    And number of records should be 2
    And I should see Opportunity one in grid with following data:
      | Channel    | First Sales Channel |
      | Status     | Open                |
      | Owner      | John Doe            |

  Scenario: Import Opportunity with new Account
    Given I fill template with data:
      | Account Customer name | Channel Name        | Opportunity name  | Status Id   |
      | Absolute new account  | First Sales Channel | Opportunity three | in_progress |
    When I import file
    Then "Absolute new account" Account was created
    Then "Absolute new account" Customer was created
    And Absolute new account customer has Opportunity three opportunity

  Scenario: Import Opportunity with new Customer
    Given the following Account:
      | Name                     | Owner   | organization  |
      | Account without Customer | @admin  | @organization |
    And I fill template with data:
      | Account Customer name    | Channel Name        | Opportunity name  | Status Id   |
      | Account without Customer | First Sales Channel | Opportunity four  | in_progress |
    When I go to Opportunity Index page
    And import file
    Then "Account without Customer" Customer was created
    And Account without Customer customer has Opportunity four opportunity

  Scenario: Import Opportunity with no Account
    Given I go to Opportunity Index page
    And I fill template with data:
      | Channel Name        | Opportunity name  | Status Id   |
      | First Sales Channel | Opportunity five  | in_progress |
    When I try import file
    Then I should see validation message "Error in row #1. Account Customer name: This value should not be blank."
    And close ui dialog

  Scenario: Import Opportunity with
    Given I go to Opportunity Index page
    And I fill template with data:
      | Account Customer name |
      | Acme                  |
    When I try import file
    Then I should see validation message "Error in row #1. Opportunity name: This value should not be blank."
    And I should see validation message "Error in row #1. Channel Name: This value should not be blank."
    And I should see validation message "Error in row #1. Status Id: This value should not be blank."
