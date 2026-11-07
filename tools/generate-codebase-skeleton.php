<?php

declare(strict_types=1);

/**
 * Codebase Skeleton Generator for Antigravity & AI Coding Assistants
 *
 * Extracts Classes, Interfaces, Traits, Public Methods, Signatures,
 * and Eloquent Model Relationships into .agents/codebase-skeleton.md
 */

require_once __DIR__ . '/../vendor/autoload.php';

$basePath = realpath(__DIR__ . '/..');
$appPath = $basePath . DIRECTORY_SEPARATOR . 'app';
$outputFile = $basePath . DIRECTORY_SEPARATOR . '.agents' . DIRECTORY_SEPARATOR . 'codebase-skeleton.md';

$sections = [
    'Models' => $appPath . DIRECTORY_SEPARATOR . 'Models',
    'Repositories (Interfaces)' => $appPath . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Interfaces',
    'Repositories (Eloquent)' => $appPath . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Eloquent',
    'Services' => $appPath . DIRECTORY_SEPARATOR . 'Services',
    'Controllers (Frontend)' => $appPath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Frontend',
    'Controllers (Admin)' => $appPath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin',
    'Controllers (Root & Auth)' => $appPath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers',
    'Requests' => $appPath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Requests',
    'Traits' => $appPath . DIRECTORY_SEPARATOR . 'Traits',
];

function getPhpFiles(string $dir, bool $recursive = true): array
{
    if (!is_dir($dir)) {
        return [];
    }

    $files = [];
    if ($recursive) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
    } else {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_file($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
                $files[] = $fullPath;
            }
        }
    }
    sort($files);
    return $files;
}

function getFullClassNameFromFile(string $filePath): ?string
{
    $contents = file_get_contents($filePath);
    $tokens = token_get_all($contents);
    $namespace = '';
    $class = '';
    $gettingNamespace = false;
    $gettingClass = false;

    for ($i = 0; $i < count($tokens); $i++) {
        $token = $tokens[$i];
        if (is_array($token)) {
            if ($token[0] === T_NAMESPACE) {
                $gettingNamespace = true;
            } elseif ($token[0] === T_CLASS || $token[0] === T_INTERFACE || $token[0] === T_TRAIT) {
                $gettingClass = true;
            } elseif ($gettingNamespace) {
                if ($token[0] === T_STRING || $token[0] === T_NAME_QUALIFIED) {
                    $namespace .= $token[1];
                }
            } elseif ($gettingClass) {
                if ($token[0] === T_STRING) {
                    $class = $token[1];
                    break;
                }
            }
        } elseif ($token === ';') {
            $gettingNamespace = false;
        }
    }

    return $class ? ($namespace ? $namespace . '\\' . $class : $class) : null;
}

function formatMethodSignature(ReflectionMethod $method): string
{
    $params = [];
    foreach ($method->getParameters() as $param) {
        $paramStr = '';
        if ($param->hasType()) {
            $paramType = $param->getType();
            if ($paramType instanceof ReflectionNamedType) {
                $paramStr .= ($paramType->allowsNull() && $paramType->getName() !== 'mixed' ? '?' : '') . $paramType->getName() . ' ';
            } elseif ($paramType instanceof ReflectionUnionType) {
                $types = array_map(fn($t) => $t->getName(), $paramType->getTypes());
                $paramStr .= implode('|', $types) . ' ';
            }
        }
        if ($param->isPassedByReference()) {
            $paramStr .= '&';
        }
        if ($param->isVariadic()) {
            $paramStr .= '...';
        }
        $paramStr .= '$' . $param->getName();

        if ($param->isDefaultValueAvailable()) {
            try {
                $default = $param->getDefaultValue();
                if (is_null($default)) {
                    $paramStr .= ' = null';
                } elseif (is_bool($default)) {
                    $paramStr .= ' = ' . ($default ? 'true' : 'false');
                } elseif (is_array($default)) {
                    $paramStr .= ' = []';
                } elseif (is_string($default)) {
                    $paramStr .= " = '" . (strlen($default) > 20 ? substr($default, 0, 17) . '...' : $default) . "'";
                } else {
                    $paramStr .= ' = ' . var_export($default, true);
                }
            } catch (\Throwable $e) {
                $paramStr .= ' = [default]';
            }
        }
        $params[] = $paramStr;
    }

    $returnStr = '';
    if ($method->hasReturnType()) {
        $returnType = $method->getReturnType();
        if ($returnType instanceof ReflectionNamedType) {
            $returnStr = ': ' . ($returnType->allowsNull() && $returnType->getName() !== 'mixed' ? '?' : '') . $returnType->getName();
        } elseif ($returnType instanceof ReflectionUnionType) {
            $types = array_map(fn($t) => $t->getName(), $returnType->getTypes());
            $returnStr = ': ' . implode('|', $types);
        }
    }

    $static = $method->isStatic() ? 'static ' : '';
    return "{$static}function {$method->getName()}(" . implode(', ', $params) . "){$returnStr}";
}

