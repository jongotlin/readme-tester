Feature: Filter argument
  In order to run failing tests during development
  As a user
  I need to be able to filter specific examples

  Scenario: I filter an example
    Given a markdown file:
    """
    <!--
        @example foo
        @expectOutput foo
    -->
    ```php
    echo 'foo';
    ```
    <!--
        @example bar
        @expectOutput bar
    -->
    ```php
    echo 'bar';
    ```
    """
    And the command line argument '--filter=bar'
    When I run readme tester
    Then 1 examples are evaluated
    And 1 examples are ignored

  Scenario: I filter using a regexp
    Given a markdown file:
    """
    <!-- @example 1 -->
    <!-- @expectOutput 1 -->
    ```php
    echo '1';
    ```
    <!-- @example 2 -->
    <!-- @expectOutput 2 -->
    ```php
    echo '2';
    ```
    <!-- @example foo -->
    <!-- @expectOutput foo -->
    ```php
    echo 'foo';
    ```
    """
    And the command line argument '--filter=/[0-9]+/'
    When I run readme tester
    Then 2 examples are evaluated
    And 1 examples are ignored

  Scenario: I filter an inherited example
    Given a markdown file:
    """
    <!-- @example foo -->
    ```php
    $str = 'foo';
    ```
    <!--
        @example bar
        @include foo
        @expectOutput foo
    -->
    ```php
    echo $str;
    ```
    """
    And the command line argument '--filter=bar'
    When I run readme tester
    Then 1 examples are evaluated
    And 1 examples are ignored
    And 0 failures are found

  Scenario: I filter flagged only
    Given a markdown file:
    """
    ```php
    // this-example-has-no-annotation
    ```
    <!-- @example foo -->
    ```php
    // this-examples-has-an-annotation
    ```
    """
    And the command line argument '--flagged-only'
    When I run readme tester
    Then 1 examples are evaluated
    And 1 examples are ignored

  Scenario: I filter flagged only using unnamed example
    Given a markdown file:
    """
    ```php
    // this-example-has-no-annotation
    ```
    <!-- @example -->
    ```php
    // this-examples-has-an-annotation
    ```
    """
    And the command line argument '--flagged-only'
    When I run readme tester
    Then 1 examples are evaluated
    And 1 examples are ignored
