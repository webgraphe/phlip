# phlip
A dialect for PHP in the form of s-expressions like LISP.

## Usage

```php
use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Program;

$program = Program::parse('(+ 1 2 3 4)');
$result = $program->execute(new PhlipyContext);
var_dump($result);
```

Outputs `int(10)`

## Features

* Easy to use
* Easy to learn
* Homoiconic!
* Open to extensions to build your own s-expr language
* Replaceable components designed with role interfaces
* Ships with a literal REPL `(loop (print (eval (read))))`
* Create test suites in your own language with PHPUnit and `vendor/bin/phlipunit`