function detectRelationship(ReflectionMethod $method): ?string
{
    $declaringClass = $method->getDeclaringClass();
    $file = $method->getFileName();
    $startLine = $method->getStartLine();
    $endLine = $method->getEndLine();

    if (!$file || !is_readable($file) || $startLine === false || $endLine === false) {
        return null;
    }

    $lines = file($file);
    $methodBody = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

    $relationPattern = '/return\s+\$this->(hasOne|hasMany|belongsTo|belongsToMany|morphTo|morphMany|morphToMany|hasManyThrough|hasOneThrough)\s*\(([^;]+)\);/s';
    if (preg_match($relationPattern, $methodBody, $matches)) {
        $relationType = $matches[1];
        $args = trim($matches[2]);
        $args = preg_replace('/\s+/', ' ', $args);
        return "{$relationType}({$args})";
    }

    return null;
}

$processedClasses = [];
$markdown = "# Codebase Architecture Skeleton Map\n\n";
$markdown .= "> Generated: " . date('Y-m-d H:i:s') . "\n";
$markdown .= "> This document provides a high-speed instant lookup index of all Models, Repositories, Services, Controllers, and public method signatures.\n\n";

$markdown .= "## Table of Contents\n";
foreach ($sections as $sectionName => $path) {
    $anchor = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $sectionName));
    $markdown .= "- [{$sectionName}](#{$anchor})\n";
}
$markdown .= "\n---\n\n";

foreach ($sections as $sectionName => $path) {
    $markdown .= "## {$sectionName}\n\n";
    $isDirectOnly = ($sectionName === 'Controllers (Root & Auth)');
    $files = getPhpFiles($path, !$isDirectOnly);

    if (empty($files)) {
        $markdown .= "_No files found._\n\n";
        continue;
    }

    $count = 0;
    foreach ($files as $file) {
        $className = getFullClassNameFromFile($file);
        if (!$className || isset($processedClasses[$className])) {
            continue;
        }

        try {
            if (!class_exists($className) && !interface_exists($className) && !trait_exists($className)) {
                continue;
            }

            $ref = new ReflectionClass($className);
            $processedClasses[$className] = true;
            $count++;

            $type = 'class';
            if ($ref->isInterface()) {
                $type = 'interface';
            } elseif ($ref->isTrait()) {
                $type = 'trait';
            } elseif ($ref->isAbstract()) {
                $type = 'abstract class';
            }

            $parent = $ref->getParentClass() ? ' extends ' . $ref->getParentClass()->getShortName() : '';
            $interfaces = $ref->getInterfaceNames();
            $implements = !empty($interfaces) ? ' implements ' . implode(', ', array_map(fn($i) => basename(str_replace('\\', '/', $i)), $interfaces)) : '';

            $markdown .= "### `{$type} {$ref->getShortName()}`\n";
            $markdown .= "- **Namespace**: `\\{$ref->getNamespaceName()}`\n";
            if ($parent || $implements) {
                $markdown .= "- **Hierarchy**: `{$ref->getShortName()}{$parent}{$implements}`\n";
            }

            // Check if Model
            $isModel = $ref->isSubclassOf('Illuminate\Database\Eloquent\Model');
            if ($isModel) {
                $defaultProps = $ref->getDefaultProperties();
                $table = $defaultProps['table'] ?? strtolower($ref->getShortName()) . 's';
                $fillable = $defaultProps['fillable'] ?? [];
                
                $markdown .= "- **Table**: `{$table}`\n";
                if (!empty($fillable)) {
                    $markdown .= "- **Fillable**: `" . implode('`, `', $fillable) . "`\n";
                }

                // Detect relationships
                $relations = [];
                foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
                    if ($m->getDeclaringClass()->getName() === $className && !$m->isStatic()) {
                        $rel = detectRelationship($m);
                        if ($rel) {
                            $relations[$m->getName()] = $rel;
                        }
                    }
                }

                if (!empty($relations)) {
                    $markdown .= "- **Eloquent Relationships**:\n";
                    foreach ($relations as $methodName => $relDefinition) {
                        $markdown .= "  - `{$methodName}()`: `{$relDefinition}`\n";
                    }
                }
            }

            // Public Methods declared in this class
            $methods = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
            $ownMethods = array_filter($methods, fn($m) => $m->getDeclaringClass()->getName() === $className && !str_starts_with($m->getName(), '__'));

            if (!empty($ownMethods)) {
                $markdown .= "- **Public API Methods**:\n";
                $markdown .= "  ```php\n";
                foreach ($ownMethods as $m) {
                    $markdown .= "  " . formatMethodSignature($m) . "\n";
                }
                $markdown .= "  ```\n";
            }

            $markdown .= "\n";

        } catch (\Throwable $e) {
            // Ignore reflection errors on individual files
        }
    }

    if ($count === 0) {
        $markdown .= "_No declared classes found in this directory._\n\n";
    }

    $markdown .= "---\n\n";
}

file_put_contents($outputFile, $markdown);
echo "Successfully generated skeleton to: {$outputFile} (" . number_format(strlen($markdown)) . " bytes)\n";
