Feature: Test standings API.
  Test API with valid and invalid date format query.

  Background: I have teams and matches in database.
    Given There are teams:
      | id | name              |
      | 1  | Manchester City   |
      | 2  | Manchester United |
      | 3  | Arsenal           |
      | 4  | Tottenham Hotspur |
      | 5  | Fulham            |
    And There are matches:
      | homeTeamId | homeTeamScore | awayTeamId | awayTeamScore | dateTimeOfMatch |
      | 1          | 1             | 2          | 0             | 2011-05-09      |
      | 1          | 3             | 3          | 2             | 2011-05-10      |
      | 1          | 2             | 4          | 2             | 2011-05-11      |
      | 5          | 2             | 1          | 2             | 2011-05-12      |
      | 4          | 1             | 5          | 4             | 2011-05-12      |

  Scenario: I want to get standings data.
  I send response without query.
  I see valid data.
    When I send a GET request to "/api/standings"
    Then the response code should be 200
    And the response should contain json:
    """
      [
        {
          "place": 1,
          "name": "Manchester City",
          "score": 8,
          "wins": 2,
          "losses": 0,
          "draws": 2,
          "played": 4
        },
        {
          "place": 2,
          "name": "Fulham",
          "score": 4,
          "wins": 1,
          "losses": 0,
          "draws": 1,
          "played": 2
        },
        {
          "place": 3,
          "name": "Tottenham Hotspur",
          "score": 1,
          "wins": 0,
          "losses": 1,
          "draws": 1,
          "played": 2
        },
        {
          "place": 4,
          "name": "Manchester United",
          "score": 0,
          "wins": 0,
          "losses": 1,
          "draws": 0,
          "played": 1
        },
        {
          "place": 5,
          "name": "Arsenal",
          "score": 0,
          "wins": 0,
          "losses": 1,
          "draws": 0,
          "played": 1
        }
      ]
    """

  Scenario: I want to get standings data in range of dates.
  I send valid response.
  I see valid data.
    When I send a GET request to "/api/standings?from=2011-05-09&to=2011-05-09"
    Then the response code should be 200
    And the response should contain json:
    """
      [
        {
          "place": 1,
          "name": "Manchester City",
          "score": 3,
          "wins": 1,
          "losses": 0,
          "draws": 0,
          "played": 1
        },
        {
          "place": 2,
          "name": "Manchester United",
          "score": 0,
          "wins": 0,
          "losses": 1,
          "draws": 0,
          "played": 1
        }
      ]
    """

  Scenario: I want to get standings data.
  I send invalid query.
  I see validation response error.
    When I send a GET request to "/api/standings?from=2011-05-009&to=2011-05-09"
    Then the response code should be 400
    And the response should contain json:
    """
      {
        "code": 400,
        "message": "Query parameter from value '2011-05-009' violated a constraint (Query parameter value '2011-05-009', does not match requirements '^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$')",
        "errors": null
      }
    """