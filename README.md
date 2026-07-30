# Multi-Tenant Document Validation

This is a validation component for a multi-tenant document processing platform. It allows different tenants to have their own custom validation rules for uploaded documents.

## Requirements
- Docker and Docker Compose

## Project Setup

1. Start the Docker container in the background:
    docker compose up -d

2. Enter the PHP container:
    docker compose exec --user 1000 app sh

3. Install project dependencies (PHPUnit):
    composer install

## Usage

To see how the validation works, run the integration script. It demonstrates creating a validator, setting up tenant rules, and validating both valid and invalid documents.

    php run.php

## Testing

The project includes unit tests for individual rules and the main validator component. To run the tests, execute:

    ./vendor/bin/phpunit

## Design Reasoning

The code is built using the **Strategy Pattern**. The main `DocumentValidator` class does not have validation logic inside it. Instead, it uses the `ValidationRule` interface. If we need to add a new rule in the future, we just create a new class. We don't need to change the core validator class. This follows the **Open/Closed Principle**.

To support multiple tenants, I created a `RuleProvider` interface. The validator asks this provider to get the correct rules for a specific `tenantId`. This keeps the validation engine completely separate from the data storage (like a database or config files).

I also used modern PHP 8.2 features. The `Document` and `ValidationResult` objects use `readonly` properties. This makes them immutable (they cannot be changed after creation), which prevents accidental data changes during the validation process and makes the code safer.