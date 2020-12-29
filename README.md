## Embeddable scripts for PHP

<a href="https://packagist.org/packages/webgraphe/phlip"><img src="https://img.shields.io/packagist/dt/webgraphe/phlip" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/webgraphe/phlip"><img src="https://img.shields.io/packagist/v/webgraphe/phlip" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/webgraphe/phlip"><img src="https://img.shields.io/packagist/l/webgraphe/phlip" alt="License"></a>

[Phlip](https://github.com/webgraphe/phlip) (pronounced \\ˈflip\\) is an embeddable scripting language for
[PHP](https://www.php.net) based on [s-expressions](https://en.wikipedia.org/wiki/S-expression). In a nutshell:

A [lexer](https://en.wikipedia.org/wiki/Lexical_analysis) tokenizes scripts and a
[parser](https://en.wikipedia.org/wiki/Parsing#Computer_languages) assembles data structures. A script's behavior
originates from data and code elements resolved from a controlled context. Integration is simpler with the _Phlipy_
[dialect](https://en.wikipedia.org/wiki/Programming_language#Dialects,_flavors_and_implementations).

Refer to `Webgraphe\Phlip\Tests\Unit\ReadmeTest` for the example below:
```php
<?php

use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;

// Tokenize and parse code into a program
$program = Program::parse('(lambda (x) (* x x))');
// Bootstrap a new context with different level of Phlipy dialects
$context = Phlipy::basic()->getContext();
// Execute program within said context
$square = $program->execute($context);

// In this case, return value is an anonymous function - a lambda - calculating the square of a number
// as per source code "(* x x)"
var_dump($square(M_PI)); // (double)9.8696044010894
```

Install with [`composer require webgraphe/phlip`](https://packagist.org/packages/webgraphe/phlip)

### Why use Phlip
* Easy to use
* Easy to learn
* Build your own dialect!
* Interoperable with PHP Classes
* Create [test suites](https://en.wikipedia.org/wiki/Unit_testing) with `phlipunit` (built on top of [PHPUnit](https://phpunit.de))
* Ships with a literal [REPL](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop) `(loop (print (eval (read))))`

**DISCLAIMER**

Lisp is a often considered a [Homoiconic](https://en.wikipedia.org/wiki/Homoiconicity) (code as data) language.
Despite Phlip's usage of S-expressions and a somewhat successful attempt at reproducing McCarthy's eval, as the author
I do not consider Phlip be an homoiconic language as it relies on PHP's internal data structures such as `array`,
`stdClass`, native scalars, `Closure` and invokable classes for performance and convenience. 

Phlip is embeddable in PHP and was built to allow transportation of code and data in a manner that can stay
relatively secure, provided that functionalities interoperable with PHP stay in check.

For reference: `Webgraphe\Phlip\Tests\System\LispTest::testMcCarthyEval()`
