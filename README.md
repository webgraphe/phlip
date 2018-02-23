# phlip
A JSON-alike dialect supporting [s-expressions](https://en.wikipedia.org/wiki/S-expression).

> "_It's literally JSON with closures_"

Consider this Phlip script:
```lisp
(let
    (
        (factorial (lambda (x) (if (= x 0) 1 (* x (factorial (- x 1))))))
    )
    {
        "factorial-of-10": (factorial 10)
    }
)
```
This would return:
```lisp
{
    "factorial-of-10": 3628800
}
```
Phlip considers `:` and `,` as white-spaces, and declares objects and arrays (respectively maps and vectors) the same as JSON using `{}` and `[]`. By declaring `null`, `true` and `false` in a context you can _flip_ a JSON stream into a Phlip script!

That's _code-as-data_ and _data-as-code_ at its best.

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
; factorial.phlip
(lambda (x) (if (= x 0) 1 (* x (factorial (- x 1)))))
```

Then parse said script file (`lambda` returns a closure):
```php
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;

$factorial = Program::parseFile('path/to/fibonacci.phlip')->execute(Phlipy::context());

$factorial(8); // int(40320)
```

Give it a try with the `phlip` REPL!

## Features

* Easy to use
* Easy to learn
* Homoiconic! (the code has the same structure as the data)
* Transform existing JSON streams with closures and symbols
* Open to extensions to build your own s-expr language
* Replaceable components designed with role interfaces
* Ships with a literal REPL `(loop (print (eval (read))))`
* Create test suites in your own language with PHPUnit and `vendor/bin/phlipunit`
