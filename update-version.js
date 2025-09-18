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
const readmeFile = path.join(__dirname, 'plugin', 'readme.txt');

if (!fs.existsSync(pluginFile)) {
    console.log(`Error: Plugin file not found at ${pluginFile}`);
    process.exit(1);
}

if (!fs.existsSync(readmeFile)) {
    console.log(`Error: Readme file not found at ${readmeFile}`);
    process.exit(1);
}

try {
    // Update plugin file
    let pluginContent = fs.readFileSync(pluginFile, 'utf8');

    // Update the version in the plugin header
    pluginContent = pluginContent.replace(
        /Version: \d+\.\d+\.\d+/,
        `Version: ${newVersion}`
    );

    // Update the version constant
    pluginContent = pluginContent.replace(
        /define\('SHOWPASS_PLUGIN_VERSION', '\d+\.\d+\.\d+'\);/,
        `define('SHOWPASS_PLUGIN_VERSION', '${newVersion}');`
    );

    // Write back to plugin file
    fs.writeFileSync(pluginFile, pluginContent, 'utf8');
    
    console.log(`✅ Successfully updated version to ${newVersion} in ${pluginFile}`);
    
    // Update readme file
    let readmeContent = fs.readFileSync(readmeFile, 'utf8');

    // Update the stable tag version
    readmeContent = readmeContent.replace(
        /Stable tag: \d+\.\d+\.\d+/,
        `Stable tag: ${newVersion}`
    );

    // Write back to readme file
    fs.writeFileSync(readmeFile, readmeContent, 'utf8');
    
    console.log(`✅ Successfully updated version to ${newVersion} in ${readmeFile}`);
    
    // Optional: Show what was changed
    console.log('\n📝 Changes made:');
    console.log(`   - Plugin header: Version: ${newVersion}`);
    console.log(`   - Plugin constant: SHOWPASS_PLUGIN_VERSION = '${newVersion}'`);
    console.log(`   - Readme stable tag: Stable tag: ${newVersion}`);
    console.log('\n💡 All script enqueues using SHOWPASS_PLUGIN_VERSION will now use this version automatically!');
    
} catch (error) {
    console.log(`Error: ${error.message}`);
    process.exit(1);
} 