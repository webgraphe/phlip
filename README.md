## Embeddable scripts for PHP

[Phlip](https://github.com/webgraphe/phlip) (pronounced \\ˈflip\\) is an embeddable scripting language for
[PHP](https://www.php.net) based on [s-expressions](https://en.wikipedia.org/wiki/S-expression). In a nutshell:

A [lexer](https://en.wikipedia.org/wiki/Lexical_analysis) tokenizes scripts and a
[parser](https://en.wikipedia.org/wiki/Parsing#Computer_languages) assembles data structures. A script's behavior
originates from data and code elements resolved from a controlled context. Integration is simpler with the _Phlipy_
[dialect](https://en.wikipedia.org/wiki/Programming_language#Dialects,_flavors_and_implementations).

Refer to `tests/Unit/ReadmeTest.php` for the example below:
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
* [Homoiconic](https://en.wikipedia.org/wiki/Homoiconicity) (code as data)
* Create [test suites](https://en.wikipedia.org/wiki/Unit_testing) with `phlipunit` (built on top of [PHPUnit](https://phpunit.de))
* Ships with a literal [REPL](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop) `(loop (print (eval (read))))`
