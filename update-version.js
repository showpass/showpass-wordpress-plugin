#!/usr/bin/env node

/**
 * Simple script to update the Showpass plugin version
 * Usage: node update-version.js 4.0.2
 */

const fs = require('fs');
const path = require('path');

// Get version from command line arguments
const newVersion = process.argv[2];

if (!newVersion) {
    console.log('Usage: node update-version.js <new-version>');
    console.log('Example: node update-version.js 4.0.2');
    process.exit(1);
}

// Validate version format (simple check)
if (!/^\d+\.\d+\.\d+$/.test(newVersion)) {
    console.log('Error: Version must be in format X.Y.Z (e.g., 4.0.2)');
    process.exit(1);
}

const pluginFile = path.join(__dirname, 'plugin', 'showpass-wordpress-plugin.php');

if (!fs.existsSync(pluginFile)) {
    console.log(`Error: Plugin file not found at ${pluginFile}`);
    process.exit(1);
}

try {
    // Read the file
    let content = fs.readFileSync(pluginFile, 'utf8');

    // Update the version in the plugin header
    content = content.replace(
        /Version: \d+\.\d+\.\d+/,
        `Version: ${newVersion}`
    );

    // Update the version constant
    content = content.replace(
        /define\('SHOWPASS_PLUGIN_VERSION', '\d+\.\d+\.\d+'\);/,
        `define('SHOWPASS_PLUGIN_VERSION', '${newVersion}');`
    );

    // Write back to file
    fs.writeFileSync(pluginFile, content, 'utf8');
    
    console.log(`✅ Successfully updated version to ${newVersion} in ${pluginFile}`);
    
    // Optional: Show what was changed
    console.log('\n📝 Changes made:');
    console.log(`   - Plugin header: Version: ${newVersion}`);
    console.log(`   - Constant: SHOWPASS_PLUGIN_VERSION = '${newVersion}'`);
    console.log('\n💡 All script enqueues using SHOWPASS_PLUGIN_VERSION will now use this version automatically!');
    
} catch (error) {
    console.log(`Error: ${error.message}`);
    process.exit(1);
} 