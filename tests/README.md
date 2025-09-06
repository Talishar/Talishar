# Talishar Test Suite

This directory contains comprehensive unit tests for the Talishar application, focusing on security, validation, and business logic.

## 🧪 Test Structure

```
tests/
├── bootstrap.php                    # Test environment setup
├── Security/                        # Security-related tests
│   ├── SQLInjectionTest.php         # SQL injection prevention tests
│   ├── XSSPreventionTest.php        # XSS prevention tests
│   ├── CSRFProtectionTest.php       # CSRF protection tests
│   └── SessionManagementTest.php    # Session security tests
├── Validation/                      # Input validation tests
│   └── InputValidationTest.php      # Comprehensive validation tests
├── BusinessLogic/                   # Core business logic tests
│   └── GameLogicTest.php           # Game logic validation tests
└── Integration/                     # Integration tests (future)
```

## 🚀 Running Tests

### Option 1: Custom Test Runner (Recommended for now)
```bash
php run_tests.php
```

### Option 2: PHPUnit (After installing dependencies)
```bash
# Install PHPUnit
composer install --dev

# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite Security
./vendor/bin/phpunit --testsuite Validation
./vendor/bin/phpunit --testsuite BusinessLogic

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

## 📋 Test Categories

### Security Tests
- **SQL Injection Prevention**: Tests that malicious SQL is properly escaped and parameterized queries are used
- **XSS Prevention**: Tests that HTML special characters are properly escaped
- **CSRF Protection**: Tests token generation, validation, and form protection
- **Session Management**: Tests secure session configuration and management

### Validation Tests
- **Input Validation**: Tests all input validation functions
- **Data Sanitization**: Tests string and HTML sanitization
- **Type Validation**: Tests integer, boolean, JSON, URL, and IP validation
- **Edge Cases**: Tests boundary conditions and error handling

### Business Logic Tests
- **Game Logic**: Tests core game functionality
- **User Management**: Tests user-related functions
- **Data Integrity**: Tests data validation and consistency

## 🔧 Test Configuration

### PHPUnit Configuration (`phpunit.xml`)
- **Bootstrap**: `tests/bootstrap.php`
- **Test Suites**: Organized by category
- **Coverage**: Includes Libraries, AccountFiles, and includes directories
- **Exclusions**: Excludes test files, vendor, and asset directories

### Bootstrap File (`tests/bootstrap.php`)
- Sets up test environment
- Mocks database connections
- Provides test utilities
- Configures error reporting

## 📊 Test Coverage

The test suite covers:
- ✅ SQL injection prevention
- ✅ XSS prevention  
- ✅ CSRF protection
- ✅ Session management
- ✅ Input validation
- ✅ Business logic validation
- ✅ Edge cases and error handling

## 🛠️ Adding New Tests

### 1. Create Test File
```php
<?php

namespace Talishar\Tests\YourCategory;

use PHPUnit\Framework\TestCase;

class YourTestClass extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up test data
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up after test
    }

    public function testYourFunction()
    {
        // Your test logic
        $this->assertTrue(true);
    }
}
```

### 2. Add to Test Runner
Update `run_tests.php` to include your new test class.

### 3. Follow Naming Conventions
- Test methods: `testSomething()`
- Test classes: `SomethingTest`
- Use descriptive test names
- Test both valid and invalid inputs

## 🎯 Test Best Practices

### Security Tests
- Test malicious inputs (SQL injection, XSS, CSRF)
- Test edge cases and boundary conditions
- Verify proper escaping and sanitization
- Test authentication and authorization

### Validation Tests
- Test valid inputs (should pass)
- Test invalid inputs (should fail)
- Test edge cases (empty, null, boundary values)
- Test type validation (string, int, bool, etc.)

### Business Logic Tests
- Test core functionality
- Test error conditions
- Test data integrity
- Test user workflows

## 🐛 Debugging Tests

### Common Issues
1. **Session conflicts**: Tests may interfere with each other
2. **Database dependencies**: Use mocked connections
3. **File permissions**: Ensure test files are readable
4. **PHP version**: Tests require PHP 7.4+

### Debug Mode
```bash
# Run with verbose output
php run_tests.php 2>&1 | tee test_output.log

# Run specific test
php -d display_errors=1 run_tests.php
```

## 📈 Continuous Integration

### GitHub Actions (Future)
```yaml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install --dev
      - name: Run tests
        run: ./vendor/bin/phpunit
```

## 🔍 Test Results Interpretation

### Success Indicators
- ✅ All tests pass
- High code coverage (>80%)
- No security vulnerabilities detected
- Fast execution time

### Failure Indicators
- ❌ Any test fails
- Low code coverage (<50%)
- Security tests fail
- Slow execution time

## 📚 Resources

- [PHPUnit Documentation](https://phpunit.readthedocs.io/)
- [PHP Testing Best Practices](https://phpunit.readthedocs.io/en/9.5/writing-tests-for-phpunit.html)
- [Security Testing Guidelines](https://owasp.org/www-project-web-security-testing-guide/)

---

**Note**: This test suite was created to validate the security fixes implemented in Talishar. It focuses on preventing SQL injection, XSS, CSRF attacks, and ensuring proper input validation.
