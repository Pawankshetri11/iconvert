<?php
// Test script for license system
echo "License System Test\n";
echo "==================\n\n";

// Generate a test license key
$testKey = 'ROYAL-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 16));

echo "Generated Test License Key: $testKey\n\n";

echo "Testing Main Application License System:\n";
echo "=========================================\n\n";

echo "1. The main application will work in offline mode for testing\n";
echo "2. Any license key starting with 'ROYAL-' will be accepted\n";
echo "3. Domain locking is enforced (one key per domain)\n\n";

echo "To test the license system:\n";
echo "1. Visit your main application admin panel\n";
echo "2. Go to License section\n";
echo "3. Use this test key: $testKey\n";
echo "4. Fill in test details:\n";
echo "   - Client Name: Test Client\n";
echo "   - Email: test@example.com\n";
echo "5. Click 'Activate License'\n\n";

echo "Expected Results:\n";
echo "- ✅ License should activate successfully\n";
echo "- ✅ App should unlock and allow full access\n";
echo "- ✅ License status should show as 'Active'\n";
echo "- ✅ Server connection status should show current state\n\n";

echo "For Production Deployment:\n";
echo "========================\n";
echo "1. Deploy license-server-root/ to server.4amtech.in\n";
echo "2. Run installation at https://server.4amtech.in/install\n";
echo "3. Copy the generated license key\n";
echo "4. Update main app .env:\n";
echo "   LICENSE_SERVER_URL=https://server.4amtech.in/api/license\n";
echo "   LICENSE_REQUIRE_ONLINE=true\n";
echo "5. Use the generated key in main app\n\n";

echo "Test Key for immediate use: $testKey\n";
?>