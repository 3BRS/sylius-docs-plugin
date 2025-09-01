@managing_documentation
Feature: Managing documentation pages
    In order to provide documentation to administrators
    As an Administrator
    I want to be able to view and navigate through documentation pages

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And there are no documentation files in the documentation directory

    @ui
    Scenario: Accessing the documentation section from admin menu
        When I visit the admin dashboard
        Then I should see "Documentation" menu item
        When I click "Documentation" menu item
        Then I should be redirected to the documentation index page

    @ui
    Scenario: Viewing documentation index page with available files
        Given there is a "index.md" documentation file with content:
            """
            # Welcome to Documentation

            This is the main documentation page.

            [Getting started](getting-started.md)

            [Configuration](configuration.md)
            """
        And there is a "getting-started.md" documentation file
        And there is a "configuration.md" documentation file
        When I go to the documentation index page
        Then I should see "Welcome to Documentation" on index page
        And I should see "This is the main documentation page" on index page
        And I should see a list of available documentation files:
            | getting-started |
            | configuration   |

    @ui
    Scenario: Viewing documentation index page without index file
        Given there is no "index.md" file
        And there is a "getting-started.md" documentation file
        And there is a "configuration.md" documentation file
        And there is a "other.md" documentation file
        When I go to the documentation index page
        Then I should not see any rendered content
        And I should see "Missing Documentation" on index page

    @ui
    Scenario: Viewing a specific documentation page
        Given there is a "getting-started.md" documentation file with content:
            """
            # Getting Started

            ## Installation

            Follow these steps to install the plugin.

            [Go back to main page](index.md)
            """
        When I go to the documentation page for "getting-started"
        Then I should see "Getting Started" heading
        And I should see "Installation" heading
        And I should see "Follow these steps to install the plugin."

    @ui
    Scenario: Viewing documentation page with markdown formatting
        Given there is a "formatting-test.md" documentation file with content:
            """
            # Formatting Test

            This is **bold text** and this is *italic text*.

            ## Code Block

            ```php
            <?php
            echo "Hello World";
            ```

            - List item 1
            - List item 2
            - List item 3
            """
        When I go to the documentation page for "formatting-test"
        Then I should see properly formatted markdown content
        And I should see a code block with PHP code
        And I should see a bulleted list

    @ui
    Scenario: Accessing documentation page with images
        Given there is a "images-test.md" documentation file with content:
            """
            # Images Test

            Here is an image:

            ![Test Image](test-image.png)
            """
        And there is a "test-image.png" file in the documentation directory
        When I go to the documentation page for "images-test"
        Then I should see the image displayed correctly

    @ui
    Scenario: Navigation between documentation pages using internal links
        Given there is a "index.md" documentation file with content:
            """
            # Main Page

            [Go to Getting Started](getting-started.md)
            """
        And there is a "getting-started.md" documentation file with content:
            """
            # Getting Started

            [Back to main](index.md)
            """
        When I go to the documentation index page
        And I click on "Go to Getting Started" link
        Then I should be on the documentation page for "getting-started"
        And I should see "Getting Started" heading
        When I click on "Back to main" link
        Then I should be on the documentation index page

    @ui
    Scenario: Accessing valid documentation image
        Given there is a "diagram.png" file in the documentation directory
        When I access the documentation image "diagram.png"
        Then I should see the image content
        And the response should have proper image content type
