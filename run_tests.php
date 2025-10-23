<?php

/**
 * Test Runner Script for Talishar
 * Provides an easy way to run tests and see results
 */

// Include the test bootstrap
require_once __DIR__ . '/tests/bootstrap.php';

echo "🧪 Talishar Test Suite\n";
echo "=====================\n\n";

// Test results storage
$testResults = [
    'total' => 0,
    'passed' => 0,
    'failed' => 0,
    'errors' => []
];

/**
 * Run a test class
 */
function runTestClass($className, $testFile) {
    global $testResults;
    
    echo "Running $className...\n";
    
    try {
        // Include the test file
        require_once $testFile;
        
        // Create test instance
        $test = new $className();
        
        // Get all test methods
        $reflection = new ReflectionClass($className);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        
        $testMethods = array_filter($methods, function($method) {
            return strpos($method->getName(), 'test') === 0;
        });
        
        foreach ($testMethods as $method) {
            $testResults['total']++;
            $methodName = $method->getName();
            
            try {
                // Set up test
                if (method_exists($test, 'setUp')) {
                    $test->setUp();
                }
                
                // Run test
                $test->{$methodName}();
                
                // Clean up test
                if (method_exists($test, 'tearDown')) {
                    $test->tearDown();
                }
                
                $testResults['passed']++;
                echo "  ✅ $methodName\n";
                
            } catch (Exception $e) {
                $testResults['failed']++;
                $testResults['errors'][] = "$className::$methodName: " . $e->getMessage();
                echo "  ❌ $methodName: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "  ❌ Error loading test class: " . $e->getMessage() . "\n";
        $testResults['errors'][] = "$className: " . $e->getMessage();
    }
    
    echo "\n";
}

// Define test classes to run
$testClasses = [
    'Talishar\Tests\Security\SQLInjectionTest' => __DIR__ . '/tests/Security/SQLInjectionTest.php',
    'Talishar\Tests\Security\XSSPreventionTest' => __DIR__ . '/tests/Security/XSSPreventionTest.php',
    'Talishar\Tests\Security\CSRFProtectionTest' => __DIR__ . '/tests/Security/CSRFProtectionTest.php',
    'Talishar\Tests\Security\SessionManagementTest' => __DIR__ . '/tests/Security/SessionManagementTest.php',
    'Talishar\Tests\Validation\InputValidationTest' => __DIR__ . '/tests/Validation/InputValidationTest.php',
    'Talishar\Tests\BusinessLogic\GameLogicTest' => __DIR__ . '/tests/BusinessLogic/GameLogicTest.php'
];

// Run all tests
foreach ($testClasses as $className => $testFile) {
    if (file_exists($testFile)) {
        runTestClass($className, $testFile);
    } else {
        echo "❌ Test file not found: $testFile\n\n";
    }
}

// Display results
echo "📊 Test Results Summary\n";
echo "=======================\n";
echo "Total Tests: " . $testResults['total'] . "\n";
echo "Passed: " . $testResults['passed'] . "\n";
echo "Failed: " . $testResults['failed'] . "\n";
echo "Success Rate: " . ($testResults['total'] > 0 ? round(($testResults['passed'] / $testResults['total']) * 100, 2) : 0) . "%\n\n";

if ($testResults['failed'] > 0) {
    echo "❌ Failed Tests:\n";
    foreach ($testResults['errors'] as $error) {
        echo "  - $error\n";
    }
    echo "\n";
}

if ($testResults['failed'] === 0 && $testResults['total'] > 0) {
    echo "🎉 All tests passed! Your security fixes are working correctly.\n";
} elseif ($testResults['total'] === 0) {
    echo "⚠️  No tests were run. Please check your test files.\n";
} else {
    echo "⚠️  Some tests failed. Please review the errors above.\n";
}

echo "\n";
echo "💡 To run tests with PHPUnit (if installed):\n";
echo "   composer install --dev\n";
echo "   ./vendor/bin/phpunit\n\n";

?>
