## Embeddable scripts for PHP

[Phlip](https://github.com/webgraphe/phlip) (pronounced \\ˈflip\\) is an embeddable scripting language for
[PHP](https://www.php.net) based on [s-expressions](https://en.wikipedia.org/wiki/S-expression). In a nutshell:

> &laquo; _It's JSON with closures._ &raquo;

A [lexer](https://en.wikipedia.org/wiki/Lexical_analysis) and a
[parser](https://en.wikipedia.org/wiki/Parsing#Computer_languages) analyze scripts observing Phlip's syntax rules.
A script's behavior originates from named data and code elements resolved from a controlled context. Integration
is simpler with the _Phlipy_
[dialect](https://en.wikipedia.org/wiki/Programming_language#Dialects,_flavors_and_implementations).

```php
<?php

use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;

// Tokenize and parse code into a program
$program = Program::parse('(lambda (x) (* x x))');
// Bootstrap a new context with the Phlipy dialect
$context = Phlipy::bootstrap();
// Execute program within said context
$square = $program->execute($context);

// In this case, return value is an anonymous function - a lambda - calculating the square of a number
var_dump($square(M_PI)); // (double)9.8696044010894
```

### Why use Phlip
* Easy to use
* Easy to learn
* [Homoiconic](https://en.wikipedia.org/wiki/Homoiconicity) (code as data)
* Transform existing JSON streams with closures and symbols
* Build your own dialect!
* Create [test suites](https://en.wikipedia.org/wiki/Unit_testing) with `phlipunit` (built on top of [PHPUnit](https://phpunit.de))
* Ships with a literal [REPL](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop)
