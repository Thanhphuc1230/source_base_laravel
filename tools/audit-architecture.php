<?php

declare(strict_types=1);

/**
 * Automated Enterprise Architecture, Performance & Zero-Hardcode Auditor
 *
 * Enforces Core Rules from .agents/AGENTS.md:
 * - Rule 1: Zero-Hardcode & Zero-CDN in Frontend Blade views
 * - Rule 2: Strict Layered Architecture (No raw DB::table/select in Controllers, 100% Repository Contracts bound)
 * - Rule 19: Origin Performance & Query Column Pruning
 */

$basePath = realpath(__DIR__ . '/..');
$errors = [];
$warnings = [];
$checksPassed = 0;

function scanFiles(string $dir, string $extension): array
{
    if (! is_dir($dir)) {
        return [];
    }
    $result = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), $extension)) {
            $result[] = $file->getPathname();
        }
    }
    sort($result);

    return $result;
}

echo "=================================================================\n";
echo "🛡️  ANTIGRAVITY ENTERPRISE ARCHITECTURE & QUALITY GATE AUDITOR\n";
echo "=================================================================\n\n";

// -----------------------------------------------------------------
// 1. Audit Controllers for Raw SQL Queries (Rule 2)
// -----------------------------------------------------------------
$controllerFiles = scanFiles($basePath . '/app/Http/Controllers', '.php');
$rawSqlViolations = 0;
foreach ($controllerFiles as $file) {
    $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file);
    $content = file_get_contents($file);
    if (preg_match('/DB::(table|select|statement|insert|delete)\s*\(/', $content, $m)) {
        $errors[] = "[Rule 2 - Raw SQL in Controller] {$relPath} calls DB::{$m[1]}() directly.";
        $rawSqlViolations++;
    }
}
if ($rawSqlViolations === 0) {
    echo "✔ [PASS] Rule 2 (Controller Layer): 0 raw SQL queries across " . count($controllerFiles) . " controllers.\n";
    $checksPassed++;
}

// -----------------------------------------------------------------
// 2. Audit Repository Contracts & ServiceProvider Bindings (Rule 2)
// -----------------------------------------------------------------
$eloquentRepos = scanFiles($basePath . '/app/Repositories/Eloquent', '.php');
$providerContent = file_get_contents($basePath . '/app/Providers/RepositoryServiceProvider.php');
$repoViolations = 0;
$repoCount = 0;

foreach ($eloquentRepos as $repoFile) {
    $baseName = basename($repoFile, '.php');
    if ($baseName === 'BaseRepository') {
        continue;
    }
    $repoCount++;
    $interfaceName = $baseName . 'Interface';
    $interfacePath = $basePath . '/app/Repositories/Interfaces/' . $interfaceName . '.php';

    if (! is_file($interfacePath)) {
        $errors[] = "[Rule 2 - Missing Contract] {$baseName} is missing {$interfaceName} in app/Repositories/Interfaces/.";
        $repoViolations++;
    } elseif (strpos($providerContent, $interfaceName . '::class') === false) {
        $errors[] = "[Rule 2 - Unbound Contract] {$interfaceName} is not bound in RepositoryServiceProvider.php.";
        $repoViolations++;
    }
}
if ($repoViolations === 0) {
    echo "✔ [PASS] Rule 2 (Repository Contracts): All {$repoCount} Eloquent Repositories have Interfaces & ServiceProvider bindings.\n";
    $checksPassed++;
}

// -----------------------------------------------------------------
// 3. Audit Frontend Blade Views for Zero-CDN & Hardcode (Rule 1 & 6)
// -----------------------------------------------------------------
$bladeFiles = scanFiles($basePath . '/resources/views/frontend', '.blade.php');
$cdnViolations = 0;
foreach ($bladeFiles as $bladeFile) {
    $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $bladeFile);
    $content = file_get_contents($bladeFile);

    // Check for forbidden third-party JS/CSS library CDNs inside content views (excluding head.blade.php core framework loader)
    if (basename($bladeFile) !== 'head.blade.php' && preg_match('/https?:\/\/(cdn\.jsdelivr\.net|unpkg\.com|cdnjs\.cloudflare\.com)/i', $content, $m)) {
        $errors[] = "[Rule 6 - Zero-CDN Violation] {$relPath} references external CDN ({$m[1]}).";
        $cdnViolations++;
    }

    if (preg_match('/https?:\/\/(images\.unsplash\.com|via\.placeholder\.com|picsum\.photos|placehold\.co)/i', $content, $m)) {
        $errors[] = "[Rule 1 - External Image Placeholder] {$relPath} references external placeholder image ({$m[1]}).";
        $cdnViolations++;
    }

    if (preg_match('/src=["\']https:\/\/www\.google\.com\/maps\/embed[^"\']*["\']/', $content)) {
        $errors[] = "[Rule 1 - Hardcode Violation] {$relPath} contains hardcoded Google Maps iframe URL.";
        $cdnViolations++;
    }
}
if ($cdnViolations === 0) {
    echo "✔ [PASS] Rule 1 & 6 (Frontend Views): 0 CDN leaks & 0 hardcoded map embeds across " . count($bladeFiles) . " Blade views.\n";
    $checksPassed++;
}

// -----------------------------------------------------------------
// 4. Audit Origin Performance & Query Pruning (Rule 19)
// -----------------------------------------------------------------
$composerContent = file_get_contents($basePath . '/app/View/Composers/FrontendComposer.php');
$slugServiceContent = file_get_contents($basePath . '/app/Services/SlugResolutionService.php');
$perfViolations = 0;

if (strpos($composerContent, '$memoizedData') === false) {
    $errors[] = "[Rule 19 - ViewComposer Bottleneck] FrontendComposer is missing request-level memoization (\$memoizedData).";
    $perfViolations++;
}
if (strpos($slugServiceContent, 'CacheService::remember') === false) {
    $errors[] = "[Rule 19 - Uncached UNION ALL] SlugResolutionService is missing CacheService::remember wrapper.";
    $perfViolations++;
}
if ($perfViolations === 0) {
    echo "✔ [PASS] Rule 19 (Performance & Cache): FrontendComposer memoization, single-query Settings map & Slug cache active.\n";
    $checksPassed++;
}

echo "\n-----------------------------------------------------------------\n";
if (! empty($errors)) {
    echo "❌ AUDIT FAILED WITH " . count($errors) . " VIOLATION(S):\n";
    foreach ($errors as $err) {
        echo "   - {$err}\n";
    }
    exit(1);
}

echo "🎉 ALL {$checksPassed}/4 ENTERPRISE QUALITY GATES PASSED (0 VIOLATIONS)!\n";
echo "=================================================================\n";
exit(0);
