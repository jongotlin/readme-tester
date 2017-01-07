Readme-Tester
=============

[![Packagist Version](https://img.shields.io/packagist/v/hanneskod/readme-tester.svg?style=flat-square)](https://packagist.org/packages/hanneskod/readme-tester)
[![Build Status](https://img.shields.io/travis/hanneskod/readme-tester/master.svg?style=flat-square)](https://travis-ci.org/hanneskod/readme-tester)
[![Quality Score](https://img.shields.io/scrutinizer/g/hanneskod/readme-tester.svg?style=flat-square)](https://scrutinizer-ci.com/g/hanneskod/readme-tester)
[![Dependency Status](https://img.shields.io/gemnasium/hanneskod/readme-tester.svg?style=flat-square)](https://gemnasium.com/hanneskod/readme-tester)

Validate PHP code examples in markdown files.

Why?
----
Did you update your library, but forgot to update code examples in README? Are
your users complaining on syntax errors in your examples? Do you find it too
cumbersome to manually test all examples? Then readme-tester is for you!

Readme-tester lets you automate the process of validating PHP code examples in
markdown files. You can use the command line tool, or integrate with your
phpunit test suite and never have to worry about failing examples again.

Table of contents
-----------------
  * [Installation](#installation)
  * [Writing examples](#writing-examples)
    * [Annotations](#annotations)
    * [Naming examples](#naming-examples)
    * [Ignoring examples](iIgnoring-examples)
    * [Expectations](#expectations)
    * [Linking examples together](#linking-examples-together)
  * [The command line tool](#the-command-line-tool)
  * [PHPUnit integration](#phpunit-integration)

Installation
------------
Install using composer

```shell
composer require --dev hanneskod/readme-tester
```

> NOTE! Readme-tester works just as good as a development dependency of your
> project as installed *globally*.

Writing examples
----------------
When readme-tester validates a markdown file all php colorized code blocks are
executed. In markdown this means using a fenced block with the `php` language
identifier.

```
\`\`\`php
// This code is validated
\`\`\`
```

### Annotations

To specify how examples should be tested readme-tester uses annotations hidden
inside HTML comments. In this way testing related instructions are hidden when
rendered on github or similar.

A block of annotations can look like this

```
<!-- @example "an example" -->
<!-- @expectOutput /foobar/ -->
```

Or like this

```
<!--
    @example "an example"
    @expectOutput /foobar/
-->
```

Readme-tester will only recongnize annotations directly before the code block
example, meaning that there must be no content between the annotations and the
code, not even empty lines. If in doubt view the contents of
[this file](/README.md).

#### Annotation arguments

Annotation tags are prefixed with `@` and are followed by a list of arguments
separated by spaces. If an argument includes spaces it must be enclosed in
double quotes (`"`).

### Naming examples

Examples may be named using the `@example` annotation. Naming is optional but
gives better error reporting and simplifies referencing.

### Ignoring examples

Examples may be ignored using the `@ignore` annotation. Ignored examples are
not validated in any way.

<!-- @ignore -->
```php
// Example is preceded by <!-- @ignore -->
// This code is skipped, the syntax error is ignored.
echo foobar";
```

### Expectations

Add assertions to code blocks using one of the expectation annotations.
Multiple expectations can be specified for an example.

#### Expecting output

Assert the output of an example using a regular expression.

<!-- @expectOutput "/regular expression/" -->
```php
// Example is preceded by <!-- @expectOutput "/regular expression/" -->
echo "This output is matched using a regular expression";
```

If the annotation argument of `@expectOutput` is not a valid regular expression
it will be transformed into one, `abc` is transformed into `/^abc$/`.

<!-- @expectOutput abc -->
```php
// Example is preceded by <!-- @expectOutput abc -->
echo "abc";
```

#### Expecting exceptions

<!-- @expectException Exception -->
```php
// Example is preceded by <!-- @expectException Exception -->
throw new Exception();
```

#### Expecting return values

<!-- @expectReturnType integer -->
```php
// Example is preceded by <!-- @expectReturnType integer -->
// Type descriptor used by gettype() or a class name can be used
return 1;
```

<!-- @expectReturn /foo/ -->
```php
// Example is preceded by <!-- @expectReturn /foo/ -->
return 'foo';
```
#### Expecting nothing

<!-- @expectNothing -->
```php
// Example is preceded by <!-- @expectNothing -->
// nothing is expected here..
```

### Linking examples together

An example may extend a previous example using the `@extends` annotation. This
will copy the code of the extended example into the extending and wrapp it in
output buffering so that output may be asserted in each of the examples.

<!--
    @example parent
    @expectOutput A
-->
```php
/*
Example is preceded by
<!--
    @example parent
    @expectOutput A
-->
*/
$data = ['parent' => 'A', 'child' => 'B'];
echo $data['parent'];
```

Now we can extend the named example `parent`.

<!--
    @example child
    @extends parent
    @expectOutput B
-->
```php
/*
Example is preceded by
<!--
    @example child
    @extends parent
    @expectOutput B
-->
*/
echo $data['child'];
```

The command line tool
---------------------
Using the command line tool is as simple as

```shell
vendor/bin/readme-tester test README.md
```

For more detailed information use

```shell
vendor/bin/readme-tester help test
```

PHPUnit integration
-------------------
Subclass `ReadmeTestCase` and use `assertReadme()` to run readme snippets
through phpunit. For an extra bonus ddd a different testsuite for example
testing by removing the `Test` suffix from your test class and define a
testsuite in `phpunit.xml`. A standard setup may look like the following:

```php
class ReadmeIntegration extends \hanneskod\readmetester\PHPUnit\ReadmeTestCase
{
    public function testReadmeIntegrationTests()
    {
        $this->assertReadme('README.md');
    }
}
```

And in `phpunit.xml`:

```xml
<phpunit bootstrap="./vendor/autoload.php">
    <testsuites>
        <testsuite name="unit">
            <directory>./tests</directory>
        </testsuite>
        <testsuite name="examples">
            <file>./tests/ReadmeIntegration.php</file>
        </testsuite>
    </testsuites>
</phpunit>
```

### Integrating with global installations

If readme-tester is expected to be installed *globally* we dont't want phpunit
to break when readme-tester is not present. Use the `AssertReadme` class instead
of subclassing `ReadmeTestCase`.

```php
class GlobalReadmeIntegration extends \PHPUnit_Framework_TestCase
{
    public function testReadmeIntegrationTests()
    {
        if (!class_exists('hanneskod\readmetester\PHPUnit\AssertReadme')) {
            $this->markTestSkipped('Readme-tester is not available.');
        }

        (new \hanneskod\readmetester\PHPUnit\AssertReadme($this))->assertReadme('README.md');
    }
}
```
