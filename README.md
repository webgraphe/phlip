# phlip
A dialect for PHP in the form of s-expressions like LISP.

## Usage

Create a `Program` by parsing a script: 

```php
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;

$program = Program::parse('(* 1 2 3 4)');
```

Execute said program in a context:

```php
$factorialOf4 = $program->execute(Phlipy::context());
var_dump($factorialOf4); // int(24)
```

Define programs in script files:
```lisp
; fibonacci.phlip
(define fibonacci
    (lambda (x)
        (let ((even 0) (odd 1))
            (while (> x 1)
                (set even (+ even odd))
                (set odd (+ odd even))
                (set x (- x 2)))
            (if (% x 2)
                odd
                even))))
```

Then parse said script file:
```php
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;

$context = Phlipy::context();
Program::parseFile('path/to/fibonacci.phlip')->execute($context);
```

And even execute assertions in unit tests:
```php
Program::parse('(assert-equals 7540113804746346429 (fibonacci 92))')->execute($context);
```

## Features

* Easy to use
* Easy to learn
* Homoiconic!
* Open to extensions to build your own s-expr language
* Replaceable components designed with role interfaces
* Ships with a literal REPL `(loop (print (eval (read))))`
* Create test suites in your own language with PHPUnit and `vendor/bin/phlipunit`
